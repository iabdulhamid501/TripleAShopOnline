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
    <meta name="description" content="Returns & Refunds Policy – Triple A ShopOnline">
    <meta name="author" content="Triple A ShopOnline">
    <title>Returns & Refunds | Triple A ShopOnline</title>

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
        .policy-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .policy-card h2 {
            font-family: 'Instrument Serif', serif;
            font-size: 1.8rem;
            margin-bottom: 1rem;
            border-left: 4px solid #C47A5E;
            padding-left: 15px;
        }
        .policy-card h3 {
            font-weight: 600;
            font-size: 1.2rem;
            margin-top: 1.5rem;
            margin-bottom: 0.8rem;
            color: #C47A5E;
        }
        .policy-card p, .policy-card ul {
            color: #4A4440;
            line-height: 1.7;
        }
        .policy-card ul {
            padding-left: 1.5rem;
        }
        .policy-card li {
            margin-bottom: 0.5rem;
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
            .policy-card h2 {
                font-size: 1.4rem;
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
            <li class="breadcrumb-item active" aria-current="page">Returns & Refunds</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-undo-alt" style="color:#C47A5E;"></i> Returns & Refunds</h1>
        <p>We want you to love your purchase. If you're not completely satisfied, we're here to help.</p>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Return Policy Card -->
            <div class="policy-card">
                <h2>Return Policy</h2>
                <p>At Triple A ShopOnline, customer satisfaction is our top priority. If you are not entirely happy with your purchase, you may return eligible items within <strong>30 days</strong> of delivery.</p>
                
                <h3>Eligibility for Returns</h3>
                <ul>
                    <li>Items must be unused, unworn, and in the same condition as received.</li>
                    <li>Original packaging, tags, and accessories must be intact.</li>
                    <li>Products must not be damaged due to misuse or improper handling.</li>
                    <li>Certain items (e.g., perishable goods, intimate apparel, gift cards) are non-returnable.</li>
                </ul>

                <h3>How to Initiate a Return</h3>
                <ul>
                    <li>Contact our customer support at <a href="mailto:tripleashoponline@gmail.com">tripleashoponline@gmail.com</a> with your order number and reason for return.</li>
                    <li>We will provide you with a return authorization and instructions on where to send the item.</li>
                    <li>Pack the item securely, include the original invoice, and ship to the address provided.</li>
                    <li>We recommend using a trackable shipping service – return shipping costs are the customer’s responsibility unless the item arrived defective or incorrect.</li>
                </ul>

                <h3>Refund Process</h3>
                <ul>
                    <li>Once we receive and inspect your return, we will notify you of the approval or rejection of your refund.</li>
                    <li>If approved, refunds will be processed to your original payment method within <strong>5–7 business days</strong>.</li>
                    <li>For COD orders, refunds will be issued via bank transfer or store credit (as per your preference).</li>
                    <li>Shipping charges are non-refundable unless the return is due to our error (wrong or defective item).</li>
                </ul>

                <h3>Exchanges</h3>
                <p>If you need to exchange an item for a different size or colour, please initiate a return for the original item and place a new order for the desired product. This ensures faster processing.</p>

                <h3>Damaged or Incorrect Items</h3>
                <p>If you receive a damaged, defective, or wrong item, please contact us within <strong>48 hours</strong> of delivery. We will arrange a free return and send a replacement as soon as possible.</p>

                <h3>Contact Us</h3>
                <p>If you have any questions about returns or refunds, please reach out to our support team:</p>
                <ul>
                    <li><i class="fas fa-envelope"></i> <a href="mailto:tripleashoponline@gmail.com">tripleashoponline@gmail.com</a></li>
                    <li><i class="fas fa-phone-alt"></i> (234) 09064823328</li>
                    <li><i class="fas fa-clock"></i> Monday–Friday, 9am – 6pm (WAT)</li>
                </ul>
            </div>

            <!-- Additional Info Card -->
            <div class="policy-card">
                <h2>Refund Timing</h2>
                <p>After your return is approved, refunds are typically processed within 5–7 business days. Depending on your bank or credit card issuer, it may take additional time for the credit to appear in your account. We appreciate your patience.</p>
                <p><strong>Store credit</strong> refunds are issued immediately as a coupon code that can be used on future purchases.</p>
                <p><strong>Note:</strong> Sale items may be subject to a different return policy – please check the product page or contact us for details.</p>
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