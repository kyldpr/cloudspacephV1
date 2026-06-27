<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <title>CloudSpacePH • Access Portal</title>
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
            background: #f5ede4; /* warm beige */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            margin: 0;
            position: relative;
        }

        /* ── Planet decoration (minimal) ── */
        .planet-bg {
            position: fixed;
            bottom: 5%;
            right: 5%;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #f4c2c2, #b2c9ab);
            box-shadow: 0 0 30px rgba(244, 194, 194, 0.2);
            z-index: 0;
            pointer-events: none;
        }
        .planet-bg::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 180%;
            height: 16px;
            background: rgba(178, 201, 171, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%) rotate(-20deg);
            border: 2px solid rgba(244, 194, 194, 0.4);
        }

        /* ── Card Container ── */
        .container {
            background: #fcf8f0; /* light beige */
            border-radius: 30px;
            box-shadow: 
                6px 12px 0 #b2c9ab,
                8px 20px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 440px;
            padding: 1.8rem 2rem 1.5rem;
            animation: fadeInUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
            border: 3px solid #1a2a5e; /* navy */
            position: relative;
            z-index: 1;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Brand Header ── */
        .brand-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .brand-name {
            font-size: 2rem;
            font-weight: 900;
            color: #1a2a5e;
            text-transform: uppercase;
            letter-spacing: -0.02em;
            text-shadow: 2px 2px 0 #f4c2c2, 4px 4px 0 rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .brand-name .icon-planet {
            display: inline-block;
            width: 32px;
            height: 32px;
            background: radial-gradient(circle at 30% 30%, #f4c2c2, #b2c9ab);
            border-radius: 50%;
            position: relative;
            flex-shrink: 0;
        }
        .brand-name .icon-planet::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 140%;
            height: 10px;
            background: rgba(178, 201, 171, 0.5);
            border-radius: 50%;
            transform: translate(-50%, -50%) rotate(-20deg);
            border: 1px solid rgba(244, 194, 194, 0.6);
        }

        .brand-sub {
            font-size: 0.8rem;
            color: #4a6a8a;
            font-weight: 600;
            margin-top: 0.1rem;
            letter-spacing: 0.02em;
        }

        /* ── Tabs ── */
        .tabs {
            display: flex;
            gap: 0.3rem;
            background: #f5ede4;
            border-radius: 60px;
            padding: 0.3rem;
            margin-bottom: 1.5rem;
            border: 2px solid #b2c9ab;
            box-shadow: 2px 3px 0 #dbb594;
        }

        .tab-btn {
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
            letter-spacing: 0.01em;
            font-family: inherit;
        }

        .tab-btn:hover {
            color: #0b2559;
        }

        .tab-btn.active {
            background: #b2c9ab;
            color: #0a0a2e;
            box-shadow: 2px 3px 0 #8a9a7a;
            transform: translateY(-1px);
        }

        .tab-btn:focus-visible {
            outline: 2px solid #1a2a5e;
            outline-offset: 1px;
        }

        /* ── Form Panels ── */
        .form-panel {
            display: none;
            animation: fadeSlide 0.25s ease both;
        }

        .form-panel.active {
            display: block;
        }

        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Form Elements ── */
        .form-group {
            margin-bottom: 0.9rem;
        }

        label {
            display: block;
            font-size: 0.8rem;
            font-weight: 800;
            color: #0a0a2e;
            margin-bottom: 0.25rem;
            letter-spacing: -0.01em;
        }

        input {
            width: 100%;
            height: 42px;
            padding: 0 1rem;
            border: 2px solid #b2c9ab;
            border-radius: 20px;
            font-size: 0.9rem;
            color: #0a0a2e;
            background: #fff9f0;
            transition: all 0.2s ease;
            outline: none;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02), 1px 2px 0 #dbb594;
            font-family: inherit;
        }

        input:focus {
            border-color: #f4c2c2;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(244, 194, 194, 0.2), 1px 2px 0 #dbb594;
        }

        input::placeholder {
            color: #9bb0d0;
            font-weight: 400;
            font-size: 0.85rem;
        }

        /* ── Password Strength (Register) ── */
        .strength-container {
            margin-top: 0.35rem;
        }

        .strength-bar-wrapper {
            height: 6px;
            background: #e8d5c4;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.2rem;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            background: #d32f2f;
            transition: width 0.25s ease, background 0.25s ease;
            border-radius: 4px;
        }

        .strength-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: #4a6a8a;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .strength-label span {
            font-weight: 800;
        }

        /* ── Checklist ── */
        .checklist {
            list-style: none;
            padding: 0.5rem 1rem;
            margin-bottom: 1rem;
            background: #fff9f0;
            border-radius: 20px;
            border: 2px solid #b2c9ab;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.15rem 0.6rem;
            box-shadow: 1px 2px 0 #dbb594;
        }

        .checklist-item {
            font-size: 0.75rem;
            color: #4a6a8a;
            display: flex;
            align-items: center;
            font-weight: 600;
            transition: color 0.2s ease;
            padding: 0.1rem 0;
        }

        .checklist-item::before {
            content: "✕";
            color: #d32f2f;
            font-weight: 700;
            margin-right: 0.4rem;
            display: inline-block;
            width: 16px;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .checklist-item.valid {
            color: #0f7b3a;
        }

        .checklist-item.valid::before {
            content: "✓";
            color: #0f7b3a;
        }

        /* ── Submit Button ── */
        .submit-btn {
            width: 100%;
            height: 46px;
            background: #b2c9ab;
            color: #0a0a2e;
            border: none;
            border-radius: 30px;
            font-size: 0.95rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 2px 4px 0 #8a9a7a;
            letter-spacing: 0.02em;
            font-family: inherit;
        }

        .submit-btn:hover:not(:disabled) {
            background: #9fb89a;
            transform: translateY(-2px);
            box-shadow: 2px 6px 0 #8a9a7a;
        }

        .submit-btn:active:not(:disabled) {
            transform: translateY(2px);
            box-shadow: 2px 2px 0 #8a9a7a;
        }

        .submit-btn:disabled {
            background: #d4d9c8;
            color: #6a7a5a;
            cursor: not-allowed;
            box-shadow: 2px 2px 0 #8a9a7a;
            transform: none;
        }

        /* ── Messages ── */
        .message {
            margin-top: 0.8rem;
            font-size: 0.85rem;
            text-align: center;
            min-height: 24px;
            font-weight: 700;
            padding: 0.4rem 1rem;
            border-radius: 30px;
            transition: all 0.25s ease;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            box-shadow: 1px 2px 0 #8a9a7a;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            box-shadow: 1px 2px 0 #dbb594;
        }

        /* ── Footer Link ── */
        .footer-link {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.8rem;
            color: #4a6a8a;
        }

        .footer-link a {
            color: #1a2a5e;
            text-decoration: none;
            font-weight: 800;
            transition: color 0.2s;
        }

        .footer-link a:hover {
            color: #0a0a2e;
            text-decoration: underline;
        }

        /* ── Responsive ── */
        @media (max-width: 480px) {
            .container {
                padding: 1.2rem 1rem 1rem;
                max-width: 100%;
                border-radius: 24px;
            }
            .brand-name {
                font-size: 1.6rem;
            }
            .brand-name .icon-planet {
                width: 24px;
                height: 24px;
            }
            .brand-sub {
                font-size: 0.7rem;
            }
            .tab-btn {
                font-size: 0.78rem;
                padding: 0.4rem 0.2rem;
            }
            input {
                height: 38px;
                font-size: 0.85rem;
                padding: 0 0.8rem;
            }
            .submit-btn {
                height: 42px;
                font-size: 0.85rem;
            }
            .checklist {
                grid-template-columns: 1fr 1fr;
                padding: 0.4rem 0.8rem;
                gap: 0.1rem 0.4rem;
            }
            .checklist-item {
                font-size: 0.7rem;
            }
            .planet-bg {
                width: 70px;
                height: 70px;
                bottom: 3%;
                right: 3%;
            }
            .planet-bg::before {
                height: 12px;
                width: 160%;
            }
        }

        @media (max-width: 380px) {
            .checklist {
                grid-template-columns: 1fr;
            }
            .brand-name {
                font-size: 1.3rem;
            }
            .container {
                padding: 0.9rem 0.7rem;
            }
        }
    </style>
</head>
<body>

    <!-- ─── Background Planet ─── -->
    <div class="planet-bg"></div>

    <div class="container">

        <!-- Brand -->
        <div class="brand-header">
            <div class="brand-name">
                <span class="icon-planet"></span>
                CloudSpacePH
            </div>
            <div class="brand-sub">Secure Cloud Access</div>
        </div>

        <!-- Tabs -->
        <div class="tabs" role="tablist">
            <button class="tab-btn active" data-tab="login" role="tab" aria-selected="true">Sign In</button>
            <button class="tab-btn" data-tab="register" role="tab" aria-selected="false">Create Account</button>
        </div>

        <!-- ============ LOGIN PANEL ============ -->
        <div id="panel-login" class="form-panel active" role="tabpanel">
            <form id="loginForm" autocomplete="on">
                <div class="form-group">
                    <label for="loginUsername">👤 Username</label>
                    <input type="text" id="loginUsername" placeholder="Enter your username" required autocomplete="username" />
                </div>
                <div class="form-group">
                    <label for="loginPassword">🔐 Password</label>
                    <input type="password" id="loginPassword" placeholder="Enter your password" required autocomplete="current-password" />
                </div>
                <button type="submit" class="submit-btn">🚀 Sign In</button>
            </form>
            <div id="loginMessage" class="message"></div>
            <div class="footer-link">
                Don't have an account? <a href="#" id="switchToRegister">Register here</a>
            </div>
        </div>

        <!-- ============ REGISTER PANEL ============ -->
        <div id="panel-register" class="form-panel" role="tabpanel">
            <form id="registerForm" autocomplete="on">
                <div class="form-group">
                    <label for="regUsername">👤 Username</label>
                    <input type="text" id="regUsername" placeholder="Choose a username" required autocomplete="username" />
                </div>
                <div class="form-group">
                    <label for="regEmail">📧 Email</label>
                    <input type="email" id="regEmail" placeholder="you@company.com" required autocomplete="email" />
                </div>
                <div class="form-group">
                    <label for="regPassword">🔑 Password</label>
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

                <button type="submit" class="submit-btn" id="regSubmitBtn" disabled>📝 Register Node</button>
            </form>
            <div id="registerMessage" class="message"></div>
            <div class="footer-link">
                Already registered? <a href="#" id="switchToLogin">Sign in here</a>
            </div>
        </div>

    </div>

    <script>
        // ───────────────────────────────────────────────
        // 1. TAB SWITCHING
        // ───────────────────────────────────────────────
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
            btn.addEventListener('click', () => {
                switchTab(btn.dataset.tab);
            });
        });

        document.getElementById('switchToRegister').addEventListener('click', (e) => {
            e.preventDefault();
            switchTab('register');
        });
        document.getElementById('switchToLogin').addEventListener('click', (e) => {
            e.preventDefault();
            switchTab('login');
        });

        // ───────────────────────────────────────────────
        // 2. PASSWORD STRENGTH (Register)
        // ───────────────────────────────────────────────
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
            strengthBar.style.backgroundColor = val.length === 0 ? '#e8d5c4' : strengthColors[score];
            strengthText.textContent = val.length === 0 ? 'Empty' : strengthLabels[score];
            strengthText.style.color = val.length === 0 ? '#4a6a8a' : strengthColors[score];

            regSubmitBtn.disabled = score !== 4;
            return checks;
        }

        regPassword.addEventListener('input', () => {
            evaluatePassword(regPassword.value);
        });

        // ───────────────────────────────────────────────
        // 3. LOGIN SUBMIT → store username & token → redirect
        // ───────────────────────────────────────────────
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const msg = document.getElementById('loginMessage');
            msg.className = 'message';
            msg.textContent = '';

            const username = document.getElementById('loginUsername').value.trim();
            const password = document.getElementById('loginPassword').value;

            const payload = {
                action: 'login',
                username: username,
                password: password,
            };

            try {
                const res = await fetch('auth-api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();

                if (data.status === 'success') {
                    // Store username and token
                    localStorage.setItem('cloudspace_username', username);
                    if (data.token) {
                        localStorage.setItem('cloudspace_token', data.token);
                    }
                    // Redirect to dashboard
                    window.location.href = 'dashboard.php';
                } else {
                    msg.classList.add('error');
                    msg.textContent = data.message || 'Login failed.';
                }
            } catch (_) {
                msg.classList.add('error');
                msg.textContent = 'Network error. Please try again.';
            }
        });

        // ───────────────────────────────────────────────
        // 4. REGISTER SUBMIT (no auto-login)
        // ───────────────────────────────────────────────
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

            const payload = {
                action: 'register',
                username,
                email,
                password,
            };

            try {
                const res = await fetch('auth-api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();

                if (data.status === 'success') {
                    msg.classList.add('success');
                    msg.textContent = data.message || 'Registration successful! You can now sign in.';

                    // Reset form
                    document.getElementById('regUsername').value = '';
                    document.getElementById('regEmail').value = '';
                    regPassword.value = '';
                    strengthBar.style.width = '0%';
                    strengthText.textContent = 'Empty';
                    strengthText.style.color = '#4a6a8a';
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

        // ───────────────────────────────────────────────
        // 5. INIT
        // ───────────────────────────────────────────────
        regSubmitBtn.disabled = true;

        // If already logged in, redirect to dashboard
        if (localStorage.getItem('cloudspace_username')) {
            window.location.href = 'dashboard.php';
        }
    </script>

</body>
</html>
