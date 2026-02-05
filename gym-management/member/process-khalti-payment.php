<?php
session_start();
include('../dbConnection.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: SubmitBooking.php");
    exit();
}

$pidx = $_POST['pidx'] ?? '';
$method = $_POST['method'] ?? '';

if (empty($pidx)) {
    $_SESSION['transaction_msg'] = '<script>Swal.fire({ icon:"error", title:"Invalid Request", timer:1500, showConfirmButton:false });</script>';
    header("Location: message.php");
    exit();
}

// Get booking details including subscription_months
$stmt = $conn->prepare("SELECT Booking_id, total_price, member_email, member_date, subscription_months FROM submitbookingt_tb WHERE pidx = ?");
$stmt->bind_param("s", $pidx);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    $_SESSION['transaction_msg'] = '<script>Swal.fire({ icon:"error", title:"Booking Not Found", timer:1500, showConfirmButton:false });</script>';
    header("Location: message.php");
    exit();
}

// --- NEW LOGIC: Calculate Subscription Dates ---
$start_date = date('Y-m-d'); // Today
$months = (int)$booking['subscription_months'];
$end_date = date('Y-m-d', strtotime("+$months months", strtotime($start_date)));
// -----------------------------------------------

$confirmation_code = 'KHL' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
$payment_status = 'paid'; 
$payment_method = ucfirst($method);

// Update booking with payment AND subscription dates
$stmt = $conn->prepare("UPDATE submitbookingt_tb SET 
    payment_status = ?, 
    payment_method = ?, 
    confirmation_code = ?,
    subscription_start_date = ?,
    subscription_end_date = ?,
    payment_date = NOW()
    WHERE Booking_id = ?");
$stmt->bind_param("sssssi", $payment_status, $payment_method, $confirmation_code, $start_date, $end_date, $booking['Booking_id']);

if ($stmt->execute()) {
    // Update schedule record
    $active_status = 'active';
    $stmt2 = $conn->prepare("UPDATE tbl_bookings SET status = ? WHERE member_email = ? AND class_date = ?");
    $stmt2->bind_param("sss", $active_status, $booking['member_email'], $booking['member_date']);
    $stmt2->execute();
    $stmt2->close();

    $_SESSION['transaction_msg'] = '<script>
    Swal.fire({
        icon:"success",
        title:"Payment Successful!",
        html:"<strong>Subscription Active Until:</strong> ' . $end_date . '",
        confirmButtonText:"OK"
    }).then(()=>{ window.location.href="mybooking.php"; });
    </script>';
} else {
    $_SESSION['transaction_msg'] = '<script>Swal.fire({ icon:"error", title:"Payment Failed", text:"Database error", timer:2000, showConfirmButton:false });</script>';
}

$stmt->close();
header("Location: message.php");
exit();
?>