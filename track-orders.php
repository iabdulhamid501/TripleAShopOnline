<?php
session_start();
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="Track your orders - Triple A ShopOnline">
    <meta name="author" content="Triple A ShopOnline">
    <title>Track Orders | Triple A ShopOnline</title>

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
        h1, h2, h3 {
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
        .track-card {
            background: white;
            border-radius: 28px;
            border: 1px solid #EFE8E2;
            padding: 40px 32px;
            margin-bottom: 48px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }
        .track-card h2 {
            font-size: 2rem;
            margin-bottom: 12px;
            color: #2A2826;
        }
        .track-card .subtitle {
            color: #7A726C;
            font-size: 0.9rem;
            margin-bottom: 32px;
        }
        .form-group {
            margin-bottom: 24px;
            text-align: left;
        }
        .form-group label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4A4440;
            margin-bottom: 6px;
            display: block;
        }
        .form-control {
            width: 100%;
            padding: 12px 16px;
            font-size: 0.9rem;
            border: 1px solid #E0D6CE;
            border-radius: 20px;
            background: #FFFFFF;
            transition: 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .form-control:focus {
            outline: none;
            border-color: #C47A5E;
            box-shadow: 0 0 0 3px rgba(196,122,94,0.1);
        }
        .btn-track {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 12px 32px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: 0.2s;
            cursor: pointer;
        }
        .btn-track:hover {
            background: #A85E44;
            transform: translateY(-2px);
        }
        @media (max-width: 768px) {
            .track-card {
                padding: 28px 20px;
            }
            .track-card h2 {
                font-size: 1.6rem;
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
            <li class="active">Track Your Order</li>
        </ul>
    </div>

    <div class="track-card">
        <h2><i class="fas fa-search-location" style="color:#C47A5E;"></i> Track Your Order</h2>
        <p class="subtitle">Please enter your Order ID and the email address used at checkout. This information was provided on your receipt and in the confirmation email.</p>
        
        <form class="register-form" method="post" action="order-details.php">
            <div class="form-group">
                <label for="exampleOrderId1">Order ID <span style="color:#C47A5E;">*</span></label>
                <input type="text" class="form-control" name="orderid" id="exampleOrderId1" placeholder="e.g., 12345" required>
            </div>
            <div class="form-group">
                <label for="exampleBillingEmail1">Registered Email <span style="color:#C47A5E;">*</span></label>
                <input type="email" class="form-control" name="email" id="exampleBillingEmail1" placeholder="you@example.com" required>
            </div>
            <button type="submit" name="submit" class="btn-track">Track Order <i class="fas fa-arrow-right"></i></button>
        </form>
    </div>

    <!-- Brand Slider (horizontal) -->
    <?php include('includes/brands-slider.php'); ?>
</div>

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
</script>
</body>
</html>