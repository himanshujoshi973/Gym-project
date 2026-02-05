<?php 
define('TITLE', 'Submit Booking'); 
define('PAGE', 'SubmitBooking'); 
include('includes/header.php');  
include('../dbConnection.php'); 
session_start(); 

// Redirect if not logged in
if (!isset($_SESSION['is_login'])) {
    echo "<script> location.href='memberLogin.php'; </script>";
    exit();
}

$mEmail = $_SESSION['mEmail'];

// Get member name
$sql = "SELECT m_name FROM memberlogin_tb WHERE m_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $mEmail);
$stmt->execute();
$stmt->bind_result($mName);
$stmt->fetch();
$stmt->close();

// Pre-fill class/date if redirected from viewschedule
$preSelectedClass = isset($_GET['class']) ? $_GET['class'] : '';
$preSelectedDate = isset($_GET['date']) ? $_GET['date'] : '';

// Booking submission logic
if (isset($_POST['Submitbooking'])) {
    if (
        empty($mName) || empty($mEmail) || empty($_POST['membermobile']) || 
        empty($_POST['bookingtype']) || empty($_POST['trainer']) || 
        empty($_POST['bookingdate']) || empty($_POST['memberadd1']) || 
        empty($_POST['subscription']) || empty($_POST['paymentmethod'])
    ) {
        $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> All Fields Are Required </div>';
    } else {
        // Prevent double booking
        $checkStmt = $conn->prepare("SELECT COUNT(*) as count FROM submitbookingt_tb WHERE member_email = ? AND booking_type = ? AND member_date = ?");
        $checkStmt->bind_param("sss", $mEmail, $_POST['bookingtype'], $_POST['bookingdate']);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $existingBooking = $checkResult->fetch_assoc()['count'];
        $checkStmt->close();

        if ($existingBooking > 0) {
            $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> You already have a booking for this class on this date! </div>';
        } else {
            $mmobile = $_POST['membermobile'];
            $btype = $_POST['bookingtype'];
            $trai = $_POST['trainer'];
            $madd1 = $_POST['memberadd1'];
            $bdate = $_POST['bookingdate'];
            $subscription = $_POST['subscription'];
            $paymentMethod = $_POST['paymentmethod'];
            $totalPrice = $_POST['totalprice'];

            $stmt = $conn->prepare("INSERT INTO submitbookingt_tb 
                (member_name, member_email, member_mobile, member_add1, booking_type, trainer, member_date, subscription_months, payment_method, total_price, payment_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->bind_param("sssssssisd", $mName, $mEmail, $mmobile, $madd1, $btype, $trai, $bdate, $subscription, $paymentMethod, $totalPrice);

            if ($stmt->execute()) {
                $genid = $conn->insert_id;
                $_SESSION['myid'] = $genid;
                
                if ($paymentMethod == 'Khalti') {
                    $_SESSION['khalti_booking'] = [
                        'booking_id' => $genid,
                        'amount' => $totalPrice
                    ];
                    echo "<script>location.href='payment-request.php?booking_id=$genid&amount=$totalPrice&name=" . urlencode($mName) . "&email=" . urlencode($mEmail) . "&phone=$mmobile';</script>";
                } else {
                    echo "<script>alert('Booking Successful! Booking ID: $genid. Payment: Cash on Delivery'); location.href='mybooking.php';</script>";
                }
                exit();
            } else {
                $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to make booking: ' . $conn->error . '</div>';
            }

            $stmt->close();
        }
    }
}
?>

<!-- Booking Form -->
<div class="col-sm-8 mt-5 mx-auto">
    <div class="card">
        <div class="card-header bg-primary text-white text-center">
            <h3><b>Make Booking</b></h3>
        </div>
        <div class="card-body">
            <?php if (!empty($preSelectedClass)): ?>
                <div class="alert alert-info">
                    <strong>Pre-selected:</strong> <?php echo htmlspecialchars($preSelectedClass); ?> 
                    <?php if (!empty($preSelectedDate)): ?>
                        on <?php echo htmlspecialchars($preSelectedDate); ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <form action="SubmitBooking.php" method="POST">
                <?php if (isset($msg)) { echo $msg; } ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputName">Full Name</label>
                            <input type="text" class="form-control" id="inputName" name="membername" 
                                   value="<?php echo htmlspecialchars($mName); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputEmail">Email</label>
                            <input type="email" class="form-control" id="inputEmail" name="memberemail" 
                                   value="<?php echo htmlspecialchars($mEmail); ?>" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputMobile">Mobile</label>
                            <input type="text" class="form-control" id="inputMobile" name="membermobile"
                                   placeholder="Enter mobile number" required maxlength="10" pattern="^(98|97)\d{8}$"
                                   title="Enter a valid 10-digit Nepali mobile number starting with 98 or 97"
                                   onkeypress="return isInputNumber(event)">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputbookingtype">Booking Type</label>
                            <select class="form-control" id="inputbookingtype" name="bookingtype" required>
                                <option value="">Select</option>
                                <option <?php echo ($preSelectedClass == 'Yoga class') ? 'selected' : ''; ?>>Yoga class</option>
                                <option <?php echo ($preSelectedClass == 'Zumba class') ? 'selected' : ''; ?>>Zumba class</option>
                                <option <?php echo ($preSelectedClass == 'Cardio class') ? 'selected' : ''; ?>>Cardio class</option>
                                <option <?php echo ($preSelectedClass == 'Weight lifting') ? 'selected' : ''; ?>>Weight lifting</option>
                                <option <?php echo ($preSelectedClass == 'Endurance Training') ? 'selected' : ''; ?>>Endurance Training</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputTrainer">Select Trainer</label>
                            <select class="form-control" id="inputTrainer" name="trainer" required>
                                <option value="">Select</option>
                                <option>Aashish Thapa (4:00AM-9:00AM)</option>
                                <option>Bikash Thapa (9:00AM-4:00PM)</option>
                                <option>Anupama (9:00AM-4:00PM)</option>
                                <option>Santoshi (4:00AM-9:00AM)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputDate">Date</label>
                            <input type="date" class="form-control" id="inputDate" name="bookingdate" 
                                   value="<?php echo htmlspecialchars($preSelectedDate); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputSubscription">Subscription Duration</label>
                            <select class="form-control" id="inputSubscription" name="subscription" required onchange="calculatePrice()">
                                <option value="">Select</option>
                                <option value="1">1 Month - Rs. 2000/month</option>
                                <option value="3">3 Months - Rs. 1900/month (5% off)</option>
                                <option value="6">6 Months - Rs. 1800/month (10% off)</option>
                                <option value="12">12 Months - Rs. 1700/month (15% off)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputPayment">Payment Method</label>
                            <select class="form-control" id="inputPayment" name="paymentmethod" required>
                                <option value="">Select Payment Method</option>
                                <option value="Khalti">Khalti (Online Payment)</option>
                                <option value="COD">Cash on Delivery</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info" id="priceDisplay" style="display: none;">
                    <h5 class="mb-0">
                        <strong>Total Amount:</strong> 
                        <span class="float-right">Rs. <span id="totalPrice">0</span></span>
                    </h5>
                    <small class="text-muted" id="priceBreakdown"></small>
                </div>

                <input type="hidden" name="totalprice" id="totalPriceInput" value="0">

                <div class="form-group">
                    <label for="inputAddress">Address</label>
                    <input type="text" class="form-control" id="inputAddress" placeholder="Add address" name="memberadd1" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg" name="Submitbooking">Submit Booking</button>
                    <a href="viewschedule.php" class="btn btn-secondary btn-lg ml-2">Back to Schedule</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS input validation and price calculation -->
<script>
function isInputNumber(evt) {
    var ch = String.fromCharCode(evt.which);
    if (!(/[0-9]/.test(ch))) {
        evt.preventDefault();
        return false;
    }
}

function calculatePrice() {
    var subscription = document.getElementById("inputSubscription").value;
    var priceDisplay = document.getElementById("priceDisplay");
    var totalPriceSpan = document.getElementById("totalPrice");
    var priceBreakdown = document.getElementById("priceBreakdown");
    var totalPriceInput = document.getElementById("totalPriceInput");
    
    if (subscription === "") {
        priceDisplay.style.display = "none";
        totalPriceInput.value = "0";
        return;
    }
    
    var basePrice = 2000;
    var monthlyRate = basePrice;
    var discount = 0;
    var totalPrice = 0;
    
    switch(subscription) {
        case "1":
            monthlyRate = 2000;
            discount = 0;
            totalPrice = 2000;
            break;
        case "3":
            monthlyRate = 1900;
            discount = 5;
            totalPrice = 5700;
            break;
        case "6":
            monthlyRate = 1800;
            discount = 10;
            totalPrice = 10800;
            break;
        case "12":
            monthlyRate = 1700;
            discount = 15;
            totalPrice = 20400;
            break;
    }
    
    totalPriceSpan.textContent = totalPrice.toLocaleString();
    totalPriceInput.value = totalPrice;
    
    if (discount > 0) {
        priceBreakdown.textContent = subscription + " months × Rs. " + monthlyRate.toLocaleString() + "/month (" + discount + "% discount applied)";
    } else {
        priceBreakdown.textContent = subscription + " month × Rs. " + monthlyRate.toLocaleString() + "/month";
    }
    
    priceDisplay.style.display = "block";
}

// Set min date to today
window.onload = function () {
    var today = new Date();
    var day = ("0" + today.getDate()).slice(-2);
    var month = ("0" + (today.getMonth() + 1)).slice(-2);
    var year = today.getFullYear();
    var todayDate = year + "-" + month + "-" + day;
    document.getElementById("inputDate").setAttribute("min", todayDate);
};
</script>

<?php 
include('includes/footer.php'); 
$conn->close(); 
?>
