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

        /* ── Thumbnail (enlarged & clickable) ── */
        .post-thumb {
            flex: 0 0 160px;
            height: 160px;
            border-radius: 16px;
            overflow: hidden;
            background: #f5ede4;
            border: 2px solid #c4a88a;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .post-thumb:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
        .post-meta .views-count {
            margin-left: 0.5rem;
            color: #4a6a8a;
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

        /* ── Like Button Styles ── */
        .like-btn, .like-btn-detail {
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            padding: 0.2rem 0.4rem;
            border-radius: 30px;
            transition: all 0.2s;
            font-family: inherit;
            color: #1a2a5e;
        }
        .like-btn:hover, .like-btn-detail:hover {
            background: #f4c2c2;
            transform: scale(1.05);
        }
        .like-btn:active, .like-btn-detail:active {
            transform: scale(0.95);
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

        /* ── SETTINGS TABS (full width) ── */
        .settings-tabs {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
            margin-bottom: 1.8rem;
        }
        .settings-tab {
            flex: 1 1 0;
            min-width: 0;
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
        /* ── Recent search remove button ── */
        .search-item .remove-recent {
            margin-left: auto;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 0.8rem;
            color: #4a6a8a;
            padding: 0 0.2rem;
            border-radius: 50%;
            transition: background 0.2s, color 0.2s;
            line-height: 1;
        }
        .search-item .remove-recent:hover {
            background: #f4c2c2;
            color: #c62828;
        }

        /* ── Forum Navigation Tabs (full width) ── */
        .forum-nav-container {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
            margin-bottom: 1.8rem;
        }
        .forum-nav-btn {
            flex: 1 1 0;
            min-width: 0;
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
        /* ── Toast close button ── */
        .toast .toast-close {
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            font-weight: bold;
            color: #4a6a8a;
            padding: 0 0.2rem;
            transition: color 0.2s;
            line-height: 1;
        }
        .toast .toast-close:hover {
            color: #c62828;
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

        /* ── Delete Confirmation Modal (smooth, no glow) ── */
        .delete-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            transition: opacity 200ms ease;
            will-change: opacity;
        }
        .delete-modal-overlay.active {
            display: flex;
            opacity: 1;
            transition: opacity 300ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        .delete-modal {
            background: #fcf8f0;
            border: 2px solid #b2c9ab;
            border-radius: 28px;
            padding: 2rem 2.5rem;
            max-width: 420px;
            width: 90%;
            box-shadow: 6px 8px 0 #dbb594;
            transform: scale(0.95);
            opacity: 0;
            transition: transform 200ms ease, opacity 200ms ease;
            will-change: transform, opacity;
        }
        .delete-modal-overlay.active .delete-modal {
            transform: scale(1);
            opacity: 1;
            transition: transform 300ms cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 300ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        .delete-modal h3 {
            font-size: 1.3rem;
            font-weight: 900;
            color: #1a2a5e;
            margin-bottom: 0.5rem;
        }
        .delete-modal p {
            color: #1a2a5e;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        .delete-modal .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }
        .delete-modal .modal-actions button {
            padding: 0.6rem 1.8rem;
            border: none;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
            box-shadow: 2px 3px 0 #8a9a7a;
        }
        .delete-modal .modal-actions .cancel-btn {
            background: #b2c9ab;
            color: #0a0a2e;
        }
        .delete-modal .modal-actions .cancel-btn:hover {
            background: #9fb89a;
            transform: translateY(-2px);
        }
        .delete-modal .modal-actions .confirm-btn {
            background: #c62828;
            color: #fff;
            box-shadow: 2px 3px 0 #8b1a1a;
        }
        .delete-modal .modal-actions .confirm-btn:hover {
            background: #b71c1c;
            transform: translateY(-2px);
        }

        /* ── Edit Modal ── */
        .edit-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            transition: opacity 200ms ease;
            will-change: opacity;
        }
        .edit-modal-overlay.active {
            display: flex;
            opacity: 1;
            transition: opacity 300ms cubic-bezier(0.4,0,0.2,1);
        }
        .edit-modal {
            background: #fcf8f0;
            border: 2px solid #b2c9ab;
            border-radius: 28px;
            padding: 2rem 2.5rem;
            max-width: 500px;
            width: 90%;
            box-shadow: 6px 8px 0 #dbb594;
            transform: scale(0.95);
            opacity: 0;
            transition: transform 200ms ease, opacity 200ms ease;
            will-change: transform, opacity;
        }
        .edit-modal-overlay.active .edit-modal {
            transform: scale(1);
            opacity: 1;
            transition: transform 300ms cubic-bezier(0.4,0,0.2,1),
                        opacity 300ms cubic-bezier(0.4,0,0.2,1);
        }
        .edit-modal h3 {
            font-size: 1.3rem;
            font-weight: 900;
            color: #1a2a5e;
            margin-bottom: 0.5rem;
        }
        .edit-modal .form-group {
            margin-bottom: 1rem;
        }
        .edit-modal .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 800;
            color: #0a0a2e;
            margin-bottom: 0.25rem;
        }
        .edit-modal .form-group input,
        .edit-modal .form-group textarea {
            width: 100%;
            padding: 0.6rem 1rem;
            border: 2px solid #b2c9ab;
            border-radius: 20px;
            font-size: 0.9rem;
            color: #0a0a2e;
            background: #fff9f0;
            outline: none;
            font-family: inherit;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02), 1px 2px 0 #dbb594;
        }
        .edit-modal .form-group input:focus,
        .edit-modal .form-group textarea:focus {
            border-color: #f4c2c2;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(244,194,194,0.2), 1px 2px 0 #dbb594;
        }
        .edit-modal .form-group textarea {
            resize: vertical;
            min-height: 90px;
        }
        .edit-modal .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1rem;
        }
        .edit-modal .modal-actions button {
            padding: 0.6rem 1.8rem;
            border: none;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
            box-shadow: 2px 3px 0 #8a9a7a;
        }
        .edit-modal .modal-actions .cancel-btn {
            background: #b2c9ab;
            color: #0a0a2e;
        }
        .edit-modal .modal-actions .cancel-btn:hover {
            background: #9fb89a;
            transform: translateY(-2px);
        }
        .edit-modal .modal-actions .submit-btn {
            background: #1a2a5e;
            color: #fcf8f0;
            box-shadow: 2px 3px 0 #0a0a2e;
        }
        .edit-modal .modal-actions .submit-btn:hover {
            background: #0f1a3a;
            transform: translateY(-2px);
        }

        /* ── Log Detail Modal ── */
        .log-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            transition: opacity 200ms ease;
            will-change: opacity;
        }
        .log-modal-overlay.active {
            display: flex;
            opacity: 1;
            transition: opacity 300ms cubic-bezier(0.4,0,0.2,1);
        }
        .log-modal {
            background: #fcf8f0;
            border: 2px solid #b2c9ab;
            border-radius: 28px;
            padding: 2rem 2.5rem;
            max-width: 500px;
            width: 90%;
            box-shadow: 6px 8px 0 #dbb594;
            transform: scale(0.95);
            opacity: 0;
            transition: transform 200ms ease, opacity 200ms ease;
            will-change: transform, opacity;
        }
        .log-modal-overlay.active .log-modal {
            transform: scale(1);
            opacity: 1;
            transition: transform 300ms cubic-bezier(0.4,0,0.2,1),
                        opacity 300ms cubic-bezier(0.4,0,0.2,1);
        }
        .log-modal h3 {
            font-size: 1.3rem;
            font-weight: 900;
            color: #1a2a5e;
            margin-bottom: 1rem;
        }
        .log-detail-item {
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            color: #0a0a2e;
        }
        .log-detail-item strong {
            color: #1a2a5e;
        }
        .log-modal .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }
        .log-modal .modal-actions button {
            padding: 0.6rem 1.8rem;
            border: none;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
            box-shadow: 2px 3px 0 #8a9a7a;
        }
        .log-modal .modal-actions .cancel-btn {
            background: #b2c9ab;
            color: #0a0a2e;
        }
        .log-modal .modal-actions .cancel-btn:hover {
            background: #9fb89a;
            transform: translateY(-2px);
        }
        /* ── Delete button in log modal (matching delete confirm style) ── */
        .log-modal .modal-actions .delete-btn {
            background: #c62828;
            color: #fff;
            box-shadow: 2px 3px 0 #8b1a1a;
            border: none;
            padding: 0.6rem 1.8rem;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }
        .log-modal .modal-actions .delete-btn:hover {
            background: #b71c1c;
            transform: translateY(-2px);
            box-shadow: 2px 5px 0 #8b1a1a;
        }
        .log-modal .modal-actions .delete-btn:active {
            transform: translateY(2px);
            box-shadow: 2px 1px 0 #8b1a1a;
        }

        /* ── Image Viewer Modal ── */
        .image-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(6px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            opacity: 0;
            transition: opacity 250ms ease;
            will-change: opacity;
        }
        .image-modal-overlay.active {
            display: flex;
            opacity: 1;
        }
        .image-modal {
            position: relative;
            max-width: 90vw;
            max-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .image-modal img {
            max-width: 100%;
            max-height: 90vh;
            border-radius: 12px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.4);
            border: 3px solid #b2c9ab;
            object-fit: contain;
            background: #fcf8f0;
        }
        .image-modal-close {
            position: absolute;
            top: -20px;
            right: -20px;
            background: #1a2a5e;
            color: #fcf8f0;
            border: none;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            font-size: 1.8rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 2px 4px 0 #0a0a2e;
            transition: all 0.2s ease;
        }
        .image-modal-close:hover {
            background: #f4c2c2;
            color: #1a2a5e;
            transform: rotate(90deg);
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

    <!-- ─── DELETE CONFIRMATION MODAL ─── -->
    <div class="delete-modal-overlay" id="deleteModal">
        <div class="delete-modal">
            <h3>⚠️ Delete Thread</h3>
            <p>Are you sure you want to permanently delete this thread? This action cannot be undone.</p>
            <div class="modal-actions">
                <button class="cancel-btn" id="deleteCancelBtn">Cancel</button>
                <button class="confirm-btn" id="deleteConfirmBtn">Delete</button>
            </div>
        </div>
    </div>

    <!-- ─── EDIT THREAD MODAL ─── -->
    <div class="edit-modal-overlay" id="editModal">
        <div class="edit-modal">
            <h3>✏️ Edit Thread</h3>
            <form id="editThreadForm" style="margin-top:0.5rem;">
                <div class="form-group">
                    <label for="editTitle">Title</label>
                    <input type="text" id="editTitle" required />
                </div>
                <div class="form-group">
                    <label for="editContent">Content</label>
                    <textarea id="editContent" rows="6" required></textarea>
                </div>
                <div class="form-group">
                    <label for="editImage">Update Image <span style="font-weight:normal;font-size:0.8rem;color:#4a6a8a;">(optional – leave empty to keep current)</span></label>
                    <label class="custom-file-upload">
                        <input type="file" id="editImage" accept="image/*" />
                        📁 Choose Image
                    </label>
                    <span id="editFileChosen" style="margin-left:0.8rem;font-size:0.85rem;color:#4a6a8a;">No file chosen</span>
                </div>
                <div class="modal-actions">
                    <button type="button" class="cancel-btn" id="editCancelBtn">Cancel</button>
                    <button type="submit" class="submit-btn" id="editConfirmBtn">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ─── LOG DETAIL MODAL ─── -->
    <div class="log-modal-overlay" id="logModal">
        <div class="log-modal">
            <h3>📋 Log Details</h3>
            <div class="log-detail-item"><strong>🕒 Timestamp:</strong> <span id="logTimestamp"></span></div>
            <div class="log-detail-item"><strong>IP Address:</strong> <span id="logIP"></span></div>
            <div class="log-detail-item"><strong>Country:</strong> <span id="logCountry"></span></div>
            <div class="log-detail-item"><strong>User Agent:</strong> <span id="logUserAgent"></span></div>
            <div class="log-detail-item"><strong>Action:</strong> <span id="logAction"></span></div>
            <div class="modal-actions" style="margin-top:1.5rem;">
                <button class="cancel-btn" id="logCloseBtn">Close</button>
                <button class="delete-btn" id="logDeleteBtn">🗑 Delete This Log</button>
            </div>
        </div>
    </div>

    <!-- ─── IMAGE VIEWER MODAL ─── -->
    <div class="image-modal-overlay" id="imageModal">
        <div class="image-modal">
            <img id="imageModalImg" src="" alt="Full image" />
            <button class="image-modal-close" id="imageModalClose">&times;</button>
        </div>
    </div>

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

        // ── Cache helpers ──
        const CACHE_KEY = 'cloudspace_threads_cache';
        const CACHE_EXPIRY = 5 * 60 * 1000; // 5 minutes

        function getCachedThreads() {
            try {
                const cached = localStorage.getItem(CACHE_KEY);
                if (!cached) return null;
                const data = JSON.parse(cached);
                if (Date.now() - data.timestamp > CACHE_EXPIRY) {
                    localStorage.removeItem(CACHE_KEY);
                    return null;
                }
                return data.posts;
            } catch {
                return null;
            }
        }

        function setCachedThreads(posts) {
            localStorage.setItem(CACHE_KEY, JSON.stringify({
                timestamp: Date.now(),
                posts: posts
            }));
        }

        function clearThreadCache() {
            localStorage.removeItem(CACHE_KEY);
        }

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

        // ── Toast Helper with dismiss button ──
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            const icon = type === 'success' ? '✅' : '❌';
            toast.innerHTML = `<span class="toast-icon">${icon}</span><span class="toast-message">${escapeHtml(message)}</span>`;

            // ── Dismiss button ──
            const closeBtn = document.createElement('span');
            closeBtn.className = 'toast-close';
            closeBtn.textContent = '×';
            closeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toast.classList.add('hide');
                setTimeout(() => toast.remove(), 400);
            });
            toast.appendChild(closeBtn);

            toastContainer.appendChild(toast);
            // Auto-dismiss after 4 seconds
            const timer = setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => toast.remove(), 400);
            }, 4000);
            // Clear timer if manually dismissed
            closeBtn.addEventListener('click', () => clearTimeout(timer));
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

        // ── Updated loadLogs with clickable log items ──
        async function loadLogs() {
            const token = localStorage.getItem('cloudspace_token');
            const list = document.getElementById('logList');
            list.innerHTML = '<li class="empty-state">Loading logs...</li>';
            if (!token) {
                list.innerHTML = '<li class="empty-state">Not authenticated.</li>';
                return;
            }
            try {
                const res = await fetch('auth-api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'get_logs', token: token })
                });
                const data = await res.json();
                if (data.status !== 'success') {
                    list.innerHTML = `<li class="empty-state">${data.message || 'Failed to load logs.'}</li>`;
                    return;
                }
                const logs = data.logs || [];
                if (logs.length === 0) {
                    list.innerHTML = '<li class="empty-state">No recent logs available.</li>';
                    return;
                }
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
                    const displayDate = dateObj.toLocaleDateString('en-US', {
                        weekday: 'long',
                        month: 'long',
                        day: 'numeric',
                        year: 'numeric'
                    });
                    html += `<li style="padding:0.3rem 0;font-weight:800;color:#1a2a5e;background:transparent;border-bottom:2px solid #b2c9ab;">📅 ${displayDate}</li>`;
                    groups[dateKey].forEach(log => {
                        const time = new Date(log.timestamp).toLocaleTimeString('en-US', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        // Store full log data as data attributes for modal
                        html += `
                            <li class="log-item" style="cursor:pointer;" data-log='${JSON.stringify(log).replace(/'/g, "&apos;")}'>
                                <span>🕒 ${time} &bull; ${escapeHtml(log.action)}</span>
                                <span class="log-time">IP: ${escapeHtml(log.ip_address)}</span>
                            </li>
                        `;
                    });
                });
                list.innerHTML = html;

                // ── Attach click event to each log item to open modal ──
                document.querySelectorAll('.log-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const logData = JSON.parse(this.dataset.log);
                        openLogModal(logData);
                    });
                });

            } catch (err) {
                console.warn('loadLogs error:', err);
                list.innerHTML = '<li class="empty-state">Network error loading logs.</li>';
            }
        }

        // ── Log Modal ──
        let currentLogTimestamp = null;

        function openLogModal(logData) {
            currentLogTimestamp = logData.timestamp;
            document.getElementById('logTimestamp').textContent = logData.timestamp;
            document.getElementById('logIP').textContent = logData.ip_address || 'N/A';
            document.getElementById('logCountry').textContent = logData.country || 'N/A';
            document.getElementById('logUserAgent').textContent = logData.user_agent || 'N/A';
            document.getElementById('logAction').textContent = logData.action;
            document.getElementById('logModal').classList.add('active');
        }

        function closeLogModal() {
            document.getElementById('logModal').classList.remove('active');
        }

        // ── Delete a specific log entry ──
        async function deleteLogEntry() {
            if (!currentLogTimestamp) return;
            if (!confirm('Delete this log entry?')) return;

            const token = localStorage.getItem('cloudspace_token');
            if (!token) {
                showToast('Authentication required.', 'error');
                return;
            }

            try {
                const res = await fetch('auth-api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'delete_log',
                        token: token,
                        timestamp: currentLogTimestamp
                    })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    showToast('Log deleted.', 'success');
                    closeLogModal();
                    loadLogs(); // refresh list
                } else {
                    showToast(data.message || 'Failed to delete log.', 'error');
                }
            } catch (err) {
                showToast('Network error.', 'error');
            }
        }

        // ── Attach event listeners for log modal ──
        document.getElementById('logCloseBtn').addEventListener('click', closeLogModal);
        document.getElementById('logDeleteBtn').addEventListener('click', deleteLogEntry);
        // Click outside to close
        document.getElementById('logModal').addEventListener('click', function(e) {
            if (e.target === this) closeLogModal();
        });

        // ── Image Modal Functions ──
        function openImageModal(imageUrl) {
            const modal = document.getElementById('imageModal');
            const img = document.getElementById('imageModalImg');
            img.src = imageUrl;
            modal.classList.add('active');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.remove('active');
        }

        // ── Attach close events for image modal ──
        document.getElementById('imageModalClose').addEventListener('click', closeImageModal);
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) closeImageModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('imageModal').classList.contains('active')) {
                closeImageModal();
            }
        });

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
                        img.src = imgUrl;
                        img.alt = 'Thumbnail';
                        img.loading = 'lazy';
                        // ── Click to open image modal ──
                        thumbDiv.addEventListener('click', function(e) {
                            e.stopPropagation();
                            openImageModal(imgUrl);
                        });
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
                const viewIcon = '👁️';
                const likeIcon = post.liked ? '❤️' : '🤍';
                metaSpan.innerHTML = `
                    <span class="author"><span class="post-author-avatar">${escapeHtml(authorInitial)}</span>${escapeHtml(post.author)}</span>
                    <span class="date">${post.timestamp ? post.timestamp.split('T')[0] : ''}</span>
                    <span class="comments-count">💬 ${post.comment_count || 0}</span>
                    <span class="views-count">${viewIcon} ${post.views || 0}</span>
                    <button class="like-btn" data-id="${post.id}" data-liked="${post.liked ? 'true' : 'false'}">${likeIcon} ${post.likes || 0}</button>
                `;
                contentDiv.appendChild(titleSpan);
                contentDiv.appendChild(metaSpan);
                const isOwner = post.author.toLowerCase() === username.toLowerCase();
                if (isOwner) {
                    const actionsDiv = document.createElement('div');
                    actionsDiv.className = 'post-actions';
                    const editBtn = document.createElement('button');
                    editBtn.className = 'edit-btn'; editBtn.innerHTML = '✎'; editBtn.title = 'Edit Thread';
                    editBtn.addEventListener('click', e => {
                        e.stopPropagation();
                        openEditModal(post.id, post.title, post.content);
                    });
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

        // ── Event delegation for like buttons in post list ──
        document.getElementById('globalForumsListElement').addEventListener('click', async function(e) {
            const btn = e.target.closest('.like-btn');
            if (!btn) return;
            e.stopPropagation();
            const postId = btn.dataset.id;
            const token = localStorage.getItem('cloudspace_token');
            if (!token) { showToast('Please log in to like.', 'error'); return; }
            try {
                const res = await fetch(API_BASE, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
                    body: JSON.stringify({ action: 'like_post', post_id: postId })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    const newIcon = data.liked ? '❤️' : '🤍';
                    btn.innerHTML = `${newIcon} ${data.likes}`;
                    btn.dataset.liked = data.liked ? 'true' : 'false';
                    const post = allPosts.find(p => p.id === postId);
                    if (post) { post.liked = data.liked; post.likes = data.likes; }
                } else { showToast(data.message || 'Failed to like.', 'error'); }
            } catch (err) { showToast('Network error.', 'error'); }
        });

        // ── Event delegation for like buttons in post detail ──
        document.getElementById('forumPostDetailView').addEventListener('click', async function(e) {
            const btn = e.target.closest('.like-btn-detail');
            if (!btn) return;
            const postId = btn.dataset.id;
            const token = localStorage.getItem('cloudspace_token');
            if (!token) { showToast('Please log in to like.', 'error'); return; }
            try {
                const res = await fetch(API_BASE, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
                    body: JSON.stringify({ action: 'like_post', post_id: postId })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    const newIcon = data.liked ? '❤️' : '🤍';
                    btn.innerHTML = `${newIcon} ${data.likes}`;
                    btn.dataset.liked = data.liked ? 'true' : 'false';
                    const post = allPosts.find(p => p.id === postId);
                    if (post) { post.liked = data.liked; post.likes = data.likes; }
                    const listBtn = document.querySelector(`.like-btn[data-id="${postId}"]`);
                    if (listBtn) {
                        listBtn.innerHTML = `${newIcon} ${data.likes}`;
                        listBtn.dataset.liked = data.liked ? 'true' : 'false';
                    }
                } else { showToast(data.message || 'Failed to like.', 'error'); }
            } catch (err) { showToast('Network error.', 'error'); }
        });

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
                if (isRecent) { searchDropdown.style.display = 'none'; searchWrapper.classList.remove('active'); return; }
                const noResult = document.createElement('div');
                noResult.className = 'search-item'; noResult.innerText = 'No results found';
                searchDropdown.appendChild(noResult);
            } else {
                items.forEach((itemText) => {
                    const div = document.createElement('div');
                    div.className = `search-item ${isRecent ? 'recent' : ''}`;

                    // ── Text content ──
                    const textSpan = document.createElement('span');
                    textSpan.textContent = itemText;
                    div.appendChild(textSpan);

                    // ── Remove button for recent items only ──
                    if (isRecent) {
                        const removeBtn = document.createElement('button');
                        removeBtn.className = 'remove-recent';
                        removeBtn.textContent = '×';
                        removeBtn.title = 'Remove from recent searches';
                        removeBtn.dataset.term = itemText;
                        div.appendChild(removeBtn);
                    }

                    // ── Click on item itself to execute search ──
                    div.addEventListener('click', (e) => {
                        // Ignore if click is on remove button
                        if (e.target.closest('.remove-recent')) return;
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

        function showSearchDropdown(val) {
            if (!val) { renderDropdown(getRecentSearches(), true); return; }
            const mockDatabase = ['Dashboard analytics', 'User settings', 'Forum threads', 'System log history', 'Cloud storage file space'];
            const filteredResults = mockDatabase.filter(item => item.toLowerCase().includes(val.toLowerCase()));
            renderDropdown(filteredResults, false);
        }

        // ── Remove recent search on button click (using event delegation on the static parent) ──
        document.getElementById('searchResultsDropdown').addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-recent');
            if (!btn) return;
            e.stopPropagation();
            const term = btn.dataset.term;
            if (term) {
                let searches = getRecentSearches();
                searches = searches.filter(s => s.toLowerCase() !== term.toLowerCase());
                localStorage.setItem(RECENT_SEARCHES_KEY, JSON.stringify(searches));
                const currentVal = searchInput.value;
                showSearchDropdown(currentVal);
            }
        });

        function addActive(items) {
            if (!items) return false;
            removeActive(items);
            if (currentFocus >= items.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = items.length - 1;
            items[currentFocus].classList.add('selected');
        }
        function removeActive(items) { for (let i = 0; i < items.length; i++) items[i].classList.remove('selected'); }
        function ensureVisible(element) {
            if (!element) return;
            const parent = searchDropdown;
            if (element.offsetTop < parent.scrollTop) parent.scrollTop = element.offsetTop;
            else if ((element.offsetTop + element.clientHeight) > (parent.scrollTop + parent.clientHeight))
                parent.scrollTop = element.offsetTop + element.clientHeight - parent.clientHeight;
        }

        searchInput.addEventListener('input', function() {
            const val = this.value;
            if (!val) { renderDropdown(getRecentSearches(), true); return; }
            const mockDatabase = ['Dashboard analytics', 'User settings', 'Forum threads', 'System log history', 'Cloud storage file space'];
            const filteredResults = mockDatabase.filter(item => item.toLowerCase().includes(val.toLowerCase()));
            renderDropdown(filteredResults, false);
        });
        searchInput.addEventListener('focus', function() { if (!this.value) renderDropdown(getRecentSearches(), true); });
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
            if (!searchWrapper.contains(e.target)) { searchDropdown.style.display = 'none'; searchWrapper.classList.remove('active'); }
        });

        // ── Updated toggleForumSection ──
        function toggleForumSection(targetSection) {
            currentForumSubView = targetSection;

            // ── Always hide the detail view when switching tabs ──
            document.getElementById('forumPostDetailView').style.display = 'none';

            // Update tab button states
            document.getElementById('forumFeedTabBtn').classList.toggle('active', targetSection === 'feed');
            document.getElementById('forumMyTabBtn').classList.toggle('active', targetSection === 'my-posts');
            document.getElementById('forumCreateTabBtn').classList.toggle('active', targetSection === 'create');

            const feedPanel = document.getElementById('forumMainFeedPanel');
            const createPanel = document.getElementById('forumThreadCreationPanel');
            const heading = document.getElementById('forumFeedHeadingText');

            if (targetSection === 'create') {
                feedPanel.style.display = 'none';
                createPanel.style.display = 'block';
            } else {
                // Show feed panel, hide create panel
                createPanel.style.display = 'none';
                feedPanel.style.display = 'block';
                heading.textContent = targetSection === 'my-posts' ? '👤 My Thread Uploads' : 'Latest Public Records';
                fetchAndRenderPosts(targetSection === 'my-posts');
            }
        }

        // ── Caching-aware fetch ──
        async function fetchAndRenderPosts(myPostsOnly = false) {
            const listElement = document.getElementById('globalForumsListElement');
            listElement.innerHTML = '<li class="empty-state">Loading...</li>';

            // 1. Try cache (only if not filtering by author)
            if (!myPostsOnly) {
                const cached = getCachedThreads();
                if (cached && cached.length > 0) {
                    allPosts = cached;
                    allPosts.sort((a,b) => new Date(b.timestamp) - new Date(a.timestamp));
                    const searchVal = searchInput.value.trim();
                    if (searchVal) {
                        filteredPosts = allPosts.filter(post => fuzzyMatch(searchVal, post.title) || fuzzyMatch(searchVal, post.content));
                    } else {
                        filteredPosts = allPosts;
                    }
                    renderPosts(filteredPosts);
                    // Still fetch in background to refresh cache silently
                    fetchFreshThreads(myPostsOnly);
                    return;
                }
            }

            // 2. No cache – fetch fresh
            await fetchFreshThreads(myPostsOnly);
        }

        async function fetchFreshThreads(myPostsOnly = false) {
            const savedToken = localStorage.getItem('cloudspace_token') || '';
            try {
                let requestURL = `api/forums/forums-api.php?action=list_posts`;
                if (myPostsOnly) {
                    requestURL += `&author=${encodeURIComponent(username)}`;
                }
                const response = await fetch(requestURL, {
                    method: 'GET',
                    headers: { 'Authorization': `Bearer ${savedToken}` }
                });
                const dataset = await response.json();
                if (dataset.status !== 'success') {
                    document.getElementById('globalForumsListElement').innerHTML =
                        `<li class="empty-state">❌ ${dataset.message}</li>`;
                    return;
                }
                allPosts = dataset.posts || [];
                allPosts.sort((a,b) => new Date(b.timestamp) - new Date(a.timestamp));

                // Cache only if not myPostsOnly
                if (!myPostsOnly) {
                    setCachedThreads(allPosts);
                }

                const searchVal = searchInput.value.trim();
                if (searchVal) {
                    filteredPosts = allPosts.filter(post => fuzzyMatch(searchVal, post.title) || fuzzyMatch(searchVal, post.content));
                } else {
                    filteredPosts = allPosts;
                }
                renderPosts(filteredPosts);
            } catch (err) {
                document.getElementById('globalForumsListElement').innerHTML =
                    '<li class="empty-state">Network error.</li>';
            }
        }

        // ── Edit Thread with Modal ──
        let editPostId = null; // store the post ID being edited

        function openEditModal(postId, currentTitle, currentContent) {
            editPostId = postId;
            document.getElementById('editTitle').value = currentTitle;
            document.getElementById('editContent').value = currentContent;
            document.getElementById('editFileChosen').textContent = 'No file chosen';
            document.getElementById('editImage').value = ''; // reset file input
            document.getElementById('editModal').classList.add('active');
        }

        async function submitEditForm(e) {
            e.preventDefault();
            const title = document.getElementById('editTitle').value.trim();
            const content = document.getElementById('editContent').value.trim();
            if (!title || !content) {
                showToast('Title and content are required.', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'update_post');
            formData.append('post_id', editPostId);
            formData.append('title', title);
            formData.append('content', content);

            const imageInput = document.getElementById('editImage');
            if (imageInput.files[0]) {
                formData.append('image', imageInput.files[0]);
            }

            const token = localStorage.getItem('cloudspace_token');
            try {
                const response = await fetch('api/forums/forums-api.php', {
                    method: 'POST',
                    headers: { 'Authorization': 'Bearer ' + token },
                    body: formData   // multipart/form-data
                });
                const data = await response.json();
                if (data.status === 'success') {
                    showToast('Thread updated successfully!', 'success');
                    clearThreadCache(); // invalidate cache
                    document.getElementById('editModal').classList.remove('active');
                    fetchAndRenderPosts(currentForumSubView === 'my-posts');
                } else {
                    showToast('Error: ' + data.message, 'error');
                }
            } catch (err) {
                showToast('Network error.', 'error');
            }
        }

        // ── Attach event listeners for edit modal ──
        document.getElementById('editCancelBtn').addEventListener('click', function() {
            document.getElementById('editModal').classList.remove('active');
        });
        document.getElementById('editThreadForm').addEventListener('submit', submitEditForm);
        document.getElementById('editImage').addEventListener('change', function(e) {
            document.getElementById('editFileChosen').textContent = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
        });

        // ── Delete Thread with custom modal (optimized, timing updated) ──
        async function deleteThread(postId) {
            const modal = document.getElementById('deleteModal');
            const confirmBtn = document.getElementById('deleteConfirmBtn');
            const cancelBtn = document.getElementById('deleteCancelBtn');

            // Set postId as data attribute for reference
            modal.dataset.postId = postId;
            modal.classList.add('active');

            // Return a promise that resolves when user chooses
            return new Promise((resolve) => {
                const handleConfirm = () => {
                    closeModal(() => resolve(true));
                };
                const handleCancel = () => {
                    closeModal(() => resolve(false));
                };
                const handleOutside = (e) => {
                    if (e.target === modal) {
                        closeModal(() => resolve(false));
                    }
                };

                const closeModal = (callback) => {
                    // Remove the 'active' class to trigger fade-out (200ms)
                    modal.classList.remove('active');
                    // Wait for the transition to finish (200ms) then call callback
                    setTimeout(() => {
                        callback();
                        // Clean up event listeners
                        confirmBtn.removeEventListener('click', handleConfirm);
                        cancelBtn.removeEventListener('click', handleCancel);
                        modal.removeEventListener('click', handleOutside);
                    }, 200);
                };

                confirmBtn.addEventListener('click', handleConfirm);
                cancelBtn.addEventListener('click', handleCancel);
                modal.addEventListener('click', handleOutside);
            }).then((confirmed) => {
                if (!confirmed) return;

                // ── Actual deletion ──
                const token = localStorage.getItem('cloudspace_token');
                if (!token) {
                    showToast('Authentication token missing.', 'error');
                    window.location.href = 'login';
                    return;
                }
                (async () => {
                    try {
                        const response = await fetch('api/forums/forums-api.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': 'Bearer ' + token
                            },
                            body: JSON.stringify({ action: 'delete_post', post_id: postId })
                        });
                        const data = await response.json();
                        if (data.status === 'success') {
                            showToast('Thread deleted.', 'success');
                            clearThreadCache();
                            fetchAndRenderPosts(currentForumSubView === 'my-posts');
                        } else {
                            showToast('Error: ' + data.message, 'error');
                        }
                    } catch (err) {
                        showToast('Network error.', 'error');
                    }
                })();
            });
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

                // Add views and likes to detail meta
                const meta = document.querySelector('.post-detail-meta');
                // Remove any previously added extra spans
                meta.querySelectorAll('.views-count, .likes-count').forEach(el => el.remove());
                const viewSpan = document.createElement('span');
                viewSpan.className = 'views-count';
                viewSpan.textContent = `👁️ ${data.post.views || 0}`;
                const likeSpan = document.createElement('span');
                likeSpan.className = 'likes-count';
                const heartIcon = data.post.liked ? '❤️' : '🤍';
                likeSpan.innerHTML = `<button class="like-btn-detail" data-id="${data.post.id}" data-liked="${data.post.liked ? 'true' : 'false'}">${heartIcon} ${data.post.likes || 0}</button>`;
                meta.appendChild(viewSpan);
                meta.appendChild(likeSpan);

                const bodyEl = document.getElementById('forumDetailBody');
                let bodyHtml = `<div style="white-space:pre-wrap;">${escapeHtml(data.post.content)}</div>`;
                if (data.post.image) {
                    const imgUrl = getSecureImageUrl(data.post.image);
                    if (imgUrl) bodyHtml += `<hr style="border:2px solid #b2c9ab;margin:1rem 0;"><img src="${imgUrl}" alt="Post image" class="forum-image-preview-card">`;
                }
                bodyEl.innerHTML = bodyHtml;
                renderForumComments(data.comments);
                document.getElementById('forumCommentInput').value = '';

                // Call view_post to increment views
                (async () => {
                    const token = localStorage.getItem('cloudspace_token');
                    if (token) {
                        try {
                            await fetch(API_BASE, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
                                body: JSON.stringify({ action: 'view_post', post_id: postId })
                            });
                            const current = parseInt(viewSpan.textContent.match(/\d+/)?.[0] || 0);
                            viewSpan.textContent = `👁️ ${current + 1}`;
                        } catch (e) {}
                    }
                })();

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
            const token = localStorage.getItem('cloudspace_token');
            if (!token) { showToast('Authentication token missing.', 'error'); window.location.href = 'login'; return; }
            btn.disabled = true; btn.textContent = 'Posting...';
            try {
                const res = await fetch(API_BASE, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
                    body: JSON.stringify({ action: 'add_comment', post_id: currentForumPostId, content: content })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    input.value = '';
                    showToast('💬 Comment posted!', 'success');
                    const postRes = await fetch(API_BASE + '?action=get_post&post_id=' + encodeURIComponent(currentForumPostId));
                    const postData = await postRes.json();
                    if (postData.status === 'success') renderForumComments(postData.comments);
                    fetchAndRenderPosts(currentForumSubView === 'my-posts');
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
                    clearThreadCache();
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