<?php
// ============================================================
// InfinityFree Compatible File Manager
// Single-file upload to htdocs/manager.php
// ============================================================

// Debug mode: shows exact errors instead of blank 500 page
error_reporting(E_ALL);
ini_set('display_errors', 1);

$baseDir = dirname(__FILE__);
$selfFile = basename(__FILE__);

// ---- API ROUTE ----
if (isset($_GET['api']) || isset($_POST['action']) || isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? $_GET['action'] ?? '';
    $path = sanitizePath($_POST['path'] ?? $_GET['path'] ?? '');
    $fullPath = $baseDir . ($path ? '/' . $path : '');
    
    if (!isWithinBase($fullPath, $baseDir)) {
        jsonOut(['error' => 'Access denied: path outside allowed directory']);
        exit;
    }
    
    switch ($action) {
        case 'list':  jsonOut(actionList($fullPath)); break;
        case 'read':  jsonOut(actionRead($fullPath)); break;
        case 'write': jsonOut(actionWrite($fullPath, $_POST['content'] ?? '')); break;
        case 'delete': jsonOut(actionDelete($fullPath)); break;
        case 'mkdir': jsonOut(actionMkdir($fullPath)); break;
        case 'rename': jsonOut(actionRename($fullPath, $_POST['newName'] ?? '')); break;
        case 'upload': jsonOut(actionUpload($fullPath)); break;
        default: jsonOut(['error' => 'Unknown action']);
    }
    exit;
}

// ---- HELPERS ----
function jsonOut($data) {
    echo json_encode($data);
}

function sanitizePath($path) {
    $path = str_replace("\0", '', $path);
    $path = str_replace('\\', '/', $path);
    $parts = explode('/', $path);
    $safe = [];
    foreach ($parts as $part) {
        if ($part === '' || $part === '.') continue;
        if ($part === '..') continue;
        $safe[] = $part;
    }
    return implode('/', $safe);
}

function isWithinBase($path, $base) {
    $rp = @realpath($path);
    $rb = @realpath($base);
    if ($rp === false) {
        $rp = @realpath(dirname($path));
        if ($rp === false) return false;
    }
    return strpos($rp, $rb) === 0;
}

function actionList($path) {
    if (!is_dir($path)) return ['error' => 'Not a directory'];
    $items = [];
    $files = @scandir($path);
    if ($files === false) return ['error' => 'Cannot read directory. Check CHMOD is 755.'];
    
    global $selfFile;
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        if ($file === $selfFile) continue;
        $fp = $path . '/' . $file;
        $items[] = [
            'name' => $file,
            'type' => is_dir($fp) ? 'folder' : 'file',
            'size' => is_file($fp) ? @filesize($fp) : 0,
            'date' => date('Y-m-d H:i', @filemtime($fp) ?: time()),
            'writable' => is_writable($fp)
        ];
    }
    return ['success' => true, 'items' => $items];
}

function actionRead($path) {
    if (!is_file($path)) return ['error' => 'File not found'];
    $content = @file_get_contents($path);
    if ($content === false) return ['error' => 'Cannot read file'];
    return ['success' => true, 'content' => base64_encode($content)];
}

function actionWrite($path, $content) {
    $dir = dirname($path);
    if (!is_dir($dir)) return ['error' => 'Directory does not exist'];
    if (!is_writable($dir)) return ['error' => 'Directory not writable. Set CHMOD to 755.'];
    
    $raw = base64_decode($content);
    if ($raw === false) return ['error' => 'Invalid content encoding'];
    
    $result = @file_put_contents($path, $raw);
    if ($result === false) return ['error' => 'Failed to write file'];
    return ['success' => true, 'bytes' => $result];
}

function actionDelete($path) {
    if (!file_exists($path)) return ['error' => 'Not found'];
    global $selfFile;
    if (basename($path) === $selfFile) return ['error' => 'Cannot delete this manager'];
    
    if (is_dir($path)) {
        $files = @scandir($path);
        if (count($files) > 2) return ['error' => 'Directory not empty'];
        $result = @rmdir($path);
    } else {
        $result = @unlink($path);
    }
    if (!$result) return ['error' => 'Failed to delete. Check permissions.'];
    return ['success' => true];
}

