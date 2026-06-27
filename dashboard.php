<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <title>CloudSpacePH • Dashboard</title>
    <!-- Google Font: Open Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet" />
    <style>
        /* ── Reset & Base ── */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: #f5ede4;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            padding: 0;
            margin: 0;
            position: relative;
        }

        /* ── Main Layout: Sidebar + Content ── */
        .app {
            display: flex;
            width: 100%;
            max-width: 1440px;
            min-height: 100vh;
            background: #fcf8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        /* ── Sidebar ── */
        .sidebar {
            flex: 0 0 auto;              /* don't grow or shrink */
            width: fit-content;           /* size to content */
            min-width: 200px;             /* ensure a minimum for readability */
            max-width: 320px;             /* prevent it from becoming too wide */
            background: #fcf8f0;
            padding: 2rem 1.8rem;
            border-right: 3px solid #b2c9ab;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            /* Hide scrollbars while keeping scroll functionality */
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;     /* Firefox */
        }
        /* Hide scrollbar for Chrome, Safari and Opera */
        .sidebar::-webkit-scrollbar,
        .main::-webkit-scrollbar {
            display: none;
        }

        .sidebar .brand {
            font-size: 1.6rem;
            font-weight: 900;
            color: #1a2a5e;
            text-transform: uppercase;
            letter-spacing: -0.02em;
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .sidebar .brand span {
            background: #b2c9ab;
            padding: 0.2rem 0.6rem;
            border-radius: 30px;
            font-size: 0.8rem;
            color: #0a0a2e;
        }

        .sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex: 1;
        }

        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.8rem 1.2rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            color: #1a2a5e;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent;
            border: none;
            cursor: pointer;
            width: 100%;
            text-align: left;
            font-family: inherit;
            white-space: nowrap;  /* keep text on one line */
        }

        .sidebar nav a:hover {
            background: #e8d5c4;
            transform: translateX(4px);
        }

        .sidebar nav a.active {
            background: #b2c9ab;
            color: #0a0a2e;
            box-shadow: none;
        }

        .sidebar .logout-btn {
            margin-top: auto;
            background: #f4c2c2;
            border: none;
            padding: 0.7rem 1rem;
            border-radius: 40px;
            font-weight: 700;
            font-size: 0.95rem;
            color: #1a2a5e;
            cursor: pointer;
            transition: all 0.2s ease;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-family: inherit;
            box-shadow: 2px 4px 0 #d4a0a0;
            white-space: nowrap;
        }

        .sidebar .logout-btn:hover {
            background: #e8b4b8;
            transform: translateX(4px);
            box-shadow: 2px 4px 0 #c09090;
        }

        /* Sidebar icons – minimal planet/star drawings */
        .icon-planet {
            display: inline-block;
            width: 24px;
            height: 24px;
            background: radial-gradient(circle at 30% 30%, #f4c2c2, #b2c9ab);
            border-radius: 50%;
            position: relative;
            flex-shrink: 0;
        }
        .icon-planet::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 130%;
            height: 8px;
            background: rgba(178, 201, 171, 0.5);
            border-radius: 50%;
            transform: translate(-50%, -50%) rotate(-20deg);
            border: 1px solid rgba(244, 194, 194, 0.6);
        }
        .icon-star {
            display: inline-block;
            width: 10px;
            height: 10px;
            background: #ffe066;
            border-radius: 50%;
            box-shadow: 0 0 6px #ffe066;
            flex-shrink: 0;
        }

        /* ── Main Content ── */
        .main {
            flex: 1;
            padding: 2rem 2.5rem;
            background: #fcf8f0;
            overflow-y: auto;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* ── PAGES ── */
        .page {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        .page.hidden {
            display: none;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── USER CARD ── */
        .user-card {
            background: #fff9f0;
            border-radius: 28px;
            padding: 1.2rem 1.8rem;
            margin-bottom: 2rem;
            border: 2px solid #b2c9ab;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            flex-wrap: wrap;
            box-shadow: 4px 6px 0 #dbb594;
        }

        .user-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #f4c2c2;
            color: #1a2a5e;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 2rem;
            text-transform: uppercase;
            flex-shrink: 0;
            border: 3px solid #fcf8f0;
            box-shadow: 2px 4px 0 #d4a0a0;
        }

        .user-info { flex: 1; }
        .user-info .name { font-size: 1.3rem; font-weight: 800; color: #0a0a2e; }
        .user-info .detail { font-size: 0.85rem; color: #1a2a5e; margin-top: 0.1rem; }
        .user-info .detail span { font-weight: 700; color: #0a0a2e; }
        .user-info .warning { color: #c0392b; font-weight: 700; }

        /* ── SECTION TITLES ── */
        .section-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #0a0a2e;
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .section-title .badge {
            background: #b2c9ab;
            color: #0a0a2e;
            font-size: 0.7rem;
            padding: 0.2rem 0.8rem;
            border-radius: 40px;
            box-shadow: 1px 2px 0 #8a9a7a;
        }

        /* ── POST LIST ── */
        .post-list { list-style: none; padding: 0; }
        .post-item {
            background: #fff9f0;
            border-radius: 20px;
            padding: 0.8rem 1.2rem;
            margin-bottom: 0.8rem;
            border: 2px solid #b2c9ab;
            transition: all 0.15s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            cursor: pointer;
            box-shadow: 2px 3px 0 #dbb594;
        }
        .post-item:hover { transform: translateY(-2px); background: #fcf3e8; box-shadow: 2px 5px 0 #dbb594; }
        .post-item:active { transform: translateY(2px); box-shadow: 2px 1px 0 #dbb594; }
        .post-title { font-weight: 700; color: #0a0a2e; font-size: 1rem; }
        .post-meta { font-size: 0.75rem; color: #1a2a5e; display: flex; align-items: center; gap: 0.6rem; }
        .post-meta .author { font-weight: 700; color: #1a2a5e; }
        .post-meta .date { color: #4a6a8a; }
        .post-meta .comments-count { color: #1a2a5e; font-weight: 800; }
        .empty-state { text-align: center; color: #1a2a5e; font-size: 1rem; padding: 2rem 0; }

        /* ── POST DETAIL ── */
        #postDetailView {
            display: none;
        }

        .post-detail-header {
            margin-bottom: 1rem;
        }

        .post-detail-title {
            font-size: 1.4rem;
            font-weight: 900;
            color: #0a0a2e;
            line-height: 1.3;
        }

        .post-detail-meta {
            font-size: 0.8rem;
            color: #1a2a5e;
            margin-top: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .post-detail-meta .author { font-weight: 700; color: #1a2a5e; }

        .post-detail-body {
            background: #fff9f0;
            border-radius: 20px;
            padding: 1.2rem;
            margin: 0.8rem 0 1.5rem;
            border: 2px solid #b2c9ab;
            font-size: 0.95rem;
            color: #0a0a2e;
            line-height: 1.7;
            white-space: pre-wrap;
            box-shadow: 2px 3px 0 #dbb594;
        }

        /* ── COMMENTS ── */
        .comment-section-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0a0a2e;
            margin-bottom: 0.6rem;
        }

        .comments-list { list-style: none; padding: 0; margin-bottom: 1.2rem; }

        .comment-item {
            background: #fff9f0;
            border-radius: 18px;
            padding: 0.7rem 1rem;
            margin-bottom: 0.6rem;
            border: 2px solid #b2c9ab;
            box-shadow: 2px 2px 0 #dbb594;
        }

        .comment-item .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.25rem;
            font-size: 0.75rem;
        }

        .comment-item .comment-author {
            font-weight: 800;
            color: #1a2a5e;
        }

        .comment-item .comment-date {
            color: #4a6a8a;
        }

        .comment-item .comment-content {
            font-size: 0.9rem;
            color: #0a0a2e;
            line-height: 1.5;
            white-space: pre-wrap;
        }

        .comment-item .you-badge {
            background: #f4c2c2;
            color: #1a2a5e;
            font-size: 0.6rem;
            padding: 0.05rem 0.5rem;
            border-radius: 30px;
            margin-left: 0.4rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .comment-form {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .comment-form textarea {
            width: 100%;
            padding: 0.7rem 1rem;
            border: 2px solid #b2c9ab;
            border-radius: 20px;
            font-size: 0.9rem;
            color: #0a0a2e;
            background: #fff9f0;
            resize: vertical;
            min-height: 70px;
            max-height: 160px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s ease;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02), 1px 2px 0 #dbb594;
        }

        .comment-form textarea:focus {
            border-color: #f4c2c2;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(244, 194, 194, 0.3), 1px 2px 0 #dbb594;
        }

        .comment-form .comment-submit {
            align-self: flex-end;
            background: #b2c9ab;
            color: #0a0a2e;
            border: none;
            padding: 0.5rem 1.6rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 2px 4px 0 #8a9a7a;
        }

        .comment-form .comment-submit:hover {
            background: #9fb89a;
            transform: translateY(-2px);
            box-shadow: 2px 6px 0 #8a9a7a;
        }

        .comment-form .comment-submit:disabled {
            background: #d4d9c8;
            box-shadow: 2px 2px 0 #8a9a7a;
            cursor: not-allowed;
            transform: none;
        }

        .comment-status {
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.3rem 0.8rem;
            border-radius: 30px;
            margin-top: 0.3rem;
            display: none;
        }

        .comment-status.success {
            display: block;
            background: #d4edda;
            color: #155724;
            box-shadow: 1px 2px 0 #8a9a7a;
        }
        .comment-status.error {
            display: block;
            background: #f8d7da;
            color: #721c24;
            box-shadow: 1px 2px 0 #dbb594;
        }

        .back-btn {
            background: transparent;
            border: none;
            color: #1a2a5e;
            font-weight: 800;
            font-size: 1rem;
            cursor: pointer;
            padding: 0.2rem 0.5rem;
            border-radius: 30px;
            transition: all 0.2s;
            margin-bottom: 0.5rem;
        }
        .back-btn:hover {
            background: #f4c2c2;
            transform: translateY(-2px);
            box-shadow: 1px 2px 0 #dbb594;
        }

        /* ── SETTINGS PAGES ── */
        .settings-tabs {
            display: flex;
            gap: 0.3rem;
            background: #f5ede4;
            border-radius: 60px;
            padding: 0.3rem;
            margin-bottom: 1.8rem;
            border: 2px solid #b2c9ab;
            box-shadow: 2px 3px 0 #dbb594;
        }

        .settings-tab {
            flex: 1;
            border: none;
            background: transparent;
            padding: 0.5rem 0.2rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 800;
            color: #1a2a5e;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }

        .settings-tab.active {
            background: #f4c2c2;
            color: #0a0a2e;
            box-shadow: 2px 3px 0 #dbb594;
            transform: translateY(-1px);
        }

        .settings-panel {
            display: none;
            animation: fadeSlide 0.25s ease both;
        }

        .settings-panel.active {
            display: block;
        }

        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 800;
            color: #0a0a2e;
            margin-bottom: 0.25rem;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 0.6rem 1rem;
            border: 2px solid #b2c9ab;
            border-radius: 20px;
            font-size: 0.9rem;
            color: #0a0a2e;
            background: #fff9f0;
            outline: none;
            transition: border-color 0.2s ease;
            font-family: inherit;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02), 1px 2px 0 #dbb594;
        }

        .form-group input:focus, .form-group textarea:focus {
            border-color: #f4c2c2;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(244, 194, 194, 0.2), 1px 2px 0 #dbb594;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 90px;
        }

        .submit-btn {
            background: #b2c9ab;
            color: #0a0a2e;
            border: none;
            padding: 0.6rem 2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 2px 4px 0 #8a9a7a;
        }

        .submit-btn:hover:not(:disabled) {
            background: #9fb89a;
            transform: translateY(-2px);
            box-shadow: 2px 6px 0 #8a9a7a;
        }

        .submit-btn:disabled {
            background: #d4d9c8;
            box-shadow: 2px 2px 0 #8a9a7a;
            cursor: not-allowed;
            transform: none;
        }

        .settings-message {
            margin-top: 0.8rem;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 0.3rem 1rem;
            border-radius: 30px;
        }

        .settings-message.success {
            background: #d4edda;
            color: #155724;
            box-shadow: 1px 2px 0 #8a9a7a;
        }
        .settings-message.error {
            background: #f8d7da;
            color: #721c24;
            box-shadow: 1px 2px 0 #dbb594;
        }

        .log-list {
            list-style: none;
            padding: 0;
            background: #fff9f0;
            border-radius: 20px;
            border: 2px solid #b2c9ab;
            box-shadow: 2px 3px 0 #dbb594;
        }

        .log-item {
            padding: 0.7rem 1rem;
            border-bottom: 1px solid #b2c9ab;
            font-size: 0.85rem;
            color: #0a0a2e;
            display: flex;
            justify-content: space-between;
        }

        .log-item:last-child {
            border-bottom: none;
        }

        .log-item .log-time {
            color: #4a6a8a;
            font-weight: 600;
        }

        /* ── Refactored Mobile Responsive Layout ── */
        @media (max-width: 768px) {
            .app {
                flex-direction: column;
                height: 100vh;
                overflow: hidden;
            }

            .sidebar {
                width: 100%;
                height: 64px;
                position: relative;
                border-right: none;
                border-bottom: 2px solid #b2c9ab;
                padding: 0 1.2rem;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                overflow: hidden;
                box-shadow: 0 2px 10px rgba(0,0,0,0.03);
                flex: 0 0 auto;
                min-width: unset;
                max-width: unset;
            }

            .sidebar .brand {
                margin-bottom: 0;
                font-size: 1.2rem;
                flex-shrink: 0;
                white-space: nowrap;
            }

            .sidebar .brand span {
                display: none;
            }

            .sidebar nav {
                flex-direction: row;
                gap: 0.25rem;
                flex: unset;
                align-items: center;
            }

            .sidebar nav a {
                padding: 0.5rem 0.8rem;
                font-size: 0.85rem;
                width: auto;
                border-radius: 8px;
            }

            .sidebar nav a:hover {
                transform: none;
            }

            .sidebar .logout-btn {
                margin-top: 0;
                width: auto;
                padding: 0.4rem;
                background: transparent;
                box-shadow: none;
                border-radius: 50%;
            }

            .sidebar .logout-btn:hover {
                background: #f4c2c2;
                transform: none;
                box-shadow: none;
            }

            .sidebar .logout-btn {
                font-size: 0;
            }
            .sidebar .logout-btn span {
                font-size: 1.1rem;
            }

            .main {
                flex: 1;
                padding: 1.2rem;
                overflow-y: auto;
            }

            .user-card {
                flex-direction: column;
                align-items: flex-start;
                padding: 1rem;
            }
            .user-avatar {
                width: 48px;
                height: 48px;
                font-size: 1.5rem;
            }
            .post-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.2rem;
            }
            .settings-tabs {
                flex-wrap: wrap;
                gap: 0.1rem;
            }
            .settings-tab {
                flex: 1 0 30%;
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>

<div class="app">

    <!-- ─── SIDEBAR ─── -->
    <aside class="sidebar">
        <div class="brand">
            <span class="icon-planet"></span>
            CloudSpacePH
            <span>v1</span>
        </div>
        <nav>
            <a href="#" class="active" data-page="dashboard">
                <span class="icon-star"></span> Dashboard
            </a>
            <a href="#" data-page="settings">
                <span class="icon-planet" style="width:18px;height:18px;"></span> Settings
            </a>
        </nav>
        <button class="logout-btn" id="logoutBtn">
            <span style="display:inline-block;width:20px;height:20px;background:#d4a0a0;border-radius:50%;text-align:center;line-height:20px;font-size:1rem;">🚪</span>
            Sign Out
        </button>
    </aside>

    <!-- ─── MAIN CONTENT ─── -->
    <main class="main">

        <!-- ─── DASHBOARD PAGE ─── -->
        <div id="page-dashboard" class="page">
            <div class="user-card" id="userCard">
                <div class="user-avatar" id="userAvatar">?</div>
                <div class="user-info">
                    <div class="name" id="userName">Loading...</div>
                    <div class="detail" id="userDetail">📧 <span>—</span> &bull; 🎫 <span>—</span></div>
                    <div class="detail" style="font-size:0.75rem; color:#4a6a8a;" id="userSince">📅 Joined: —</div>
                </div>
            </div>

            <div id="postListView">
                <div class="section-title">
                    📝 Forum Posts
                    <span class="badge" id="postCount">0</span>
                </div>
                <ul class="post-list" id="postList">
                    <li class="empty-state">⏳ Loading posts...</li>
                </ul>
            </div>

            <div id="postDetailView">
                <div class="post-detail-header">
                    <button class="back-btn" id="backBtn">← Back</button>
                    <div class="post-detail-title" id="detailTitle"></div>
                    <div class="post-detail-meta">
                        ✍️ by <span class="author" id="detailAuthor"></span>
                        <span class="date" id="detailDate"></span>
                    </div>
                </div>
                <div class="post-detail-body" id="detailBody"></div>
                <div class="comment-section-title">💬 Comments</div>
                <ul class="comments-list" id="commentsList">
                    <li class="empty-state">No comments yet. Be the first! 😊</li>
                </ul>
                <div class="comment-form">
                    <textarea id="commentInput" placeholder="Write a comment..." maxlength="1000"></textarea>
                    <button class="comment-submit" id="commentSubmitBtn">📤 Post Comment</button>
                    <div class="comment-status" id="commentStatus"></div>
                </div>
            </div>
        </div>

        <!-- ─── SETTINGS PAGE ─── -->
        <div id="page-settings" class="page hidden">
            <div class="settings-tabs" id="settingsTabs">
                <button class="settings-tab active" data-tab="change-password">🔑 Change Password</button>
                <button class="settings-tab" data-tab="account-logs">📜 Account Logs</button>
                <button class="settings-tab" data-tab="report-bug">🐞 Report a Bug</button>
            </div>

            <div id="settings-change-password" class="settings-panel active">
                <form id="changePasswordForm">
                    <div class="form-group">
                        <label for="currentPassword">🔐 Current Password</label>
                        <input type="password" id="currentPassword" required />
                    </div>
                    <div class="form-group">
                        <label for="newPassword">🆕 New Password</label>
                        <input type="password" id="newPassword" required />
                    </div>
                    <div class="form-group">
                        <label for="confirmNewPassword">✅ Confirm New Password</label>
                        <input type="password" id="confirmNewPassword" required />
                    </div>
                    <button type="submit" class="submit-btn">🔄 Update Password</button>
                    <div id="passwordMessage" class="settings-message"></div>
                </form>
            </div>

            <div id="settings-account-logs" class="settings-panel">
                <p style="font-size:0.9rem;color:#1a2a5e;margin-bottom:1rem;">📋 Recent login activity and account actions.</p>
                <ul class="log-list" id="logList">
                    <li class="empty-state">No logs available.</li>
                </ul>
                <p style="font-size:0.8rem;color:#4a6a8a;margin-top:0.5rem;">* This feature requires a backend endpoint to fetch logs.</p>
            </div>

            <div id="settings-report-bug" class="settings-panel">
                <form id="bugReportForm">
                    <div class="form-group">
                        <label for="bugTitle">📌 Title</label>
                        <input type="text" id="bugTitle" placeholder="Brief description" required />
                    </div>
                    <div class="form-group">
                        <label for="bugDescription">📝 Description</label>
                        <textarea id="bugDescription" placeholder="What happened?" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">📨 Submit Report</button>
                    <div id="bugMessage" class="settings-message"></div>
                </form>
                <p style="font-size:0.8rem;color:#4a6a8a;margin-top:0.5rem;">* This feature requires a backend endpoint to store bug reports.</p>
            </div>
        </div>

    </main>
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
    const navLinks = document.querySelectorAll('.sidebar nav a');
    const logoutBtn = document.getElementById('logoutBtn');

    // ── Navigation switching ──
    function showPage(pageId) {
        document.querySelectorAll('.page').forEach(p => p.classList.add('hidden'));
        document.getElementById('page-' + pageId).classList.remove('hidden');

        navLinks.forEach(link => {
            link.classList.toggle('active', link.dataset.page === pageId);
        });

        if (pageId === 'settings') {
            showSettingsTab('change-password');
        }
    }

    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const page = link.dataset.page;
            if (page === 'dashboard') {
                if (postDetailView.style.display === 'block') {
                    goBackToList();
                }
                showPage('dashboard');
            } else {
                showPage('settings');
            }
        });
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

    // ── Load posts ──
    async function loadPosts() {
        try {
            const res = await fetch(API_BASE + '?action=list_posts');
            const data = await res.json();
            if (data.status !== 'success') {
                document.getElementById('postList').innerHTML = '<li class="empty-state">Failed to load posts.</li>';
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
            document.getElementById('postList').innerHTML = '<li class="empty-state">Network error loading posts.</li>';
        }
    }

    // ── Open post detail ──
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
            list.innerHTML = '<li class="empty-state">No comments yet. Be the first! 😊</li>';
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

    // ── Post comment ──
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
                statusEl.textContent = 'Comment posted! ✅';
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
        btn.textContent = '📤 Post Comment';
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

    // ── Load profile ──
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
                📧 <span>${escapeHtml(userData.email || '—')}</span> &bull;
                🎫 <span>${userData.account_type || '1'}</span>
            `;
            document.getElementById('userSince').textContent =
                '📅 Joined: ' + (userData.date_created || '—');
        } catch (error) {
            console.warn('Profile load error:', error);
            document.getElementById('userAvatar').textContent = username.charAt(0).toUpperCase();
            document.getElementById('userName').textContent = username;
            document.getElementById('userDetail').innerHTML = `
                <span class="warning">⚠️ Profile data unavailable</span> &bull;
                📧 <span>—</span> &bull; 🎫 <span>—</span>
            `;
            document.getElementById('userSince').textContent = '📅 Joined: —';
        }
    }

    // ── Logout ──
    logoutBtn.addEventListener('click', () => {
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
                msg.textContent = '✅ Password updated successfully.';
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
        // TODO: send to backend
        msg.classList.add('success');
        msg.textContent = '✅ Thank you! Your report has been submitted.';
        document.getElementById('bugReportForm').reset();
    });

    // ── Account Logs (placeholder) ──
    async function loadLogs() {
        const list = document.getElementById('logList');
        list.innerHTML = '<li class="empty-state">No logs available.</li>';
    }

    // ── Init ──
    loadProfile();
    loadPosts();
    loadLogs();
    showPage('dashboard');
</script>

</body>
</html>
