<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <title>CloudSpacePH Access Portal</title>
    <style>
        /* ── Reset & Base ── */
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
            align-items: center;
            padding: 1rem;
            margin: 0;
        }

        .container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 16px 48px rgba(0, 20, 60, 0.45), 0 6px 16px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 440px;
            padding: 1.5rem 1.5rem 1.25rem;
            animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-header { text-align: center; margin-bottom: 1.1rem; }
        .brand-name { font-size: 1.5rem; font-weight: 800; color: #0b2559; letter-spacing: -0.02em; text-transform: uppercase; }
        .brand-sub { font-size: 0.75rem; color: #4a6a9f; font-weight: 500; margin-top: 0.05rem; }

        .tabs {
            display: flex; gap: 0.3rem; background: #eef3fc; border-radius: 40px;
            padding: 0.3rem; margin-bottom: 1.25rem; box-shadow: inset 0 1px 3px rgba(0,0,0,0.04);
        }
        .tab-btn {
            flex: 1; border: none; background: transparent; padding: 0.45rem 0.3rem;
            border-radius: 30px; font-size: 0.85rem; font-weight: 700; color: #3a5a8a;
            cursor: pointer; transition: all 0.2s ease; letter-spacing: 0.01em;
        }
        .tab-btn:hover { color: #0b2559; }
        .tab-btn.active { background: #ffffff; color: #1a3a8a; box-shadow: 0 2px 8px rgba(26,58,138,0.15); }
        .tab-btn:focus-visible { outline: 2px solid #1a3a8a; outline-offset: 1px; }

        .form-panel { display: none; animation: fadeSlide 0.25s ease both; }
        .form-panel.active { display: block; }
        @keyframes fadeSlide { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

        .form-group { margin-bottom: 0.85rem; }
        label { display: block; font-size: 0.78rem; font-weight: 700; color: #0b2559; margin-bottom: 0.25rem; letter-spacing: -0.01em; }
        input {
            width: 100%; height: 40px; padding: 0 0.9rem; border: 1.5px solid #d6e2f5;
            border-radius: 10px; font-size: 0.88rem; color: #0b1a3a; background: #f8faff;
            transition: all 0.2s ease; outline: none;
        }
        input:focus { border-color: #1a3a8a; background: #ffffff; box-shadow: 0 0 0 4px rgba(26,58,138,0.10); }
        input::placeholder { color: #9bb0d0; font-weight: 400; font-size: 0.82rem; }

        .strength-container { margin-top: 0.35rem; }
        .strength-bar-wrapper { height: 5px; background: #e2eaf9; border-radius: 4px; overflow: hidden; margin-bottom: 0.2rem; }
        .strength-bar { height: 100%; width: 0%; background: #d32f2f; transition: width 0.25s ease, background 0.25s ease; border-radius: 4px; }
        .strength-label { font-size: 0.65rem; font-weight: 700; color: #4a6a9f; text-transform: uppercase; letter-spacing: 0.02em; }
        .strength-label span { font-weight: 800; }

        .checklist {
            list-style: none; padding: 0.5rem 0.9rem; margin-bottom: 1rem; background: #f4f8ff;
            border-radius: 10px; border: 1.5px solid #dce6f5; display: grid;
            grid-template-columns: 1fr 1fr; gap: 0.15rem 0.6rem;
        }
        .checklist-item { font-size: 0.72rem; color: #4a6a9f; display: flex; align-items: center; font-weight: 500; transition: color 0.2s ease; padding: 0.1rem 0; }
        .checklist-item::before { content: "✕"; color: #d32f2f; font-weight: 700; margin-right: 0.4rem; display: inline-block; width: 14px; font-size: 0.8rem; flex-shrink: 0; }
        .checklist-item.valid { color: #0f7b3a; }
        .checklist-item.valid::before { content: "✓"; color: #0f7b3a; }

        .submit-btn {
            width: 100%; height: 42px; background: #1a3a8a; color: #fff; border: none;
            border-radius: 12px; font-size: 0.95rem; font-weight: 700; cursor: pointer;
            transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(26,58,138,0.25);
            letter-spacing: 0.02em;
        }
        .submit-btn:hover:not(:disabled) { background: #0f2a6a; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(26,58,138,0.30); }
        .submit-btn:active:not(:disabled) { transform: translateY(0px); }
        .submit-btn:disabled { background: #b0c8e8; color: #ffffffcc; cursor: not-allowed; box-shadow: none; transform: none; }

        .message { margin-top: 0.75rem; font-size: 0.8rem; text-align: center; min-height: 22px; font-weight: 600; padding: 0.35rem 0.8rem; border-radius: 10px; transition: all 0.25s ease; }
        .message.success { background: #e4f6ed; color: #0f7b3a; }
        .message.error { background: #fde8e8; color: #b71c1c; }

        .footer-link { text-align: center; margin-top: 1rem; font-size: 0.78rem; color: #4a6a9f; }
        .footer-link a { color: #1a3a8a; text-decoration: none; font-weight: 700; }
        .footer-link a:hover { text-decoration: underline; }

        @media (max-width: 480px) {
            .container { padding: 1.1rem 1rem 0.9rem; max-width: 100%; border-radius: 16px; }
            .brand-name { font-size: 1.3rem; }
            .brand-sub { font-size: 0.7rem; }
            .tab-btn { font-size: 0.78rem; padding: 0.35rem 0.2rem; }
            input { height: 36px; font-size: 0.82rem; padding: 0 0.7rem; }
            .submit-btn { height: 38px; font-size: 0.85rem; }
            .checklist { grid-template-columns: 1fr 1fr; padding: 0.4rem 0.7rem; gap: 0.1rem 0.4rem; }
            .checklist-item { font-size: 0.67rem; }
        }
        @media (max-width: 380px) {
            .checklist { grid-template-columns: 1fr; }
            .brand-name { font-size: 1.1rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="brand-header">
            <div class="brand-name">CloudSpacePH</div>
            <div class="brand-sub">Secure Cloud Access</div>
        </div>

        <div class="tabs" role="tablist">
            <button class="tab-btn active" data-tab="login" role="tab" aria-selected="true">Sign In</button>
            <button class="tab-btn" data-tab="register" role="tab" aria-selected="false">Create Account</button>
        </div>

        <!-- LOGIN -->
        <div id="panel-login" class="form-panel active" role="tabpanel">
            <form id="loginForm" autocomplete="on">
                <div class="form-group">
                    <label for="loginUsername">Username</label>
                    <input type="text" id="loginUsername" placeholder="Enter your username" required autocomplete="username" />
                </div>
                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <input type="password" id="loginPassword" placeholder="Enter your password" required autocomplete="current-password" />
                </div>
                <button type="submit" class="submit-btn">Sign In</button>
            </form>
            <div id="loginMessage" class="message"></div>
            <div class="footer-link">
                Don't have an account? <a href="#" id="switchToRegister">Register here</a>
            </div>
        </div>

        <!-- REGISTER -->
        <div id="panel-register" class="form-panel" role="tabpanel">
            <form id="registerForm" autocomplete="on">
                <div class="form-group">
                    <label for="regUsername">Username</label>
                    <input type="text" id="regUsername" placeholder="Choose a username" required autocomplete="username" />
                </div>
                <div class="form-group">
                    <label for="regEmail">Email</label>
                    <input type="email" id="regEmail" placeholder="you@company.com" required autocomplete="email" />
                </div>
                <div class="form-group">
                    <label for="regPassword">Password</label>
                    <input type="password" id="regPassword" placeholder="Create a strong password" required autocomplete="new-password" />
                    <div class="strength-container">
                        <div class="strength-bar-wrapper">
                            <div id="strengthBar" class="strength-bar"></div>
                        </div>
                        <div class="strength-label">Security: <span id="strengthText">Empty</span></div>
                    </div>
                </div>
                <ul class="checklist">
                    <li id="reqLength" class="checklist-item">≥ 8 chars</li>
                    <li id="reqUpper" class="checklist-item">Uppercase</li>
                    <li id="reqNumber" class="checklist-item">Number</li>
                    <li id="reqSymbol" class="checklist-item">Special char</li>
                </ul>
                <button type="submit" class="submit-btn" id="regSubmitBtn" disabled>Register Node</button>
            </form>
            <div id="registerMessage" class="message"></div>
            <div class="footer-link">
                Already registered? <a href="#" id="switchToLogin">Sign in here</a>
            </div>
        </div>
    </div>

    <script>
        // ── TAB SWITCHING ──
        const tabs = document.querySelectorAll('.tab-btn');
        const panels = {
            login: document.getElementById('panel-login'),
            register: document.getElementById('panel-register'),
        };

        function switchTab(tabId) {
            tabs.forEach((btn) => {
                const isActive = btn.dataset.tab === tabId;
                btn.classList.toggle('active', isActive);
                btn.setAttribute('aria-selected', isActive);
            });
            Object.entries(panels).forEach(([id, panel]) => {
                panel.classList.toggle('active', id === tabId);
            });
            document.getElementById('loginMessage').className = 'message';
            document.getElementById('loginMessage').textContent = '';
            document.getElementById('registerMessage').className = 'message';
            document.getElementById('registerMessage').textContent = '';
        }

        tabs.forEach((btn) => {
            btn.addEventListener('click', () => switchTab(btn.dataset.tab));
        });
        document.getElementById('switchToRegister').addEventListener('click', (e) => { e.preventDefault(); switchTab('register'); });
        document.getElementById('switchToLogin').addEventListener('click', (e) => { e.preventDefault(); switchTab('login'); });

        // ── PASSWORD STRENGTH ──
        const regPassword = document.getElementById('regPassword');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        const regSubmitBtn = document.getElementById('regSubmitBtn');
        const reqLength = document.getElementById('reqLength');
        const reqUpper = document.getElementById('reqUpper');
        const reqNumber = document.getElementById('reqNumber');
        const reqSymbol = document.getElementById('reqSymbol');

        const strengthColors = ['#d32f2f', '#d32f2f', '#f5a623', '#2b7be4', '#0f7b3a'];
        const strengthLabels = ['Empty', 'Weak', 'Fair', 'Good', 'Strong'];

        function evaluatePassword(val) {
            const checks = {
                length: val.length >= 8,
                upper: /[A-Z]/.test(val),
                number: /[0-9]/.test(val),
                symbol: /[^A-Za-z0-9]/.test(val),
            };
            reqLength.classList.toggle('valid', checks.length);
            reqUpper.classList.toggle('valid', checks.upper);
            reqNumber.classList.toggle('valid', checks.number);
            reqSymbol.classList.toggle('valid', checks.symbol);

            const score = Object.values(checks).filter(Boolean).length;
            const pct = val.length === 0 ? 0 : (score / 4) * 100;
            strengthBar.style.width = pct + '%';
            strengthBar.style.backgroundColor = val.length === 0 ? '#e2eaf9' : strengthColors[score];
            strengthText.textContent = val.length === 0 ? 'Empty' : strengthLabels[score];
            strengthText.style.color = val.length === 0 ? '#4a6a9f' : strengthColors[score];
            regSubmitBtn.disabled = score !== 4;
            return checks;
        }
        regPassword.addEventListener('input', () => evaluatePassword(regPassword.value));

        // ── LOGIN SUBMIT → redirect to "/dashboard" ──
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const msg = document.getElementById('loginMessage');
            msg.className = 'message';
            msg.textContent = '';

            const username = document.getElementById('loginUsername').value.trim();
            const password = document.getElementById('loginPassword').value;

            try {
                const res = await fetch('auth-api', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'login', username, password }),
                });
                const data = await res.json();

                if (data.status === 'success') {
                    localStorage.setItem('cloudspace_username', username);
                    window.location.href = 'dashboard';   // clean URL
                } else {
                    msg.classList.add('error');
                    msg.textContent = data.message || 'Login failed.';
                }
            } catch (_) {
                msg.classList.add('error');
                msg.textContent = 'Network error. Please try again.';
            }
        });

        // ── REGISTER SUBMIT ──
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const msg = document.getElementById('registerMessage');
            msg.className = 'message';
            msg.textContent = '';

            const username = document.getElementById('regUsername').value.trim();
            const email = document.getElementById('regEmail').value.trim();
            const password = regPassword.value;

            const checks = evaluatePassword(password);
            if (Object.values(checks).filter(Boolean).length !== 4) {
                msg.classList.add('error');
                msg.textContent = 'Please meet all password requirements.';
                return;
            }

            try {
                const res = await fetch('auth-api', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'register', username, email, password }),
                });
                const data = await res.json();

                if (data.status === 'success') {
                    msg.classList.add('success');
                    msg.textContent = data.message || 'Registration successful! You can now sign in.';
                    document.getElementById('regUsername').value = '';
                    document.getElementById('regEmail').value = '';
                    regPassword.value = '';
                    strengthBar.style.width = '0%';
                    strengthText.textContent = 'Empty';
                    strengthText.style.color = '#4a6a9f';
                    regSubmitBtn.disabled = true;
                    document.querySelectorAll('.checklist-item').forEach((el) => el.classList.remove('valid'));
                } else {
                    msg.classList.add('error');
                    msg.textContent = data.message || 'Registration failed.';
                }
            } catch (_) {
                msg.classList.add('error');
                msg.textContent = 'Network error. Please try again.';
            }
        });

        // ── INIT ──
        regSubmitBtn.disabled = true;
        // If already logged in, go to dashboard
        if (localStorage.getItem('cloudspace_username')) {
            window.location.href = 'dashboard';
        }
    </script>
</body>
</html>