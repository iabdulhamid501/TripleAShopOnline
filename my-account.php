<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login']) == 0) {   
    header('location: login.php');
    exit();
} else {
    $user_id = intval($_SESSION['id']);

    // Update profile (name & contact) – using prepared statement
    if(isset($_POST['update'])) {
        $name = trim($_POST['name']);
        $contactno = trim($_POST['contactno']);
        $update_stmt = mysqli_prepare($con, "UPDATE users SET name = ?, contactno = ? WHERE id = ?");
        mysqli_stmt_bind_param($update_stmt, "ssi", $name, $contactno, $user_id);
        if(mysqli_stmt_execute($update_stmt)) {
            echo "<script>alert('Your info has been updated');</script>";
        }
        mysqli_stmt_close($update_stmt);
    }

    date_default_timezone_set('Asia/Kolkata');
    $currentTime = date('d-m-Y h:i:s A', time());

    // Change password – using prepared statement
    if(isset($_POST['submit'])) {
        $cpass = md5($_POST['cpass']);
        $newpass = md5($_POST['newpass']);
        
        // Check current password
        $check_stmt = mysqli_prepare($con, "SELECT password FROM users WHERE id = ?");
        mysqli_stmt_bind_param($check_stmt, "i", $user_id);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($check_stmt);
        
        if($row && $row['password'] == $cpass) {
            $update_stmt = mysqli_prepare($con, "UPDATE users SET password = ?, updationDate = ? WHERE id = ?");
            mysqli_stmt_bind_param($update_stmt, "ssi", $newpass, $currentTime, $user_id);
            if(mysqli_stmt_execute($update_stmt)) {
                echo "<script>alert('Password Changed Successfully !!');</script>";
            } else {
                echo "<script>alert('Password change failed. Please try again.');</script>";
            }
            mysqli_stmt_close($update_stmt);
        } else {
            echo "<script>alert('Current Password does not match !!');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>My Account | Triple A ShopOnline</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Owl Carousel CSS (for brand slider) -->
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/owl.theme.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #FCF9F5;
            color: #2A2826;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .account-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 1.8rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
        .account-card h3 {
            font-family: 'Instrument Serif', serif;
            font-size: 1.4rem;
            margin-bottom: 1rem;
            border-left: 4px solid #C47A5E;
            padding-left: 12px;
        }
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4A4440;
            margin-bottom: 0.5rem;
        }
        .form-control {
            border-radius: 20px;
            border: 1px solid #E0D6CE;
            padding: 0.6rem 1rem;
        }
        .form-control:focus {
            border-color: #C47A5E;
            box-shadow: 0 0 0 3px rgba(196,122,94,0.1);
        }
        .btn-submit {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: 0.2s;
        }
        .btn-submit:hover {
            background: #A85E44;
        }
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
        }
        .breadcrumb-custom .breadcrumb-item a {
            color: #C47A5E;
            text-decoration: none;
        }
        /* Sidebar on the right – custom styling */
        .sidebar-right {
            padding-left: 0;
        }
        @media (min-width: 768px) {
            .sidebar-right {
                padding-left: 20px;
            }
            .main-content {
                padding-right: 20px;
            }
        }
        .veloria-sidebar-module {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .veloria-sidebar-module h4 {
            font-family: 'Instrument Serif', serif;
            font-size: 1.2rem;
            margin-bottom: 1rem;
            border-bottom: 2px solid #C47A5E;
            display: inline-block;
            padding-bottom: 4px;
        }
        .account-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .account-links li {
            margin-bottom: 12px;
        }
        .account-links a {
            color: #4A4440;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 0;
            transition: 0.2s;
        }
        .account-links a:hover {
            color: #C47A5E;
            padding-left: 8px;
        }
        .account-links a i {
            width: 24px;
            color: #C47A5E;
        }
        .container-fluid.px-0 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .row.g-0 {
            margin-left: 0;
            margin-right: 0;
        }
        [class*="col-"] {
            padding-left: 0;
            padding-right: 0;
        }
        @media (max-width: 768px) {
            .account-card {
                margin: 0 12px 1.5rem;
            }
            .sidebar-right {
                padding: 0 12px;
            }
        }
    </style>
    <script type="text/javascript">
        function valid() {
            if(document.chngpwd.cpass.value == "") {
                alert("Current Password Field is Empty !!");
                document.chngpwd.cpass.focus();
                return false;
            } else if(document.chngpwd.newpass.value == "") {
                alert("New Password Field is Empty !!");
                document.chngpwd.newpass.focus();
                return false;
            } else if(document.chngpwd.cnfpass.value == "") {
                alert("Confirm Password Field is Empty !!");
                document.chngpwd.cnfpass.focus();
                return false;
            } else if(document.chngpwd.newpass.value != document.chngpwd.cnfpass.value) {
                alert("Password and Confirm Password Field do not match !!");
                document.chngpwd.cnfpass.focus();
                return false;
            }
            return true;
        }
    </script>
</head>
<body oncontextmenu="return false;">

<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container-fluid px-0">
    <div class="row g-0">
        <!-- Main Content (Left side) -->
        <div class="col-md-9 col-lg-9 main-content">
            <div class="px-4 pt-3">
                <div class="d-flex justify-content-between align-items-center mt-2 mb-4">
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">My Account</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">My Account</li>
                        </ol>
                    </nav>
                </div>

                <!-- Profile Update Card -->
                <div class="account-card">
                    <h3><i class="fas fa-user-circle"></i> Personal Information</h3>
                    <?php
                    $user_stmt = mysqli_prepare($con, "SELECT * FROM users WHERE id = ?");
                    mysqli_stmt_bind_param($user_stmt, "i", $user_id);
                    mysqli_stmt_execute($user_stmt);
                    $user_result = mysqli_stmt_get_result($user_stmt);
                    $row = mysqli_fetch_assoc($user_result);
                    mysqli_stmt_close($user_stmt);
                    ?>
                    <form method="post">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span style="color:#C47A5E;">*</span></label>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Number <span style="color:#C47A5E;">*</span></label>
                                <input type="text" name="contactno" class="form-control" value="<?php echo htmlspecialchars($row['contactno']); ?>" maxlength="10" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" name="update" class="btn-submit">Update Profile <i class="fas fa-save ms-2"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Change Password Card -->
                <div class="account-card">
                    <h3><i class="fas fa-key"></i> Change Password</h3>
                    <form method="post" name="chngpwd" onSubmit="return valid();">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Current Password <span style="color:#C47A5E;">*</span></label>
                                <input type="password" name="cpass" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">New Password <span style="color:#C47A5E;">*</span></label>
                                <input type="password" name="newpass" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm Password <span style="color:#C47A5E;">*</span></label>
                                <input type="password" name="cnfpass" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" name="submit" class="btn-submit">Change Password <i class="fas fa-exchange-alt ms-2"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar (Right side) -->
        <div class="col-md-3 col-lg-3 sidebar-right">
            <div class="px-4 pt-3">
                <!-- Custom sidebar content – you can include myaccount-sidebar.php or write directly -->
                <div class="veloria-sidebar-module">
                    <h4><i class="fas fa-user-cog"></i> Account Menu</h4>
                    <ul class="account-links">
                        <li><a href="my-account.php"><i class="fas fa-user"></i> My Account</a></li>
                        <li><a href="bill-ship-addresses.php"><i class="fas fa-truck"></i> Shipping / Billing Address</a></li>
                        <li><a href="order-history.php"><i class="fas fa-history"></i> Order History</a></li>
                        <li><a href="pending-orders.php"><i class="fas fa-hourglass-half"></i> Payment Pending Orders</a></li>
                        <li><a href="my-wishlist.php"><i class="fas fa-heart"></i> My Wishlist</a></li>
                        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/brands-slider.php'); ?>
<?php include('includes/footer.php'); ?>

<!-- jQuery (required for Owl Carousel) -->
<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function(){
        if($('#brand-slider').length) {
            $("#brand-slider").owlCarousel({
                autoPlay: 3000,
                items: 5,
                itemsDesktop: [1199, 4],
                itemsDesktopSmall: [979, 3],
                navigation: true,
                pagination: false
            });
        }
    });
</script>
</body>
</html>
<?php } ?>