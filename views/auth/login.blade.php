<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Clinic Appointment System</title>
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <style>
        :root {
            /* Application Theming - Aligned with style.css */
            --primary: #2c5282;
            --primary-light: #4299e1;
            --bg-medical: #eef2f7;
            --bg-white: #ffffff;
            --text-charcoal: #2d3748;
            --text-slate: #718096;
            --border: #e2e8f0;
            --radius: 6px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, system-ui, sans-serif;
            background-color: var(--bg-medical);
            color: var(--text-charcoal);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-container {
            background: var(--bg-white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
            border: 1px solid var(--border);
        }

        .login-header {
            background-color: var(--primary);
            color: #ffffff;
            padding: 24px 20px;
            text-align: center;
        }

        .login-brand-icon {
            font-size: 2rem;
            margin-bottom: 12px;
            opacity: 0.9;
            display: block;
        }

        .login-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .login-header p {
            opacity: 0.85;
            font-size: 0.875rem;
        }

        .login-body {
            padding: 24px;
        }

        .alert {
            padding: 10px 14px;
            border-radius: var(--radius);
            margin-bottom: 16px;
            font-size: 0.8125rem;
            border-left: 4px solid #f56565;
            background: #fff5f5;
            color: #c53030;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: var(--text-charcoal);
            font-weight: 600;
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.9375rem;
            transition: all 0.2s ease;
            background: #f8f9fa;
            color: var(--text-charcoal);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.1);
        }

        .math-challenge-box {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--bg-medical);
            padding: 10px;
            border-radius: var(--radius);
            margin-bottom: 10px;
            border: 1px solid var(--border);
        }

        .math-question-text {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.1rem;
            min-width: 60px;
            text-align: center;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: var(--radius);
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 4px;
        }

        .btn-login:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }

        .login-footer {
            margin-top: 16px;
            text-align: center;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .login-footer p {
            color: var(--text-slate);
            font-size: 0.75rem;
        }

        .demo-box {
            background: #f8f9fa;
            padding: 12px;
            border-radius: var(--radius);
            margin-top: 16px;
            border: 1px solid var(--border);
        }

        .demo-box h4 {
            color: var(--primary);
            margin-bottom: 8px;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .demo-box p {
            color: var(--text-charcoal);
            font-size: 0.8125rem;
            margin: 4px 0;
        }

        .demo-box code {
            background: #ffffff;
            padding: 2px 6px;
            border-radius: 4px;
            color: var(--primary);
            font-weight: 600;
            border: 1px solid var(--border);
        }

        .error-message {
            color: #c53030;
            font-size: 0.75rem;
            margin-top: 4px;
            display: block;
            min-height: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-hospital login-brand-icon"></i>
            <h1>Clinic Management</h1>
            <p>Authorized access only</p>
        </div>
        
        <div class="login-body">
            <?php if ($flash): ?>
                <div class="alert">
                    <?php echo $flash['message']; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="loginForm">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-control"
                        placeholder="Enter your credentials"
                        required 
                        autofocus
                    >
                    <span class="error-message" id="username-error"></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control"
                        placeholder="••••••••"
                        required
                    >
                    <span class="error-message" id="password-error"></span>
                </div>

                <div class="form-group">
                    <label>Security Verification</label>
                    <div class="math-challenge-box">
                        <span class="math-question-text"><?php echo $math_question; ?> = </span>
                        <input 
                            type="number" 
                            id="math_answer"
                            name="math_answer" 
                            class="form-control"
                            placeholder="?"
                            required
                        >
                    </div>
                    <span class="error-message" id="math-error"></span>
                </div>

                <button type="submit" class="btn-login">Sign In</button>
            </form>

            <div class="demo-box">
                <h4>📝 Demo Credentials</h4>
                <p><strong>Admin:</strong> <code>admin</code> / <code>admin123</code></p>
                <p><strong>Staff:</strong> <code>staff</code> / <code>admin123</code></p>
            </div>

            <div class="login-footer">
                <p>&copy; 2026 Clinic Appointment System. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script src="<?php echo asset('js/form-validator.js'); ?>"></script>
    <script>
        // Initialize live form validation for login
        const loginValidator = new FormValidator('loginForm', {
            validateOnInput: true,
            validateOnBlur: true,
            showSuccessIcons: false, // Don't show success icons on login form
            debounceDelay: 300
        });

        // Add custom validators
        loginValidator.addValidator('username', (value) => {
            if (value.length < 3) {
                return 'Username must be at least 3 characters';
            }
            return true;
        });

        loginValidator.addValidator('password', (value) => {
            if (value.length < 6) {
                return 'Password must be at least 6 characters';
            }
            return true;
        });

        loginValidator.addValidator('math_answer', (value) => {
            if (!value) {
                return 'Please solve the problem';
            }
            return true;
        });

        // Add validation hints
        document.getElementById('username')?.setAttribute('title', 'Enter your username or email');
        document.getElementById('password')?.setAttribute('title', 'Enter your password');
        document.getElementById('math_answer')?.setAttribute('title', 'Solve the math problem to continue');
    </script>
</body>
</html>
