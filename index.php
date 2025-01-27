<?php
session_start();
include("config.php");
include 'error_modal.php';

// Check if the user is already logged in
if (isset($_SESSION['UID'])) {
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Study KPI</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
<body>
    <div class="index-header">
        <div class="hero-section">
            <div class="desc-box">
                <div class="brand-title">
                    <ion-icon name="school-outline"></ion-icon>
                    <h1>My Study KPI</h1>
                </div>
                <div class="tagline">
                    <h2>Your Smart Academic Companion</h2>
                    <p>Transform your academic journey with our intelligent study planner</p>
                </div>
                <div class="feature-highlights">
                    <div class="highlight-item">
                        <ion-icon name="trophy-outline"></ion-icon>
                        <span>Track Progress</span>
                    </div>
                    <div class="highlight-item">
                        <ion-icon name="calendar-outline"></ion-icon>
                        <span>Plan Activities</span>
                    </div>
                    <div class="highlight-item">
                        <ion-icon name="analytics-outline"></ion-icon>
                        <span>Monitor CGPA</span>
                    </div>
                </div>
                <div class="cta-buttons">
                    <a href="#loginRegisterSection" onclick="showLoginRegister()" class="login-register-button">
                        <ion-icon name="log-in-outline"></ion-icon>
                        <span>Get Started</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="login-register-section" id="loginRegisterSection">
        <div class="auth-container">
            <div class="auth-box">
                <div class="auth-wrapper">
                    <!-- Left Side - Decorative -->
                    <div class="auth-banner">
                        <div class="banner-content">
                            <ion-icon name="school-outline"></ion-icon>
                            <h2>Welcome to My Study KPI</h2>
                            <p>Track your academic journey with our smart tools</p>
                        </div>
                    </div>
                    
                    <!-- Right Side - Forms -->
                    <div class="auth-forms">
                        <div class="auth-nav">
                            <button class="auth-tab active" onclick="showLogin()">
                                <ion-icon name="log-in-outline"></ion-icon>
                                <span>Login</span>
                            </button>
                            <button class="auth-tab" onclick="showRegister()">
                                <ion-icon name="person-add-outline"></ion-icon>
                                <span>Register</span>
                            </button>
                        </div>
                        
                        <div class="login-form" id="loginForm">
                            <form action="login_action.php" method="post">
                                <div class="welcome-text">
                                    <h3>Welcome Back!</h3>
                                    <p>Let's continue your journey</p>
                                </div>
                                
                                <div>
                                    <div class="input-group login-input-group">
                                        <ion-icon name="person-outline"></ion-icon>
                                        <input type="text" class="login-input" placeholder="Username" name="userName" required>
                                    </div>
                                    <div class="input-group login-input-group">
                                        <ion-icon name="lock-closed-outline"></ion-icon>
                                        <input type="password" class="login-input" placeholder="Password" name="userPwd" required>
                                        <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                    </div>
                                    <button type="submit" class="auth-button">
                                        <div class="button-content">
                                            <span>Login</span>
                                            <ion-icon name="arrow-forward-outline"></ion-icon>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="register-form" id="registerForm" style="display: none;">
                            <form action="register_action.php" method="post">
                                <div class="welcome-text">
                                    <h3>Create Account</h3>
                                    <p>Begin your academic excellence</p>
                                </div>
                                
                                <div></div>
                                    <div class="input-group register-input-group">
                                        <ion-icon name="id-card-outline"></ion-icon>
                                        <input type="text" class="register-input" placeholder="Matric No" name="matricNo" required>
                                        <span class="input-highlight"></span>
                                    </div>
                                    <div class="input-group register-input-group">
                                        <ion-icon name="mail-outline"></ion-icon>
                                        <input type="email" class="register-input" placeholder="Email Address" name="userEmail" required>
                                        <span class="input-highlight"></span>
                                    </div>
                                    <div class="input-group register-input-group">
                                        <ion-icon name="lock-closed-outline"></ion-icon>
                                        <input type="password" class="register-input" placeholder="Password" name="userPwd" required>
                                        <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                    </div>
                                    <div class="input-group register-input-group">
                                        <ion-icon name="shield-outline"></ion-icon>
                                        <input type="password" class="register-input" placeholder="Confirm Password" name="confirmPwd" required>
                                        <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                            <ion-icon name="eye-outline"></ion-icon>
                                        </button>
                                    </div>
                                    <button type="submit" class="auth-button">
                                        <div class="button-content">
                                            <span>Create Account</span>
                                            <ion-icon name="arrow-forward-outline"></ion-icon>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>Copyright (c) 2023 - All Reserved to Fazli</p>
    </footer>
    </div>
    <script src = "script.js"></script> <!-- All JavaScript Function in script.js files -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
