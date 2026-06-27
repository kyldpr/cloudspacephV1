<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <title>CloudSpacePH • Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #1a3a8a;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            padding: 1rem;
            margin: 0;
        }

        .container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 16px 48px rgba(0, 20, 60, 0.45), 0 6px 16px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 640px;
            padding: 1.5rem 1.5rem 1.25rem;
            animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── NAVIGATION ── */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
            gap: 0.5rem;
            border-bottom: 2px solid #eef3fc;
            padding-bottom: 0.5rem;
        }

        .brand {
            font-size: 1.3rem;
            font-weight: 800;
            color: #0b2559;
            letter-spacing: -0.02em;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .nav-links a, .nav-links button {
            background: transparent;
            border: none;
            padding: 0.3rem 0.8rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 700;
            color: #4a6a9f;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .nav-links a:hover, .nav-links button:hover {
            background: #eef3fc;
            color: #1a3a8a;
        }

        .nav-links a.active, .nav-links button.active {
            background: #1a3a8a;
            color: #fff;
        }

        .hamburger {
            display: none;
            background: transparent;
            border: none;
            font-size: 1.8rem;
            color: #0b2559;
            cursor: pointer;
            padding: 0 0.2rem;
        }

        .logout-btn {
            border: 1.5px solid #d6e2f5 !important;
            padding: 0.25rem 1rem !important;
            border-radius: 30px !important;
            font-size: 0.75rem !important;
            color: #1a3a8a !important;
            background: transparent !important;
        }

        .logout-btn:hover {
            background: #1a3a8a !important;
            color: #fff !important;
            border-color: #1a3a8a !important;
        }

        /* Mobile nav */
        @media (max-width: 480px) {
            .hamburger { display: block; }
            .nav-links {
                display: none;
                width: 100%;
                flex-direction: column;
                align-items: stretch;
                gap: 0.3rem;
                padding: 0.5rem 0 0;
                border-top: 1px solid #eef3fc;
                margin-top: 0.3rem;
            }
            .nav-links.open { display: flex; }
            .nav-links a, .nav-links button {
                text-align: center;
                padding: 0.5rem;
            }
            .logout-btn {
                border: none !important;
                color: #b71c1c !important;
                font-weight: 700;
            }
            .logout-btn:hover {
                background: #fde8e8 !important;
                color: #b71c1c !important;
            }
        }

        /* ── PAGES ── */
        .page {
            display: block;
        }

        .page.hidden {
            display: none;
        }

        /* ── USER CARD (unchanged) ── */
        .user-card {
            background: #f4f8ff;
            border-radius: 16px;
            padding: 1rem 1.2rem;
            margin-bottom: 1.5rem;
            border: 1.5px solid #dce6f5;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .user-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #1a3a8a;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.5rem;
            text-transform: uppercase;
            flex-shrink: 0;
        }

        .user-info { flex: 1; }
        .user-info .name { font-size: 1.1rem; font-weight: 700; color: #0b2559; }
        .user-info .detail { font-size: 0.78rem; color: #4a6a9f; margin-top: 0.1rem; }
        .user-info .detail span { font-weight: 600; color: #0b2559; }
        .user-info .warning { color: #b71c1c; font-weight: 600; }

        /* ── SECTION TITLES ── */
        .section-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #0b2559;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title .badge {
            background: #1a3a8a;
            color: #fff;
            font-size: 0.6rem;
            padding: 0.1rem 0.6rem;
            border-radius: 20px;
        }

        /* ── POST LIST (unchanged) ── */
        .post-list { list-style: none; padding: 0; }
        .post-item {
            background: #f8faff;
            border-radius: 12px;
            padding: 0.7rem 1rem;
            margin-bottom: 0.6rem;
            border: 1px solid #eef3fc;
            transition: background 0.15s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            cursor: pointer;
        }
        .post-item:hover { background: #eef3fc; }
        .post-item:active { background: #dce6f5; }
        .post-title { font-weight: 600; color: #0b2559; font-size: 0.9rem; }
        .post-meta { font-size: 0.7rem; color: #6a8ab0; display: flex; align-items: center; gap: 0.6rem; }
        .post-meta .author { font-weight: 600; color: #1a3a8a; }
        .post-meta .date { color: #8aa3c0; }
        .post-meta .comments-count { color: #1a3a8a; font-weight: 600; }
        .empty-state { text-align: center; color: #6a8ab0; font-size: 0.9rem; padding: 2rem 0; }

        /* ── POST DETAIL (unchanged) ── */
        #postDetailView {
            display: none;
        }

        .post-detail-header {
            margin-bottom: 1rem;
        }

        .post-detail-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0b2559;
            line-height: 1.3;
        }

        .post-detail-meta {
            font-size: 0.75rem;
            color: #6a8ab0;
            margin-top: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .post-detail-meta .author { font-weight: 600; color: #1a3a8a; }

        .post-detail-body {
            background: #f4f8ff;
            border-radius: 12px;
            padding: 1rem;
            margin: 0.75rem 0 1.25rem;
            border: 1px solid #eef3fc;
            font-size: 0.88rem;
            color: #1a2a4a;
            line-height: 1.6;
            white-space: pre-wrap;
        }

        /* ── COMMENTS (unchanged) ── */
        .comment-section-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: #0b2559;
            margin-bottom: 0.6rem;
        }

        .comments-list { list-style: none; padding: 0; margin-bottom: 1rem; }

        .comment-item {
            background: #f8faff;
            border-radius: 10px;
            padding: 0.65rem 0.9rem;
            margin-bottom: 0.5rem;
            border: 1px solid #eef3fc;
        }

        .comment-item .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.25rem;
            font-size: 0.72rem;
        }

        .comment-item .comment-author {
            font-weight: 700;
            color: #1a3a8a;
        }

        .comment-item .comment-date {
            color: #8aa3c0;
        }

        .comment-item .comment-content {
            font-size: 0.84rem;
            color: #1a2a4a;
            line-height: 1.5;
            white-space: pre-wrap;
        }

        .comment-item .you-badge {
            background: #1a3a8a;
            color: #fff;
            font-size: 0.55rem;
            padding: 0.05rem 0.35rem;
            border-radius: 8px;
            margin-left: 0.3rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .comment-form {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .comment-form textarea {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border: 1.5px solid #d6e2f5;
            border-radius: 10px;
            font-size: 0.85rem;
            color: #0b1a3a;
            background: #f8faff;
            resize: vertical;
            min-height: 60px;
            max-height: 150px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s ease;
        }

        .comment-form textarea:focus {
            border-color: #1a3a8a;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(26, 58, 138, 0.10);
        }

        .comment-form textarea::placeholder {
            color: #9bb0d0;
        }

        .comment-form .comment-submit {
            align-self: flex-end;
            background: #1a3a8a;
            color: #fff;
            border: none;
            padding: 0.45rem 1.2rem;
            border-radius: 30px;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .comment-form .comment-submit:hover {
            background: #0f2a6a;
        }

        .comment-form .comment-submit:disabled {
            background: #b0c8e8;
            cursor: not-allowed;
        }

        .comment-status {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.3rem 0.7rem;
            border-radius: 8px;
            margin-top: 0.35rem;
            display: none;
        }

        .comment-status.success {
            display: block;
            background: #e4f6ed;
            color: #0f7b3a;
        }

        .comment-status.error {
            display: block;
            background: #fde8e8;
            color: #b71c1c;
        }

        /* ── SETTINGS PAGES ── */
        .settings-tabs {
            display: flex;
            gap: 0.3rem;
            background: #eef3fc;
            border-radius: 40px;
            padding: 0.2rem;
            margin-bottom: 1.5rem;
        }

        .settings-tab {
            flex: 1;
            border: none;
            background: transparent;
            padding: 0.4rem 0.2rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #3a5a8a;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }

        .settings-tab.active {
            background: #ffffff;
            color: #1a3a8a;
            box-shadow: 0 2px 8px rgba(26, 58, 138, 0.15);
        }

        .settings-panel {
            display: none;
            animation: fadeSlide 0.2s ease both;
        }

        .settings-panel.active {
            display: block;
        }

        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 0.85rem;
        }

        .form-group label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            color: #0b2559;
            margin-bottom: 0.25rem;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 0.55rem 0.8rem;
            border: 1.5px solid #d6e2f5;
            border-radius: 10px;
            font-size: 0.85rem;
            color: #0b1a3a;
            background: #f8faff;
            outline: none;
            transition: border-color 0.2s ease;
            font-family: inherit;
        }

        .form-group input:focus, .form-group textarea:focus {
            border-color: #1a3a8a;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(26, 58, 138, 0.10);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .submit-btn {
            background: #1a3a8a;
            color: #fff;
            border: none;
            padding: 0.55rem 1.5rem;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .submit-btn:hover:not(:disabled) {
            background: #0f2a6a;
        }

        .submit-btn:disabled {
            background: #b0c8e8;
            cursor: not-allowed;
        }

        .settings-message {
            margin-top: 0.75rem;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.3rem 0.8rem;
            border-radius: 10px;
        }

        .settings-message.success {
            background: #e4f6ed;
            color: #0f7b3a;
        }

        .settings-message.error {
            background: #fde8e8;
            color: #b71c1c;
        }

        .log-list {
            list-style: none;
            padding: 0;
            background: #f8faff;
            border-radius: 10px;
            border: 1px solid #eef3fc;
        }

        .log-item {
            padding: 0.6rem 0.9rem;
            border-bottom: 1px solid #eef3fc;
            font-size: 0.8rem;
            color: #1a2a4a;
            display: flex;
            justify-content: space-between;
        }

        .log-item:last-child {
            border-bottom: none;
        }

        .log-item .log-time {
            color: #8aa3c0;
            font-weight: 500;
        }

        @media (max-width: 480px) {
            .container { padding: 1rem 0.9rem 0.8rem; border-radius: 16px; }
            .brand { font-size: 1.1rem; }
            .user-card { flex-direction: column; align-items: flex-start; padding: 0.8rem; }
            .user-avatar { width: 44px; height: 44px; font-size: 1.2rem; }
            .post-item { flex-direction: column; align-items: flex-start; gap: 0.2rem; }
            .post-meta { font-size: 0.65rem; }
            .post-detail-title { font-size: 1rem; }
            .comment-item .comment-content { font-size: 0.8rem; }
            .settings-tabs { flex-wrap: wrap; gap: 0.1rem; }
            .settings-tab { flex: 1 0 30%; font-size: 0.7rem; padding: 0.3rem 0.1rem; }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- ─── NAVIGATION ─── -->
    <nav class="navbar">
        <div class="brand">CloudSpacePH</div>
        <button class="hamburger" id="hamburgerBtn" aria-label="Toggle navigation">☰</button>
        <div class="nav-links" id="navLinks">
            <a href="#" class="active" data-page="dashboard">Dashboard</a>
            <a href="#" data-page="settings">Settings</a>
            <button class="logout-btn" id="logoutBtn">Sign Out</button>
        </div>
    </nav>

    <!-- ─── DASHBOARD PAGE ─── -->
    <div id="page-dashboard" class="page">
        <!-- User Card -->
        <div class="user-card" id="userCard">
            <div class="user-avatar" id="userAvatar">?</div>
            <div class="user-info">
                <div class="name" id="userName">Loading...</div>
                <div class="detail" id="userDetail">Email: <span>—</span> &bull; Account: <span>—</span></div>
                <div class="detail" style="font-size:0.7rem; color:#8aa3c0;" id="userSince">Joined: —</div>
            </div>
        </div>

        <!-- Post List -->
        <div id="postListView">
            <div class="section-title">
                Forum Posts
                <span class="badge" id="postCount">0</span>
            </div>
            <ul class="post-list" id="postList">
                <li class="empty-state">Loading posts...</li>
            </ul>
        </div>

        <!-- Post Detail (hidden by default) -->
        <div id="postDetailView">
            <div class="post-detail-header">
                <button class="back-btn" id="backBtn" style="background:transparent;border:none;color:#1a3a8a;font-weight:700;cursor:pointer;margin-bottom:0.5rem;">← Back</button>
                <div class="post-detail-title" id="detailTitle"></div>
                <div class="post-detail-meta">
                    by <span class="author" id="detailAuthor"></span>
                    <span class="date" id="detailDate"></span>
                </div>
            </div>

            <div class="post-detail-body" id="detailBody"></div>

            <div class="comment-section-title">Comments</div>
            <ul class="comments-list" id="commentsList">
                <li class="empty-state">No comments yet. Be the first!</li>
            </ul>

            <div class="comment-form">
                <textarea id="commentInput" placeholder="Write a comment..." maxlength="1000"></textarea>
                <button class="comment-submit" id="commentSubmitBtn">Post Comment</button>
                <div class="comment-status" id="commentStatus"></div>
            </div>
        </div>
    </div>

    <!-- ─── SETTINGS PAGE ─── -->
    <div id="page-settings" class="page hidden">
        <div class="settings-tabs" id="settingsTabs">
            <button class="settings-tab active" data-tab="change-password">Change Password</button>
            <button class="settings-tab" data-tab="account-logs">Account Logs</button>
            <button class="settings-tab" data-tab="report-bug">Report a Bug</button>
        </div>

        <!-- Change Password -->
        <div id="settings-change-password" class="settings-panel active">
            <form id="changePasswordForm">
                <div class="form-group">
                    <label for="currentPassword">Current Password</label>
                    <input type="password" id="currentPassword" required />
                </div>
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" id="newPassword" required />
                </div>
                <div class="form-group">
                    <label for="confirmNewPassword">Confirm New Password</label>
                    <input type="password" id="confirmNewPassword" required />
                </div>
                <button type="submit" class="submit-btn">Update Password</button>
                <div id="passwordMessage" class="settings-message"></div>
            </form>
        </div>

        <!-- Account Logs -->
        <div id="settings-account-logs" class="settings-panel">
            <p style="font-size:0.85rem;color:#4a6a9f;margin-bottom:1rem;">Recent login activity and account actions.</p>
            <ul class="log-list" id="logList">
                <li class="empty-state">No logs available.</li>
            </ul>
            <p style="font-size:0.75rem;color:#8aa3c0;margin-top:0.5rem;">* This feature requires a backend endpoint to fetch logs.</p>
        </div>

        <!-- Report a Bug -->
        <div id="settings-report-bug" class="settings-panel">
            <form id="bugReportForm">
                <div class="form-group">
                    <label for="bugTitle">Title</label>
                    <input type="text" id="bugTitle" placeholder="Brief description" required />
                </div>
                <div class="form-group">
                    <label for="bugDescription">Description</label>
                    <textarea id="bugDescription" placeholder="What happened?" required></textarea>
                </div>
                <button type="submit" class="submit-btn">Submit Report</button>
                <div id="bugMessage" class="settings-message"></div>
            </form>
            <p style="font-size:0.75rem;color:#8aa3c0;margin-top:0.5rem;">* This feature requires a backend endpoint to store bug reports.</p>
        </div>
    </div>

</div>

<script>
    // ── Check authentication ──
    const username = localStorage.getItem('cloudspace_username');
    if (!username) {
        window.location.href = 'login';
    }

    const API_BASE = 'api/forums/forums-api.php';
    const AUTH_API = 'auth-api.php';

    // ── State ──
    let currentPostId = null;
    let currentPost = null;

    // ── UI references ──
    const postListView = document.getElementById('postListView');
    const postDetailView = document.getElementById('postDetailView');
    const backBtn = document.getElementById('backBtn');
    const pageDashboard = document.getElementById('page-dashboard');
    const pageSettings = document.getElementById('page-settings');
    const navLinks = document.querySelectorAll('.nav-links a');
    const hamburger = document.getElementById('hamburgerBtn');
    const navLinksContainer = document.getElementById('navLinks');

    // ── Navigation switching ──
    function showPage(pageId) {
        // Hide all pages
        document.querySelectorAll('.page').forEach(p => p.classList.add('hidden'));
        document.getElementById('page-' + pageId).classList.remove('hidden');

        // Update active nav link
        navLinks.forEach(link => {
            link.classList.toggle('active', link.dataset.page === pageId);
        });

        // Close mobile menu
        navLinksContainer.classList.remove('open');

        // If switching to settings, show first settings tab
        if (pageId === 'settings') {
            showSettingsTab('change-password');
        }
    }

    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const page = link.dataset.page;
            if (page === 'dashboard') {
                // If we are in post detail, go back to list first
                if (postDetailView.style.display === 'block') {
                    goBackToList();
                }
                showPage('dashboard');
            } else {
                showPage('settings');
            }
        });
    });

    // Hamburger toggle
    hamburger.addEventListener('click', () => {
        navLinksContainer.classList.toggle('open');
    });

    // ── Settings Tabs ──
    const settingsTabs = document.querySelectorAll('.settings-tab');
    const settingsPanels = {
        'change-password': document.getElementById('settings-change-password'),
        'account-logs': document.getElementById('settings-account-logs'),
        'report-bug': document.getElementById('settings-report-bug')
    };

    function showSettingsTab(tabId) {
        settingsTabs.forEach(tab => {
            tab.classList.toggle('active', tab.dataset.tab === tabId);
        });
        Object.entries(settingsPanels).forEach(([id, panel]) => {
            panel.classList.toggle('active', id === tabId);
        });
        // Clear messages
        document.getElementById('passwordMessage').className = 'settings-message';
        document.getElementById('passwordMessage').textContent = '';
        document.getElementById('bugMessage').className = 'settings-message';
        document.getElementById('bugMessage').textContent = '';
    }

    settingsTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            showSettingsTab(tab.dataset.tab);
        });
    });

    // ── Load posts (unchanged) ──
    async function loadPosts() {
        try {
            const res = await fetch(API_BASE + '?action=list_posts');
            const data = await res.json();

            if (data.status !== 'success') {
                document.getElementById('postList').innerHTML =
                    '<li class="empty-state">Failed to load posts.</li>';
                return;
            }

            const posts = data.posts;
            document.getElementById('postCount').textContent = posts.length;

            const postList = document.getElementById('postList');
            postList.innerHTML = '';

            if (posts.length === 0) {
                postList.innerHTML = '<li class="empty-state">No forum posts yet.</li>';
                return;
            }

            posts.forEach(post => {
                const li = document.createElement('li');
                li.className = 'post-item';
                li.setAttribute('data-post-id', post.id);
                li.innerHTML = `
                    <span class="post-title">${escapeHtml(post.title)}</span>
                    <span class="post-meta">
                        <span class="author">${escapeHtml(post.author)}</span>
                        <span class="date">${formatDate(post.timestamp)}</span>
                        <span class="comments-count">💬 ${post.comment_count || 0}</span>
                    </span>
                `;
                li.addEventListener('click', () => openPost(post.id));
                postList.appendChild(li);
            });

        } catch (err) {
            console.error('loadPosts error:', err);
            document.getElementById('postList').innerHTML =
                '<li class="empty-state">Network error loading posts.</li>';
        }
    }

    // ── Open post detail (unchanged) ──
    async function openPost(postId) {
        currentPostId = postId;

        try {
            const res = await fetch(API_BASE + '?action=get_post&post_id=' + encodeURIComponent(postId));
            const data = await res.json();

            if (data.status !== 'success') {
                alert('Could not load post.');
                return;
            }

            currentPost = data.post;

            document.getElementById('detailTitle').textContent = data.post.title;
            document.getElementById('detailAuthor').textContent = data.post.author;
            document.getElementById('detailDate').textContent = formatDate(data.post.timestamp);
            document.getElementById('detailBody').textContent = data.post.content;

            renderComments(data.comments);

            postListView.style.display = 'none';
            postDetailView.style.display = 'block';
            backBtn.style.display = 'inline-block';

            document.getElementById('commentInput').value = '';
            document.getElementById('commentStatus').className = 'comment-status';
            document.getElementById('commentStatus').textContent = '';

        } catch (err) {
            console.error('openPost error:', err);
            alert('Network error loading post.');
        }
    }

    function renderComments(comments) {
        const list = document.getElementById('commentsList');
        list.innerHTML = '';

        if (!comments || comments.length === 0) {
            list.innerHTML = '<li class="empty-state">No comments yet. Be the first!</li>';
            return;
        }

        const loggedUser = username.toLowerCase();

        comments.forEach(comment => {
            const li = document.createElement('li');
            li.className = 'comment-item';

            const isYours = comment.author.toLowerCase() === loggedUser;

            li.innerHTML = `
                <div class="comment-header">
                    <span class="comment-author">
                        ${escapeHtml(comment.author)}
                        ${isYours ? '<span class="you-badge">You</span>' : ''}
                    </span>
                    <span class="comment-date">${formatDate(comment.timestamp)}</span>
                </div>
                <div class="comment-content">${escapeHtml(comment.content)}</div>
            `;
            list.appendChild(li);
        });
    }

    // ── Post comment (unchanged) ──
    async function postComment() {
        const input = document.getElementById('commentInput');
        const content = input.value.trim();
        const statusEl = document.getElementById('commentStatus');
        const btn = document.getElementById('commentSubmitBtn');

        if (!content) {
            statusEl.className = 'comment-status error';
            statusEl.textContent = 'Please write a comment.';
            return;
        }

        if (!currentPostId) return;

        btn.disabled = true;
        btn.textContent = 'Posting...';
        statusEl.className = 'comment-status';
        statusEl.textContent = '';

        try {
            const res = await fetch(API_BASE, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'add_comment',
                    post_id: currentPostId,
                    author: username,
                    content: content
                })
            });
            const data = await res.json();

            if (data.status === 'success') {
                input.value = '';
                statusEl.className = 'comment-status success';
                statusEl.textContent = 'Comment posted!';
                setTimeout(() => {
                    statusEl.className = 'comment-status';
                    statusEl.textContent = '';
                }, 2500);

                const postRes = await fetch(API_BASE + '?action=get_post&post_id=' + encodeURIComponent(currentPostId));
                const postData = await postRes.json();
                if (postData.status === 'success') {
                    renderComments(postData.comments);
                }
                loadPosts();

            } else {
                statusEl.className = 'comment-status error';
                statusEl.textContent = data.message || 'Failed to post comment.';
            }

        } catch (err) {
            console.error('postComment error:', err);
            statusEl.className = 'comment-status error';
            statusEl.textContent = 'Network error posting comment.';
        }

        btn.disabled = false;
        btn.textContent = 'Post Comment';
    }

    function goBackToList() {
        currentPostId = null;
        currentPost = null;
        postDetailView.style.display = 'none';
        postListView.style.display = 'block';
        backBtn.style.display = 'none';
    }

    backBtn.addEventListener('click', goBackToList);

    document.getElementById('commentSubmitBtn').addEventListener('click', postComment);
    document.getElementById('commentInput').addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
            e.preventDefault();
            postComment();
        }
    });

    // ── Format date ──
    function formatDate(dateStr) {
        if (!dateStr) return '—';
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return dateStr;
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) +
            ' at ' + d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // ── Load profile (unchanged) ──
    const profileUrl = 'api/profiles/users/' + username.toLowerCase() + '.json';

    async function loadProfile() {
        try {
            const res = await fetch(profileUrl);
            if (!res.ok) throw new Error('Profile file not found');
            const userData = await res.json();

            document.getElementById('userAvatar').textContent =
                (userData.username || username).charAt(0).toUpperCase();
            document.getElementById('userName').textContent = userData.username || username;
            document.getElementById('userDetail').innerHTML = `
                Email: <span>${escapeHtml(userData.email || '—')}</span> &bull;
                Account: <span>${userData.account_type || '1'}</span>
            `;
            document.getElementById('userSince').textContent =
                'Joined: ' + (userData.date_created || '—');

        } catch (error) {
            console.warn('Profile load error:', error);
            document.getElementById('userAvatar').textContent = username.charAt(0).toUpperCase();
            document.getElementById('userName').textContent = username;
            document.getElementById('userDetail').innerHTML = `
                <span class="warning">⚠️ Profile data unavailable</span> &bull;
                Email: <span>—</span> &bull; Account: <span>—</span>
            `;
            document.getElementById('userSince').textContent = 'Joined: —';
        }
    }

    // ── Logout ──
    document.getElementById('logoutBtn').addEventListener('click', () => {
        localStorage.removeItem('cloudspace_username');
        window.location.href = 'login';
    });

    // ── Change Password ──
    document.getElementById('changePasswordForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const msg = document.getElementById('passwordMessage');
        msg.className = 'settings-message';
        msg.textContent = '';

        const current = document.getElementById('currentPassword').value;
        const newPass = document.getElementById('newPassword').value;
        const confirm = document.getElementById('confirmNewPassword').value;

        if (newPass.length < 8) {
            msg.classList.add('error');
            msg.textContent = 'New password must be at least 8 characters.';
            return;
        }
        if (newPass !== confirm) {
            msg.classList.add('error');
            msg.textContent = 'Passwords do not match.';
            return;
        }

        try {
            const res = await fetch(AUTH_API, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'change_password',
                    username: username,
                    current_password: current,
                    new_password: newPass
                })
            });
            const data = await res.json();

            if (data.status === 'success') {
                msg.classList.add('success');
                msg.textContent = 'Password updated successfully.';
                document.getElementById('changePasswordForm').reset();
            } else {
                msg.classList.add('error');
                msg.textContent = data.message || 'Failed to update password.';
            }
        } catch (err) {
            msg.classList.add('error');
            msg.textContent = 'Network error.';
        }
    });

    // ── Bug Report (placeholder) ──
    document.getElementById('bugReportForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const msg = document.getElementById('bugMessage');
        msg.className = 'settings-message';
        msg.textContent = '';

        const title = document.getElementById('bugTitle').value.trim();
        const desc = document.getElementById('bugDescription').value.trim();

        if (!title || !desc) {
            msg.classList.add('error');
            msg.textContent = 'Please fill in all fields.';
            return;
        }

        // TODO: send to backend (e.g., /api/bug-report.php)
        // For now, just show success
        msg.classList.add('success');
        msg.textContent = 'Thank you! Your report has been submitted.';
        document.getElementById('bugReportForm').reset();
    });

    // ── Account Logs (placeholder) ──
    async function loadLogs() {
        // TODO: fetch from backend
        const list = document.getElementById('logList');
        list.innerHTML = '<li class="empty-state">No logs available.</li>';
    }

    // ── Init ──
    loadProfile();
    loadPosts();
    loadLogs();

    // By default, show dashboard
    showPage('dashboard');
</script>

</body>
</html>