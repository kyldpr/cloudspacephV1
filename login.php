<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <title>CloudSpacePH • Access Portal</title>
    <!-- Google Font: Open Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet" />
    <style>
        /* ── CSS Variables for Theming ── */
        :root {
            /* Background */
            --bg-gradient: linear-gradient(135deg, #0a0a2e 0%, #0b0b1a 100%);

            /* Stars */
            --star-color: #fff;
            --star-glow: 0 0 8px rgba(255, 255, 255, 0.9);

            /* Planet 1 (Bottom-left) */
            --planet-1-bg: radial-gradient(circle at 30% 30%, #f4c2c2, #d4a0a0);
            --planet-1-shadow: 0 0 80px rgba(244, 194, 194, 0.8);
            --planet-1-border: rgba(255, 200, 200, 0.5);
            --ring-1-border: rgba(255, 200, 200, 0.4);
            --ring-1-bg: rgba(255, 200, 200, 0.1);

            /* Planet 2 (Top-right) */
            --planet-2-bg: radial-gradient(circle at 30% 30%, #b2c9ab, #8a9a7a);
            --planet-2-shadow: 0 0 70px rgba(178, 201, 171, 0.7);
            --planet-2-border: rgba(178, 201, 171, 0.4);
            --ring-2-border: rgba(178, 201, 171, 0.3);
            --ring-2-bg: rgba(178, 201, 171, 0.1);

            /* Planet 4 (Bottom-right) - kept */
            --planet-4-bg: radial-gradient(circle at 30% 30%, #f5a623, #d4a0a0);
            --planet-4-shadow: 0 0 75px rgba(245, 166, 35, 0.7);
            --planet-4-border: rgba(245, 166, 35, 0.4);
            --ring-4-border: rgba(245, 166, 35, 0.3);
            --ring-4-bg: rgba(245, 166, 35, 0.1);

            /* Card */
            --card-bg: #fcf8f0;
            --card-border: #1a2a5e;
            --card-shadow: 6px 12px 0 #b2c9ab, 8px 20px 40px rgba(0, 0, 0, 0.6);

            /* Animation speed */
            --float-duration: 8s;
            --twinkle-duration: 2.5s;
        }

        /* ── Light Theme Overrides ── */
        body.light-theme {
            --bg-gradient: linear-gradient(135deg, #e0d6c8 0%, #f5ede4 100%);

            --star-color: #a0a0a0;
            --star-glow: 0 0 4px rgba(0, 0, 0, 0.1);

            --planet-1-bg: radial-gradient(circle at 30% 30%, #d4c4b0, #c0b0a0);
            --planet-1-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            --planet-1-border: rgba(180, 160, 140, 0.3);
            --ring-1-border: rgba(180, 160, 140, 0.2);
            --ring-1-bg: rgba(180, 160, 140, 0.05);

            --planet-2-bg: radial-gradient(circle at 30% 30%, #a0b8b0, #809090);
            --planet-2-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            --planet-2-border: rgba(160, 180, 170, 0.3);
            --ring-2-border: rgba(160, 180, 170, 0.2);
            --ring-2-bg: rgba(160, 180, 170, 0.05);

            --planet-4-bg: radial-gradient(circle at 30% 30%, #d4c0a0, #b0a080);
            --planet-4-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            --planet-4-border: rgba(180, 160, 140, 0.3);
            --ring-4-border: rgba(180, 160, 140, 0.2);
            --ring-4-bg: rgba(180, 160, 140, 0.05);

            --card-bg: #fff9f0;
            --card-border: #b2c9ab;
            --card-shadow: 6px 12px 0 #dbb594, 8px 20px 40px rgba(0, 0, 0, 0.1);

            /* Hide some stars and planets in light mode */
            --star-hide: none;
            --planet-3-opacity: 0;
            --planet-5-opacity: 0;
        }

        /* ── Reset & Base ── */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            margin: 0;
            position: relative;
            overflow: hidden;
            transition: background 0.5s ease;
        }

        /* ── Theme Toggle Button ── */
        .theme-toggle {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 10;
        }

        .toggle-btn {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 2px solid rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            width: 48px;
            height: 48px;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        body.light-theme .toggle-btn {
            background: rgba(0, 0, 0, 0.08);
            border-color: rgba(0, 0, 0, 0.15);
            color: #1a2a5e;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .toggle-btn:hover {
            transform: scale(1.1);
        }

        /* ── Background Elements Container ── */
        .bg-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        /* ── Stars (optimized: 8 stars with GPU-friendly properties) ── */
        .star {
            position: absolute;
            border-radius: 50%;
            background: var(--star-color);
            box-shadow: var(--star-glow);
            animation: twinkle var(--twinkle-duration) ease-in-out infinite alternate;
            transition: background 0.5s, box-shadow 0.5s;
            will-change: opacity, transform;
        }
        .star:nth-child(1) { top: 10%; left: 5%; width: 6px; height: 6px; animation-duration: 2.2s; animation-delay: 0s; }
        .star:nth-child(2) { top: 20%; left: 85%; width: 3px; height: 3px; animation-duration: 2.8s; animation-delay: 0.6s; }
        .star:nth-child(3) { top: 40%; left: 10%; width: 5px; height: 5px; animation-duration: 1.8s; animation-delay: 1.2s; }
        .star:nth-child(4) { top: 70%; left: 90%; width: 4px; height: 4px; animation-duration: 3s; animation-delay: 0.3s; }
        .star:nth-child(5) { top: 85%; left: 20%; width: 3px; height: 3px; animation-duration: 2.4s; animation-delay: 0.9s; }
        .star:nth-child(6) { top: 15%; left: 50%; width: 5px; height: 5px; animation-duration: 2s; animation-delay: 1.5s; }
        .star:nth-child(7) { top: 60%; left: 30%; width: 4px; height: 4px; animation-duration: 2.6s; animation-delay: 0.2s; }
        .star:nth-child(8) { top: 45%; left: 70%; width: 6px; height: 6px; animation-duration: 2.0s; animation-delay: 0.8s; }

        /* Hide extra stars in light mode (nth-child beyond 8 are hidden) */
        body.light-theme .star:nth-child(n+7) {
            display: none;
        }

        @keyframes twinkle {
            0% { opacity: 0.3; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1.1); }
        }

        /* ── Planets (reduced to 3: planet-1, planet-2, planet-4) ── */
        .planet {
            position: absolute;
            border-radius: 50%;
            animation: float var(--float-duration) ease-in-out infinite alternate;
            transition: background 0.5s, box-shadow 0.5s, opacity 0.8s, border-color 0.5s;
            will-change: transform;
        }
        .planet::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-20deg);
            border-radius: 50%;
            border: 2px solid var(--planet-1-border);
            transition: border-color 0.5s;
        }
        .planet.ring::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 160%;
            height: 12px;
            border-radius: 50%;
            transform: translate(-50%, -50%) rotate(-25deg);
            border: 2px solid var(--ring-1-border);
            background: var(--ring-1-bg);
            box-shadow: 0 0 30px rgba(255,255,255,0.05);
            transition: border-color 0.5s, background 0.5s;
        }

        /* Planet 1 */
        .planet-1 {
            width: 120px;
            height: 120px;
            bottom: 5%;
            left: 3%;
            background: var(--planet-1-bg);
            box-shadow: var(--planet-1-shadow);
            animation-duration: 10s;
        }
        .planet-1::before {
            width: 130%;
            height: 14px;
            border-color: var(--planet-1-border);
        }
        .planet-1.ring::after {
            border-color: var(--ring-1-border);
            background: var(--ring-1-bg);
        }

        /* Planet 2 */
        .planet-2 {
            width: 80px;
            height: 80px;
            top: 8%;
            right: 5%;
            background: var(--planet-2-bg);
            box-shadow: var(--planet-2-shadow);
            animation-duration: 11s;
            animation-delay: 0.5s;
        }
        .planet-2::before {
            width: 140%;
            height: 10px;
            border-color: var(--planet-2-border);
        }
        .planet-2.ring::after {
            border-color: var(--ring-2-border);
            background: var(--ring-2-bg);
        }

        /* Planet 4 (Bottom-right) */
        .planet-4 {
            width: 90px;
            height: 90px;
            bottom: 10%;
            right: 8%;
            background: var(--planet-4-bg);
            box-shadow: var(--planet-4-shadow);
            animation-duration: 12s;
            animation-delay: 0.3s;
        }
        .planet-4::before {
            width: 150%;
            height: 12px;
            border-color: var(--planet-4-border);
        }
        .planet-4.ring::after {
            border-color: var(--ring-4-border);
            background: var(--ring-4-bg);
        }

        /* Removed planet-3 and planet-5 for performance */

        /* Floating animation - slower and smoother */
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            100% { transform: translateY(-15px) rotate(3deg); }
        }

        /* ── Card Container ── */
        .container {
            background: var(--card-bg);
            border-radius: 30px;
            box-shadow: var(--card-shadow);
            width: 100%;
            max-width: 440px;
            padding: 1.8rem 2rem 1.5rem;
            animation: fadeInUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
            border: 3px solid var(--card-border);
            position: relative;
            z-index: 1;
            transition: background 0.5s, border-color 0.5s, box-shadow 0.5s;
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
            text-shadow: 2px 2px 0 #f4c2c2, 0 0 20px rgba(255,255,255,0.1);
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

        /* ── Password Strength ── */
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
            .planet-1 { width: 80px; height: 80px; }
            .planet-2 { width: 60px; height: 60px; }
            .planet-4 { width: 70px; height: 70px; }
            .toggle-btn { width: 40px; height: 40px; font-size: 1.2rem; top: 1rem; right: 1rem; }
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

    <!-- ─── THEME TOGGLE ─── -->
    <div class="theme-toggle">
        <button class="toggle-btn" id="themeToggleBtn" aria-label="Toggle theme">🌙</button>
    </div>

    <!-- ─── Background Elements ─── -->
    <div class="bg-elements">
        <!-- Stars (8) -->
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>

        <!-- Planets (3) -->
        <div class="planet planet-1 ring"></div>
        <div class="planet planet-2 ring"></div>
        <div class="planet planet-4 ring"></div>
    </div>

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
        // 0. THEME TOGGLE with localStorage caching
        // ───────────────────────────────────────────────
        const toggleBtn = document.getElementById('themeToggleBtn');
        const STORAGE_KEY = 'cloudspace_theme';

        // Load saved theme
        const savedTheme = localStorage.getItem(STORAGE_KEY);
        if (savedTheme === 'light') {
            document.body.classList.add('light-theme');
            toggleBtn.textContent = '☀️';
        } else {
            // default dark
            toggleBtn.textContent = '🌙';
        }

        toggleBtn.addEventListener('click', () => {
            document.body.classList.toggle('light-theme');
            const isLight = document.body.classList.contains('light-theme');
            toggleBtn.textContent = isLight ? '☀️' : '🌙';
            localStorage.setItem(STORAGE_KEY, isLight ? 'light' : 'dark');
        });

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
                    localStorage.setItem('cloudspace_username', username);
                    if (data.token) {
                        localStorage.setItem('cloudspace_token', data.token);
                    }
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