<?php
define('TITLE', 'Member Profile');
define('PAGE', 'memberProfile');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();
if($_SESSION['is_login']){
    $mEmail = $_SESSION['mEmail'];
} else {
    echo "<script> location.href='memberLogin.php'; </script>";
}

$sql = "SELECT * FROM memberlogin_tb WHERE m_email='$mEmail'";
$result = $conn->query($sql);
if($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $mName = $row["m_name"]; 
}

if(isset($_REQUEST['nameupdate'])){
    if(($_REQUEST['rName'] == "")){
        $passmsg = '<div class="alert alert-warning" role="alert"> Fill All Fields </div>';
    } else {
        $mName = $_REQUEST["rName"];
        $sql = "UPDATE memberlogin_tb SET m_name = '$mName' WHERE m_email = '$mEmail'";
        if($conn->query($sql) == TRUE){
            $passmsg = '<div class="alert alert-success" role="alert"> Updated Successfully </div>';
        } else {
            $passmsg = '<div class="alert alert-danger" role="alert"> Unable to Update </div>';
        }
    }
}

// Get member statistics
$bookingStats = $conn->query("SELECT COUNT(*) as total_bookings FROM submitbookingt_tb WHERE member_email = '$mEmail'")->fetch_assoc();
$activeBookings = $conn->query("SELECT COUNT(*) as active_bookings FROM submitbookingt_tb WHERE member_email = '$mEmail' AND member_date >= CURDATE()")->fetch_assoc();
$completedBookings = $conn->query("SELECT COUNT(*) as completed_bookings FROM submitbookingt_tb WHERE member_email = '$mEmail' AND member_date < CURDATE()")->fetch_assoc();

