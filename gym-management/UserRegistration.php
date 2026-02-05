<?php
// ================== ERROR REPORTING ==================
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ================== DB CONNECTION ==================
include('dbConnection.php');

$regmsg = ""; // Initialize message variable

// ================== REGISTER LOGIC ==================
// Changed to check if the request is POST to ensure logic runs even if button name is missing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Trim inputs
    $mName     = isset($_POST['mName']) ? trim($_POST['mName']) : "";
    $mEmail    = isset($_POST['mEmail']) ? trim($_POST['mEmail']) : "";
    $mPassword = isset($_POST['mPassword']) ? trim($_POST['mPassword']) : "";

    // --- SERVER SIDE VALIDATION LOGIC ---
    // Strict Email Pattern: Requires at least one digit
    $emailPattern = "/^(?=.*[0-9])[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

    if (empty($mName) || empty($mEmail) || empty($mPassword)) {
        $regmsg = '<div class="alert alert-warning">All fields are required.</div>';
    } 
    elseif (!preg_match($emailPattern, $mEmail)) {
        $regmsg = '<div class="alert alert-danger">Invalid email. Must contain at least one number.</div>';
    } 
    elseif (strlen($mPassword) < 6 || !preg_match("/[0-9]/", $mPassword) || !preg_match("/[a-zA-Z]/", $mPassword)) {
        $regmsg = '<div class="alert alert-danger">Password must be 6+ characters with letters and numbers.</div>';
    }
    elseif (!preg_match("/^[a-zA-Z ]*$/", $mName) || strlen($mName) < 2) {
        $regmsg = '<div class="alert alert-danger">Invalid name (letters only, min 2 chars).</div>';
    }
    else {
        // Check if email exists
        $checkStmt = $conn->prepare("SELECT m_login_id FROM memberlogin_tb WHERE m_email = ?");
        $checkStmt->bind_param("s", $mEmail);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $regmsg = '<div class="alert alert-warning">This email is already registered.</div>';
        } else {
            // Hash password
            $hashedPassword = password_hash($mPassword, PASSWORD_DEFAULT);

            // Insert user
            $insertStmt = $conn->prepare(
                "INSERT INTO memberlogin_tb (m_name, m_email, m_password, status) 
                 VALUES (?, ?, ?, 'active')"
            );

            if (!$insertStmt) {
                $regmsg = '<div class="alert alert-danger">Database error: ' . $conn->error . '</div>';
            } else {
                $insertStmt->bind_param("sss", $mName, $mEmail, $hashedPassword);
                if ($insertStmt->execute()) {
                    $regmsg = '<div class="alert alert-success">Account successfully created! <a href="./member/memberLogin.php">Login here</a></div>';
                } else {
                    $regmsg = '<div class="alert alert-danger">Registration failed. Please try again.</div>';
                }
                $insertStmt->close();
            }
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
</head> 
<body> 
    <div class="gym-gradient-bg"> 
        <div class="auth-container"> 
            <div class="auth-card">
                <div class="auth-header"> 
                    <div class="brand-logo"> 
                        <div class="brand-icon"> <i class="fas fa-dumbbell"></i> </div> 
                    </div> 
                    <h1 class="auth-title">Fitness Point</h1> 
                    <p class="auth-subtitle">Create Your Account</p> 
                </div> 

                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="signupForm" novalidate>
                    <div id="message-container">
                        <?php if(!empty($regmsg)) { echo $regmsg; } ?> 
                    </div>

                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label> 
                        <input type="text" class="form-input" id="name" name="mName" placeholder="Enter your full name" required value="<?php echo isset($mName) ? htmlspecialchars($mName) : ''; ?>">
                        <i class="fas fa-user input-icon"></i>
                    </div> 

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-input" id="email" name="mEmail" placeholder="e.g. user123@gmail.com" required value="<?php echo isset($mEmail) ? htmlspecialchars($mEmail) : ''; ?>">
                        <i class="fas fa-envelope input-icon"></i> 
                        <small class="form-text">Must include at least one number.</small>
                    </div> 

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-input" id="password" name="mPassword" placeholder="Min 6 chars, letters & numbers" required minlength="6">
                        <i class="fas fa-lock input-icon"></i> 
                    </div> 

                    <input type="hidden" name="mSignup" value="1">

                    <button type="submit" class="btn-primary" id="signupBtn">
                        <span class="btn-text">Create Account</span> 
                    </button> 

                    <div style="margin-top: 1rem; padding: 1rem; background: rgba(99, 102, 241, 0.1); border-radius: 0.5rem; border-left: 4px solid #6366f1;"> 
                        <small style="font-size: 0.75rem; color: #666;">
                            <i class="fas fa-info-circle"></i> By clicking Create Account, you agree to our Terms.
                        </small> 
                    </div> 
                </form> 

                <div class="auth-footer">
                    <p>Already have an account? <a href="./member/memberLogin.php" class="auth-link">Sign in here</a></p>
                    <a href="index.php" class="btn-secondary"> <i class="fas fa-home"></i> Back to Home </a> 
                </div> 
            </div> 
        </div> 
    </div>

    <script> 
    document.addEventListener('DOMContentLoaded', function() { 
        const form = document.getElementById('signupForm'); 
        const signupBtn = document.getElementById('signupBtn'); 
        const inputs = document.querySelectorAll('.form-input'); 

        // Real-time validation
        inputs.forEach(input => { 
            input.addEventListener('input', function() { validateField(this); }); 
            input.addEventListener('blur', function() { validateField(this); }); 
        }); 

        form.addEventListener('submit', function(e) { 
            let isFormValid = true;
            inputs.forEach(input => {
                if (!validateField(input)) {
                    isFormValid = false;
                }
            });

            if (!isFormValid) {
                e.preventDefault(); // Stop form if JS validation fails
            } else {
                // Change button text to show it's working
                const btnText = signupBtn.querySelector('.btn-text');
                if(btnText) btnText.textContent = 'Processing...';
                // Note: Do NOT disable the button here as it can prevent the POST data from sending in some browsers
            }
        }); 

        function validateField(field) { 
            const value = field.value.trim(); 
            let isValid = true; 
            let message = ''; 

            if (field.id === 'email') { 
                const emailRegex = /^(?=.*[0-9])[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; 
                isValid = emailRegex.test(value); 
                message = isValid ? '' : 'Email must contain a number (e.g. user1@gmail.com)'; 
            } else if (field.id === 'password') { 
                const hasLetter = /[a-zA-Z]/.test(value);
                const hasNumber = /[0-9]/.test(value);
                isValid = value.length >= 6 && hasLetter && hasNumber; 
                message = isValid ? '' : '6+ chars, must have letters and numbers'; 
            } else if (field.id === 'name') {
                const nameRegex = /^[a-zA-Z\s]*$/;
                isValid = value.length >= 2 && nameRegex.test(value);
                message = isValid ? '' : 'Name must be 2+ letters (no numbers)';
            }

            updateFieldValidation(field, isValid, message); 
            return isValid;
        } 

        function updateFieldValidation(field, isValid, message) { 
            let errorMsg = field.parentElement.querySelector('.field-error'); 
            if (!isValid) { 
                if (!errorMsg) { 
                    errorMsg = document.createElement('small'); 
                    errorMsg.className = 'field-error'; 
                    errorMsg.style.cssText = 'color: #ef4444; font-size: 0.75rem; display: block; margin-top: 5px;'; 
                    field.parentElement.appendChild(errorMsg); 
                } 
                errorMsg.textContent = message; 
                field.style.borderColor = '#ef4444'; 
            } else { 
                if (errorMsg) errorMsg.remove(); 
                field.style.borderColor = '#6366f1'; 
            } 
        } 

        // Auto-hide alert messages
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(a => a.style.display = 'none');
        }, 6000);
    }); 
    </script> 
</body> 
</html>