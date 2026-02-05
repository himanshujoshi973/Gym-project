<?php
session_start();

// Check if booking details are provided
if (!isset($_GET['booking_id']) || !isset($_GET['name']) || !isset($_GET['email']) || !isset($_GET['phone'])) {
    $_SESSION['transaction_msg'] = '<script>
    Swal.fire({ icon:"error", title:"Invalid Request", timer:1500, showConfirmButton:false });
    </script>';
    header("Location: SubmitBooking.php");
    exit();
}

// Get booking details from URL
$booking_id = (int)$_GET['booking_id'];
$name = htmlspecialchars($_GET['name']);
$email = htmlspecialchars($_GET['email']);
$phone = htmlspecialchars($_GET['phone']);
$purchase_order_id = "GYM-" . $booking_id;
$purchase_order_name = "Gym Booking #" . $booking_id;

// Connect to database to verify booking
include('../dbConnection.php');

$stmt = $conn->prepare("SELECT Booking_id, total_price FROM submitbookingt_tb WHERE Booking_id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    $_SESSION['transaction_msg'] = '<script>
    Swal.fire({ icon:"error", title:"Booking Not Found", timer:1500, showConfirmButton:false });
    </script>';
    header("Location: SubmitBooking.php");
    exit();
}

$amount = ((float)$booking['total_price']) * 100; // Convert to paisa

// Store booking details in session for verification after payment
$_SESSION['khalti_booking'] = [
    'booking_id' => $booking_id,
    'amount' => $amount,
    'purchase_order_id' => $purchase_order_id
];

// Generate unique pidx for this transaction
$pidx = 'pidx_' . uniqid() . '_' . $booking_id;

// Update booking with pidx
$stmt = $conn->prepare("UPDATE submitbookingt_tb SET pidx = ? WHERE Booking_id = ?");
$stmt->bind_param("si", $pidx, $booking_id);
$stmt->execute();
$stmt->close();

header("Location: khalti-payment-page.php?pidx=" . $pidx);
exit();
?>
