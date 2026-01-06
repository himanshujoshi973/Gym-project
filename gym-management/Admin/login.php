<?php
include('../dbConnection.php');
session_start();
if(!isset($_SESSION['is_adminlogin'])){
    if(isset($_REQUEST['aEmail'])){
        $aEmail = mysqli_real_escape_string($conn,trim($_REQUEST['aEmail']));
        $aPassword = mysqli_real_escape_string($conn,trim($_REQUEST['aPassword']));
        $sql = "SELECT a_email, a_password FROM adminlogin_tb WHERE a_email='".$aEmail."' AND a_password='".$aPassword."' limit 1";
        $result = $conn->query($sql);
        if($result->num_rows == 1){
            $_SESSION['is_adminlogin'] = true;
            $_SESSION['aEmail'] = $aEmail;
            // Redirecting to RequesterProfile page on Correct Email and Pass
            echo "<script> location.href='dashboard.php'; </script>";
            exit;
        } else {
            $msg = '<div class="alert alert-warning" role="alert"> Enter Valid Email and Password </div>';
        }
    }
} else {
    echo "<script> location.href='dashboard.php'; </script>";
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
    
    <title>Admin Login - Fitness Point</title>
    
    <style>
        /* Admin-specific styling with different color scheme */
        .admin-gradient-bg {
            background: linear-gradient(135deg, #dc2626 0%, #7c2d12 100%);
            min-height: 100vh;
            position: relative;
        }
        
        .admin-gradient-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('/placeholder.svg?height=1080&width=1920') center/cover;
            opacity: 0.1;
            z-index: 0;
        }
        
        .admin-brand-icon {
            background: linear-gradient(135deg, #dc2626, #7c2d12);
        }
        
        .admin-title {
            background: linear-gradient(135deg, #dc2626, #7c2d12);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .admin-btn-primary {
            background: linear-gradient(135deg, #dc2626, #7c2d12);
        }
        
        .admin-btn-primary:hover {
            box-shadow: 0 10px 25px rgba(220, 38, 38, 0.3);
        }
    </style>
</head>

<body>
    <!-- Complete redesign with admin-specific styling -->
    <div class="admin-gradient-bg">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="brand-logo">
                        <div class="brand-icon admin-brand-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                    <h1 class="auth-title admin-title">Fitness Point</h1>
                    <p class="auth-subtitle">Admin Control Panel</p>
                </div>

                <form action="" method="POST" id="adminLoginForm">
                    <?php if(isset($msg)) { echo $msg; } ?>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Admin Email</label>
                        <input type="email" class="form-input" id="email" name="aEmail" placeholder="Enter admin email" required>
                        <i class="fas fa-user-tie input-icon"></i>
                        <small class="form-text">Authorized personnel only.</small>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Admin Password</label>
                        <input type="password" class="form-input" id="password" name="aPassword" placeholder="Enter admin password" required>
                        <i class="fas fa-shield-alt input-icon"></i>
                    </div>

                    <button type="submit" class="btn-primary admin-btn-primary" id="adminLoginBtn">
                        <span class="btn-text">Access Dashboard</span>
                    </button>
                </form>

                <div class="auth-footer">
                    <div style="background: rgba(220, 38, 38, 0.1); padding: 1rem; border-radius: 0.5rem; border-left: 4px solid #dc2626; margin-bottom: 1rem;">
                        <small style="color: #dc2626; font-weight: 600;">
                            <i class="fas fa-exclamation-triangle"></i> Restricted Access - Admin Only
                        </small>
                    </div>
                    <a href="../index.php" class="btn-secondary">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Added professional JavaScript for admin form -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('adminLoginForm');
            const loginBtn = document.getElementById('adminLoginBtn');
            const inputs = document.querySelectorAll('.form-input');

            // Add loading state to button
            form.addEventListener('submit', function() {
                loginBtn.innerHTML = '<span class="loading-spinner"></span>Authenticating...';
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

            // Security warning for admin access
            console.warn('🔒 Admin Access Detected - Unauthorized access is prohibited');
        });
    </script>
</body>
</html>