// Get recent bookings
$recentBookings = $conn->query("SELECT * FROM submitbookingt_tb WHERE member_email = '$mEmail' ORDER BY member_date DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Profile - Grande Fitness</title>
    
    <!-- Professional styling -->
    <link rel="stylesheet" href="../css/globals.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Complete dashboard redesign with modern styling */
        .dashboard-container {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #0891b2 0%, #6366f1 100%);
            color: white;
            padding: 2rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: url('/placeholder.svg?height=200&width=200') no-repeat center;
            opacity: 0.1;
            transform: rotate(15deg);
        }
        
        .profile-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
            border: 3px solid rgba(255,255,255,0.3);
            animation: pulse 2s infinite;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-left: 4px solid;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, transparent 0%, rgba(8, 145, 178, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .stat-card:hover::before {
            opacity: 1;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
        }
        
        .stat-card.primary { border-left-color: #0891b2; }
        .stat-card.success { border-left-color: #10b981; }
        .stat-card.warning { border-left-color: #f59e0b; }
        .stat-card.info { border-left-color: #6366f1; }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            font-family: 'Space Grotesk', sans-serif;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #0891b2, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-label {
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.875rem;
        }
        
        .stat-icon {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 2rem;
            opacity: 0.1;
        }
        
        .profile-form-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .form-card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .form-card-body {
            padding: 2rem;
        }
        
        .profile-form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .profile-form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .profile-form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            background: #f9fafb;
            color: #374151;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
        }
        
        .profile-form-input:focus {
            border-color: #0891b2;
            box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.1);
            background: white;
            transform: translateY(-1px);
        }
        
        .profile-form-input:read-only {
            background: #f3f4f6;
            cursor: not-allowed;
        }
        
        .profile-btn-primary {
            background: linear-gradient(135deg, #0891b2, #6366f1);
            color: white;
            border: none;
            padding: 0.875rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .profile-btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .profile-btn-primary:hover::before {
            left: 100%;
        }
        
        .profile-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(8, 145, 178, 0.3);
        }
        
        .recent-bookings-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .booking-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.3s ease;
        }
        
        .booking-item:hover {
            background: #f8fafc;
        }
        
        .booking-item:last-child {
            border-bottom: none;
        }
        
        .booking-type {
            font-weight: 600;
            color: #0891b2;
        }
        
        .booking-date {
            color: #64748b;
            font-size: 0.875rem;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .action-btn {
            background: white;
            border: 2px solid #e5e7eb;
            padding: 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            color: #374151;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .action-btn:hover {
            border-color: #0891b2;
            color: #0891b2;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(8, 145, 178, 0.15);
            text-decoration: none;
        }
        
        .welcome-message {
            background: linear-gradient(135deg, rgba(8, 145, 178, 0.1), rgba(99, 102, 241, 0.1));
            border: 1px solid rgba(8, 145, 178, 0.2);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .animate-delay-1 { animation-delay: 0.1s; }
        .animate-delay-2 { animation-delay: 0.2s; }
        .animate-delay-3 { animation-delay: 0.3s; }
        
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Complete dashboard layout redesign -->
    <div class="dashboard-container">
        <div class="container mx-auto px-4 max-w-6xl">
            <!-- Dashboard Header -->
            <div class="dashboard-header animate-fade-in">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="profile-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold mb-2">Welcome back, <?php echo htmlspecialchars($mName); ?>!</h1>
                            <p class="opacity-90">Manage your fitness journey and track your progress</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm opacity-75">Member since</p>
                        <p class="font-semibold"><?php echo date('M Y'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Welcome Message -->
            <div class="welcome-message animate-fade-in animate-delay-1">
                <p class="text-lg font-semibold text-gray-700">
                    <i class="fas fa-star text-yellow-500"></i>
                    Ready to crush your fitness goals today?
                </p>
            </div>

            <!-- Statistics Grid -->
            <div class="stats-grid animate-fade-in animate-delay-2">
                <div class="stat-card primary">
                    <div class="stat-number"><?php echo $bookingStats['total_bookings']; ?></div>
                    <div class="stat-label">Total Bookings</div>
                    <i class="fas fa-calendar-check stat-icon"></i>
                </div>
                <div class="stat-card success">
                    <div class="stat-number"><?php echo $activeBookings['active_bookings']; ?></div>
                    <div class="stat-label">Active Bookings</div>
                    <i class="fas fa-clock stat-icon"></i>
                </div>
                <div class="stat-card warning">
                    <div class="stat-number"><?php echo $completedBookings['completed_bookings']; ?></div>
                    <div class="stat-label">Completed Sessions</div>
                    <i class="fas fa-trophy stat-icon"></i>
                </div>
                <div class="stat-card info">
                    <div class="stat-number"><?php echo date('d'); ?></div>
                    <div class="stat-label">Days This Month</div>
                    <i class="fas fa-calendar-day stat-icon"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Profile Update Form -->
                <div class="profile-form-card animate-fade-in animate-delay-3">
                    <div class="form-card-header">
                        <h3 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-user-edit text-blue-600"></i>
                            Update Profile Information
                        </h3>
                        <p class="text-gray-600 mt-1">Keep your profile information up to date</p>
                    </div>
                    <div class="form-card-body">
                        <form method="POST" id="profileForm">
                            <?php if(isset($passmsg)) { echo $passmsg; } ?>
                            
                            <div class="profile-form-group">
                                <label for="inputEmail" class="profile-form-label">Email Address</label>
                                <input type="email" class="profile-form-input" id="inputEmail" 
                                       value="<?php echo htmlspecialchars($mEmail); ?>" readonly>
                                <small class="text-gray-500 text-sm mt-1 block">
                                    <i class="fas fa-info-circle"></i> Email cannot be changed for security reasons
                                </small>
                            </div>
                            
                            <div class="profile-form-group">
                                <label for="inputName" class="profile-form-label">Full Name</label>
                                <input type="text" class="profile-form-input" id="inputName" name="rName" 
                                       value="<?php echo htmlspecialchars($mName); ?>" required>
                            </div>
                            
                            <button type="submit" class="profile-btn-primary" name="nameupdate" id="updateBtn">
                                <i class="fas fa-save"></i>
                                Update Profile
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="recent-bookings-card animate-fade-in animate-delay-3">
                    <div class="form-card-header">
                        <h3 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-history text-green-600"></i>
                            Recent Bookings
                        </h3>
                        <p class="text-gray-600 mt-1">Your latest fitness sessions</p>
                    </div>
                    <div>
                        <?php if($recentBookings->num_rows > 0): ?>
                            <?php while($booking = $recentBookings->fetch_assoc()): ?>
                                <div class="booking-item">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="booking-type"><?php echo htmlspecialchars($booking['booking_type']); ?></div>
                                            <div class="booking-date">
                                                <i class="fas fa-calendar"></i>
                                                <?php echo date('M d, Y', strtotime($booking['member_date'])); ?>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm text-gray-600">Trainer</div>
                                            <div class="font-semibold"><?php echo htmlspecialchars($booking['trainer']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="booking-item text-center py-8">
                                <i class="fas fa-calendar-plus text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">No bookings yet</p>
                                <p class="text-sm text-gray-400">Start your fitness journey by booking a session!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions animate-fade-in animate-delay-3">
                <a href="SubmitBooking.php" class="action-btn">
                    <i class="fas fa-plus-circle"></i>
                    New Booking
                </a>
                <a href="mybooking.php" class="action-btn">
                    <i class="fas fa-list"></i>
                    My Bookings
                </a>
                <a href="viewschedule.php" class="action-btn">
                    <i class="fas fa-calendar-alt"></i>
                    View Schedule
                </a>
                <a href="memberLogin.php?logout=1" class="action-btn" onclick="return confirm('Are you sure you want to logout?')">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced JavaScript for dashboard interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('profileForm');
            const updateBtn = document.getElementById('updateBtn');
            const nameInput = document.getElementById('inputName');

            // Add loading state to update button
            form.addEventListener('submit', function() {
                updateBtn.innerHTML = '<span class="loading-spinner"></span>Updating...';
                updateBtn.disabled = true;
            });

            // Real-time validation for name field
            nameInput.addEventListener('input', function() {
                const value = this.value.trim();
                if (value.length < 2) {
                    this.style.borderColor = '#ef4444';
                    updateBtn.disabled = true;
                } else {
                    this.style.borderColor = '#10b981';
                    updateBtn.disabled = false;
                }
            });

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });

            // Add hover effects to stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Animate numbers on page load
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach(number => {
                const finalValue = parseInt(number.textContent);
                let currentValue = 0;
                const increment = finalValue / 30;
                
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        number.textContent = finalValue;
                        clearInterval(timer);
                    } else {
                        number.textContent = Math.floor(currentValue);
                    }
                }, 50);
            });

            // Add ripple effect to buttons
            const buttons = document.querySelectorAll('.profile-btn-primary, .action-btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        background: rgba(255, 255, 255, 0.3);
                        border-radius: 50%;
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        pointer-events: none;
                    `;
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => ripple.remove(), 600);
                });
            });

            // Add CSS for ripple animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>
</html>

<?php
include('includes/footer.php'); 
?>
