<?php
// ================== ERROR REPORTING ==================
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ================== DB CONNECTION ==================
include('dbConnection.php');

// ================== REGISTER LOGIC ==================
if (isset($_POST['mSignup'])) {

    // Trim inputs
    $mName     = trim($_POST['mName']);
    $mEmail    = trim($_POST['mEmail']);
    $mPassword = trim($_POST['mPassword']);

    // Empty check
    if ($mName === "" || $mEmail === "" || $mPassword === "") {
        $regmsg = '<div class="alert alert-warning">All fields are required</div>';
    } else {

        // Check if email exists
        $checkStmt = $conn->prepare(
            "SELECT m_login_id FROM memberlogin_tb WHERE m_email = ?"
        );
        $checkStmt->bind_param("s", $mEmail);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $regmsg = '<div class="alert alert-warning">Email already registered</div>';
        } else {

            // Hash password
            $hashedPassword = password_hash($mPassword, PASSWORD_DEFAULT);

            // Insert user
            $insertStmt = $conn->prepare(
                "INSERT INTO memberlogin_tb (m_name, m_email, m_password, status)
                 VALUES (?, ?, ?, 'active')"
            );

            if (!$insertStmt) {
                die("Prepare failed: " . $conn->error);
            }

            $insertStmt->bind_param("sss", $mName, $mEmail, $hashedPassword);

            if ($insertStmt->execute()) {
                $regmsg = '<div class="alert alert-success">
                            Account successfully created
                          </div>';
            } else {
                die("Insert failed: " . $insertStmt->error);
            }

            $insertStmt->close();
        }

        $checkStmt->close();
    }
}
?>

<!DOCTYPE html> 
<html lang="en">
     <head>
         <meta charset="UTF-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge">
           <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
           <title>Sign Up - Fitness Point</title> 
           <link rel="stylesheet" href="css/globals.css"> 
           <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"> 
           <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> 
           </head> <body> <!-- Complete redesign with modern gradient background and animations --> 
            <div class="gym-gradient-bg"> <div class="auth-container"> <div class="auth-card">
                 <div class="auth-header"> <div class="brand-logo"> <div class="brand-icon"> 
                    <i class="fas fa-dumbbell"></i> </div> </div> <h1 class="auth-title">Fitness Point</h1> 
                    <p class="auth-subtitle">Create Your Account</p> </div> <form action="" method="POST" id="signupForm">
                         <?php if(isset($regmsg)) { echo $regmsg; } ?> <div class="form-group">
                             <label for="name" class="form-label">Full Name</label> 
                             <input type="text" class="form-input" id="name" name="mName" placeholder="Enter your full name" required>
                              <i class="fas fa-user input-icon"></i>
                               </div> <div class="form-group">
                                 <label for="email" class="form-label">Email Address</label>
                                  <input type="email" class="form-input" id="email" name="mEmail" placeholder="Enter your email" required>
                                   <i class="fas fa-envelope input-icon"></i> <small class="form-text">We'll never share your email with anyone else.</small>
                                    </div> <div class="form-group">
                                         <label for="password" class="form-label">Password</label>
                                          <input type="password" class="form-input" id="password" name="mPassword" placeholder="Create a strong password" required minlength="6">
                                           <i class="fas fa-lock input-icon"></i> 
                                           <small class="form-text">Password must be at least 6 characters long.</small> 
                                           </div> <button type="submit" class="btn-primary" name="mSignup" id="signupBtn">
                                             <span class="btn-text">Create Account</span> 
                                             </button> <div style="margin-top: 1rem; padding: 1rem; background: rgba(99, 102, 241, 0.1); border-radius: 0.5rem; border-left: 4px solid var(--secondary);"> 
                                                <small style="font-size: 0.75rem; color: var(--muted-foreground);">
                                                     <i class="fas fa-info-circle"></i> By clicking Create Account, you agree to our Terms, Data Policy and Cookie Policy. </small> 
                                                     </div> </form> <div class="auth-footer">
                                                         <p>Already have an account?
                                                             <a href="./member/memberLogin.php" class="auth-link">Sign in here</a></p>
                                                              <a href="index.php" class="btn-secondary"> <i class="fas fa-home"></i> Back to Home </a> 
                                                              </div> </div> </div> </div>
                                                               <!-- Added professional JavaScript for form interactions and validation --> 
                                                                <script> document.addEventListener('DOMContentLoaded', function() { const form = document.getElementById('signupForm'); const signupBtn = document.getElementById('signupBtn'); const inputs = document.querySelectorAll('.form-input'); const passwordInput = document.getElementById('password'); // Password strength indicator passwordInput.addEventListener('input', function() { const password = this.value; const strength = getPasswordStrength(password); updatePasswordStrength(strength); }); function getPasswordStrength(password) { let strength = 0; if (password.length >= 6) strength++; if (password.match(/[a-z]/)) strength++; if (password.match(/[A-Z]/)) strength++; if (password.match(/[0-9]/)) strength++; if (password.match(/[^a-zA-Z0-9]/)) strength++; return strength; } function updatePasswordStrength(strength) { const colors = ['#ef4444', '#f97316', '#eab308', '#22c55e', '#16a34a']; const texts = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong']; let indicator = document.querySelector('.password-strength'); if (!indicator) { indicator = document.createElement('div'); indicator.className = 'password-strength'; indicator.style.cssText = margin-top: 0.5rem; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600; transition: all 0.3s ease; ; passwordInput.parentElement.appendChild(indicator); } if (passwordInput.value.length > 0) { indicator.style.backgroundColor = colors[strength - 1] + '20'; indicator.style.color = colors[strength - 1]; indicator.textContent = Password Strength: ${texts[strength - 1]}; indicator.style.display = 'block'; } else { indicator.style.display = 'none'; } } // Add loading state to button form.addEventListener('submit', function() { signupBtn.innerHTML = '<span class="loading-spinner"></span>Creating Account...'; signupBtn.disabled = true; }); // Enhanced input animations inputs.forEach(input => { input.addEventListener('focus', function() { this.parentElement.classList.add('focused'); }); input.addEventListener('blur', function() { if (!this.value) { this.parentElement.classList.remove('focused'); } }); // Real-time validation input.addEventListener('input', function() { validateField(this); }); }); function validateField(field) { const value = field.value.trim(); let isValid = true; let message = ''; switch(field.type) { case 'email': const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; isValid = emailRegex.test(value); message = isValid ? '' : 'Please enter a valid email address'; break; case 'password': isValid = value.length >= 6; message = isValid ? '' : 'Password must be at least 6 characters'; break; default: isValid = value.length > 0; message = isValid ? '' : 'This field is required'; } updateFieldValidation(field, isValid, message); } function updateFieldValidation(field, isValid, message) { let errorMsg = field.parentElement.querySelector('.field-error'); if (!isValid && message) { if (!errorMsg) { errorMsg = document.createElement('small'); errorMsg.className = 'field-error'; errorMsg.style.cssText = 'color: var(--destructive); font-size: 0.75rem; margin-top: 0.25rem; display: block;'; field.parentElement.appendChild(errorMsg); } errorMsg.textContent = message; field.style.borderColor = 'var(--destructive)'; } else { if (errorMsg) { errorMsg.remove(); } field.style.borderColor = isValid && field.value ? 'var(--primary)' : 'var(--border)'; } } // Auto-hide alerts after 5 seconds const alerts = document.querySelectorAll('.alert'); alerts.forEach(alert => { setTimeout(() => { alert.style.opacity = '0'; alert.style.transform = 'translateY(-10px)'; setTimeout(() => alert.remove(), 300); }, 5000); }); }); 
</script> </body> </html>