function actionMkdir($path) {
    if (file_exists($path)) return ['error' => 'Already exists'];
    $result = @mkdir($path, 0755, true);
    if (!$result) return ['error' => 'Failed to create directory. Set CHMOD on parent to 755.'];
    return ['success' => true];
}

function actionRename($path, $newName) {
    if (!file_exists($path)) return ['error' => 'Not found'];
    $newName = str_replace(['/', '\\', '..'], '', $newName);
    if (empty($newName)) return ['error' => 'Invalid name'];
    
    $newPath = dirname($path) . '/' . $newName;
    if (file_exists($newPath)) return ['error' => 'Target already exists'];
    
    $result = @rename($path, $newPath);
    if (!$result) return ['error' => 'Failed to rename. Check permissions.'];
    return ['success' => true];
}

function actionUpload($path) {
    if (!is_dir($path)) return ['error' => 'Invalid upload directory'];
    if (empty($_FILES['file'])) return ['error' => 'No file uploaded'];
    
    $file = $_FILES['file'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['error' => 'Upload error code: ' . $file['error']];
    }
    
    $name = basename($file['name']);
    $name = str_replace(['/', '\\', '..'], '', $name);
    
    $target = $path . '/' . $name;
    if (!@move_uploaded_file($file['tmp_name'], $target)) {
        return ['error' => 'Move failed. Check directory is writable (CHMOD 755).'];
    }
    return ['success' => true, 'name' => $name];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfinityFile Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #1e3a5f; --primary-light: #2c5282; --accent: #3182ce;
            --bg: #f7fafc; --sidebar-bg: #1a202c; --card-bg: #ffffff;
            --border: #e2e8f0; --text: #2d3748; --text-light: #718096;
            --danger: #e53e3e; --danger-hover: #c53030; --success: #38a169;
            --warning: #d69e2e;
        }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--bg); color: var(--text); height: 100vh; overflow: hidden; }
        .header { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); color: white; padding: 0 24px; height: 60px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header-brand { display: flex; align-items: center; gap: 12px; font-size: 20px; font-weight: 600; }
        .header-brand i { font-size: 24px; color: #63b3ed; }
        .header-info { font-size: 13px; opacity: 0.9; }
        .container { display: flex; height: calc(100vh - 60px); }
        .sidebar { width: 260px; background: var(--sidebar-bg); color: #cbd5e0; display: flex; flex-direction: column; border-right: 1px solid #2d3748; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #2d3748; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #a0aec0; }
        .tree { padding: 10px; overflow-y: auto; flex: 1; }
        .tree-item { padding: 8px 12px; cursor: pointer; border-radius: 6px; display: flex; align-items: center; gap: 10px; transition: all 0.2s; font-size: 14px; }
        .tree-item:hover { background: #2d3748; color: white; }
        .tree-item.active { background: var(--accent); color: white; }
        .tree-item i { width: 20px; text-align: center; }
        .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .toolbar { background: var(--card-bg); padding: 12px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .btn { padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }
        .btn-primary { background: var(--accent); color: white; }
        .btn-primary:hover { background: var(--primary-light); }
        .btn-success { background: var(--success); color: white; }
        .btn-success:hover { background: #2f855a; }
        .btn-danger { background: var(--danger); color: white; }
        .btn-danger:hover { background: var(--danger-hover); }
        .btn-secondary { background: #edf2f7; color: var(--text); border: 1px solid var(--border); }
        .btn-secondary:hover { background: #e2e8f0; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .breadcrumb { margin-left: auto; display: flex; align-items: center; gap: 8px; font-size: 14px; color: var(--text-light); }
        .breadcrumb a { color: var(--accent); text-decoration: none; cursor: pointer; }
        .breadcrumb a:hover { text-decoration: underline; }
        .file-list { flex: 1; overflow-y: auto; padding: 20px; }
        .file-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; }
        .file-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 10px; padding: 16px; cursor: pointer; transition: all 0.2s; position: relative; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .file-card:hover { transform: translateY(-2px); box-shadow: 0 10px 15px rgba(0,0,0,0.1); border-color: var(--accent); }
        .file-card.selected { border-color: var(--accent); background: #ebf8ff; box-shadow: 0 0 0 3px rgba(49,130,206,0.2); }
        .file-checkbox { position: absolute; top: 12px; right: 12px; width: 18px; height: 18px; cursor: pointer; accent-color: var(--accent); }
        .file-icon { font-size: 40px; text-align: center; margin-bottom: 12px; color: var(--accent); }
        .file-icon.folder { color: #d69e2e; }
        .file-icon.php { color: #777bb4; }
        .file-icon.html { color: #e44d26; }
        .file-icon.css { color: #264de4; }
        .file-icon.js { color: #f7df1e; }
        .file-icon.image { color: #38a169; }
        .file-icon.text { color: var(--text-light); }
        .file-name { font-size: 14px; font-weight: 600; text-align: center; word-break: break-all; margin-bottom: 4px; }
        .file-meta { font-size: 12px; color: var(--text-light); text-align: center; }
        .context-menu { position: absolute; background: white; border: 1px solid var(--border); border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); padding: 6px 0; z-index: 1000; display: none; min-width: 180px; }
        .context-menu-item { padding: 10px 16px; cursor: pointer; font-size: 13px; display: flex; align-items: center; gap: 10px; transition: background 0.15s; }
        .context-menu-item:hover { background: #f7fafc; }
        .context-menu-item.danger { color: var(--danger); }
        .context-menu-divider { height: 1px; background: var(--border); margin: 6px 0; }
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); display: none; align-items: center; justify-content: center; z-index: 2000; backdrop-filter: blur(4px); }
        .modal-overlay.active { display: flex; }
        .modal { background: var(--card-bg); border-radius: 12px; width: 90%; max-width: 800px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 25px 50px rgba(0,0,0,0.25); animation: modalSlide 0.3s ease; }
        @keyframes modalSlide { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        .modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .modal-title { font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-light); width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
        .modal-close:hover { background: #f7fafc; color: var(--danger); }
        .modal-body { padding: 24px; overflow-y: auto; flex: 1; }
        .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; }
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: var(--text); }
        .form-input, .form-textarea, .form-select { width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; font-family: inherit; transition: border-color 0.2s; }
        .form-input:focus, .form-textarea:focus, .form-select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(49,130,206,0.1); }
        .form-textarea { min-height: 400px; resize: vertical; font-family: 'Consolas', 'Monaco', 'Courier New', monospace; line-height: 1.5; tab-size: 4; }
        .status-bar { background: var(--card-bg); border-top: 1px solid var(--border); padding: 8px 24px; font-size: 12px; color: var(--text-light); display: flex; justify-content: space-between; }
        .upload-zone { border: 2px dashed var(--border); border-radius: 10px; padding: 40px; text-align: center; transition: all 0.2s; cursor: pointer; }
        .upload-zone:hover, .upload-zone.dragover { border-color: var(--accent); background: #ebf8ff; }
        .upload-zone i { font-size: 48px; color: var(--accent); margin-bottom: 12px; }
        .toast-container { position: fixed; bottom: 24px; right: 24px; z-index: 3000; display: flex; flex-direction: column; gap: 10px; }
        .toast { background: var(--card-bg); border-left: 4px solid var(--success); padding: 14px 20px; border-radius: 6px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 10px; animation: toastSlide 0.3s ease; min-width: 280px; }
        .toast.error { border-left-color: var(--danger); }
        .toast.warning { border-left-color: var(--warning); }
        @keyframes toastSlide { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .empty-state { text-align: center; padding: 60px 20px; color: var(--text-light); }
        .empty-state i { font-size: 64px; margin-bottom: 16px; opacity: 0.3; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #a0aec0; }
        @media (max-width: 768px) { .sidebar { display: none; } .file-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); } }
    </style>
</head>
<body>

<div class="header">
    <div class="header-brand">
        <i class="fas fa-cube"></i>
        <span>InfinityFile Manager</span>
    </div>
    <div class="header-info">
        <i class="fas fa-server"></i> InfinityFree Edition
    </div>
</div>

<div class="container">
    <div class="sidebar">
        <div class="sidebar-header">Navigation</div>
        <div class="tree" id="tree"></div>
    </div>

    <div class="main">
        <div class="toolbar">
            <button class="btn btn-success" onclick="showNewFileModal()">
                <i class="fas fa-file-plus"></i> New File
            </button>
            <button class="btn btn-primary" onclick="showNewFolderModal()">
                <i class="fas fa-folder-plus"></i> New Folder
            </button>
            <button class="btn btn-secondary" onclick="showUploadModal()">
                <i class="fas fa-cloud-upload"></i> Upload
            </button>
            <button class="btn btn-secondary" onclick="loadFiles()">
                <i class="fas fa-sync"></i> Refresh
            </button>
            <button class="btn btn-danger" id="btnMultiDelete" onclick="deleteSelected()" disabled>
                <i class="fas fa-trash"></i> Delete Selected
            </button>
            
            <div class="breadcrumb" id="breadcrumb"></div>
        </div>

        <div class="file-list" id="fileList">
            <div class="file-grid" id="fileGrid"></div>
        </div>

        <div class="status-bar">
            <span id="statusText">Loading...</span>
            <span id="selectionText"></span>
        </div>
    </div>
</div>

<input type="file" id="fileInput" multiple style="display:none" onchange="handleUpload(event)">

<div class="context-menu" id="contextMenu">
    <div class="context-menu-item" onclick="contextOpen()"><i class="fas fa-eye"></i> Open</div>
    <div class="context-menu-item" onclick="contextEdit()"><i class="fas fa-edit"></i> Edit</div>
    <div class="context-menu-divider"></div>
    <div class="context-menu-item" onclick="contextRename()"><i class="fas fa-pen"></i> Rename</div>
    <div class="context-menu-item" onclick="contextDownload()"><i class="fas fa-download"></i> Download</div>
    <div class="context-menu-divider"></div>
    <div class="context-menu-item danger" onclick="contextDelete()"><i class="fas fa-trash"></i> Delete</div>
</div>

<div class="modal-overlay" id="newFileModal">
    <div class="modal" style="max-width:450px;">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-file-plus"></i> Create New File</div>
            <button class="modal-close" onclick="closeModal('newFileModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">File Name</label>
                <input type="text" class="form-input" id="newFileName" placeholder="e.g. index.php, style.css">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('newFileModal')">Cancel</button>
            <button class="btn btn-success" onclick="createNewFile()">Create File</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="newFolderModal">
    <div class="modal" style="max-width:450px;">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-folder-plus"></i> Create New Folder</div>
            <button class="modal-close" onclick="closeModal('newFolderModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Folder Name</label>
                <input type="text" class="form-input" id="newFolderName" placeholder="e.g. assets, includes">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('newFolderModal')">Cancel</button>
            <button class="btn btn-primary" onclick="createNewFolder()">Create Folder</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="editModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-code"></i> Edit: <span id="editFileName"></span></div>
            <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
        </div>
        <div class="modal-body">
            <textarea class="form-textarea" id="fileEditor" placeholder="File content..."></textarea>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('editModal')">Cancel</button>
            <button class="btn btn-success" onclick="saveFile()"><i class="fas fa-save"></i> Save</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="uploadModal">
    <div class="modal" style="max-width:500px;">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-cloud-upload"></i> Upload Files</div>
            <button class="modal-close" onclick="closeModal('uploadModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="upload-zone" id="uploadZone" onclick="document.getElementById('fileInput').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <h3>Drop files here or click to browse</h3>
                <p style="color:var(--text-light);margin-top:8px;font-size:13px;">Supports PHP, HTML, CSS, JS, TXT, images, etc.</p>
            </div>
            <div id="uploadList" style="margin-top:16px;"></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('uploadModal')">Close</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="renameModal">
    <div class="modal" style="max-width:450px;">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-pen"></i> Rename</div>
            <button class="modal-close" onclick="closeModal('renameModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">New Name</label>
                <input type="text" class="form-input" id="renameInput">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('renameModal')">Cancel</button>
            <button class="btn btn-primary" onclick="confirmRename()">Rename</button>
        </div>
    </div>
</div>

<div class="toast-container" id="toastContainer"></div>

<script>
const API_URL = window.location.pathname;
let currentPath = '';
let selectedItems = new Set();
let contextTarget = null;
let editingFile = null;
let renameTarget = null;
let currentItems = [];

async function api(action, params = {}) {
    const formData = new FormData();
    formData.append('action', action);
    for (let [k, v] of Object.entries(params)) {
        formData.append(k, v);
    }
    try {
        const res = await fetch(API_URL + '?api=1', { method: 'POST', body: formData });
        const text = await res.text();
        try {
            return JSON.parse(text);
        } catch (e) {
            return { error: 'Server returned invalid JSON. Raw: ' + text.substring(0, 300) };
        }
    } catch (err) {
        return { error: 'Network error: ' + err.message };
    }
}

function getFileIcon(name, type) {
    if (type === 'folder') return '<i class="fas fa-folder file-icon folder"></i>';
    const ext = name.split('.').pop().toLowerCase();
    const map = { php: 'fa-file-code', html: 'fa-html5', css: 'fa-css3-alt', js: 'fa-js', txt: 'fa-file-alt', jpg: 'fa-file-image', jpeg: 'fa-file-image', png: 'fa-file-image', gif: 'fa-file-image', svg: 'fa-file-image', pdf: 'fa-file-pdf', zip: 'fa-file-archive' };
    return `<i class="fas ${map[ext] || 'fa-file'} file-icon ${ext}"></i>`;
}

function formatSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
}

function showToast(msg, type = 'success') {
    const c = document.getElementById('toastContainer');
    const t = document.createElement('div');
    t.className = 'toast ' + (type === 'error' ? 'error' : type === 'warning' ? 'warning' : '');
    const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
    t.innerHTML = `<i class="fas fa-${icon}"></i> <span>${msg}</span>`;
    c.appendChild(t);
    setTimeout(() => t.remove(), 3000);
}

async function loadFiles() {
    const res = await api('list', { path: currentPath });
    const grid = document.getElementById('fileGrid');
    if (res.error) {
        grid.innerHTML = `<div class="empty-state" style="grid-column:1/-1;"><i class="fas fa-exclamation-triangle"></i><h3>Error</h3><p>${res.error}</p></div>`;
        document.getElementById('statusText').textContent = 'Error';
        return;
    }
    currentItems = res.items || [];
    if (currentItems.length === 0) {
        grid.innerHTML = `<div class="empty-state" style="grid-column:1/-1;"><i class="fas fa-folder-open"></i><h3>This folder is empty</h3><p>Create a file or folder to get started</p></div>`;
    } else {
        let html = '';
        for (let item of currentItems) {
            const sel = selectedItems.has(item.name);
            html += `
            <div class="file-card ${sel ? 'selected' : ''}" 
                 data-name="${item.name}" data-type="${item.type}"
                 ondblclick="openItem('${item.name}')"
                 onclick="selectItem(event, '${item.name}')"
                 oncontextmenu="showContextMenu(event, '${item.name}')">
                <input type="checkbox" class="file-checkbox" ${sel ? 'checked' : ''} 
                       onclick="event.stopPropagation(); toggleSelect('${item.name}')">
                ${getFileIcon(item.name, item.type)}
                <div class="file-name">${item.name}</div>
                <div class="file-meta">${item.type === 'folder' ? 'Folder' : formatSize(item.size)} &bull; ${item.date}</div>
            </div>`;
        }
        grid.innerHTML = html;
    }
    renderBreadcrumb();
    renderTree();
    document.getElementById('statusText').textContent = `${currentItems.length} item(s)`;
}

function renderBreadcrumb() {
    const bc = document.getElementById('breadcrumb');
    const parts = currentPath.split('/').filter(p => p);
    let html = '<i class="fas fa-home" style="cursor:pointer" onclick="navigateTo(\'\')"></i>';
    let accum = '';
    for (let i = 0; i < parts.length; i++) {
        accum += (accum ? '/' : '') + parts[i];
        html += ` <span style="color:var(--border)">/</span> <a onclick="navigateTo('${accum}')">${parts[i]}</a>`;
    }
    bc.innerHTML = html;
}

function renderTree() {
    const tree = document.getElementById('tree');
    tree.innerHTML = `<div class="tree-item ${currentPath === '' ? 'active' : ''}" onclick="navigateTo('')"><i class="fas fa-hdd"></i> Root (htdocs)</div>`;
}

function navigateTo(path) {
    currentPath = path;
    selectedItems.clear();
    updateSelectionUI();
    loadFiles();
}

function selectItem(e, name) {
    if (e.ctrlKey || e.metaKey) {
        toggleSelect(name);
    } else {
        selectedItems.clear();
        selectedItems.add(name);
        updateSelectionUI();
        loadFiles();
    }
}

function toggleSelect(name) {
    if (selectedItems.has(name)) selectedItems.delete(name);
    else selectedItems.add(name);
    updateSelectionUI();
    loadFiles();
}

function updateSelectionUI() {
    const btn = document.getElementById('btnMultiDelete');
    btn.disabled = selectedItems.size === 0;
    document.getElementById('selectionText').textContent = selectedItems.size ? `${selectedItems.size} selected` : '';
}

async function openItem(name) {
    const item = currentItems.find(i => i.name === name);
    if (!item) return;
    if (item.type === 'folder') {
        navigateTo(currentPath ? currentPath + '/' + name : name);
    } else {
        editFile(name);
    }
}

async function editFile(name) {
    const res = await api('read', { path: currentPath ? currentPath + '/' + name : name });
    if (res.error) { showToast(res.error, 'error'); return; }
    editingFile = name;
    document.getElementById('editFileName').textContent = name;
    document.getElementById('fileEditor').value = atob(res.content);
    document.getElementById('editModal').classList.add('active');
    setTimeout(() => document.getElementById('fileEditor').focus(), 100);
}

async function saveFile() {
    if (!editingFile) return;
    const content = btoa(document.getElementById('fileEditor').value);
    const path = currentPath ? currentPath + '/' + editingFile : editingFile;
    const res = await api('write', { path: path, content: content });
    if (res.error) { showToast(res.error, 'error'); return; }
    closeModal('editModal');
    showToast('Saved successfully');
    editingFile = null;
    loadFiles();
}

async function createNewFile() {
    const name = document.getElementById('newFileName').value.trim();
    if (!name) return showToast('Enter a file name', 'error');
    const path = currentPath ? currentPath + '/' + name : name;
    const res = await api('write', { path: path, content: btoa('') });
    if (res.error) { showToast(res.error, 'error'); return; }
    closeModal('newFileModal');
    showToast('File created');
    loadFiles();
}

async function createNewFolder() {
    const name = document.getElementById('newFolderName').value.trim();
    if (!name) return showToast('Enter a folder name', 'error');
    const path = currentPath ? currentPath + '/' + name : name;
    const res = await api('mkdir', { path: path });
    if (res.error) { showToast(res.error, 'error'); return; }
    closeModal('newFolderModal');
    showToast('Folder created');
    loadFiles();
}

async function deleteSelected() {
    if (selectedItems.size === 0) return;
    if (!confirm(`Delete ${selectedItems.size} item(s)?`)) return;
    for (let name of selectedItems) {
        const path = currentPath ? currentPath + '/' + name : name;
        await api('delete', { path: path });
    }
    selectedItems.clear();
    updateSelectionUI();
    showToast('Deleted');
    loadFiles();
}

async function deleteItem(name) {
    if (!confirm(`Delete "${name}"?`)) return;
    const path = currentPath ? currentPath + '/' + name : name;
    const res = await api('delete', { path: path });
    if (res.error) { showToast(res.error, 'error'); return; }
    selectedItems.delete(name);
    updateSelectionUI();
    showToast('Deleted');
    loadFiles();
}

function showContextMenu(e, name) {
    e.preventDefault();
    contextTarget = name;
    const menu = document.getElementById('contextMenu');
    menu.style.display = 'block';
    menu.style.left = Math.min(e.pageX, window.innerWidth - 200) + 'px';
    menu.style.top = Math.min(e.pageY, window.innerHeight - 250) + 'px';
}

function hideContextMenu() {
    document.getElementById('contextMenu').style.display = 'none';
}

function contextOpen() { hideContextMenu(); if (contextTarget) openItem(contextTarget); }
function contextEdit() { hideContextMenu(); if (contextTarget) editFile(contextTarget); }
function contextDelete() { hideContextMenu(); if (contextTarget) deleteItem(contextTarget); }

function contextRename() {
    hideContextMenu();
    if (!contextTarget) return;
    renameTarget = contextTarget;
    document.getElementById('renameInput').value = contextTarget;
    document.getElementById('renameModal').classList.add('active');
}

async function confirmRename() {
    const newName = document.getElementById('renameInput').value.trim();
    if (!newName || newName === renameTarget) { closeModal('renameModal'); return; }
    const oldPath = currentPath ? currentPath + '/' + renameTarget : renameTarget;
    const res = await api('rename', { path: oldPath, newName: newName });
    if (res.error) { showToast(res.error, 'error'); return; }
    renameTarget = null;
    closeModal('renameModal');
    showToast('Renamed');
    loadFiles();
}

function contextDownload() {
    hideContextMenu();
    if (!contextTarget) return;
    const path = currentPath ? currentPath + '/' + contextTarget : contextTarget;
    fetch(API_URL + '?api=1&action=read&path=' + encodeURIComponent(path))
        .then(r => r.json())
        .then(res => {
            if (res.error) return showToast(res.error, 'error');
            const blob = new Blob([atob(res.content)], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = contextTarget;
            a.click();
            URL.revokeObjectURL(url);
        });
}

function showNewFileModal() {
    document.getElementById('newFileName').value = '';
    document.getElementById('newFileModal').classList.add('active');
    setTimeout(() => document.getElementById('newFileName').focus(), 100);
}

function showNewFolderModal() {
    document.getElementById('newFolderName').value = '';
    document.getElementById('newFolderModal').classList.add('active');
    setTimeout(() => document.getElementById('newFolderName').focus(), 100);
}

function showUploadModal() {
    document.getElementById('uploadModal').classList.add('active');
    document.getElementById('uploadList').innerHTML = '';
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

async function handleUpload(e) {
    const files = e.target.files;
    if (!files.length) return;
    const list = document.getElementById('uploadList');
    for (let file of files) {
        const div = document.createElement('div');
        div.style.cssText = 'padding:8px;background:#f7fafc;border-radius:4px;margin-bottom:6px;font-size:13px;';
        div.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Uploading ${file.name}...`;
        list.appendChild(div);
        
        const formData = new FormData();
        formData.append('action', 'upload');
        formData.append('path', currentPath);
        formData.append('file', file);
        
        const res = await fetch(API_URL + '?api=1', { method: 'POST', body: formData });
        const text = await res.text();
        let json;
        try { json = JSON.parse(text); } catch(e) { json = { error: text.substring(0, 200) }; }
        
        if (json.error) {
            div.innerHTML = `<i class="fas fa-times" style="color:var(--danger)"></i> ${file.name}: ${json.error}`;
        } else {
            div.innerHTML = `<i class="fas fa-check" style="color:var(--success)"></i> ${file.name}: Done`;
        }
    }
    showToast('Upload complete');
    loadFiles();
}

// Drag & drop
const fileListEl = document.getElementById('fileList');
fileListEl.addEventListener('dragover', e => { e.preventDefault(); fileListEl.style.background = '#ebf8ff'; });
fileListEl.addEventListener('dragleave', e => { e.preventDefault(); fileListEl.style.background = ''; });
fileListEl.addEventListener('drop', e => {
    e.preventDefault();
    fileListEl.style.background = '';
    if (e.dataTransfer.files.length) {
        document.getElementById('fileInput').files = e.dataTransfer.files;
        handleUpload({ target: { files: e.dataTransfer.files } });
    }
});

document.addEventListener('click', () => hideContextMenu());
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('active'));
    if (e.key === 'Delete' && selectedItems.size > 0 && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
        deleteSelected();
    }
});

// Init
loadFiles();
</script>

</body>
</html>