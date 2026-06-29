<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <title>CloudSpacePH • Dashboard</title>
    <!-- Google Font: Open Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet" />
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'unsafe-inline' https:; style-src 'unsafe-inline' https:; img-src 'self' data: https:; font-src https:; connect-src 'self';">
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
        .app {
            display: flex;
            width: 100%;
            max-width: 1440px;
            min-height: 100vh;
            background: #fcf8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        /* ── Sidebar (unchanged) ── */
        .sidebar {
            flex: 0 0 auto;
            width: fit-content;
            min-width: 200px;
            max-width: 320px;
            background: #fcf8f0;
            padding: 2rem 1.8rem;
            border-right: 3px solid #b2c9ab;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
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
            white-space: nowrap;
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
        .page {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        .page.hidden {
            display: none;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── USER CARD (unchanged) ── */
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
        .user-info {
            flex: 1;
        }
        .user-info .name {
            font-size: 1.3rem;
            font-weight: 800;
            color: #0a0a2e;
        }
        .user-info .detail {
            font-size: 0.85rem;
            color: #1a2a5e;
            margin-top: 0.1rem;
        }
        .user-info .detail span {
            font-weight: 700;
            color: #0a0a2e;
        }
        .user-info .warning {
            color: #c0392b;
            font-weight: 700;
        }

        /* ── SECTION TITLES (enhanced) ── */
        .section-title {
            font-size: 1.3rem;
            font-weight: 900;
            color: #0a0a2e;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            letter-spacing: -0.02em;
            text-shadow: 1px 1px 0 rgba(0,0,0,0.05);
        }
        .section-title .badge {
            background: #b2c9ab;
            color: #0a0a2e;
            font-size: 0.7rem;
            padding: 0.2rem 0.8rem;
            border-radius: 40px;
            box-shadow: 1px 2px 0 #8a9a7a;
        }

        /* ── POST LIST – CARD STYLE (enhanced contrast) ── */
        .post-list {
            list-style: none;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .post-item {
            background: #fff9f0;
            border-radius: 20px;
            padding: 1.2rem 1.5rem;
            border: 2px solid #c4a88a;
            box-shadow: 2px 4px 0 #dbb594, inset 0 1px 0 rgba(255,255,255,0.8);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            position: relative;
        }
        .post-item:hover {
            transform: translateY(-3px);
            box-shadow: 2px 8px 0 #dbb594, inset 0 1px 0 rgba(255,255,255,0.9);
            background: #fcf3e8;
        }
        .post-item:active {
            transform: translateY(3px);
            box-shadow: 2px 2px 0 #dbb594;
        }

        /* Thumbnail */
        .post-thumb {
            flex: 0 0 110px;
            height: 110px;
            border-radius: 16px;
            overflow: hidden;
            background: #f5ede4;
            border: 2px solid #c4a88a;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .post-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .post-thumb .no-image {
            font-size: 2.5rem;
            color: #b2c9ab;
            font-weight: 700;
        }

        /* Content area */
        .post-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            min-width: 0;
        }
        .post-title {
            font-weight: 900;
            color: #0a0a2e;
            font-size: 1.5rem;
            line-height: 1.2;
            text-shadow: 0 1px 0 rgba(255,255,255,0.5);
        }
        .post-meta {
            font-size: 0.8rem;
            color: #1a2a5e;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .post-meta .author {
            font-weight: 700;
            color: #1a2a5e;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .post-meta .date {
            color: #4a6a8a;
        }
        .post-meta .comments-count {
            color: #1a2a5e;
            font-weight: 800;
        }

        /* Author avatar (circle with initial) */
        .post-author-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f4c2c2;
            color: #1a2a5e;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.9rem;
            text-transform: uppercase;
            border: 2px solid #b2c9ab;
            flex-shrink: 0;
        }

        .empty-state {
            text-align: center;
            color: #1a2a5e;
            font-size: 1rem;
            padding: 2rem 0;
        }

        /* ── POST ACTIONS (Edit/Delete) unchanged ── */
        .post-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-left: auto;
            flex-shrink: 0;
        }
        .edit-btn, .delete-btn {
            background: transparent;
            border: 2px solid #b2c9ab;
            border-radius: 30px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #1a2a5e;
            font-size: 1rem;
            box-shadow: 1px 2px 0 #dbb594;
        }
        .edit-btn:hover {
            background: #b2c9ab;
            transform: translateY(-2px);
        }
        .delete-btn:hover {
            background: #f4c2c2;
            border-color: #d32f2f;
            color: #d32f2f;
            transform: translateY(-2px);
        }

        /* ── POST DETAIL (enhanced titles) ── */
        #forumPostDetailView {
            display: none;
        }
        .post-detail-header {
            margin-bottom: 1rem;
        }
        .post-detail-title {
            font-size: 1.8rem;
            font-weight: 900;
            color: #0a0a2e;
            line-height: 1.2;
            text-shadow: 0 1px 0 rgba(255,255,255,0.5);
        }
        .post-detail-meta {
            font-size: 0.8rem;
            color: #1a2a5e;
            margin-top: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .post-detail-meta .author {
            font-weight: 700;
            color: #1a2a5e;
        }
        .post-detail-body {
            background: #fff9f0;
            border-radius: 20px;
            padding: 1.2rem;
            margin: 0.8rem 0 1.5rem;
            border: 2px solid #c4a88a;
            font-size: 0.95rem;
            color: #0a0a2e;
            line-height: 1.7;
            white-space: pre-wrap;
            box-shadow: 2px 3px 0 #dbb594;
        }
        .comment-section-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0a0a2e;
            margin-bottom: 0.6rem;
        }
        .comments-list {
            list-style: none;
            padding: 0;
            margin-bottom: 1.2rem;
        }
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
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02), 1px 2px 0 #dbb594;
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
            padding: 0.6rem 2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 2px 4px 0 #8a9a7a;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
        }
        .comment-form .comment-submit:hover:not(:disabled) {
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

        /* ── SETTINGS TABS (unchanged) ── */
        .settings-tabs {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
            margin-bottom: 1.8rem;
        }
        .settings-tab {
            padding: 0.6rem 1.5rem;
            background: #b2c9ab;
            color: #0a0a2e;
            border: none;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.9rem;
            cursor: pointer;
            box-shadow: 2px 4px 0 #8a9a7a;
            transition: all 0.2s ease;
            font-family: inherit;
            white-space: nowrap;
        }
        .settings-tab:hover {
            background: #9fb89a;
            transform: translateY(-2px);
            box-shadow: 2px 6px 0 #8a9a7a;
        }
        .settings-tab:active {
            transform: translateY(2px);
            box-shadow: 2px 1px 0 #8a9a7a;
        }
        .settings-tab.active {
            background: #1a2a5e;
            color: #fcf8f0;
            box-shadow: 2px 4px 0 #0a0a2e;
        }
        .settings-tab.active:hover {
            background: #1a2a5e;
            color: #fcf8f0;
            transform: translateY(-2px);
            box-shadow: 2px 6px 0 #0a0a2e;
        }
        .settings-panel {
            display: none;
            animation: fadeSlide 0.25s ease both;
        }
        .settings-panel.active {
            display: block;
        }
        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
        .form-group input,
        .form-group textarea {
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
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02), 1px 2px 0 #dbb594;
        }
        .form-group input:focus,
        .form-group textarea:focus {
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
            font-family: inherit;
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

        /* ── Themed Search Bar ── */
        .search-wrapper {
            position: relative;
            width: 100%;
            z-index: 1000;
        }
        .search-input-container {
            display: flex;
            align-items: center;
            background: #fff9f0;
            border: 2px solid #b2c9ab;
            border-radius: 24px;
            padding: 8px 16px;
            box-shadow: 2px 3px 0 #dbb594;
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        .search-input-container:focus-within {
            border-color: #f4c2c2;
            box-shadow: 0 0 0 4px rgba(244,194,194,0.2), 2px 3px 0 #dbb594;
        }
        .search-wrapper.active .search-input-container {
            border-radius: 24px 24px 0 0;
            box-shadow: 0 4px 6px rgba(32,33,36,0.28), 2px 3px 0 #dbb594;
            border-bottom-color: transparent;
        }
        .search-input-container input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 16px;
            background: transparent;
            color: #0a0a2e;
            font-family: 'Open Sans', sans-serif;
        }
        .search-input-container input::placeholder {
            color: #4a6a8a;
            font-weight: 400;
        }
        .search-results-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff9f0;
            border: 2px solid #b2c9ab;
            border-top: none;
            border-radius: 0 0 24px 24px;
            box-shadow: 0 4px 6px rgba(32,33,36,0.28), 2px 3px 0 #dbb594;
            overflow-y: auto;
            max-height: 300px;
            display: none;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .search-results-dropdown::-webkit-scrollbar {
            display: none;
        }
        .search-item {
            padding: 10px 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-size: 15px;
            color: #0a0a2e;
            border-bottom: 1px solid #e8d5c4;
        }
        .search-item:last-child {
            border-bottom: none;
        }
        .search-item.selected,
        .search-item:hover {
            background: #f4c2c2;
        }
        .search-item .icon {
            margin-right: 12px;
            color: #4a6a8a;
        }
        .search-item.recent .icon::before {
            content: "🕒";
        }

        /* ── Forum Navigation Tabs ── */
        .forum-nav-container {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
            margin-bottom: 1.8rem;
        }
        .forum-nav-btn {
            padding: 0.6rem 1.5rem;
            background: #b2c9ab;
            color: #0a0a2e;
            border: none;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.9rem;
            cursor: pointer;
            box-shadow: 2px 4px 0 #8a9a7a;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            white-space: nowrap;
            flex: 0 1 auto;
        }
        .forum-nav-btn:hover {
            background: #9fb89a;
            transform: translateY(-2px);
            box-shadow: 2px 6px 0 #8a9a7a;
        }
        .forum-nav-btn:active {
            transform: translateY(2px);
            box-shadow: 2px 1px 0 #8a9a7a;
        }
        .forum-nav-btn.active {
            background: #1a2a5e;
            color: #fcf8f0;
            box-shadow: 2px 4px 0 #0a0a2e;
        }
        .forum-nav-btn.active:hover {
            background: #1a2a5e;
            color: #fcf8f0;
            transform: translateY(-2px);
            box-shadow: 2px 6px 0 #0a0a2e;
        }

        /* ── Toast Notifications ── */
        .toast-container {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            max-width: 400px;
            width: 100%;
            pointer-events: none;
        }
        .toast {
            background: #fff9f0;
            border: 2px solid #b2c9ab;
            border-left-width: 6px;
            border-radius: 16px;
            padding: 1rem 1.2rem;
            box-shadow: 2px 4px 0 #dbb594;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            animation: toastSlide 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) both;
            pointer-events: auto;
            transition: opacity 0.3s ease;
        }
        .toast.success {
            border-left-color: #2e7d32;
        }
        .toast.error {
            border-left-color: #c62828;
        }
        .toast .toast-icon {
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .toast .toast-message {
            font-weight: 600;
            font-size: 0.95rem;
            color: #0a0a2e;
            flex: 1;
        }
        @keyframes toastSlide {
            from {
                opacity: 0;
                transform: translateX(60px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .toast.hide {
            opacity: 0;
            transform: translateX(60px);
        }

        /* ── Mobile Responsive ── */
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
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
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
                align-items: stretch;
                gap: 1rem;
            }
            .post-thumb {
                flex: 0 0 80px;
                height: 80px;
                width: 100%;
            }
            .post-actions {
                flex-direction: row;
                margin-left: 0;
                margin-top: 0.5rem;
            }
            .settings-tabs {
                gap: 0.4rem;
            }
            .settings-tab {
                padding: 0.5rem 1.1rem;
                font-size: 0.82rem;
            }
            .forum-nav-container {
                gap: 0.4rem;
            }
            .forum-nav-btn {
                padding: 0.5rem 1.1rem;
                font-size: 0.82rem;
            }
            .toast-container {
                bottom: 1rem;
                right: 1rem;
                max-width: 90%;
            }
        }
        @media (max-width: 480px) {
            .settings-tabs {
                flex-direction: column;
                gap: 0.4rem;
            }
            .settings-tab {
                width: 100%;
                text-align: center;
                justify-content: center;
            }
            .forum-nav-container {
                flex-direction: column;
                gap: 0.4rem;
            }
            .forum-nav-btn {
                width: 100%;
                justify-content: center;
            }
            .post-thumb {
                flex: 0 0 60px;
                height: 60px;
            }
        }

        /* ── Centered image in post detail ── */
        .forum-image-preview-card {
            display: block;
            margin: 1rem auto;
            max-width: 100%;
            max-height: 320px;
            border-radius: 16px;
            border: 2px solid #b2c9ab;
            object-fit: contain;
            background: #f5ede4;
        }

        /* ── Custom file input button ── */
        .custom-file-upload {
            display: inline-block;
            padding: 0.6rem 1.5rem;
            background: #b2c9ab;
            color: #0a0a2e;
            border: none;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.9rem;
            cursor: pointer;
            box-shadow: 2px 4px 0 #8a9a7a;
            transition: all 0.2s ease;
            font-family: inherit;
        }
        .custom-file-upload:hover {
            background: #9fb89a;
            transform: translateY(-2px);
            box-shadow: 2px 6px 0 #8a9a7a;
        }
        .custom-file-upload input[type="file"] {
            display: none;
        }

        /* ── Publish Button with Spinner ── */
        #forumSubmitBtn,
        .comment-submit {
            background: #1a2a5e;
            color: #fcf8f0;
            border: 2px solid #1a2a5e;
            padding: 0.75rem 1.8rem;
            font-size: 0.95rem;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 3px 3px 0 #b2c9ab;
            transition: all 0.2s ease;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
        }
        #forumSubmitBtn:hover,
        .comment-submit:hover {
            background: #b2c9ab;
            color: #0a0a2e;
            border-color: #0a0a2e;
            box-shadow: 1px 1px 0 #0a0a2e;
            transform: translate(2px, 2px);
        }
        #forumSubmitBtn:disabled {
            background: #cccccc;
            border-color: #bbbbbb;
            color: #666666;
            box-shadow: none;
            cursor: not-allowed;
            transform: none;
        }
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.8s ease-in-out infinite;
        }
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        .btn-published {
            background: #2e7d32 !important;
            border-color: #2e7d32 !important;
            color: #fff !important;
            box-shadow: 3px 3px 0 #1b5e20 !important;
        }
        .btn-published:hover {
            background: #1b5e20 !important;
            transform: translate(2px, 2px);
            box-shadow: 1px 1px 0 #1b5e20 !important;
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
                <a href="#" id="nav-forums" data-page="forums">
                    <span class="icon-planet" style="width:18px;height:18px;"></span> Forums
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
                        <div class="detail" id="userDetail">📧 <span>—</span></div>
                        <div class="detail" style="font-size:0.75rem; color:#4a6a8a;" id="userSince">📅 Joined: —</div>
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

            <!-- ─── FORUMS PAGE ─── -->
            <div id="page-forums" class="page hidden">
                <div class="user-card">
                    <div class="user-info">
                        <div class="name">🏛️ Community Interaction Hub</div>
                        <div class="detail">Share, browse, and track internal system information files.</div>
                    </div>
                </div>

                <!-- Navigation tabs -->
                <div class="forum-nav-container">
                    <button class="forum-nav-btn active" id="forumFeedTabBtn">
                        <span>🌐</span> Public Feed
                    </button>
                    <button class="forum-nav-btn" id="forumMyTabBtn">
                        <span>👤</span> My Forums
                    </button>
                    <button class="forum-nav-btn" id="forumCreateTabBtn">
                        <span>📝</span> Create Thread
                    </button>
                </div>

                <div class="forum-layout-container">
                    <!-- Feed Panel -->
                    <div id="forumMainFeedPanel">
                        <!-- Themed Search Bar -->
                        <div class="search-wrapper" id="searchWrapper">
                            <div class="search-input-container">
                                <input type="text" id="searchInput" placeholder="🔍 Search threads (fuzzy)..." autocomplete="off">
                            </div>
                            <div class="search-results-dropdown" id="searchResultsDropdown"></div>
                        </div>
                        <div class="section-title" style="margin-top:1.2rem;">
                            <span id="forumFeedHeadingText">Latest Public Records</span>
                            <span class="badge" id="resultCount"></span>
                        </div>
                        <ul class="post-list" id="globalForumsListElement">
                            <li class="empty-state">⏳ Querying dataset files...</li>
                        </ul>
                    </div>

                    <!-- Create Thread Panel -->
                    <div id="forumThreadCreationPanel" style="display: none;">
                        <div class="section-title">✨ Create a New Forum Post</div>
                        <form id="newForumFormSubmitElement" style="background:#fff9f0; padding:1.5rem; border-radius:24px; border:2px solid #b2c9ab; box-shadow:3px 4px 0 #dbb594;">
                            <div class="form-group">
                                <label>📌 Thread Subject Title</label>
                                <input type="text" id="forumFormTitle" required placeholder="Enter your subject line topic heading..." />
                            </div>
                            <div class="form-group">
                                <label>💬 Thread Content Body</label>
                                <textarea id="forumFormBody" required style="min-height:120px;" placeholder="Compose detailed discussion points here..."></textarea>
                            </div>
                            <div class="form-group">
                                <label>🖼️ Upload Resource Image <span style="font-weight:normal; font-size:0.8rem; color:#4a6a8a;">(Optional - JPG/PNG)</span></label>
                                <label class="custom-file-upload">
                                    <input type="file" id="forumFormFile" accept="image/*" />
                                    📁 Choose Image
                                </label>
                                <span id="fileChosen" style="margin-left: 0.8rem; font-size:0.85rem; color:#4a6a8a;">No file chosen</span>
                            </div>
                            <button type="submit" class="comment-submit" id="forumSubmitBtn" style="align-self:flex-start; margin-top:0.5rem;">🚀 Publish Thread</button>
                            <div id="forumFormStatus" class="comment-status"></div>
                        </form>
                    </div>

                    <!-- Post Detail Panel -->
                    <div id="forumPostDetailView">
                        <div class="post-detail-header">
                            <button class="back-btn" id="forumBackBtn">← Back to Feed</button>
                            <div class="post-detail-title" id="forumDetailTitle"></div>
                            <div class="post-detail-meta">
                                ✍️ by <span class="author" id="forumDetailAuthor"></span>
                                <span class="date" id="forumDetailDate"></span>
                            </div>
                        </div>
                        <div class="post-detail-body" id="forumDetailBody"></div>
                        <div class="comment-section-title">💬 Comments</div>
                        <ul class="comments-list" id="forumCommentsList">
                            <li class="empty-state">No comments yet. Be the first! 😊</li>
                        </ul>
                        <div class="comment-form">
                            <textarea id="forumCommentInput" placeholder="Write a comment..." maxlength="1000"></textarea>
                            <button class="comment-submit" id="forumCommentSubmitBtn">📤 Post Comment</button>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- ─── TOAST CONTAINER ─── -->
    <div class="toast-container" id="toastContainer"></div>

    <script>
        // ── Check authentication ──
        const username = localStorage.getItem('cloudspace_username');
        if (!username) {
            window.location.href = 'login';
        }

        // ── Token must be set by server after login
        if (!localStorage.getItem('cloudspace_token')) {
            window.location.href = 'login';
        }

        const API_BASE = 'api/forums/forums-api.php';
        const AUTH_API = 'auth-api.php';

        // ── State ──
        let currentForumPostId = null;
        let currentForumSubView = 'feed';
        let searchDebounceTimer = null;
        let allPosts = [];
        let filteredPosts = [];

        // ── UI references ──
        const pageDashboard = document.getElementById('page-dashboard');
        const pageSettings = document.getElementById('page-settings');
        const pageForums = document.getElementById('page-forums');
        const navLinks = document.querySelectorAll('.sidebar nav a');
        const logoutBtn = document.getElementById('logoutBtn');
        const toastContainer = document.getElementById('toastContainer');

        // New search UI elements
        const searchWrapper = document.getElementById('searchWrapper');
        const searchInput = document.getElementById('searchInput');
        const searchDropdown = document.getElementById('searchResultsDropdown');
        const resultCountSpan = document.getElementById('resultCount');

        let currentFocus = -1;
        const RECENT_SEARCHES_KEY = 'cloudspace_recent_searches';

        // ── Toast Helper ──
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            const icon = type === 'success' ? '✅' : '❌';
            toast.innerHTML = `<span class="toast-icon">${icon}</span><span class="toast-message">${escapeHtml(message)}</span>`;
            toastContainer.appendChild(toast);
            setTimeout(() => { toast.classList.add('hide'); setTimeout(() => toast.remove(), 400); }, 4000);
        }

        // ── Navigation switching ──
        function showPage(pageId) {
            document.querySelectorAll('.page').forEach(p => p.classList.add('hidden'));
            const targetPage = document.getElementById('page-' + pageId);
            if (targetPage) targetPage.classList.remove('hidden');
            navLinks.forEach(link => link.classList.toggle('active', link.dataset.page === pageId));
            if (pageId === 'settings') showSettingsTab('change-password');
            else if (pageId === 'forums') {
                document.getElementById('forumPostDetailView').style.display = 'none';
                document.getElementById('forumMainFeedPanel').style.display = 'block';
                document.getElementById('forumThreadCreationPanel').style.display = 'none';
                toggleForumSection('feed');
            }
        }
        navLinks.forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                showPage(link.dataset.page === 'dashboard' ? 'dashboard' : link.dataset.page);
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
            settingsTabs.forEach(tab => tab.classList.toggle('active', tab.dataset.tab === tabId));
            Object.entries(settingsPanels).forEach(([id, panel]) => panel.classList.toggle('active', id === tabId));
            document.getElementById('passwordMessage').className = 'settings-message';
            document.getElementById('passwordMessage').textContent = '';
            document.getElementById('bugMessage').className = 'settings-message';
            document.getElementById('bugMessage').textContent = '';
            if (tabId === 'account-logs') loadLogs();
        }
        settingsTabs.forEach(tab => tab.addEventListener('click', () => showSettingsTab(tab.dataset.tab)));

        // ── Format & escape ──
        function formatDate(dateStr) {
            if (!dateStr) return '—';
            const d = new Date(dateStr);
            if (isNaN(d.getTime())) return dateStr;
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) + ' at ' + d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        }
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        function getSecureImageUrl(filename) {
            const token = localStorage.getItem('cloudspace_token');
            if (!token || !filename) return null;
            return `api/forums/image.php?file=${encodeURIComponent(filename)}&token=${encodeURIComponent(token)}`;
        }

        // ── Load profile, logout, etc. (unchanged) ──
        async function loadProfile() {
            const token = localStorage.getItem('cloudspace_token');
            if (!token) { window.location.href = 'login'; return; }
            try {
                const res = await fetch('auth-api.php', { method:'POST', headers:{ 'Content-Type':'application/json','Authorization':'Bearer '+token }, body:JSON.stringify({action:'get_profile'}) });
                const data = await res.json();
                if (data.status !== 'success' || !data.user) throw new Error(data.message || 'Profile fetch failed');
                const user = data.user;
                document.getElementById('userAvatar').textContent = (user.username || username).charAt(0).toUpperCase();
                document.getElementById('userName').textContent = user.username || username;
                document.getElementById('userDetail').innerHTML = `📧 <span>${escapeHtml(user.email || '—')}</span>`;
                document.getElementById('userSince').textContent = '📅 Joined: ' + (user.date_created || '—');
            } catch (error) {
                console.warn('Profile load error:', error);
                document.getElementById('userAvatar').textContent = username.charAt(0).toUpperCase();
                document.getElementById('userName').textContent = username;
                document.getElementById('userDetail').innerHTML = `<span class="warning">⚠️ Profile data unavailable</span>`;
                document.getElementById('userSince').textContent = '📅 Joined: —';
            }
        }
        logoutBtn.addEventListener('click', () => {
            localStorage.removeItem('cloudspace_username');
            localStorage.removeItem('cloudspace_token');
            window.location.href = 'login';
        });
        document.getElementById('changePasswordForm').addEventListener('submit', async e => {
            e.preventDefault();
            const msg = document.getElementById('passwordMessage');
            msg.className = 'settings-message'; msg.textContent = '';
            const current = document.getElementById('currentPassword').value;
            const newPass = document.getElementById('newPassword').value;
            const confirm = document.getElementById('confirmNewPassword').value;
            if (newPass.length < 8) { msg.classList.add('error'); msg.textContent = 'New password must be at least 8 characters.'; return; }
            if (newPass !== confirm) { msg.classList.add('error'); msg.textContent = 'Passwords do not match.'; return; }
            try {
                const token = localStorage.getItem('cloudspace_token');
                const res = await fetch(AUTH_API, { method:'POST', headers:{ 'Content-Type':'application/json','Authorization':'Bearer '+token }, body:JSON.stringify({ action:'change_password', username, current_password:current, new_password:newPass }) });
                const data = await res.json();
                if (data.status === 'success') { msg.classList.add('success'); msg.textContent = '✅ Password updated successfully.'; document.getElementById('changePasswordForm').reset(); }
                else { msg.classList.add('error'); msg.textContent = data.message || 'Failed to update password.'; }
            } catch (err) { msg.classList.add('error'); msg.textContent = 'Network error.'; }
        });
        document.getElementById('bugReportForm').addEventListener('submit', async e => {
            e.preventDefault();
            const msg = document.getElementById('bugMessage');
            msg.className = 'settings-message'; msg.textContent = '';
            const title = document.getElementById('bugTitle').value.trim();
            const desc = document.getElementById('bugDescription').value.trim();
            if (!title || !desc) { msg.classList.add('error'); msg.textContent = 'Please fill in all fields.'; return; }
            msg.classList.add('success'); msg.textContent = '✅ Thank you! Your report has been submitted.';
            document.getElementById('bugReportForm').reset();
        });
        async function loadLogs() {
            const token = localStorage.getItem('cloudspace_token');
            const list = document.getElementById('logList');
            list.innerHTML = '<li class="empty-state">Loading logs...</li>';
            if (!token) { list.innerHTML = '<li class="empty-state">Not authenticated.</li>'; return; }
            try {
                const res = await fetch('auth-api.php', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({action:'get_logs',token}) });
                const data = await res.json();
                if (data.status !== 'success') { list.innerHTML = `<li class="empty-state">${data.message || 'Failed to load logs.'}</li>`; return; }
                const logs = data.logs || [];
                if (logs.length === 0) { list.innerHTML = '<li class="empty-state">No recent logs available.</li>'; return; }
                const groups = {};
                logs.forEach(log => {
                    const date = new Date(log.timestamp);
                    const key = date.toISOString().split('T')[0];
                    if (!groups[key]) groups[key] = [];
                    groups[key].push(log);
                });
                const sortedDates = Object.keys(groups).sort((a,b) => b.localeCompare(a));
                let html = '';
                sortedDates.forEach(dateKey => {
                    const dateObj = new Date(dateKey + 'T00:00:00');
                    const displayDate = dateObj.toLocaleDateString('en-US', { weekday:'long', month:'long', day:'numeric', year:'numeric' });
                    html += `<li style="padding:0.3rem 0;font-weight:800;color:#1a2a5e;background:transparent;border-bottom:2px solid #b2c9ab;">📅 ${displayDate}</li>`;
                    groups[dateKey].forEach(log => {
                        const time = new Date(log.timestamp).toLocaleTimeString('en-US', { hour:'2-digit', minute:'2-digit' });
                        html += `<li class="log-item"><span>🕒 ${time} &bull; ${escapeHtml(log.action)}</span><span class="log-time">IP: ${escapeHtml(log.ip_address)}</span></li>`;
                    });
                });
                list.innerHTML = html;
            } catch (err) {
                console.warn('loadLogs error:', err);
                list.innerHTML = '<li class="empty-state">Network error loading logs.</li>';
            }
        }

        // ============================================================
        // ── FORUMS FEATURE ──
        // ============================================================

        function fuzzyMatch(searchTerm, target) {
            if (!searchTerm) return true;
            const s = searchTerm.toLowerCase().trim();
            const t = target.toLowerCase();
            let i = 0;
            for (let ch of s) {
                i = t.indexOf(ch, i);
                if (i === -1) return false;
                i++;
            }
            return true;
        }

        async function fetchAllPosts() {
            const savedToken = localStorage.getItem('cloudspace_token') || '';
            try {
                const response = await fetch(`api/forums/forums-api.php?action=list_posts`, { method:'GET', headers:{ 'Authorization':`Bearer ${savedToken}` } });
                const dataset = await response.json();
                if (dataset.status !== 'success') throw new Error(dataset.message || 'Failed');
                allPosts = dataset.posts || [];
                allPosts.sort((a,b) => new Date(b.timestamp) - new Date(a.timestamp));
                return allPosts;
            } catch (err) { console.error('fetchAllPosts error:', err); return []; }
        }

        function renderPosts(posts) {
            const listElement = document.getElementById('globalForumsListElement');
            if (!posts || posts.length === 0) {
                listElement.innerHTML = '<li class="empty-state">No threads match your search.</li>';
                resultCountSpan.textContent = '0 results';
                return;
            }
            resultCountSpan.textContent = posts.length + ' result' + (posts.length > 1 ? 's' : '');
            listElement.innerHTML = '';
            posts.forEach(post => {
                const li = document.createElement('li');
                li.className = 'post-item';
                const thumbDiv = document.createElement('div');
                thumbDiv.className = 'post-thumb';
                if (post.image) {
                    const imgUrl = getSecureImageUrl(post.image);
                    if (imgUrl) {
                        const img = document.createElement('img');
                        img.src = imgUrl; img.alt = 'Thumbnail';
                        thumbDiv.appendChild(img);
                    } else {
                        const noImg = document.createElement('span');
                        noImg.className = 'no-image'; noImg.textContent = '📄';
                        thumbDiv.appendChild(noImg);
                    }
                } else {
                    const noImg = document.createElement('span');
                    noImg.className = 'no-image'; noImg.textContent = '📄';
                    thumbDiv.appendChild(noImg);
                }
                const contentDiv = document.createElement('div');
                contentDiv.className = 'post-content';
                const titleSpan = document.createElement('div');
                titleSpan.className = 'post-title';
                titleSpan.textContent = post.title;
                const metaSpan = document.createElement('div');
                metaSpan.className = 'post-meta';
                const authorInitial = post.author ? post.author.charAt(0).toUpperCase() : '?';
                metaSpan.innerHTML = `<span class="author"><span class="post-author-avatar">${escapeHtml(authorInitial)}</span>${escapeHtml(post.author)}</span><span class="date">${post.timestamp ? post.timestamp.split('T')[0] : ''}</span><span class="comments-count">💬 ${post.comment_count || 0}</span>`;
                contentDiv.appendChild(titleSpan);
                contentDiv.appendChild(metaSpan);
                const isOwner = post.author.toLowerCase() === username.toLowerCase();
                if (isOwner) {
                    const actionsDiv = document.createElement('div');
                    actionsDiv.className = 'post-actions';
                    const editBtn = document.createElement('button');
                    editBtn.className = 'edit-btn'; editBtn.innerHTML = '✎'; editBtn.title = 'Edit Thread';
                    editBtn.addEventListener('click', e => { e.stopPropagation(); editThread(post.id, post.title, post.content); });
                    const deleteBtn = document.createElement('button');
                    deleteBtn.className = 'delete-btn'; deleteBtn.innerHTML = '🗑'; deleteBtn.title = 'Delete Thread';
                    deleteBtn.addEventListener('click', e => { e.stopPropagation(); deleteThread(post.id); });
                    actionsDiv.appendChild(editBtn); actionsDiv.appendChild(deleteBtn);
                    li.appendChild(thumbDiv); li.appendChild(contentDiv); li.appendChild(actionsDiv);
                } else { li.appendChild(thumbDiv); li.appendChild(contentDiv); }
                li.addEventListener('click', () => openForumPost(post.id));
                listElement.appendChild(li);
            });
        }

        function applySearchFilter(searchTerm) {
            if (!searchTerm || searchTerm.trim() === '') filteredPosts = allPosts;
            else {
                const term = searchTerm.trim();
                filteredPosts = allPosts.filter(post => fuzzyMatch(term, post.title) || fuzzyMatch(term, post.content));
            }
            renderPosts(filteredPosts);
        }

        // ── Search dropdown helpers ──
        function getRecentSearches() {
            try { return JSON.parse(localStorage.getItem(RECENT_SEARCHES_KEY)) || []; } catch { return []; }
        }
        function saveRecentSearch(query) {
            if (!query.trim()) return;
            let searches = getRecentSearches();
            searches = searches.filter(item => item.toLowerCase() !== query.toLowerCase());
            searches.unshift(query);
            searches = searches.slice(0, 5);
            localStorage.setItem(RECENT_SEARCHES_KEY, JSON.stringify(searches));
        }
        function executeSearch(query) {
            if (!query.trim()) return;
            saveRecentSearch(query);
            searchDropdown.style.display = 'none';
            searchWrapper.classList.remove('active');
            applySearchFilter(query);
        }

        function renderDropdown(items, isRecent = false) {
            searchDropdown.innerHTML = '';
            if (items.length === 0) {
                if (isRecent) {
                    searchDropdown.style.display = 'none';
                    searchWrapper.classList.remove('active');
                    return;
                }
                const noResult = document.createElement('div');
                noResult.className = 'search-item';
                noResult.innerText = 'No results found';
                searchDropdown.appendChild(noResult);
            } else {
                items.forEach((itemText, index) => {
                    const div = document.createElement('div');
                    div.className = `search-item ${isRecent ? 'recent' : ''}`;
                    div.innerHTML = `<span class="icon"></span>${itemText}`;
                    div.addEventListener('click', () => {
                        searchInput.value = itemText;
                        executeSearch(itemText);
                    });
                    searchDropdown.appendChild(div);
                });
            }
            searchDropdown.style.display = 'block';
            searchWrapper.classList.add('active');
            currentFocus = -1;
        }

        function addActive(items) {
            if (!items) return false;
            removeActive(items);
            if (currentFocus >= items.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = items.length - 1;
            items[currentFocus].classList.add('selected');
        }
        function removeActive(items) {
            for (let i = 0; i < items.length; i++) items[i].classList.remove('selected');
        }
        function ensureVisible(element) {
            if (!element) return;
            const parent = searchDropdown;
            if (element.offsetTop < parent.scrollTop) parent.scrollTop = element.offsetTop;
            else if ((element.offsetTop + element.clientHeight) > (parent.scrollTop + parent.clientHeight))
                parent.scrollTop = element.offsetTop + element.clientHeight - parent.clientHeight;
        }

        // ── Search input events ──
        searchInput.addEventListener('input', function() {
            const val = this.value;
            if (!val) { renderDropdown(getRecentSearches(), true); return; }
            // Simulated suggestions – replace with actual database query if needed
            const mockDatabase = ['Dashboard analytics', 'User settings', 'Forum threads', 'System log history', 'Cloud storage file space'];
            const filteredResults = mockDatabase.filter(item => item.toLowerCase().includes(val.toLowerCase()));
            renderDropdown(filteredResults, false);
        });
        searchInput.addEventListener('focus', function() {
            if (!this.value) renderDropdown(getRecentSearches(), true);
        });
        searchInput.addEventListener('keydown', function(e) {
            let items = searchDropdown.getElementsByClassName('search-item');
            if (items.length === 0) return;
            if (e.key === 'ArrowDown') { e.preventDefault(); currentFocus++; addActive(items); ensureVisible(items[currentFocus]); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); currentFocus--; addActive(items); ensureVisible(items[currentFocus]); }
            else if (e.key === 'Enter') {
                e.preventDefault();
                if (currentFocus > -1 && items[currentFocus]) items[currentFocus].click();
                else executeSearch(this.value);
            }
        });
        document.addEventListener('click', function(e) {
            if (!searchWrapper.contains(e.target)) {
                searchDropdown.style.display = 'none';
                searchWrapper.classList.remove('active');
            }
        });

        // ── Toggle forum sections ──
        function toggleForumSection(targetSection) {
            currentForumSubView = targetSection;
            document.getElementById('forumFeedTabBtn').classList.toggle('active', targetSection === 'feed');
            document.getElementById('forumMyTabBtn').classList.toggle('active', targetSection === 'my-posts');
            document.getElementById('forumCreateTabBtn').classList.toggle('active', targetSection === 'create');
            const feedPanel = document.getElementById('forumMainFeedPanel');
            const createPanel = document.getElementById('forumThreadCreationPanel');
            const heading = document.getElementById('forumFeedHeadingText');
            if (targetSection === 'create') { feedPanel.style.display = 'none'; createPanel.style.display = 'block'; }
            else {
                createPanel.style.display = 'none'; feedPanel.style.display = 'block';
                heading.textContent = targetSection === 'my-posts' ? '👤 My Thread Uploads' : 'Latest Public Records';
                fetchAndRenderPosts(targetSection === 'my-posts');
            }
        }

        async function fetchAndRenderPosts(myPostsOnly = false) {
            const listElement = document.getElementById('globalForumsListElement');
            listElement.innerHTML = '<li class="empty-state">Loading...</li>';
            const savedToken = localStorage.getItem('cloudspace_token') || '';
            try {
                let requestURL = `api/forums/forums-api.php?action=list_posts`;
                if (myPostsOnly) requestURL += `&author=${encodeURIComponent(username)}`;
                const response = await fetch(requestURL, { method:'GET', headers:{ 'Authorization':`Bearer ${savedToken}` } });
                const dataset = await response.json();
                if (dataset.status !== 'success') { listElement.innerHTML = `<li class="empty-state">❌ ${dataset.message}</li>`; return; }
                allPosts = dataset.posts || [];
                allPosts.sort((a,b) => new Date(b.timestamp) - new Date(a.timestamp));
                const searchVal = searchInput.value.trim();
                if (searchVal) filteredPosts = allPosts.filter(post => fuzzyMatch(searchVal, post.title) || fuzzyMatch(searchVal, post.content));
                else filteredPosts = allPosts;
                renderPosts(filteredPosts);
            } catch (err) { listElement.innerHTML = '<li class="empty-state">Network error.</li>'; }
        }

        async function editThread(postId, oldTitle, oldContent) {
            const newTitle = prompt('Enter new title:', oldTitle);
            if (newTitle === null) return;
            const newContent = prompt('Enter new content:', oldContent);
            if (newContent === null) return;
            const token = localStorage.getItem('cloudspace_token');
            try {
                const response = await fetch('api/forums/forums-api.php', { method:'POST', headers:{ 'Content-Type':'application/json','Authorization':'Bearer '+token }, body:JSON.stringify({ action:'update_post', post_id:postId, title:newTitle, content:newContent }) });
                const data = await response.json();
                if (data.status === 'success') { showToast('Thread updated!', 'success'); fetchAndRenderPosts(currentForumSubView==='my-posts'); }
                else showToast('Error: ' + data.message, 'error');
            } catch (err) { showToast('Network error.', 'error'); }
        }

        async function deleteThread(postId) {
            if (!confirm('Delete this thread permanently?')) return;
            const token = localStorage.getItem('cloudspace_token');
            try {
                const response = await fetch('api/forums/forums-api.php', { method:'POST', headers:{ 'Content-Type':'application/json','Authorization':'Bearer '+token }, body:JSON.stringify({ action:'delete_post', post_id:postId }) });
                const data = await response.json();
                if (data.status === 'success') { showToast('Thread deleted.', 'success'); fetchAndRenderPosts(currentForumSubView==='my-posts'); }
                else showToast('Error: ' + data.message, 'error');
            } catch (err) { showToast('Network error.', 'error'); }
        }

        async function openForumPost(postId) {
            currentForumPostId = postId;
            try {
                const res = await fetch(API_BASE + '?action=get_post&post_id=' + encodeURIComponent(postId));
                const data = await res.json();
                if (data.status !== 'success') { alert('Could not load post.'); return; }
                document.getElementById('forumMainFeedPanel').style.display = 'none';
                document.getElementById('forumThreadCreationPanel').style.display = 'none';
                document.getElementById('forumPostDetailView').style.display = 'block';
                document.getElementById('forumDetailTitle').textContent = data.post.title;
                document.getElementById('forumDetailAuthor').textContent = data.post.author;
                document.getElementById('forumDetailDate').textContent = formatDate(data.post.timestamp);
                const bodyEl = document.getElementById('forumDetailBody');
                let bodyHtml = `<div style="white-space:pre-wrap;">${escapeHtml(data.post.content)}</div>`;
                if (data.post.image) {
                    const imgUrl = getSecureImageUrl(data.post.image);
                    if (imgUrl) bodyHtml += `<hr style="border:2px solid #b2c9ab;margin:1rem 0;"><img src="${imgUrl}" alt="Post image" class="forum-image-preview-card">`;
                }
                bodyEl.innerHTML = bodyHtml;
                renderForumComments(data.comments);
                document.getElementById('forumCommentInput').value = '';
            } catch (err) { alert('Network error loading post.'); }
        }

        function renderForumComments(comments) {
            const list = document.getElementById('forumCommentsList');
            list.innerHTML = '';
            if (!comments || comments.length === 0) { list.innerHTML = '<li class="empty-state">No comments yet. 😊</li>'; return; }
            const loggedUser = username.toLowerCase();
            comments.forEach(comment => {
                const li = document.createElement('li');
                li.className = 'comment-item';
                const isYours = comment.author.toLowerCase() === loggedUser;
                li.innerHTML = `<div class="comment-header"><span class="comment-author">${escapeHtml(comment.author)}${isYours?'<span class="you-badge">You</span>':''}</span><span class="comment-date">${formatDate(comment.timestamp)}</span></div><div class="comment-content">${escapeHtml(comment.content)}</div>`;
                list.appendChild(li);
            });
        }

        async function postForumComment() {
            const input = document.getElementById('forumCommentInput');
            const content = input.value.trim();
            const btn = document.getElementById('forumCommentSubmitBtn');
            if (!content) { showToast('Please write a comment.', 'error'); return; }
            if (!currentForumPostId) { showToast('No post selected.', 'error'); return; }
            btn.disabled = true; btn.textContent = 'Posting...';
            try {
                const res = await fetch(API_BASE, { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({action:'add_comment',post_id:currentForumPostId,author:username,content}) });
                const data = await res.json();
                if (data.status === 'success') {
                    input.value = '';
                    showToast('💬 Comment posted!', 'success');
                    const postRes = await fetch(API_BASE + '?action=get_post&post_id=' + encodeURIComponent(currentForumPostId));
                    const postData = await postRes.json();
                    if (postData.status === 'success') renderForumComments(postData.comments);
                    fetchAndRenderPosts(currentForumSubView==='my-posts');
                } else showToast(data.message || 'Failed to post comment.', 'error');
            } catch (err) { showToast('Network error.', 'error'); }
            btn.disabled = false; btn.textContent = '📤 Post Comment';
        }

        function goBackToForumFeed() {
            document.getElementById('forumPostDetailView').style.display = 'none';
            document.getElementById('forumMainFeedPanel').style.display = 'block';
            fetchAndRenderPosts(currentForumSubView==='my-posts');
        }

        async function transmitNewThreadPayload(event) {
            event.preventDefault();
            const submitBtn = document.getElementById('forumSubmitBtn');
            const statusMsg = document.getElementById('forumFormStatus');
            const titleVal = document.getElementById('forumFormTitle').value.trim();
            const bodyVal = document.getElementById('forumFormBody').value.trim();
            const imageInput = document.getElementById('forumFormFile');
            const savedToken = localStorage.getItem('cloudspace_token') || '';
            submitBtn.disabled = true; submitBtn.innerHTML = '<span class="spinner"></span> Publishing...';
            statusMsg.className = 'comment-status'; statusMsg.style.display = 'none';
            const packetBody = new FormData();
            packetBody.append('action','create_post'); packetBody.append('title',titleVal); packetBody.append('content',bodyVal);
            if (imageInput.files[0]) packetBody.append('image',imageInput.files[0]);
            try {
                const response = await fetch('api/forums/forums-api.php', { method:'POST', headers:{'Authorization':`Bearer ${savedToken}`}, body:packetBody });
                const outcome = await response.json();
                if (outcome.status === 'success') {
                    document.getElementById('newForumFormSubmitElement').reset();
                    document.getElementById('fileChosen').textContent = 'No file chosen';
                    showToast('🎉 Thread uploaded!', 'success');
                    submitBtn.className = 'comment-submit btn-published'; submitBtn.innerHTML = '✅ Published';
                    setTimeout(() => { submitBtn.className = 'comment-submit'; submitBtn.innerHTML = '🚀 Publish Thread'; submitBtn.disabled = false; }, 3000);
                    setTimeout(() => toggleForumSection('feed'), 1500);
                } else {
                    showToast(outcome.message || 'Error.', 'error');
                    statusMsg.className = 'comment-status error'; statusMsg.textContent = outcome.message; statusMsg.style.display = 'block';
                    submitBtn.disabled = false; submitBtn.innerHTML = '🚀 Publish Thread';
                }
            } catch (err) {
                showToast('Network error.', 'error');
                statusMsg.className = 'comment-status error'; statusMsg.textContent = 'Network error.'; statusMsg.style.display = 'block';
                submitBtn.disabled = false; submitBtn.innerHTML = '🚀 Publish Thread';
            }
        }

        document.getElementById('forumFormFile').addEventListener('change', function(e) {
            document.getElementById('fileChosen').textContent = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
        });

        document.getElementById('forumFeedTabBtn').addEventListener('click', () => toggleForumSection('feed'));
        document.getElementById('forumMyTabBtn').addEventListener('click', () => toggleForumSection('my-posts'));
        document.getElementById('forumCreateTabBtn').addEventListener('click', () => toggleForumSection('create'));
        document.getElementById('newForumFormSubmitElement').addEventListener('submit', transmitNewThreadPayload);

        // ── Session idle timeout ──
        let idleTimer;
        function resetIdleTimer() {
            clearTimeout(idleTimer);
            idleTimer = setTimeout(() => {
                alert('Session expired due to inactivity.');
                localStorage.removeItem('cloudspace_username'); localStorage.removeItem('cloudspace_token');
                window.location.href = 'login';
            }, 15 * 60 * 1000);
        }
        ['click','mousemove','keydown','scroll','touchstart'].forEach(e => document.addEventListener(e, resetIdleTimer));
        resetIdleTimer();

        // ── Init ──
        loadProfile();
        loadLogs();
        showPage('dashboard');

        document.getElementById('forumBackBtn').addEventListener('click', goBackToForumFeed);
        document.getElementById('forumCommentSubmitBtn').addEventListener('click', postForumComment);
        document.getElementById('forumCommentInput').addEventListener('keydown', e => {
            if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) { e.preventDefault(); postForumComment(); }
        });

        window.toggleForumSection = toggleForumSection;
        window.transmitNewThreadPayload = transmitNewThreadPayload;
        window.openForumPost = openForumPost;
    </script>
</body>
</html>