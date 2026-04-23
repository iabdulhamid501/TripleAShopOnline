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
    <meta name="description" content="Shipping Information – Delivery times, costs, and policies at Triple A ShopOnline">
    <meta name="author" content="Triple A ShopOnline">
    <title>Shipping Info | Triple A ShopOnline</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <!-- Owl Carousel (for brand slider) -->
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/owl.theme.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #FCF9F5;
            color: #2A2826;
        }
        .page-header {
            background: linear-gradient(105deg, #EFE6DF 0%, #FCF9F5 100%);
            border-radius: 32px;
            padding: 48px 32px;
            margin: 32px 0 48px;
            text-align: center;
        }
        .page-header h1 {
            font-size: 2.8rem;
            font-family: 'Instrument Serif', serif;
            margin-bottom: 12px;
        }
        .page-header p {
            font-size: 1rem;
            color: #5F5A56;
        }
        .info-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .info-card h2 {
            font-family: 'Instrument Serif', serif;
            font-size: 1.8rem;
            margin-bottom: 1rem;
            border-left: 4px solid #C47A5E;
            padding-left: 15px;
        }
        .info-card h3 {
            font-weight: 600;
            font-size: 1.2rem;
            margin-top: 1.5rem;
            margin-bottom: 0.8rem;
            color: #C47A5E;
        }
        .info-card p, .info-card ul {
            color: #4A4440;
            line-height: 1.7;
        }
        .info-card ul {
            padding-left: 1.5rem;
        }
        .info-card li {
            margin-bottom: 0.5rem;
        }
        .shipping-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        .shipping-table th, .shipping-table td {
            border: 1px solid #EFE8E2;
            padding: 10px 12px;
            text-align: left;
        }
        .shipping-table th {
            background: #FCF9F5;
            font-weight: 600;
            color: #4A4440;
        }
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
        }
        .breadcrumb-custom .breadcrumb-item a {
            color: #C47A5E;
            text-decoration: none;
        }
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }
            .info-card h2 {
                font-size: 1.4rem;
            }
            .shipping-table {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>

<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mt-4">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shipping Info</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-truck" style="color:#C47A5E;"></i> Shipping Information</h1>
        <p>Fast, reliable delivery across Nigeria. Learn about our shipping options, costs, and timelines.</p>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Shipping Options Card -->
            <div class="info-card">
                <h2>Shipping Options & Costs</h2>
                <p>We partner with trusted courier services to ensure your orders arrive safely and on time. Shipping costs are calculated based on your location and the total weight of your order.</p>

                <table class="shipping-table">
                    <thead>
                        <tr><th>Shipping Method</th><th>Delivery Time</th><th>Cost (₦)</th><th>Notes</th></thead>
                    <tbody>
                        <tr><td>Standard Delivery</td><td>3–7 business days</td><td>₦1,500 – ₦7,900</td><td>Based on weight & zone</td></tr>
                        <tr><td>Express Delivery</td><td>1–2 business days</td><td>₦50,000 – ₦150,000</td><td>Available in major cities</td></tr>
                        <tr><td>Free Shipping</td><td>5–9 business days</td><td>Free</td><td>On orders over ₦350,000</td></tr>
                    </tbody>
                </table>

                <p>Exact shipping charges are displayed at checkout after you enter your address.</p>
            </div>

            <!-- Delivery Zones & Timeframes -->
            <div class="info-card">
                <h2>Delivery Timeframes by Zone</h2>
                <ul>
                    <li><strong>Lagos, Abuja, Port Harcourt</strong> – 1–3 business days (Express available next-day)</li>
                    <li><strong>Other State Capitals</strong> – 3–5 business days</li>
                    <li><strong>Remote / Rural Areas</strong> – 5–9 business days</li>
                </ul>
                <p>Please note that delivery times may be affected by public holidays, weather conditions, or unforeseen events. We will notify you of any significant delays.</p>
            </div>

            <!-- Order Processing -->
            <div class="info-card">
                <h2>Order Processing</h2>
                <p>All orders are processed within <strong>1–2 business days</strong> (excluding weekends and public holidays). You will receive a confirmation email with tracking information once your order has been shipped.</p>
                <p>If you need to make changes to your order (address, product), please contact us within 2 hours of placing the order.</p>
            </div>

            <!-- Tracking Your Order -->
            <div class="info-card">
                <h2>How to Track Your Order</h2>
                <p>After your order is shipped, we will send you an email with a tracking number and a link to the courier's tracking page. You can also track your order from your account dashboard:</p>
                <ul>
                    <li>Log into your <a href="my-account.php">My Account</a> page.</li>
                    <li>Go to <strong>Order History</strong> and click the "Track" button next to your order.</li>
                    <li>You will see real-time updates on your shipment status.</li>
                </ul>
            </div>

            <!-- International Shipping -->
            <div class="info-card">
                <h2>International Shipping</h2>
                <p>Currently, we only ship within <strong>Nigeria</strong>. We are working to expand to other African countries soon. Stay tuned for updates!</p>
            </div>

            <!-- Returns & Damaged Items (brief) -->
            <div class="info-card">
                <h2>Damaged or Lost Shipments</h2>
                <p>If your order arrives damaged, please contact us within 48 hours with photos of the damage and the packaging. We will arrange a replacement or refund. For lost packages, we will initiate a trace with the courier and provide a refund if the package cannot be located.</p>
                <p>For more details, please see our <a href="returns.php">Returns & Refunds</a> page.</p>
            </div>

            <!-- Contact Support -->
            <div class="info-card">
                <h2>Still Have Questions?</h2>
                <p>Our customer support team is here to help:</p>
                <ul>
                    <li><i class="fas fa-envelope"></i> <a href="mailto:tripleashoponline@gmail.com">tripleashoponline@gmail.com</a></li>
                    <li><i class="fas fa-phone-alt"></i> (234) 09064823328</li>
                    <li><i class="fas fa-clock"></i> Monday–Friday, 9am – 6pm (WAT)</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Brand Slider -->
    <?php include('includes/brands-slider.php'); ?>
</div>

<?php include('includes/footer.php'); ?>

<!-- Scripts -->
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>