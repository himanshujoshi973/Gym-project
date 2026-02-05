<?php
session_start();
include('../dbConnection.php');

$pidx = $_GET['pidx'] ?? null;

if (!$pidx || !isset($_SESSION['khalti_booking'])) {
    $_SESSION['transaction_msg'] = '<script>
    Swal.fire({ icon:"error", title:"Invalid Payment Response", timer:1500, showConfirmButton:false });
    </script>';
    header("Location: SubmitBooking.php");
    exit();
}

$booking_id = $_SESSION['khalti_booking']['booking_id'];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://a.khalti.com/api/v2/epayment/lookup/",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode(['pidx' => $pidx]),
    CURLOPT_HTTPHEADER => [
        "Authorization: key test_secret_key_68791341fdd94846a146f0457ff7b455",
        "Content-Type: application/json"
    ]
]);

$response = curl_exec($curl);
curl_close($curl);

$responseArray = json_decode($response, true);

/* ---------- PROCESS STATUS ---------- */
if (isset($responseArray['status']) && $responseArray['status'] === 'Completed') {

    $confirmation_code = "GYM" . strtoupper(substr(uniqid(), -8));

    $stmt = $conn->prepare(
        "UPDATE submitbookingt_tb 
         SET payment_status='paid', 
             payment_method='Khalti',
             transaction_id=?, 
             confirmation_code=? 
         WHERE Booking_id=?"
    );
    $stmt->bind_param(
        "ssi",
        $pidx,
        $confirmation_code,
        $booking_id
    );
    $stmt->execute();
    $stmt->close();

    unset($_SESSION['khalti_booking']);

    $_SESSION['transaction_msg'] = '<script>
    Swal.fire({
        icon:"success",
        title:"Payment Successful",
        html:"Booking ID: <b>' . $booking_id . '</b><br>Confirmation Code: <b>' . $confirmation_code . '</b>",
        confirmButtonText:"My Bookings"
    }).then(()=>{ window.location.href="mybooking.php"; });
    </script>';

    header("Location: message.php");
    exit();

} else {

    $stmt = $conn->prepare(
        "UPDATE submitbookingt_tb 
         SET payment_status='failed' 
         WHERE Booking_id=?"
    );
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->close();

    unset($_SESSION['khalti_booking']);

    $_SESSION['transaction_msg'] = '<script>
    Swal.fire({
        icon:"error",
        title:"Payment Cancelled",
        text:"Payment failed or cancelled",
        confirmButtonText:"Return"
    }).then(()=>{ window.location.href="SubmitBooking.php"; });
    </script>';

    header("Location: message.php");
    exit();
}
?>
