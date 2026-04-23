<?php
session_start();
error_reporting(0);
include_once('includes/config.php');

if(isset($_POST['submit']))
{
    $orderid = $_GET['oid'];
    $ressta = "Cancelled";
    $remark = $_POST['restremark'];
    $canclbyuser = 'User';

    $query = mysqli_query($con, "insert into ordertrackhistory(orderId, remark, status, canceledBy) value('$orderid','$remark','$ressta','$canclbyuser')"); 
    $query = mysqli_query($con, "update orders set orderStatus='$ressta' where id='$orderid'");
    if ($query) {
        echo '<script>alert("Your order cancelled now.")</script>';
    } else {
        echo '<script>alert("Something went wrong. Please try again.")</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="Cancel your order - Triple A ShopOnline">
    <meta name="author" content="Triple A ShopOnline">
    <title>Cancel Order | Triple A ShopOnline</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts: Inter + Instrument Serif -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    
    <style>
        /* VELORIA global styles */
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
        h1, h2, h3, .page-title {
            font-family: 'Instrument Serif', serif;
            font-weight: 500;
            letter-spacing: -0.01em;
        }
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 24px;
        }
        /* Cancel Order Card */
        .cancel-card {
            background: white;
            border-radius: 28px;
            border: 1px solid #EFE8E2;
            padding: 32px;
            margin: 48px 0;
            box-shadow: 0 8px 20px rgba(0,0,0,0.02);
        }
        .cancel-card h2 {
            font-size: 1.8rem;
            margin-bottom: 24px;
            border-bottom: 2px solid #C47A5E;
            display: inline-block;
            padding-bottom: 6px;
        }
        .order-details {
            background: #FCF9F5;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 28px;
        }
        .order-details p {
            margin-bottom: 12px;
            font-size: 1rem;
        }
        .order-details strong {
            color: #C47A5E;
            font-weight: 600;
        }
        .info-message {
            background: #F0E9E3;
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 24px;
            font-size: 0.9rem;
        }
        .info-message i {
            color: #C47A5E;
            margin-right: 10px;
        }
        .form-group {
            margin-bottom: 24px;
        }
        .form-group label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #4A4440;
            margin-bottom: 8px;
            display: block;
        }
        textarea.form-control {
            width: 100%;
            padding: 12px 16px;
            font-size: 0.9rem;
            border: 1px solid #E0D6CE;
            border-radius: 20px;
            background: #FFFFFF;
            transition: 0.2s;
            font-family: 'Inter', sans-serif;
            resize: vertical;
        }
        textarea.form-control:focus {
            outline: none;
            border-color: #C47A5E;
            box-shadow: 0 0 0 3px rgba(196,122,94,0.1);
        }
        .btn-cancel {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 12px 28px;
            font-weight: 600;
            color: white;
            transition: 0.2s;
            cursor: pointer;
        }
        .btn-cancel:hover {
            background: #A85E44;
            transform: translateY(-2px);
        }
        .warning-message {
            background: #F7EAE8;
            border-left: 4px solid #C47A5E;
            padding: 16px 20px;
            border-radius: 16px;
            color: #A55;
            font-weight: 500;
        }
        @media (max-width: 768px) {
            .cancel-card {
                padding: 24px;
            }
            .cancel-card h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<!-- Include VELORIA header parts (same as other pages) -->
<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <div class="cancel-card">
        <?php 
        $orderid = intval($_GET['oid']);
        $query = mysqli_query($con, "select orderNumber, orderStatus from orders where id='$orderid'");
        if(mysqli_num_rows($query) > 0) {
            $row = mysqli_fetch_array($query);
            $orderNumber = $row['orderNumber'];
            $status = $row['orderStatus'];
            if(empty($status)) $status = "Waiting for confirmation";
        ?>
        <h2><i class="fas fa-times-circle" style="color:#C47A5E; margin-right:12px;"></i> Cancel Order #<?php echo htmlentities($orderNumber); ?></h2>
        
        <div class="order-details">
            <p><strong>Order Number:</strong> <?php echo htmlentities($orderNumber); ?></p>
            <p><strong>Current Status:</strong> <?php echo htmlentities($status); ?></p>
        </div>

        <?php 
        // Check if order can be cancelled (based on original logic)
        $cancellableStatuses = ["", "Packed", "Dispatched", "In Transit"];
        if(in_array($row['orderStatus'], $cancellableStatuses)) {
        ?>
            <div class="info-message">
                <i class="fas fa-info-circle"></i> Please provide a reason for cancelling this order. Once cancelled, it cannot be reversed.
            </div>
            <form method="post">
                <div class="form-group">
                    <label for="restremark">Reason for Cancellation <span style="color:#C47A5E;">*</span></label>
                    <textarea name="restremark" id="restremark" class="form-control" rows="5" placeholder="Please tell us why you want to cancel this order..." required></textarea>
                </div>
                <div>
                    <button type="submit" name="submit" class="btn-cancel">Confirm Cancellation</button>
                </div>
            </form>
        <?php 
        } elseif($status == "Cancelled") { 
        ?>
            <div class="warning-message">
                <i class="fas fa-exclamation-triangle"></i> This order has already been cancelled. No further action needed.
            </div>
        <?php 
        } else { 
        ?>
            <div class="warning-message">
                <i class="fas fa-ban"></i> You cannot cancel this order. It has already been shipped or delivered.
            </div>
        <?php 
        } 
        } else { 
        ?>
            <div class="warning-message">
                <i class="fas fa-search"></i> Order not found. Please check the order ID and try again.
            </div>
        <?php } ?>
    </div>

    <!-- Brand Slider (optional, for consistency) -->
    <?php include('includes/brands-slider.php'); ?>
</div>

<!-- Footer -->
<?php include('includes/footer.php'); ?>

<!-- Scripts (preserve for Owl Carousel if needed) -->
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