<?php
session_start();
include_once 'includes/config.php';
error_reporting(0);

$oid = intval($_GET['oid']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Track Order | Triple A ShopOnline</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <!-- Owl Carousel CSS (for brand slider) -->
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
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .track-card {
            background: white;
            border-radius: 28px;
            border: 1px solid #EFE8E2;
            padding: 32px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.02);
        }
        .track-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .track-header h1 {
            font-family: 'Instrument Serif', serif;
            font-size: 2rem;
            color: #2A2826;
            margin-bottom: 8px;
        }
        .track-header p {
            color: #7A726C;
            font-size: 0.9rem;
        }
        .order-id {
            background: #F5F0EB;
            display: inline-block;
            padding: 6px 16px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 24px;
        }
        .timeline {
            position: relative;
            margin: 24px 0;
        }
        .timeline-item {
            display: flex;
            gap: 20px;
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid #F0E9E3;
        }
        .timeline-icon {
            width: 48px;
            height: 48px;
            background: #F5F0EB;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #C47A5E;
            font-size: 1.2rem;
        }
        .timeline-content {
            flex: 1;
        }
        .timeline-status {
            font-weight: 700;
            color: #2A2826;
            margin-bottom: 6px;
            text-transform: capitalize;
        }
        .timeline-date {
            font-size: 0.75rem;
            color: #7A726C;
            margin-bottom: 6px;
        }
        .timeline-remark {
            font-size: 0.85rem;
            color: #4A4440;
        }
        .delivered-badge {
            background: #C47A5E20;
            color: #C47A5E;
            border-radius: 40px;
            padding: 6px 16px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-block;
        }
        .btn-group {
            display: flex;
            gap: 16px;
            justify-content: center;
            margin-top: 32px;
        }
        .btn-custom {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 10px 24px;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.2s;
        }
        .btn-custom:hover {
            background: #A85E44;
        }
        .btn-secondary {
            background: #E0D6CE;
            color: #2A2826;
        }
        .btn-secondary:hover {
            background: #CEC3B9;
        }
        @media (max-width: 600px) {
            .track-card {
                padding: 24px;
            }
            .timeline-item {
                gap: 12px;
            }
            .timeline-icon {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<!-- VELORIA Header -->
<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <div class="track-card">
        <div class="track-header">
            <h1><i class="fas fa-truck"></i> Order Tracking</h1>
            <p>Track the real‑time status of your order</p>
        </div>

        <div class="text-center">
            <div class="order-id">Order #<?php echo htmlspecialchars($oid); ?></div>
        </div>

        <?php
        // First check if the order exists in the 'orders' table
        $order_stmt = mysqli_prepare($con, "SELECT id FROM orders WHERE id = ?");
        mysqli_stmt_bind_param($order_stmt, "i", $oid);
        mysqli_stmt_execute($order_stmt);
        mysqli_stmt_store_result($order_stmt);
        $order_exists = mysqli_stmt_num_rows($order_stmt) > 0;
        mysqli_stmt_close($order_stmt);
        
        if(!$order_exists) {
            echo '<div class="alert alert-danger text-center" style="background:#F7EAE8; border:none; border-radius:20px; padding:20px;">
                    <i class="fas fa-exclamation-triangle"></i> Order #' . htmlspecialchars($oid) . ' does not exist.
                  </div>';
        } else {
            // Fetch order history from ordertrackhistory
            $hist_stmt = mysqli_prepare($con, "SELECT * FROM ordertrackhistory WHERE orderId = ? ORDER BY postingDate DESC");
            mysqli_stmt_bind_param($hist_stmt, "i", $oid);
            mysqli_stmt_execute($hist_stmt);
            $ret = mysqli_stmt_get_result($hist_stmt);
            $num = mysqli_num_rows($ret);
            
            if($num > 0) {
        ?>
        <div class="timeline">
            <?php while($row = mysqli_fetch_assoc($ret)) { ?>
            <div class="timeline-item">
                <div class="timeline-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-status"><?php echo htmlspecialchars($row['status']); ?></div>
                    <div class="timeline-date"><?php echo htmlspecialchars($row['postingDate']); ?></div>
                    <div class="timeline-remark"><?php echo nl2br(htmlspecialchars($row['remark'])); ?></div>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php 
            } else { 
        ?>
        <div class="alert alert-warning text-center" style="background:#F7EAE8; border:none; border-radius:20px; padding:20px;">
            <i class="fas fa-info-circle"></i> Order not processed yet. Please check back later.
        </div>
        <?php }
            mysqli_stmt_close($hist_stmt);
            
            // Check if order status is 'Delivered' from the orders table
            $status_stmt = mysqli_prepare($con, "SELECT orderStatus FROM orders WHERE id = ?");
            mysqli_stmt_bind_param($status_stmt, "i", $oid);
            mysqli_stmt_execute($status_stmt);
            $status_res = mysqli_stmt_get_result($status_stmt);
            if($status_row = mysqli_fetch_assoc($status_res)) {
                $currentStatus = $status_row['orderStatus'];
                if($currentStatus == 'Delivered') {
                    echo '<div class="text-center mt-4"><span class="delivered-badge"><i class="fas fa-check-circle"></i> Product Delivered Successfully</span></div>';
                }
            }
            mysqli_stmt_close($status_stmt);
        }
        ?>

        <div class="btn-group">
            <button onclick="window.print();" class="btn-custom btn-secondary"><i class="fas fa-print"></i> Print</button>
            <a href="index.php" class="btn-custom"><i class="fas fa-home"></i> Back to Home</a>
        </div>
    </div>
</div>

<!-- Brand Slider (horizontal) -->
<?php include('includes/brands-slider.php'); ?>

<!-- Footer -->
<?php include('includes/footer.php'); ?>

<!-- jQuery & Owl Carousel (for horizontal brand slider) -->
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
    
    function f2() { window.close(); }
    function f3() { window.print(); }
</script>
</body>
</html>