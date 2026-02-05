<?php
session_start();
define('TITLE', 'Payment Status');
define('PAGE', 'message');
include('includes/header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Processing...</span>
            </div>
            <p class="mt-3">Processing your transaction...</p>
        </div>
    </div>

    <?php
    // Display the transaction message
    if (isset($_SESSION['transaction_msg'])) {
        echo $_SESSION['transaction_msg'];
        unset($_SESSION['transaction_msg']);
    } elseif (isset($_SESSION['validate_msg'])) {
        echo $_SESSION['validate_msg'];
        unset($_SESSION['validate_msg']);
    }
    ?>
</body>
</html>

<?php
include('includes/footer.php');
?>
