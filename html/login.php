<?php
// Login Page
// html/login.php

session_start();

if (isset($_SESSION['token'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ascend - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Ascend</h1>
                <p>Altar Server Management</p>
            </div>

            <form id="login-form" class="form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Login</button>

                <div id="error-message" class="error-message" style="display: none;"></div>
            </form>

            <div class="auth-footer">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>

    <script src="js/api-client.js"></script>
    <script>
        document.getElementById('login-form').addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('/api/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (data.success) {
                    // Store token and user in session
                    sessionStorage.setItem('token', data.token);
                    sessionStorage.setItem('user', JSON.stringify(data.user));
                    
                    window.location.href = 'dashboard.php';
                } else {
                    document.getElementById('error-message').textContent = data.error;
                    document.getElementById('error-message').style.display = 'block';
                }
            } catch (error) {
                document.getElementById('error-message').textContent = 'Login failed. Please try again.';
                document.getElementById('error-message').style.display = 'block';
            }
        });
    </script>
</body>
</html>
