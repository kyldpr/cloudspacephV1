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

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .brand {
            font-size: 1.3rem;
            font-weight: 800;
            color: #0b2559;
            letter-spacing: -0.02em;
            text-transform: uppercase;
        }

        .logout-btn {
            background: transparent;
            border: 1.5px solid #d6e2f5;
            padding: 0.35rem 1rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #1a3a8a;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: #1a3a8a;
            color: #fff;
            border-color: #1a3a8a;
        }

        .back-btn {
            background: transparent;
            border: 1.5px solid #d6e2f5;
            padding: 0.35rem 0.8rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #1a3a8a;
            cursor: pointer;
            transition: all 0.2s ease;
            display: none;
        }

        .back-btn:hover {
            background: #1a3a8a;
            color: #fff;
            border-color: #1a3a8a;
        }

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

        /* ── Post List ── */
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

        /* ── Post Detail View ── */
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

        /* ── Comments Section ── */
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

        /* ── Comment Form ── */
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

        @media (max-width: 480px) {
            .container { padding: 1rem 0.9rem 0.8rem; border-radius: 16px; }
            .brand { font-size: 1.1rem; }
            .user-card { flex-direction: column; align-items: flex-start; padding: 0.8rem; }
            .user-avatar { width: 44px; height: 44px; font-size: 1.2rem; }
            .post-item { flex-direction: column; align-items: flex-start; gap: 0.2rem; }
            .post-meta { font-size: 0.65rem; }
            .post-detail-title { font-size: 1rem; }
            .comment-item .comment-content { font-size: 0.8rem; }
        }
    </style>
</head>
<body>

    <div class="container">

        <div class="dashboard-header">
            <div style="display:flex;align-items:center;gap:0.4rem;">
                <button class="back-btn" id="backBtn">← Back</button>
                <div class="brand">CloudSpacePH</div>
            </div>
            <button class="logout-btn" id="logoutBtn">Sign Out</button>
        </div>

        <!-- User Card -->
        <div class="user-card" id="userCard">
            <div class="user-avatar" id="userAvatar">?</div>
            <div class="user-info">
                <div class="name" id="userName">Loading...</div>
                <div class="detail" id="userDetail">Email: <span>—</span> &bull; Account: <span>—</span></div>
                <div class="detail" style="font-size:0.7rem; color:#8aa3c0;" id="userSince">Joined: —</div>
            </div>
        </div>

        <!-- ==================== POST LIST ==================== -->
        <div id="postListView">
            <div class="section-title">
                Forum Posts
                <span class="badge" id="postCount">0</span>
            </div>

            <ul class="post-list" id="postList">
                <li class="empty-state">Loading posts...</li>
            </ul>
        </div>

        <!-- ==================== POST DETAIL ==================== -->
        <div id="postDetailView">
            <div class="post-detail-header">
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

    <script>
        // ── Check authentication ──
        const username = localStorage.getItem('cloudspace_username');
        if (!username) {
            window.location.href = 'login';
        }

        const API_BASE = 'api/forums/forums-api.php';

        // ── State ──
        let currentPostId = null;
        let currentPost = null;

        // ── UI references ──
        const postListView = document.getElementById('postListView');
        const postDetailView = document.getElementById('postDetailView');
        const backBtn = document.getElementById('backBtn');

        // ── Load posts from JSON files via API ──
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

        // ── Open a post detail view ──
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

                // Fill detail
                document.getElementById('detailTitle').textContent = data.post.title;
                document.getElementById('detailAuthor').textContent = data.post.author;
                document.getElementById('detailDate').textContent = formatDate(data.post.timestamp);
                document.getElementById('detailBody').textContent = data.post.content;

                // Render comments
                renderComments(data.comments);

                // Show detail, hide list
                postListView.style.display = 'none';
                postDetailView.style.display = 'block';
                backBtn.style.display = 'inline-block';

                // Clear comment input
                document.getElementById('commentInput').value = '';
                document.getElementById('commentStatus').className = 'comment-status';
                document.getElementById('commentStatus').textContent = '';

            } catch (err) {
                console.error('openPost error:', err);
                alert('Network error loading post.');
            }
        }

        // ── Render comments ──
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

        // ── Post a comment ──
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
                    // Clear input and reload comments
                    input.value = '';
                    statusEl.className = 'comment-status success';
                    statusEl.textContent = 'Comment posted!';
                    setTimeout(() => {
                        statusEl.className = 'comment-status';
                        statusEl.textContent = '';
                    }, 2500);

                    // Reload post to get updated comments + count
                    const postRes = await fetch(API_BASE + '?action=get_post&post_id=' + encodeURIComponent(currentPostId));
                    const postData = await postRes.json();
                    if (postData.status === 'success') {
                        renderComments(postData.comments);
                    }

                    // Also refresh the post list so the comment count updates
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

        // ── Go back to post list ──
        function goBackToList() {
            currentPostId = null;
            currentPost = null;
            postDetailView.style.display = 'none';
            postListView.style.display = 'block';
            backBtn.style.display = 'none';
        }

        // ── Format date ──
        function formatDate(dateStr) {
            if (!dateStr) return '—';
            const d = new Date(dateStr);
            if (isNaN(d.getTime())) return dateStr;
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) +
                ' at ' + d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        }

        // ── Escape HTML ──
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // ── Load profile from JSON ──
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

        // ── Back button ──
        backBtn.addEventListener('click', goBackToList);

        // ── Comment submit ──
        document.getElementById('commentSubmitBtn').addEventListener('click', postComment);

        // ── Comment input: Ctrl+Enter to submit ──
        document.getElementById('commentInput').addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                postComment();
            }
        });

        // ── Init ──
        loadProfile();
        loadPosts();
    </script>

</body>
</html>
