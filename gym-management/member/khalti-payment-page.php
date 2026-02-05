<?php
session_start();

if (!isset($_GET['pidx'])) {
    header("Location: SubmitBooking.php");
    exit();
}

$pidx = htmlspecialchars($_GET['pidx']);

include('../dbConnection.php');

$stmt = $conn->prepare("SELECT b.Booking_id, b.member_name, b.member_email, b.member_mobile, b.total_price, b.subscription_months 
                        FROM submitbookingt_tb b 
                        WHERE b.pidx = ?");
$stmt->bind_param("s", $pidx);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    echo "<script>alert('Invalid payment session'); window.location.href='SubmitBooking.php';</script>";
    exit();
}

$amount = number_format($booking['total_price'], 2);
$serviceCharge = number_format($booking['total_price'] * 0.03, 2); // 3% service charge
$totalPayable = number_format($booking['total_price'] * 1.03, 2);

// Calculate expiration time (30 minutes from now)
$expirationTime = date('M d, Y H:i', strtotime('+30 minutes'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khalti Payment</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            padding: 20px;
        }
        
        .payment-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 40px;
            align-items: flex-start;
        }
        
        .payment-details {
            flex: 1;
            background: white;
            padding: 32px;
            border-radius: 8px;
        }
        
        .payment-form-section {
            flex: 1;
            background: white;
            padding: 32px;
            border-radius: 8px;
        }
        
        h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 24px;
            color: #1a1a1a;
        }
        
        h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1a1a1a;
        }
        
        .expiration-notice {
            background: #fff9e6;
            border: 1px solid #ffe4a3;
            border-radius: 6px;
            padding: 14px 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #7a5c00;
        }
        
        .billed-to {
            margin-bottom: 32px;
        }
        
        .section-label {
            font-weight: 600;
            margin-bottom: 12px;
            color: #1a1a1a;
        }
        
        .customer-info {
            color: #666;
            line-height: 1.6;
        }
        
        .amount-summary {
            border-top: 1px solid #e5e5e5;
            padding-top: 24px;
        }
        
        .amount-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            font-size: 15px;
        }
        
        .amount-row.total {
            background: #f5f5f5;
            margin: 0 -16px;
            padding: 16px;
            font-weight: 600;
            font-size: 16px;
        }
        
        .payment-powered {
            margin-top: 40px;
            text-align: center;
        }
        
        .powered-label {
            font-size: 11px;
            font-weight: 700;
            color: #c41230;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .khalti-logo {
            width: 100px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            color: #4a4a4a;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .country-prefix {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #666;
            pointer-events: none;
        }
        
        .flag-icon {
            width: 20px;
            height: 14px;
            background: linear-gradient(to bottom, #003893 33%, white 33%, white 66%, #DC143C 66%);
            border: 1px solid #ddd;
        }
        
        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d1d1;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.2s;
        }
        
        .form-input.with-prefix {
            padding-left: 85px;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #5c2d91;
        }
        
        .form-input::placeholder {
            color: #999;
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #999;
            font-size: 18px;
        }
        
        .submit-btn {
            width: 100%;
            padding: 14px;
            background: #e5e5e5;
            color: #999;
            border: none;
            border-radius: 4px;
            font-size: 15px;
            font-weight: 500;
            cursor: not-allowed;
            margin-top: 24px;
        }
        
        .submit-btn.active {
            background: #5c2d91;
            color: white;
            cursor: pointer;
        }
        
        .submit-btn.active:hover {
            background: #4a2475;
        }
        
        .forgot-password {
            text-align: center;
            margin-top: 16px;
            font-size: 14px;
            color: #666;
        }
        
        .forgot-password a {
            color: #c41230;
            text-decoration: none;
        }
        
        .forgot-password a:hover {
            text-decoration: underline;
        }
        
        .cancel-link {
            text-align: right;
            margin-top: 60px;
        }
        
        .cancel-link a {
            color: #999;
            text-decoration: none;
            font-size: 14px;
        }
        
        .cancel-link a:hover {
            color: #666;
        }
        
        @media (max-width: 968px) {
            .payment-wrapper {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="payment-wrapper">
        <!-- Left Side: Payment Details -->
        <div class="payment-details">
            <h1>Payment Details</h1>
            
            <div class="expiration-notice">
                <span>⏰</span>
                <span>This payment will expire on <?php echo $expirationTime; ?></span>
            </div>
            
            <div class="billed-to">
                <div class="section-label">Billed To:</div>
                <div class="customer-info">
                    <?php echo htmlspecialchars($booking['member_name']); ?><br>
                    <?php echo htmlspecialchars($booking['member_mobile']); ?>, <?php echo htmlspecialchars($booking['member_email']); ?>
                </div>
            </div>
            
            <div class="amount-summary">
                <div class="section-label">Amount Summary:</div>
                <div class="amount-row">
                    <span>Service Charge</span>
                    <span>Rs <?php echo $serviceCharge; ?></span>
                </div>
                <div class="amount-row total">
                    <span>Total Payable Amount</span>
                    <span>Rs <?php echo $totalPayable; ?></span>
                </div>
            </div>
            
            <div class="payment-powered">
                <div class="powered-label">PAYMENT POWERED BY</div>
                <svg class="khalti-logo" viewBox="0 0 100 30" xmlns="http://www.w3.org/2000/svg">
                    <text x="0" y="22" font-family="Arial, sans-serif" font-size="20" font-weight="bold" fill="#5c2d91">khalti</text>
                </svg>
            </div>
        </div>
        
        <!-- Right Side: Payment Form -->
        <div class="payment-form-section">
            <h2>Pay via Khalti Wallet</h2>
            
            <form id="payment-form" method="POST" action="process-khalti-payment.php">
                <input type="hidden" name="pidx" value="<?php echo $pidx; ?>">
                <input type="hidden" name="booking_id" value="<?php echo $booking['Booking_id']; ?>">
                <input type="hidden" name="amount" value="<?php echo $totalPayable; ?>">
                
                <div class="form-group">
                    <label class="form-label">Enter Khalti ID</label>
                    <div class="input-wrapper">
                        <div class="country-prefix">
    <span style="font-size: 20px;">🇳🇵</span>
    <span>Nepal</span>
</div>

                        <input 
                            type="tel" 
                            name="khalti_mobile" 
                            id="khalti-mobile"
                            class="form-input with-prefix" 
                            placeholder="Khalti Mobile Number"
                            value="<?php echo htmlspecialchars($booking['member_mobile']); ?>"
                            maxlength="10"
                            required
                        >
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">&nbsp;</label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            name="khalti_pin" 
                            id="khalti-pin"
                            class="form-input" 
                            placeholder="Khalti Password / MPIN"
                            maxlength="4"
                            required
                        >
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            👁️
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn" id="submit-btn">Submit</button>
                
                <div class="forgot-password">
                    Forgot your password? <a href="https://khalti.com/reset-password" target="_blank">Reset Password</a>
                </div>
            </form>
            
            <div class="cancel-link">
                <a href="SubmitBooking.php">Cancel Payment</a>
            </div>
        </div>
    </div>
    
    <script>
        const mobileInput = document.getElementById('khalti-mobile');
        const pinInput = document.getElementById('khalti-pin');
        const submitBtn = document.getElementById('submit-btn');
        
        function checkFormValidity() {
            if (mobileInput.value.length === 10 && pinInput.value.length === 4) {
                submitBtn.classList.add('active');
                submitBtn.disabled = false;
            } else {
                submitBtn.classList.remove('active');
                submitBtn.disabled = true;
            }
        }
        
        mobileInput.addEventListener('input', checkFormValidity);
        pinInput.addEventListener('input', checkFormValidity);
        
        function togglePassword() {
            const pinField = document.getElementById('khalti-pin');
            pinField.type = pinField.type === 'password' ? 'text' : 'password';
        }
        
        // Initial check
        checkFormValidity();
    </script>
</body>
</html>
