<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login']) == 0) {   
    header('location: login.php');  // Fixed the broken redirect
    exit();
} else {
    $user_id = intval($_SESSION['id']);

    // ========== UPDATE BILLING ADDRESS (Prepared Statement) ==========
    if(isset($_POST['update'])) {
        $baddress = trim($_POST['billingaddress']);
        $bstate = trim($_POST['bilingstate']);
        $bcity = trim($_POST['billingcity']);
        $bpincode = trim($_POST['billingpincode']);
        
        $update_stmt = mysqli_prepare($con, "UPDATE users SET billingAddress = ?, billingState = ?, billingCity = ?, billingPincode = ? WHERE id = ?");
        mysqli_stmt_bind_param($update_stmt, "ssssi", $baddress, $bstate, $bcity, $bpincode, $user_id);
        if(mysqli_stmt_execute($update_stmt)) {
            echo "<script>alert('Billing Address has been updated');</script>";
        } else {
            echo "<script>alert('Update failed. Please try again.');</script>";
        }
        mysqli_stmt_close($update_stmt);
    }

    // ========== UPDATE SHIPPING ADDRESS (Prepared Statement) ==========
    if(isset($_POST['shipupdate'])) {
        $saddress = trim($_POST['shippingaddress']);
        $sstate = trim($_POST['shippingstate']);
        $scity = trim($_POST['shippingcity']);
        $spincode = trim($_POST['shippingpincode']);
        
        $update_stmt = mysqli_prepare($con, "UPDATE users SET shippingAddress = ?, shippingState = ?, shippingCity = ?, shippingPincode = ? WHERE id = ?");
        mysqli_stmt_bind_param($update_stmt, "ssssi", $saddress, $sstate, $scity, $spincode, $user_id);
        if(mysqli_stmt_execute($update_stmt)) {
            echo "<script>alert('Shipping Address has been updated');</script>";
        } else {
            echo "<script>alert('Update failed. Please try again.');</script>";
        }
        mysqli_stmt_close($update_stmt);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="Manage your billing and shipping addresses">
    <meta name="author" content="Triple A ShopOnline">
    <meta name="keywords" content="eCommerce, address, checkout">
    <meta name="robots" content="all">
    <title>Address Book | Triple A ShopOnline</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <!-- Owl Carousel CSS (required for brand slider) -->
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/owl.theme.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FCF9F5;
            color: #2A2826;
            line-height: 1.5;
        }
        h1, h2, h3, h4, .page-title {
            font-family: 'Instrument Serif', serif;
            font-weight: 500;
            letter-spacing: -0.01em;
        }
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 24px;
        }
        .veloria-breadcrumb {
            background: transparent;
            padding: 16px 0;
            margin: 0 0 24px;
        }
        .veloria-breadcrumb ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 0.85rem;
        }
        .veloria-breadcrumb li a {
            color: #C47A5E;
            text-decoration: none;
        }
        .veloria-breadcrumb li.active {
            color: #7A726C;
        }
        .address-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 28px;
            margin-bottom: 32px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
        .address-card h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 2px solid #C47A5E;
            display: inline-block;
            padding-bottom: 6px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4A4440;
            margin-bottom: 8px;
            display: block;
        }
        .form-group label span {
            color: #C47A5E;
        }
        .form-control {
            width: 100%;
            padding: 12px 16px;
            font-size: 0.9rem;
            border: 1px solid #E0D6CE;
            border-radius: 16px;
            background: #FFFFFF;
            transition: 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .form-control:focus {
            outline: none;
            border-color: #C47A5E;
            box-shadow: 0 0 0 3px rgba(196, 122, 94, 0.1);
        }
        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }
        .btn-update {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 10px 24px;
            font-weight: 600;
            color: white;
            transition: 0.2s;
            cursor: pointer;
        }
        .btn-update:hover {
            background: #A85E44;
            transform: translateY(-2px);
        }
        @media (max-width: 768px) {
            .address-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <div class="veloria-breadcrumb">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><i class="fas fa-chevron-right"></i></li>
            <li class="active">Address Book</li>
        </ul>
    </div>

    <div class="row" style="margin: 24px 0 48px;">
        <!-- Main content (address forms) -->
        <div class="col-md-8">
            <!-- Billing Address Card -->
            <div class="address-card">
                <h3><i class="fas fa-file-invoice" style="color:#C47A5E;"></i> Billing Address</h3>
                <?php
                // Fetch current user data using prepared statement
                $user_stmt = mysqli_prepare($con, "SELECT * FROM users WHERE id = ?");
                mysqli_stmt_bind_param($user_stmt, "i", $user_id);
                mysqli_stmt_execute($user_stmt);
                $user_result = mysqli_stmt_get_result($user_stmt);
                $row = mysqli_fetch_assoc($user_result);
                mysqli_stmt_close($user_stmt);
                ?>
                <form method="post">
                    <div class="form-group">
                        <label for="billingaddress">Billing Address <span>*</span></label>
                        <textarea class="form-control" id="billingaddress" name="billingaddress" required><?php echo htmlspecialchars($row['billingAddress']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="bilingstate">State <span>*</span></label>
                        <input type="text" class="form-control" id="bilingstate" name="bilingstate" value="<?php echo htmlspecialchars($row['billingState']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="billingcity">City <span>*</span></label>
                        <input type="text" class="form-control" id="billingcity" name="billingcity" value="<?php echo htmlspecialchars($row['billingCity']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="billingpincode">Pincode <span>*</span></label>
                        <input type="text" class="form-control" id="billingpincode" name="billingpincode" value="<?php echo htmlspecialchars($row['billingPincode']); ?>" required>
                    </div>
                    <button type="submit" name="update" class="btn-update">Update Billing Address</button>
                </form>
            </div>

            <!-- Shipping Address Card -->
            <div class="address-card">
                <h3><i class="fas fa-truck" style="color:#C47A5E;"></i> Shipping Address</h3>
                <form method="post">
                    <div class="form-group">
                        <label for="shippingaddress">Shipping Address <span>*</span></label>
                        <textarea class="form-control" id="shippingaddress" name="shippingaddress" required><?php echo htmlspecialchars($row['shippingAddress']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="shippingstate">State <span>*</span></label>
                        <input type="text" class="form-control" id="shippingstate" name="shippingstate" value="<?php echo htmlspecialchars($row['shippingState']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="shippingcity">City <span>*</span></label>
                        <input type="text" class="form-control" id="shippingcity" name="shippingcity" value="<?php echo htmlspecialchars($row['shippingCity']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="shippingpincode">Pincode <span>*</span></label>
                        <input type="text" class="form-control" id="shippingpincode" name="shippingpincode" value="<?php echo htmlspecialchars($row['shippingPincode']); ?>" required>
                    </div>
                    <button type="submit" name="shipupdate" class="btn-update">Update Shipping Address</button>
                </form>
            </div>
        </div>

        <!-- Sidebar (myaccount-sidebar.php) -->
        <div class="col-md-4">
            <?php include('includes/myaccount-sidebar.php'); ?>
        </div>
    </div>

    <!-- Brand Slider (horizontal) -->
    <?php include('includes/brands-slider.php'); ?>
</div>

<?php include('includes/footer.php'); ?>

<!-- jQuery (required for Owl Carousel) -->
<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
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