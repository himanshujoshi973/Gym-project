<?php
include('../dbConnection.php');
session_start();
if (!isset($_SESSION['is_login'])) {
    if (isset($_REQUEST['mEmail'])) {
        $mEmail = mysqli_real_escape_string($conn, trim($_REQUEST['mEmail']));
        $mPassword = trim($_REQUEST['mPassword']);

        // Get hashed password from DB
        $sql = "SELECT m_password FROM memberlogin_tb WHERE m_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $mEmail);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();

            if (password_verify($mPassword, $hashedPassword)) {
                $_SESSION['is_login'] = true;
                $_SESSION['mEmail'] = $mEmail;
                echo "<script> location.href='memberProfile.php'; </script>";
                exit;
            } else {
                $msg = '<div class="alert alert-warning" role="alert"> Invalid Password </div>';
            }
        } else {
            $msg = '<div class="alert alert-warning" role="alert"> Email not found </div>';
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- Updated to use professional styling -->
    <link rel="stylesheet" href="../css/globals.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <title>Member Login - Fitness Point</title>
</head>

<body>
    <!-- Complete redesign with modern gradient background and animations -->
    <div class="gym-gradient-bg">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="brand-logo">
                        <div class="brand-icon">
                            <i class="fas fa-dumbbell"></i>
                        </div>
                    </div>
                    <h1 class="auth-title">Fitness Point</h1>
                    <p class="auth-subtitle">Member Portal</p>
                </div>

                <form action="" method="POST" id="loginForm">
                    <?php if(isset($msg)) { echo $msg; } ?>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-input" id="email" name="mEmail" placeholder="Enter your email" required>
                        <i class="fas fa-envelope input-icon"></i>
                        <small class="form-text">We'll never share your email with anyone else.</small>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-input" id="password" name="mPassword" placeholder="Enter your password" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>

                    <button type="submit" class="btn-primary" id="loginBtn">
                        <span class="btn-text">Sign In</span>
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Don't have an account? <a href="../UserRegistration.php" class="auth-link">Sign up here</a></p>
                    <a href="../index.php" class="btn-secondary">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Added professional JavaScript for form interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const inputs = document.querySelectorAll('.form-input');

            // Add loading state to button
            form.addEventListener('submit', function() {
                loginBtn.innerHTML = '<span class="loading-spinner"></span>Signing In...';
                loginBtn.disabled = true;
            });

            // Enhanced input animations
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });

                // Auto-fill detection
                if (input.value) {
                    input.parentElement.classList.add('focused');
                }
            });

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>
