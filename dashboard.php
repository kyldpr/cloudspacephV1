<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <title>CloudSpacePH • Dashboard</title>
    <style>
        /* ───────────── RESET & BASE ───────────── */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f0f4fc;
            min-height: 100vh;
            display: flex;
            padding: 1rem;
        }

        /* ───────────── SIDEBAR ───────────── */
        .sidebar {
            width: 220px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0, 20, 60, 0.10);
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex-shrink: 0;
            margin-right: 1.5rem;
            height: fit-content;
            position: sticky;
            top: 1rem;
        }

        .sidebar .brand {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0b2559;
            text-transform: uppercase;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #eef3fc;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }

        .sidebar .nav-item {
            padding: 0.6rem 1rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #3a5a8a;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .sidebar .nav-item:hover {
            background: #eef3fc;
            color: #0b2559;
        }

        .sidebar .nav-item.active {
            background: #1a3a8a;
            color: #fff;
            box-shadow: 0 4px 12px rgba(26, 58, 138, 0.25);
        }

        .sidebar .nav-item.logout {
            margin-top: auto;
            border-top: 2px solid #eef3fc;
            padding-top: 0.75rem;
            color: #b71c1c;
        }

        .sidebar .nav-item.logout:hover {
            background: #fde8e8;
        }

        .nav-icon {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        /* ───────────── MAIN CONTENT ───────────── */
        .main-content {
            flex: 1;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0, 20, 60, 0.10);
            padding: 1.5rem 2rem;
            min-height: 80vh;
        }

        .section {
            display: none;
            animation: fadeSlide 0.3s ease both;
        }

        .section.active {
            display: block;
        }

        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #0b2559;
            margin-bottom: 1.25rem;
            border-bottom: 2px solid #eef3fc;
            padding-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-btn {
            background: transparent;
            border: 1.5px solid #d6e2f5;
            padding: 0.3rem 1rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #1a3a8a;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .back-btn:hover {
            background: #1a3a8a;
            color: #fff;
            border-color: #1a3a8a;
        }

        /* ───────────── USER CARD (Dashboard) ───────────── */
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
        .email-display {
            font-size: 0.85rem;
            font-weight: 600;
            color: #1a3a8a;
            margin-top: 0.2rem;
            background: #e8effa;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            display: inline-block;
        }

        /* ───────────── DASHBOARD WIDGETS ───────────── */
        .widget-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .widget-card {
            background: #f8faff;
            border-radius: 14px;
            padding: 1rem;
            border: 1.5px solid #eef3fc;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
        }

        .widget-card:hover {
            background: #eef3fc;
        }

        .widget-card .count {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1a3a8a;
        }

        .widget-card .label {
            font-size: 0.75rem;
            color: #4a6a9f;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .widget-card .show-link {
            font-size: 0.65rem;
            color: #6a8ab0;
            background: none;
            border: none;
            padding: 0;
            margin-top: 0.2rem;
            cursor: pointer;
            text-align: left;
            font-weight: 600;
            transition: color 0.2s ease;
            text-decoration: none;
            align-self: flex-start;
        }

        .widget-card .show-link:hover {
            color: #1a3a8a;
            text-decoration: underline;
        }

        .news-list {
            list-style: none;
            padding: 0;
        }

        .news-item {
            background: #f8faff;
            border-radius: 12px;
            padding: 0.7rem 1rem;
            margin-bottom: 0.6rem;
            border: 1px solid #eef3fc;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .news-item .title {
            font-weight: 600;
            color: #0b2559;
            font-size: 0.9rem;
        }

        .news-item .date {
            font-size: 0.7rem;
            color: #6a8ab0;
        }

        /* ───────────── FORUMS ───────────── */
        .search-bar {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .search-bar input {
            flex: 1;
            height: 40px;
            padding: 0 1rem;
            border: 1.5px solid #d6e2f5;
            border-radius: 10px;
            font-size: 0.9rem;
            background: #f8faff;
            outline: none;
            transition: all 0.2s ease;
        }

        .search-bar input:focus {
            border-color: #1a3a8a;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(26, 58, 138, 0.10);
        }

        .forum-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .forum-card {
            background: #f8faff;
            border-radius: 14px;
            padding: 1rem;
            border: 1.5px solid #eef3fc;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .forum-card:hover {
            background: #eef3fc;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 20, 60, 0.08);
        }

        .forum-card .forum-title {
            font-weight: 700;
            color: #0b2559;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .forum-card .forum-desc {
            font-size: 0.8rem;
            color: #4a6a9f;
            margin-bottom: 0.5rem;
        }

        .forum-card .forum-meta {
            font-size: 0.7rem;
            color: #6a8ab0;
            display: flex;
            justify-content: space-between;
        }

        /* ───────────── FORUM DETAIL (Threads) ───────────── */
        .forum-detail-view, .thread-detail-view {
            display: none;
        }

        .forum-detail-view.active, .thread-detail-view.active {
            display: block;
        }

        .forum-detail-header {
            background: #f4f8ff;
            border-radius: 14px;
            padding: 1.2rem;
            border: 1.5px solid #dce6f5;
            margin-bottom: 1.5rem;
        }

        .forum-detail-header h2 {
            font-size: 1.3rem;
            color: #0b2559;
        }

        .forum-detail-header p {
            color: #4a6a9f;
            margin-top: 0.2rem;
        }

        .thread-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.8rem;
        }

        .thread-card {
            background: #f8faff;
            border-radius: 12px;
            padding: 0.8rem 1rem;
            border: 1px solid #eef3fc;
            display: flex;
            flex-direction: column;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .thread-card:hover {
            background: #eef3fc;
        }

        .thread-card .thread-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .thread-card .thread-title {
            font-weight: 600;
            color: #0b2559;
            font-size: 0.95rem;
            flex: 1;
        }

        .thread-card .thread-meta {
            font-size: 0.7rem;
            color: #6a8ab0;
            display: flex;
            gap: 0.8rem;
            margin-top: 0.2rem;
        }

        .thread-card .thread-desc {
            font-size: 0.8rem;
            color: #4a6a9f;
            margin-top: 0.2rem;
        }

        .thread-card .thread-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            margin-top: 0.3rem;
        }

        /* ── Text‑only action buttons ── */
        .action-btn {
            background: none;
            border: none;
            padding: 0;
            font-size: 0.7rem;
            font-weight: 600;
            color: #6a8ab0;
            cursor: pointer;
            transition: color 0.2s ease;
            text-decoration: none;
            margin-right: 0.3rem;
        }

        .action-btn:hover {
            color: #1a3a8a;
            text-decoration: underline;
        }

        .action-btn.report-btn:hover {
            color: #b71c1c;
        }

        .action-btn.liked {
            color: #1a3a8a;
        }

        .action-btn.liked:hover {
            color: #0f2a6a;
        }

        .toggle-replies-btn {
            font-size: 0.7rem;
            font-weight: 600;
            color: #1a3a8a;
            background: none;
            border: none;
            padding: 0.1rem 0.4rem;
            cursor: pointer;
            text-decoration: underline dotted;
        }

        .toggle-replies-btn:hover {
            color: #0f2a6a;
        }

        /* ───────────── THREAD DETAIL ───────────── */
        .thread-detail-header {
            background: #f4f8ff;
            border-radius: 14px;
            padding: 1.2rem;
            border: 1.5px solid #dce6f5;
            margin-bottom: 1.5rem;
        }

        .thread-detail-header .author {
            font-weight: 700;
            color: #0b2559;
        }

        .thread-detail-header .date {
            color: #6a8ab0;
            font-size: 0.8rem;
        }

        .thread-detail-header .description {
            margin-top: 0.5rem;
            color: #1a3a8a;
        }

        .thread-detail-header .image-placeholder {
            margin-top: 0.5rem;
            background: #dce6f5;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            color: #6a8ab0;
            font-size: 0.9rem;
        }

        .thread-detail-header .thread-actions {
            margin-top: 0.5rem;
        }

        /* ───────────── COMMENTS SECTION ───────────── */
        .comment-box {
            margin-top: 1.5rem;
            background: #f8faff;
            border-radius: 14px;
            padding: 1rem;
            border: 1px solid #eef3fc;
        }

        .comment-box textarea {
            width: 100%;
            min-height: 60px;
            padding: 0.5rem 0.8rem;
            border: 1.5px solid #d6e2f5;
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: inherit;
            background: #ffffff;
            outline: none;
            resize: vertical;
            transition: border-color 0.2s ease;
        }

        .comment-box textarea:focus {
            border-color: #1a3a8a;
            box-shadow: 0 0 0 4px rgba(26, 58, 138, 0.10);
        }

        .comment-box .comment-toolbar {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            flex-wrap: wrap;
        }

        .comment-box .file-label {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.8rem;
            background: #eef3fc;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #4a6a9f;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .comment-box .file-label:hover {
            background: #dce6f5;
        }

        .comment-box .file-label input[type="file"] {
            display: none;
        }

        .comment-box .post-btn {
            margin-left: auto;
            padding: 0.3rem 1.2rem;
            background: #1a3a8a;
            color: #fff;
            border: none;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .comment-box .post-btn:hover {
            background: #0f2a6a;
        }

        .comment-box .image-preview {
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #eef3fc;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            color: #1a3a8a;
        }

        .comment-box .image-preview .remove-image {
            cursor: pointer;
            color: #b71c1c;
            font-weight: 700;
        }

        /* ───────────── COMMENT LIST ───────────── */
        .comments-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .comment-item {
            background: #f8faff;
            border-radius: 12px;
            padding: 0.8rem 1rem;
            margin-top: 0.6rem;
            border: 1px solid #eef3fc;
        }

        .comment-item .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .comment-item .comment-author {
            font-weight: 700;
            color: #0b2559;
            font-size: 0.85rem;
        }

        .comment-item .comment-date {
            font-size: 0.65rem;
            color: #8aa3c0;
        }

        .comment-item .comment-text {
            margin-top: 0.2rem;
            font-size: 0.85rem;
            color: #1a3a8a;
            line-height: 1.4;
        }

        .comment-item .comment-image {
            margin-top: 0.3rem;
            max-width: 100%;
            border-radius: 8px;
            max-height: 200px;
            object-fit: cover;
        }

        .comment-item .comment-actions {
            margin-top: 0.3rem;
            display: flex;
            gap: 0.5rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .comment-item .replies-list {
            margin-top: 0.4rem;
            padding-left: 1.5rem;
            border-left: 2px solid #dce6f5;
        }

        .comment-item .reply-item {
            background: #f8faff;
            border-radius: 10px;
            padding: 0.5rem 0.8rem;
            margin-top: 0.3rem;
            border: 1px solid #eef3fc;
        }

        .comment-item .reply-item .reply-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .comment-item .reply-item .reply-author {
            font-weight: 700;
            color: #0b2559;
            font-size: 0.8rem;
        }

        .comment-item .reply-item .reply-date {
            font-size: 0.6rem;
            color: #8aa3c0;
        }

        .comment-item .reply-item .reply-text {
            margin-top: 0.1rem;
            font-size: 0.8rem;
            color: #1a3a8a;
        }

        .comment-item .reply-item .reply-image {
            margin-top: 0.2rem;
            max-width: 100%;
            border-radius: 6px;
            max-height: 120px;
            object-fit: cover;
        }

        .comment-item .reply-item .reply-actions {
            margin-top: 0.2rem;
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        /* ───────────── INLINE REPLY TEXT FIELD ───────────── */
        .inline-reply-field {
            margin-top: 0.3rem;
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .inline-reply-field .reply-row {
            display: flex;
            gap: 0.3rem;
            align-items: center;
        }

        .inline-reply-field input[type="text"] {
            flex: 1;
            height: 30px;
            padding: 0 0.5rem;
            border: 1.5px solid #d6e2f5;
            border-radius: 8px;
            font-size: 0.8rem;
            background: #ffffff;
            outline: none;
        }

        .inline-reply-field input[type="text"]:focus {
            border-color: #1a3a8a;
            box-shadow: 0 0 0 3px rgba(26, 58, 138, 0.10);
        }

        .inline-reply-field .file-label-small {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            padding: 0.1rem 0.5rem;
            background: #eef3fc;
            border-radius: 16px;
            font-size: 0.65rem;
            font-weight: 600;
            color: #4a6a9f;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .inline-reply-field .file-label-small:hover {
            background: #dce6f5;
        }

        .inline-reply-field .file-label-small input[type="file"] {
            display: none;
        }

        .inline-reply-field .send-reply-btn {
            padding: 0.1rem 0.8rem;
            background: #1a3a8a;
            color: #fff;
            border: none;
            border-radius: 16px;
            font-size: 0.7rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .inline-reply-field .send-reply-btn:hover {
            background: #0f2a6a;
        }

        .inline-reply-field .reply-image-preview {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            background: #eef3fc;
            padding: 0.1rem 0.6rem;
            border-radius: 16px;
            font-size: 0.65rem;
            color: #1a3a8a;
        }

        .inline-reply-field .reply-image-preview .remove-image-small {
            cursor: pointer;
            color: #b71c1c;
            font-weight: 700;
        }

        /* ───────────── GLOWING LIKE BUTTON ───────────── */
        .like-btn-glow {
            position: relative;
        }

        .like-btn-glow.liked {
            color: #1a3a8a;
        }

        .like-btn-glow.liked::after {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            right: -4px;
            bottom: -4px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(26, 58, 138, 0.25) 0%, transparent 70%);
            animation: glowPulse 1.5s ease-in-out infinite;
            z-index: 0;
        }

        .like-btn-glow.liked .action-btn {
            position: relative;
            z-index: 1;
        }

        @keyframes glowPulse {
            0%, 100% { opacity: 0.6; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.2); }
        }

        .like-btn-glow .action-btn {
            position: relative;
            z-index: 1;
        }

        /* ───────────── ACCOUNT SETTINGS ───────────── */
        .settings-form {
            max-width: 400px;
        }

        .settings-form .form-group {
            margin-bottom: 1rem;
        }

        .settings-form label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            color: #0b2559;
            margin-bottom: 0.25rem;
        }

        .settings-form input {
            width: 100%;
            height: 40px;
            padding: 0 0.9rem;
            border: 1.5px solid #d6e2f5;
            border-radius: 10px;
            font-size: 0.88rem;
            background: #f8faff;
            outline: none;
            transition: all 0.2s ease;
        }

        .settings-form input:focus {
            border-color: #1a3a8a;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(26, 58, 138, 0.10);
        }

        .settings-form .submit-btn {
            background: #1a3a8a;
            color: #fff;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .settings-form .submit-btn:hover {
            background: #0f2a6a;
        }

        .log-list {
            list-style: none;
            padding: 0;
        }

        .log-item {
            background: #f8faff;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            margin-bottom: 0.4rem;
            border: 1px solid #eef3fc;
            font-size: 0.8rem;
            color: #4a6a9f;
            display: flex;
            justify-content: space-between;
        }

        .message {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .message.success { background: #e4f6ed; color: #0f7b3a; }
        .message.error { background: #fde8e8; color: #b71c1c; }

        /* ───────────── RESPONSIVE ───────────── */
        @media (max-width: 768px) {
            body { flex-direction: column; padding: 0.5rem; }
            .sidebar {
                width: 100%;
                flex-direction: row;
                flex-wrap: wrap;
                padding: 0.8rem 1rem;
                margin-right: 0;
                margin-bottom: 0.8rem;
                position: static;
                gap: 0.3rem;
            }
            .sidebar .brand { display: none; }
            .sidebar .nav-item {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }
            .sidebar .nav-item.logout {
                margin-top: 0;
                border-top: none;
                padding-top: 0;
            }
            .main-content { padding: 1rem; }
            .widget-grid { grid-template-columns: 1fr 1fr; }
            .forum-grid { grid-template-columns: 1fr 1fr; }
            .comment-box .comment-toolbar { flex-wrap: wrap; }
        }

        @media (max-width: 480px) {
            .widget-grid { grid-template-columns: 1fr; }
            .forum-grid { grid-template-columns: 1fr; }
            .sidebar .nav-item { font-size: 0.7rem; padding: 0.3rem 0.6rem; }
            .sidebar .nav-item .nav-icon { font-size: 1rem; }
            .comment-item .replies-list { padding-left: 0.8rem; }
        }
    </style>
</head>
<body>

    <!-- ─── SIDEBAR ─── -->
    <aside class="sidebar">
        <div class="brand">CloudSpacePH</div>
        <div class="nav-item active" data-section="dashboard">
            <span class="nav-icon">📊</span> Dashboard
        </div>
        <div class="nav-item" data-section="forums">
            <span class="nav-icon">💬</span> Forums
        </div>
        <div class="nav-item" data-section="settings">
            <span class="nav-icon">⚙️</span> Account Settings
        </div>
        <div class="nav-item logout" id="logoutBtn">
            <span class="nav-icon">🚪</span> Logout
        </div>
    </aside>

    <!-- ─── MAIN CONTENT ─── -->
    <main class="main-content">

        <!-- ========== DASHBOARD SECTION ========== -->
        <div id="section-dashboard" class="section active">
            <div class="section-title">Dashboard</div>

            <!-- User Card (profile) -->
            <div class="user-card" id="userCard">
                <div class="user-avatar" id="userAvatar">?</div>
                <div class="user-info">
                    <div class="name" id="userName">Loading...</div>
                    <div class="detail" id="userDetail">Account: <span>—</span> &bull; ID: <span>—</span></div>
                    <div class="email-display" id="userEmailDisplay">📧 email@example.com</div>
                    <div class="detail" style="font-size:0.7rem; color:#8aa3c0; margin-top:0.2rem;" id="userSince">Joined: —</div>
                </div>
            </div>

            <!-- Widgets -->
            <div class="widget-grid">
                <div class="widget-card">
                    <div class="count" id="notifCount">6</div>
                    <div class="label">Notifications</div>
                    <button class="show-link" onclick="alert('Show all notifications (including violations & reports)')">Show</button>
                </div>
                <div class="widget-card">
                    <div class="count" id="postCount">5</div>
                    <div class="label">Your Posts</div>
                    <button class="show-link" onclick="alert('Show your posts')">Show</button>
                </div>
                <div class="widget-card">
                    <div class="count" id="savedCount">2</div>
                    <div class="label">Saved Forums</div>
                    <button class="show-link" onclick="alert('Show saved forums')">Show</button>
                </div>
                <div class="widget-card">
                    <div class="count" id="likeCount">7</div>
                    <div class="label">Total Likes</div>
                    <button class="show-link" onclick="alert('Show total likes on your posts/threads')">Show</button>
                </div>
            </div>

            <!-- Daily News -->
            <h3 style="font-size:1rem; margin-bottom:0.5rem; color:#0b2559;">📰 Daily News</h3>
            <ul class="news-list" id="newsList">
                <li class="news-item"><span class="title">CloudSpacePH launches new security features</span><span class="date">2h ago</span></li>
                <li class="news-item"><span class="title">Forum upgrade scheduled for this weekend</span><span class="date">5h ago</span></li>
                <li class="news-item"><span class="title">New API endpoints for developers</span><span class="date">1d ago</span></li>
            </ul>
        </div>

        <!-- ========== FORUMS SECTION ========== -->
        <div id="section-forums" class="section">
            <div class="section-title" id="forumSectionTitle">
                <span>Forums</span>
            </div>

            <!-- Search Bar (visible only in grid view) -->
            <div class="search-bar" id="forumSearchBar">
                <input type="text" id="forumSearch" placeholder="Search forums..." />
                <button style="padding:0 1.2rem; background:#1a3a8a; color:#fff; border:none; border-radius:10px; font-weight:700; cursor:pointer;">Search</button>
            </div>

            <!-- Forum Grid -->
            <div class="forum-grid" id="forumGrid">
                <!-- Cards will be populated by JavaScript -->
            </div>

            <!-- Forum Detail View (list of threads) -->
            <div class="forum-detail-view" id="forumDetailView">
                <div class="forum-detail-header">
                    <h2 id="detailForumTitle">Forum Title</h2>
                    <p id="detailForumDesc">Forum description</p>
                </div>
                <div class="thread-grid" id="threadGrid">
                    <!-- Thread cards will be injected here -->
                </div>
            </div>

            <!-- Thread Detail View (full thread with comments) -->
            <div class="thread-detail-view" id="threadDetailView">
                <div class="thread-detail-header">
                    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.3rem;">
                        <div>
                            <span class="author" id="threadAuthor">User</span>
                            <span class="date" id="threadDate">Date</span>
                        </div>
                        <button class="back-btn" id="backToThreadsBtn">← Back to Threads</button>
                    </div>
                    <div class="description" id="threadDescription">Thread description</div>
                    <div class="image-placeholder" id="threadImage">📷 Optional image</div>
                    <div class="thread-actions" style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap;">
                        <span class="like-btn-glow" id="likeWrapper">
                            <button class="action-btn like-thread-btn" id="likeThreadBtn">Like (0)</button>
                        </span>
                        <button class="action-btn report-btn" id="reportThreadBtn">Report Thread</button>
                    </div>
                </div>

                <!-- Comment Box -->
                <div class="comment-box">
                    <textarea id="commentInput" placeholder="Write a comment..." rows="2"></textarea>
                    <div class="comment-toolbar">
                        <label class="file-label">
                            📎 Attach Image
                            <input type="file" id="commentImageInput" accept="image/*" />
                        </label>
                        <span id="commentImagePreview" class="image-preview" style="display:none;">
                            <span id="commentImageName"></span>
                            <span class="remove-image" id="removeCommentImage">✕</span>
                        </span>
                        <button class="post-btn" id="postCommentBtn">Post Comment</button>
                    </div>
                </div>

                <h4 style="margin:1rem 0 0.5rem; color:#0b2559;">Comments</h4>
                <ul class="comments-list" id="commentsList">
                    <!-- Comments will be rendered here -->
                </ul>
            </div>
        </div>

        <!-- ========== ACCOUNT SETTINGS SECTION ========== -->
        <div id="section-settings" class="section">
            <div class="section-title">Account Settings</div>

            <div style="display:grid; gap:1.5rem;">
                <!-- Change Password -->
                <div style="background:#f8faff; border-radius:14px; padding:1.2rem; border:1.5px solid #eef3fc;">
                    <h4 style="margin-bottom:0.75rem; color:#0b2559;">Change Password</h4>
                    <form class="settings-form" id="changePasswordForm">
                        <div class="form-group">
                            <label for="currentPass">Current Password</label>
                            <input type="password" id="currentPass" placeholder="Enter current password" required />
                        </div>
                        <div class="form-group">
                            <label for="newPass">New Password</label>
                            <input type="password" id="newPass" placeholder="Enter new password" required />
                        </div>
                        <div class="form-group">
                            <label for="confirmPass">Confirm New Password</label>
                            <input type="password" id="confirmPass" placeholder="Confirm new password" required />
                        </div>
                        <button type="submit" class="submit-btn">Update Password</button>
                    </form>
                    <div id="passwordMessage" class="message" style="margin-top:0.5rem;"></div>
                </div>

                <!-- Action Logs -->
                <div style="background:#f8faff; border-radius:14px; padding:1.2rem; border:1.5px solid #eef3fc;">
                    <h4 style="margin-bottom:0.75rem; color:#0b2559;">Recent Account Actions</h4>
                    <ul class="log-list" id="actionLogs">
                        <li class="log-item"><span>Logged in</span><span>2026-06-25 14:32</span></li>
                        <li class="log-item"><span>Changed password</span><span>2026-06-24 09:15</span></li>
                        <li class="log-item"><span>Updated profile</span><span>2026-06-23 18:40</span></li>
                    </ul>
                </div>

                <!-- Reports -->
                <div style="background:#f8faff; border-radius:14px; padding:1.2rem; border:1.5px solid #eef3fc;">
                    <h4 style="margin-bottom:0.75rem; color:#0b2559;">Reports</h4>
                    <p style="color:#4a6a9f; font-size:0.9rem;">Download your activity report or contact support.</p>
                    <button style="margin-top:0.5rem; background:#1a3a8a; color:#fff; border:none; padding:0.4rem 1.2rem; border-radius:8px; font-weight:600; cursor:pointer;">Download Report</button>
                </div>
            </div>
        </div>

    </main>

    <script>
        // ──────────────────────────────────────────────
        // 0. CACHE HELPERS
        // ──────────────────────────────────────────────
        const CACHE_EXPIRY = 5 * 60 * 1000; // 5 minutes

        function getCached(key) {
            try {
                const raw = localStorage.getItem(key);
                if (!raw) return null;
                const data = JSON.parse(raw);
                if (Date.now() - data.timestamp > CACHE_EXPIRY) {
                    localStorage.removeItem(key);
                    return null;
                }
                return data.value;
            } catch {
                return null;
            }
        }

        function setCached(key, value) {
            try {
                localStorage.setItem(key, JSON.stringify({
                    value: value,
                    timestamp: Date.now()
                }));
            } catch {}
        }

        function clearCache() {
            ['cloudspace_profile', 'cloudspace_forums'].forEach(key => localStorage.removeItem(key));
        }

        // ──────────────────────────────────────────────
        // 1. NAVIGATION (sidebar switching)
        // ──────────────────────────────────────────────
        const navItems = document.querySelectorAll('.nav-item:not(.logout)');
        const sections = {
            dashboard: document.getElementById('section-dashboard'),
            forums: document.getElementById('section-forums'),
            settings: document.getElementById('section-settings'),
        };

        navItems.forEach(item => {
            item.addEventListener('click', () => {
                const section = item.dataset.section;
                navItems.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                Object.keys(sections).forEach(key => {
                    sections[key].classList.toggle('active', key === section);
                });
                if (section === 'forums') {
                    showForumGrid();
                }
            });
        });

        // ──────────────────────────────────────────────
        // 2. LOGOUT (clears cache)
        // ──────────────────────────────────────────────
        document.getElementById('logoutBtn').addEventListener('click', () => {
            clearCache();
            localStorage.removeItem('cloudspace_username');
            window.location.href = 'login';
        });

        // ──────────────────────────────────────────────
        // 3. CHECK AUTHENTICATION
        // ──────────────────────────────────────────────
        const username = localStorage.getItem('cloudspace_username');
        if (!username) {
            window.location.href = 'login';
        }

        // ──────────────────────────────────────────────
        // 4. FETCH PROFILE VIA auth-api (with caching)
        // ──────────────────────────────────────────────
        async function loadProfile() {
            const userNameEl = document.getElementById('userName');
            const userDetailEl = document.getElementById('userDetail');
            const userEmailDisplay = document.getElementById('userEmailDisplay');
            const userSinceEl = document.getElementById('userSince');
            const userAvatar = document.getElementById('userAvatar');

            const cacheKey = 'cloudspace_profile';
            const cached = getCached(cacheKey);
            if (cached) {
                console.log('📦 Using cached profile');
                displayProfile(cached);
                return;
            }

            try {
                const res = await fetch('auth-api', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'getProfile',
                        username: username
                    })
                });
                const data = await res.json();

                if (data.status !== 'success' || !data.profile) {
                    throw new Error(data.message || 'Profile not found');
                }

                setCached(cacheKey, data.profile);
                displayProfile(data.profile);

            } catch (error) {
                console.error('❌ Error loading profile:', error);
                userAvatar.textContent = username.charAt(0).toUpperCase();
                userNameEl.textContent = username;
                userEmailDisplay.textContent = '⚠️ Email not available';
                userEmailDisplay.style.display = 'inline-block';
                userDetailEl.innerHTML = `<span class="warning">⚠️ Profile data unavailable</span>`;
                userSinceEl.textContent = 'Joined: —';
            }
        }

        function displayProfile(userData) {
            const userNameEl = document.getElementById('userName');
            const userDetailEl = document.getElementById('userDetail');
            const userEmailDisplay = document.getElementById('userEmailDisplay');
            const userSinceEl = document.getElementById('userSince');
            const userAvatar = document.getElementById('userAvatar');

            const email = userData.email || '—';
            const displayUsername = userData.username || username;
            const accountType = userData.account_type || '1';
            const joinedDate = userData.date_created || '—';
            const userId = userData.id || '—';

            userAvatar.textContent = (email !== '—' ? email : displayUsername).charAt(0).toUpperCase();
            userNameEl.textContent = displayUsername;
            userEmailDisplay.textContent = `📧 ${email}`;
            userEmailDisplay.style.display = 'inline-block';
            userDetailEl.innerHTML = `Account: <span>${accountType}</span> &bull; ID: <span>${userId}</span>`;
            userSinceEl.textContent = `Joined: ${joinedDate}`;
        }

        // ──────────────────────────────────────────────
        // 5. FORUM DATA (with comments & replies)
        // ──────────────────────────────────────────────
        const forumCacheKey = 'cloudspace_forums';

        let nextCommentId = 1;
        let nextThreadId = 1;

        function createComment(author, date, text, image = null, likes = 0) {
            return {
                id: nextCommentId++,
                author,
                date,
                text,
                image,
                likes,
                liked: false,
                replies: []
            };
        }

        function createThread(author, date, title, description, image = null, likes = 0, comments = []) {
            return {
                id: nextThreadId++,
                author,
                date,
                title,
                description,
                image,
                likes,
                liked: false,
                comments: comments.length ? comments : [
                    createComment('alice', '2026-06-25 14:30', 'Great question! I\'ve been using AWS for years.', null, 2),
                    createComment('bob', '2026-06-25 15:10', 'Azure has some nice features too.', null, 1)
                ]
            };
        }

        // Static forum data with sample comments
        const staticForumData = [
            {
                id: 1,
                title: 'General Discussion',
                desc: 'Talk about anything related to cloud computing and services.',
                threads: [
                    createThread('Admin', '2026-06-24', 'Welcome to the community!', 'A warm welcome to all new members. Feel free to introduce yourself and share your interests.', null, 5),
                    createThread('johndoe', '2026-06-23', 'What cloud provider do you use?', 'I am currently using AWS and Azure. What about you? Let\'s discuss the pros and cons.', null, 3),
                    createThread('devguru', '2026-06-22', 'Best practices for scaling', 'We need to scale our application. Any recommendations on load balancing and auto-scaling?', null, 0)
                ]
            },
            {
                id: 2,
                title: 'Security & Privacy',
                desc: 'Share tips and ask questions about securing your data.',
                threads: [
                    createThread('securitypro', '2026-06-25', 'How to enable 2FA', 'A step-by-step guide to enabling two-factor authentication on your account.', null, 2),
                    createThread('cryptoknight', '2026-06-24', 'Data encryption best practices', 'What encryption standards do you recommend for sensitive data? AES-256?', null, 0)
                ]
            },
            {
                id: 3,
                title: 'Developer Corner',
                desc: 'Code snippets, API integration, and dev tools.',
                threads: [
                    createThread('devteam', '2026-06-25', 'New API SDK released', 'We have just released a new Python SDK for our API. Check it out!', null, 0)
                ]
            }
        ];

        let forumData = [];

        function loadForumData() {
            const cached = getCached(forumCacheKey);
            if (cached) {
                console.log('📦 Using cached forums');
                forumData = cached;
                return true;
            }
            forumData = staticForumData;
            // Ensure all comments have IDs
            forumData.forEach(f => {
                f.threads.forEach(t => {
                    if (t.comments) {
                        t.comments.forEach(c => {
                            if (!c.id) c.id = nextCommentId++;
                            if (c.replies) {
                                c.replies.forEach(r => { if (!r.id) r.id = nextCommentId++; });
                            }
                        });
                    }
                });
            });
            setCached(forumCacheKey, forumData);
            console.log('📦 Forums cached');
            return true;
        }

        // ──────────────────────────────────────────────
        // 6. RENDER FORUM GRID
        // ──────────────────────────────────────────────
        const forumGrid = document.getElementById('forumGrid');
        const forumDetailView = document.getElementById('forumDetailView');
        const forumSearchBar = document.getElementById('forumSearchBar');
        const forumSectionTitle = document.getElementById('forumSectionTitle');
        const threadGrid = document.getElementById('threadGrid');
        const threadDetailView = document.getElementById('threadDetailView');

        function renderForumGrid(filter = '') {
            forumGrid.innerHTML = '';
            const filtered = forumData.filter(f =>
                f.title.toLowerCase().includes(filter.toLowerCase()) ||
                f.desc.toLowerCase().includes(filter.toLowerCase())
            );
            if (filtered.length === 0) {
                forumGrid.innerHTML = `<p style="color:#6a8ab0; text-align:center; padding:2rem;">No forums found.</p>`;
                return;
            }
            filtered.forEach(forum => {
                const card = document.createElement('div');
                card.className = 'forum-card';
                card.dataset.forumId = forum.id;
                card.innerHTML = `
                    <div class="forum-title">${forum.title}</div>
                    <div class="forum-desc">${forum.desc}</div>
                    <div class="forum-meta"><span>${forum.threads.length} threads</span></div>
                `;
                card.addEventListener('click', () => showForumDetail(forum.id));
                forumGrid.appendChild(card);
            });
        }

        // ──────────────────────────────────────────────
        // 7. SHOW FORUM DETAIL (list of threads)
        // ──────────────────────────────────────────────
        let currentForumId = null;

        function showForumDetail(forumId) {
            const forum = forumData.find(f => f.id === forumId);
            if (!forum) return;

            currentForumId = forumId;

            forumGrid.style.display = 'none';
            forumSearchBar.style.display = 'none';
            forumDetailView.classList.add('active');
            threadDetailView.classList.remove('active');

            forumSectionTitle.innerHTML = `
                <span>${forum.title}</span>
                <button class="back-btn" id="backToForumsBtn">← Back to Forums</button>
            `;

            document.getElementById('detailForumTitle').textContent = forum.title;
            document.getElementById('detailForumDesc').textContent = forum.desc;

            threadGrid.innerHTML = '';
            if (forum.threads.length === 0) {
                threadGrid.innerHTML = `<p style="color:#6a8ab0; padding:1rem;">No threads yet.</p>`;
            } else {
                forum.threads.forEach(thread => {
                    const card = document.createElement('div');
                    card.className = 'thread-card';
                    card.innerHTML = `
                        <div class="thread-header" data-thread-id="${thread.id}">
                            <span class="thread-title">${thread.title}</span>
                            <span class="thread-meta">
                                <span>by ${thread.author}</span>
                                <span>${thread.date}</span>
                            </span>
                        </div>
                        <div class="thread-desc">${thread.description}</div>
                        <div class="thread-actions">
                            <button class="action-btn report-btn report-thread-btn" data-forum="${forum.id}" data-thread="${thread.id}">Report</button>
                        </div>
                    `;
                    card.addEventListener('click', (e) => {
                        if (e.target.classList.contains('report-thread-btn')) return;
                        showThreadDetail(forum.id, thread.id);
                    });
                    card.querySelector('.report-thread-btn').addEventListener('click', function(e) {
                        e.stopPropagation();
                        reportItem('thread', forum.id, thread.id);
                    });
                    threadGrid.appendChild(card);
                });
            }

            // Attach back to forums button
            const backBtn = document.getElementById('backToForumsBtn');
            if (backBtn) {
                // Remove old listeners by cloning
                const newBackBtn = backBtn.cloneNode(true);
                backBtn.parentNode.replaceChild(newBackBtn, backBtn);
                newBackBtn.addEventListener('click', showForumGrid);
            }
        }

        // ──────────────────────────────────────────────
        // 8. SHOW THREAD DETAIL (with comments)
        // ──────────────────────────────────────────────
        let commentImageData = null;
        let replyImageData = {};
        let activeReplyId = null;

        function showThreadDetail(forumId, threadId) {
            const forum = forumData.find(f => f.id === forumId);
            if (!forum) return;
            const thread = forum.threads.find(t => t.id === threadId);
            if (!thread) return;

            forumDetailView.classList.remove('active');
            threadDetailView.classList.add('active');

            forumSectionTitle.innerHTML = `<span>${thread.title}</span>`;

            document.getElementById('threadAuthor').textContent = thread.author;
            document.getElementById('threadDate').textContent = thread.date;
            document.getElementById('threadDescription').textContent = thread.description;
            const imageContainer = document.getElementById('threadImage');
            if (thread.image) {
                imageContainer.innerHTML = `<img src="${thread.image}" alt="Thread image" style="max-width:100%; border-radius:10px;" />`;
                imageContainer.style.display = 'block';
            } else {
                imageContainer.innerHTML = '📷 No image attached';
                imageContainer.style.display = 'block';
            }

            window.currentThread = { forumId, threadId };

            // Like button for the thread (with glow)
            const likeBtn = document.getElementById('likeThreadBtn');
            const likeWrapper = document.getElementById('likeWrapper');
            const likeCount = thread.likes || 0;
            likeBtn.textContent = thread.liked ? `Unlike (${likeCount})` : `Like (${likeCount})`;
            likeWrapper.classList.toggle('liked', thread.liked);

            likeBtn.onclick = function() {
                const forum2 = forumData.find(f => f.id === forumId);
                if (!forum2) return;
                const thread2 = forum2.threads.find(t => t.id === threadId);
                if (!thread2) return;
                if (thread2.liked) {
                    thread2.likes--;
                    thread2.liked = false;
                } else {
                    thread2.likes = (thread2.likes || 0) + 1;
                    thread2.liked = true;
                }
                setCached(forumCacheKey, forumData);
                showThreadDetail(forumId, threadId);
            };

            document.getElementById('reportThreadBtn').onclick = function() {
                reportItem('thread', forumId, threadId);
            };

            // Render comments
            renderComments(thread.comments, forumId, threadId);

            // Reset comment image preview
            commentImageData = null;
            document.getElementById('commentImagePreview').style.display = 'none';
            document.getElementById('commentImageInput').value = '';
            document.getElementById('commentInput').value = '';

            // Attach back to threads button with proper listener
            const backBtn = document.getElementById('backToThreadsBtn');
            if (backBtn) {
                // Remove old listeners by cloning
                const newBackBtn = backBtn.cloneNode(true);
                backBtn.parentNode.replaceChild(newBackBtn, backBtn);
                newBackBtn.addEventListener('click', function() {
                    // Go back to forum detail view
                    showForumDetail(forumId);
                });
            }
        }

        // ──────────────────────────────────────────────
        // 9. RENDER COMMENTS (with replies)
        // ──────────────────────────────────────────────
        function renderComments(comments, forumId, threadId) {
            const container = document.getElementById('commentsList');
            container.innerHTML = '';

            if (!comments || comments.length === 0) {
                container.innerHTML = `<p style="color:#6a8ab0; padding:0.5rem;">No comments yet. Be the first to comment!</p>`;
                return;
            }

            comments.forEach(comment => {
                const li = document.createElement('li');
                li.className = 'comment-item';
                li.dataset.commentId = comment.id;

                const likeCount = comment.likes || 0;
                const likedClass = comment.liked ? 'liked' : '';

                let imageHtml = '';
                if (comment.image) {
                    imageHtml = `<img src="${comment.image}" alt="Comment image" class="comment-image" />`;
                }

                li.innerHTML = `
                    <div class="comment-header">
                        <span class="comment-author">${comment.author}</span>
                        <span class="comment-date">${comment.date}</span>
                    </div>
                    <div class="comment-text">${comment.text}</div>
                    ${imageHtml}
                    <div class="comment-actions">
                        <span class="like-btn-glow ${likedClass}">
                            <button class="action-btn like-comment-btn" data-comment-id="${comment.id}">Like (${likeCount})</button>
                        </span>
                        <button class="action-btn reply-to-comment-btn" data-comment-id="${comment.id}">Reply</button>
                        <button class="action-btn report-btn report-comment-btn" data-comment-id="${comment.id}">Report</button>
                    </div>
                    <div class="replies-list" id="repliesContainer-${comment.id}"></div>
                `;

                container.appendChild(li);

                // Like comment
                li.querySelector('.like-comment-btn').addEventListener('click', function(e) {
                    e.stopPropagation();
                    const cid = this.dataset.commentId;
                    const commentObj = findCommentById(forumData, forumId, threadId, cid);
                    if (!commentObj) return;
                    if (commentObj.liked) {
                        commentObj.likes--;
                        commentObj.liked = false;
                    } else {
                        commentObj.likes = (commentObj.likes || 0) + 1;
                        commentObj.liked = true;
                    }
                    setCached(forumCacheKey, forumData);
                    showThreadDetail(forumId, threadId);
                });

                // Reply to comment - shows inline text field
                li.querySelector('.reply-to-comment-btn').addEventListener('click', function(e) {
                    e.stopPropagation();
                    const cid = this.dataset.commentId;
                    toggleReplyField(cid, forumId, threadId);
                });

                // Report comment
                li.querySelector('.report-comment-btn').addEventListener('click', function(e) {
                    e.stopPropagation();
                    reportItem('comment', forumId, threadId, comment.id);
                });

                // Render replies for this comment
                const repliesContainer = document.getElementById(`repliesContainer-${comment.id}`);
                if (comment.replies && comment.replies.length > 0) {
                    comment.replies.forEach(reply => {
                        const replyLi = document.createElement('div');
                        replyLi.className = 'reply-item';
                        const replyLikes = reply.likes || 0;
                        const replyLikedClass = reply.liked ? 'liked' : '';
                        let replyImageHtml = '';
                        if (reply.image) {
                            replyImageHtml = `<img src="${reply.image}" alt="Reply image" class="reply-image" />`;
                        }
                        replyLi.innerHTML = `
                            <div class="reply-header">
                                <span class="reply-author">${reply.author}</span>
                                <span class="reply-date">${reply.date}</span>
                            </div>
                            <div class="reply-text">${reply.text}</div>
                            ${replyImageHtml}
                            <div class="reply-actions">
                                <span class="like-btn-glow ${replyLikedClass}">
                                    <button class="action-btn like-reply-btn" data-comment-id="${comment.id}" data-reply-id="${reply.id}">Like (${replyLikes})</button>
                                </span>
                                <button class="action-btn report-btn report-reply-btn" data-comment-id="${comment.id}" data-reply-id="${reply.id}">Report</button>
                            </div>
                        `;
                        repliesContainer.appendChild(replyLi);

                        // Like reply
                        replyLi.querySelector('.like-reply-btn').addEventListener('click', function(e) {
                            e.stopPropagation();
                            const cid = this.dataset.commentId;
                            const rid = this.dataset.replyId;
                            const commentObj = findCommentById(forumData, forumId, threadId, cid);
                            if (!commentObj) return;
                            const replyObj = commentObj.replies.find(r => r.id == rid);
                            if (!replyObj) return;
                            if (replyObj.liked) {
                                replyObj.likes--;
                                replyObj.liked = false;
                            } else {
                                replyObj.likes = (replyObj.likes || 0) + 1;
                                replyObj.liked = true;
                            }
                            setCached(forumCacheKey, forumData);
                            showThreadDetail(forumId, threadId);
                        });

                        // Report reply
                        replyLi.querySelector('.report-reply-btn').addEventListener('click', function(e) {
                            e.stopPropagation();
                            const cid = this.dataset.commentId;
                            const rid = this.dataset.replyId;
                            reportItem('reply', forumId, threadId, cid, rid);
                        });
                    });
                }

                // Check if there's an active reply field for this comment
                if (activeReplyId === comment.id) {
                    const replyField = createReplyField(comment.id, forumId, threadId);
                    li.appendChild(replyField);
                }
            });
        }

        // ──────────────────────────────────────────────
        // 10. TOGGLE REPLY FIELD
        // ──────────────────────────────────────────────
        function toggleReplyField(commentId, forumId, threadId) {
            if (activeReplyId === commentId) {
                activeReplyId = null;
                showThreadDetail(forumId, threadId);
                return;
            }
            activeReplyId = commentId;
            showThreadDetail(forumId, threadId);
        }

        // ──────────────────────────────────────────────
        // 11. CREATE REPLY FIELD (inline text field)
        // ──────────────────────────────────────────────
        function createReplyField(commentId, forumId, threadId) {
            const container = document.createElement('div');
            container.className = 'inline-reply-field';
            container.dataset.commentId = commentId;

            const row = document.createElement('div');
            row.className = 'reply-row';

            const input = document.createElement('input');
            input.type = 'text';
            input.placeholder = 'Write a reply...';
            input.id = `replyInput-${commentId}`;

            const fileLabel = document.createElement('label');
            fileLabel.className = 'file-label-small';
            fileLabel.innerHTML = '📎';
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = 'image/*';
            fileInput.id = `replyImageInput-${commentId}`;
            fileLabel.appendChild(fileInput);

            const sendBtn = document.createElement('button');
            sendBtn.className = 'send-reply-btn';
            sendBtn.textContent = 'Post';
            sendBtn.dataset.commentId = commentId;

            row.appendChild(input);
            row.appendChild(fileLabel);
            row.appendChild(sendBtn);
            container.appendChild(row);

            // Image preview for reply
            const previewContainer = document.createElement('div');
            previewContainer.className = 'reply-image-preview';
            previewContainer.style.display = 'none';
            previewContainer.id = `replyPreview-${commentId}`;
            previewContainer.innerHTML = `
                <span id="replyImageName-${commentId}"></span>
                <span class="remove-image-small" id="removeReplyImage-${commentId}">✕</span>
            `;
            container.appendChild(previewContainer);

            // File input change
            fileInput.addEventListener('change', function() {
                const preview = document.getElementById(`replyPreview-${commentId}`);
                const nameSpan = document.getElementById(`replyImageName-${commentId}`);
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        replyImageData[commentId] = e.target.result;
                        nameSpan.textContent = this.files[0].name;
                        preview.style.display = 'flex';
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Remove reply image
            document.getElementById(`removeReplyImage-${commentId}`).addEventListener('click', function() {
                delete replyImageData[commentId];
                document.getElementById(`replyPreview-${commentId}`).style.display = 'none';
                document.getElementById(`replyImageInput-${commentId}`).value = '';
            });

            // Send reply
            sendBtn.addEventListener('click', function() {
                const cid = this.dataset.commentId;
                const inputField = document.getElementById(`replyInput-${cid}`);
                const text = inputField.value.trim();
                if (!text) return;

                const forum = forumData.find(f => f.id === forumId);
                if (!forum) return;
                const thread = forum.threads.find(t => t.id === threadId);
                if (!thread) return;
                const commentObj = findCommentById(forumData, forumId, threadId, cid);
                if (!commentObj) return;

                const now = new Date();
                const dateStr = now.toISOString().slice(0,10) + ' ' + now.toTimeString().slice(0,8);
                const newReply = {
                    id: nextCommentId++,
                    author: localStorage.getItem('cloudspace_username') || 'Anonymous',
                    date: dateStr,
                    text: text,
                    image: replyImageData[cid] || null,
                    likes: 0,
                    liked: false
                };
                commentObj.replies.push(newReply);
                delete replyImageData[cid];
                activeReplyId = null;
                setCached(forumCacheKey, forumData);
                showThreadDetail(forumId, threadId);
            });

            // Enter key to submit
            input.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    sendBtn.click();
                }
            });

            return container;
        }

        // ──────────────────────────────────────────────
        // 12. FIND COMMENT BY ID
        // ──────────────────────────────────────────────
        function findCommentById(forumData, forumId, threadId, commentId) {
            const forum = forumData.find(f => f.id === forumId);
            if (!forum) return null;
            const thread = forum.threads.find(t => t.id === threadId);
            if (!thread) return null;
            return thread.comments.find(c => c.id == commentId);
        }

        // ──────────────────────────────────────────────
        // 13. POST COMMENT
        // ──────────────────────────────────────────────
        document.getElementById('postCommentBtn').addEventListener('click', function() {
            const { forumId, threadId } = window.currentThread || {};
            if (!forumId || !threadId) return;

            const input = document.getElementById('commentInput');
            const text = input.value.trim();
            if (!text) return;

            const forum = forumData.find(f => f.id === forumId);
            if (!forum) return;
            const thread = forum.threads.find(t => t.id === threadId);
            if (!thread) return;

            const now = new Date();
            const dateStr = now.toISOString().slice(0,10) + ' ' + now.toTimeString().slice(0,8);
            const newComment = {
                id: nextCommentId++,
                author: localStorage.getItem('cloudspace_username') || 'Anonymous',
                date: dateStr,
                text: text,
                image: commentImageData || null,
                likes: 0,
                liked: false,
                replies: []
            };
            thread.comments.push(newComment);
            commentImageData = null;
            document.getElementById('commentImagePreview').style.display = 'none';
            document.getElementById('commentImageInput').value = '';
            input.value = '';
            setCached(forumCacheKey, forumData);
            showThreadDetail(forumId, threadId);
        });

        // ──────────────────────────────────────────────
        // 14. COMMENT IMAGE ATTACHMENT
        // ──────────────────────────────────────────────
        document.getElementById('commentImageInput').addEventListener('change', function() {
            const preview = document.getElementById('commentImagePreview');
            const nameSpan = document.getElementById('commentImageName');
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    commentImageData = e.target.result;
                    nameSpan.textContent = this.files[0].name;
                    preview.style.display = 'flex';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        document.getElementById('removeCommentImage').addEventListener('click', function() {
            commentImageData = null;
            document.getElementById('commentImagePreview').style.display = 'none';
            document.getElementById('commentImageInput').value = '';
        });

        // ──────────────────────────────────────────────
        // 15. REPORT FUNCTION
        // ──────────────────────────────────────────────
        function reportItem(type, forumId, threadId, commentId = null, replyId = null) {
            const reason = prompt(`Why are you reporting this ${type}?`);
            if (reason === null) return;
            if (reason.trim() === '') {
                alert('Please provide a reason.');
                return;
            }
            console.log(`📨 Report submitted: type=${type}, forum=${forumId}, thread=${threadId}, commentId=${commentId}, replyId=${replyId}, reason="${reason}"`);
            alert(`✅ Thank you! Your report has been submitted. We'll review it shortly.`);
        }

        // ──────────────────────────────────────────────
        // 16. CHANGE PASSWORD (simulate)
        // ──────────────────────────────────────────────
        document.getElementById('changePasswordForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const current = document.getElementById('currentPass').value;
            const newPass = document.getElementById('newPass').value;
            const confirm = document.getElementById('confirmPass').value;
            const msg = document.getElementById('passwordMessage');

            if (!current || !newPass || !confirm) {
                msg.className = 'message error';
                msg.textContent = 'All fields are required.';
                return;
            }
            if (newPass.length < 8) {
                msg.className = 'message error';
                msg.textContent = 'New password must be at least 8 characters.';
                return;
            }
            if (newPass !== confirm) {
                msg.className = 'message error';
                msg.textContent = 'Passwords do not match.';
                return;
            }

            msg.className = 'message success';
            msg.textContent = 'Password updated successfully! (Demo)';
            document.getElementById('changePasswordForm').reset();
        });

        // ──────────────────────────────────────────────
        // 17. SHOW FORUM GRID (back from forum detail)
        // ──────────────────────────────────────────────
        function showForumGrid() {
            forumGrid.style.display = 'grid';
            forumSearchBar.style.display = 'flex';
            forumDetailView.classList.remove('active');
            threadDetailView.classList.remove('active');
            forumSectionTitle.innerHTML = `<span>Forums</span>`;
            const searchVal = document.getElementById('forumSearch').value;
            renderForumGrid(searchVal);
        }

        // ──────────────────────────────────────────────
        // 18. FORUM SEARCH
        // ──────────────────────────────────────────────
        document.getElementById('forumSearch').addEventListener('input', function() {
            if (forumGrid.style.display !== 'none') {
                renderForumGrid(this.value);
            }
        });

        // ──────────────────────────────────────────────
        // 19. INIT
        // ──────────────────────────────────────────────
        loadForumData();
        loadProfile();
        renderForumGrid();
    </script>
</body>
</html>