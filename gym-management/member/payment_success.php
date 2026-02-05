<?php 
define('TITLE', 'Payment Success'); 
define('PAGE', 'Payment'); 
include('includes/header.php');  
include('../dbConnection.php'); 
session_start(); 

// Redirect if not logged in
if (!isset($_SESSION['is_login'])) {
    echo "<script> location.href='memberLogin.php'; </script>";
    exit();
}

$bookingId = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';
$transactionId = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : '';

if (!empty($bookingId) && !empty($transactionId)) {
    // Update booking with payment status
    $stmt = $conn->prepare("UPDATE submitbookingt_tb SET payment_status = 'Paid', transaction_id = ? WHERE submit_id = ?");
    $stmt->bind_param("si", $transactionId, $bookingId);
    $stmt->execute();
    $stmt->close();
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-success">
                <div class="card-header bg-success text-white text-center">
                    <h3><b>Payment Successful!</b></h3>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                    </div>
                    
                    <h4>Your payment has been processed successfully</h4>
                    
                    <div class="alert alert-success mt-4">
                        <p class="mb-1"><strong>Booking ID:</strong> <?php echo htmlspecialchars($bookingId); ?></p>
                        <p class="mb-0"><strong>Transaction ID:</strong> <?php echo htmlspecialchars($transactionId); ?></p>
                    </div>
                    
                    <p class="text-muted">A confirmation email has been sent to your registered email address.</p>
                    
                    <div class="mt-4">
                        <a href="mybooking.php" class="btn btn-success btn-lg">View My Bookings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
include('includes/footer.php'); 
$conn->close(); 
?>
