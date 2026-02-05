<?php 
define('TITLE', 'Khalti Payment'); 
define('PAGE', 'Payment'); 
include('includes/header.php');  
session_start(); 

// Redirect if not logged in
if (!isset($_SESSION['is_login'])) {
    echo "<script> location.href='memberLogin.php'; </script>";
    exit();
}

$bookingId = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';
$amount = isset($_GET['amount']) ? $_GET['amount'] : 0;

if (empty($bookingId) || $amount <= 0) {
    echo "<script>alert('Invalid payment request'); location.href='mybooking.php';</script>";
    exit();
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white text-center">
                    <h3><b>Khalti Payment</b></h3>
                </div>
                <div class="card-body text-center">
                    <h4>Booking ID: <?php echo htmlspecialchars($bookingId); ?></h4>
                    <h2 class="text-success my-4">Rs. <?php echo number_format($amount); ?></h2>
                    
                    <div class="alert alert-info">
                        <p><strong>Pay with Khalti</strong></p>
                        <p>Click the button below to complete your payment securely via Khalti.</p>
                    </div>
                    
                    <button id="payment-button" class="btn btn-success btn-lg mb-3">
                        <i class="fas fa-credit-card"></i> Pay with Khalti
                    </button>
                    
                    <div>
                        <a href="mybooking.php" class="btn btn-secondary">Cancel & Go Back</a>
                    </div>
                    
                    <div class="mt-4">
                        <small class="text-muted">
                            Secure payment powered by Khalti<br>
                            All transactions are encrypted and secure
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Khalti Checkout Integration -->
<script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.uat.js"></script>
<script>
    var config = {
        // Replace with your actual Khalti public key
        "publicKey": "test_public_key_your_key_here",
        "productIdentity": "<?php echo $bookingId; ?>",
        "productName": "Gym Membership Booking",
        "productUrl": window.location.origin,
        "paymentPreference": [
            "KHALTI",
            "EBANKING",
            "MOBILE_BANKING",
            "CONNECT_IPS",
            "SCT"
        ],
        "eventHandler": {
            onSuccess (payload) {
                console.log(payload);
                // Payment successful
                alert('Payment Successful! Transaction ID: ' + payload.idx);
                // Redirect to success page or update booking status
                window.location.href = 'payment_success.php?booking_id=<?php echo $bookingId; ?>&transaction_id=' + payload.idx;
            },
            onError (error) {
                console.log(error);
                alert('Payment failed. Please try again.');
            },
            onClose () {
                console.log('Payment widget closed');
            }
        }
    };

    var checkout = new KhaltiCheckout(config);
    var btn = document.getElementById("payment-button");
    
    btn.onclick = function () {
        checkout.show({amount: <?php echo $amount * 100; ?>}); // Amount in paisa (multiply by 100)
    }
</script>

<?php include('includes/footer.php'); ?>
