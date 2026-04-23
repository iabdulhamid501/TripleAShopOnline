<?php 
session_start();
include_once('includes/config.php');
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="About Triple A ShopOnline – your trusted e-commerce partner in Nigeria." />
    <meta name="author" content="Triple A ShopOnline" />
    <title>About Us | Triple A ShopOnline</title>

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
        /* About hero section */
        .about-hero {
            background: linear-gradient(105deg, #EFE6DF 0%, #FCF9F5 100%);
            border-radius: 32px;
            padding: 64px 48px;
            margin: 48px 0 32px;
            text-align: center;
        }
        .about-hero h1 {
            font-size: 3rem;
            color: #2A2826;
            margin-bottom: 16px;
        }
        .about-hero p {
            font-size: 1.1rem;
            color: #5F5A56;
            max-width: 700px;
            margin: 0 auto;
        }
        /* Content sections */
        .about-content {
            display: flex;
            flex-wrap: wrap;
            gap: 48px;
            margin: 48px 0 64px;
        }
        .about-text {
            flex: 2;
        }
        .about-text p {
            margin-bottom: 20px;
            font-size: 1rem;
            color: #4A4440;
            line-height: 1.7;
        }
        .about-text h2 {
            font-size: 1.8rem;
            margin: 32px 0 16px;
            color: #2A2826;
        }
        .about-text h2:first-of-type {
            margin-top: 0;
        }
        .about-image {
            flex: 1;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 12px 28px rgba(0,0,0,0.05);
            border: 1px solid #EFE8E2;
        }
        .about-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        /* Feature list */
        .feature-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 28px;
            margin: 48px 0;
        }
        .feature-item {
            background: white;
            border-radius: 24px;
            padding: 28px 24px;
            text-align: center;
            border: 1px solid #EFE8E2;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .feature-item:hover {
            transform: translateY(-6px);
            border-color: #C47A5E;
            box-shadow: 0 12px 28px rgba(0,0,0,0.08);
        }
        .feature-item i {
            font-size: 2.5rem;
            color: #C47A5E;
            margin-bottom: 16px;
        }
        .feature-item h3 {
            font-size: 1.3rem;
            margin-bottom: 12px;
        }
        .feature-item p {
            font-size: 0.85rem;
            color: #7A726C;
        }
        @media (max-width: 768px) {
            .about-hero {
                padding: 40px 24px;
            }
            .about-hero h1 {
                font-size: 2.2rem;
            }
            .about-content {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <!-- Hero Section -->
    <div class="about-hero">
        <h1>About Triple A ShopOnline</h1>
        <p>Your trusted partner for quality products, convenience, and exceptional service in Nigeria and beyond.</p>
    </div>

    <!-- Main Content -->
    <div class="about-content">
        <div class="about-text">
            <p>E-commerce is revolutionizing the way we all shop in Nigeria. Why hop from one store to another in search of the latest phone when you can find it on the Internet in a single click? Not only mobiles – Triple A ShopOnline houses everything you can possibly imagine, from trending electronics like laptops, tablets, smartphones, and mobile accessories to in‑vogue fashion staples like shoes, clothing and lifestyle accessories; from modern furniture like sofa sets, dining tables, and wardrobes to appliances that make your life easy like washing machines, TVs, ACs, mixer grinder juicers and other time‑saving kitchen and small appliances; from home furnishings like cushion covers, mattresses and bedsheets to toys and musical instruments – we've got them all covered.</p>
            
            <p>For those of you with erratic working hours, Triple A ShopOnline is your best bet. Shop in your PJs, at night or in the wee hours of the morning. This e‑commerce never shuts down.</p>
            
            <h2>Why shop with us?</h2>
            <p>What's more, with our year‑round shopping festivals and events, our prices are irresistible. We're sure you'll find yourself picking up more than what you had in mind. If you are wondering why you should shop from Triple A ShopOnline when there are multiple options available to you, well, the reasons are simple: <strong>trust, convenience, and quality.</strong></p>
            
            <p>We are a Nigerian brand committed to bringing you authentic products at fair prices, with fast delivery and dedicated customer support. Every purchase is backed by our 30‑day return policy and secure payment options.</p>
        </div>
        <div class="about-image">
            <img src="https://picsum.photos/id/20/600/500" alt="Shopping experience" loading="lazy">
        </div>
    </div>

    <!-- Why Choose Us (Feature Grid) -->
    <div class="feature-list">
        <div class="feature-item">
            <i class="fas fa-truck-fast"></i>
            <h3>Fast Delivery</h3>
            <p>Express shipping across Nigeria. Get your order in 2‑5 business days.</p>
        </div>
        <div class="feature-item">
            <i class="fas fa-shield-alt"></i>
            <h3>Secure Payments</h3>
            <p>Multiple payment options with SSL encryption and buyer protection.</p>
        </div>
        <div class="feature-item">
            <i class="fas fa-undo-alt"></i>
            <h3>Easy Returns</h3>
            <p>30‑day hassle‑free returns. Your satisfaction is guaranteed.</p>
        </div>
        <div class="feature-item">
            <i class="fas fa-headset"></i>
            <h3>24/7 Support</h3>
            <p>Dedicated customer care team ready to assist you anytime.</p>
        </div>
    </div>
</div>

<!-- Brand Slider (horizontal) -->
<?php include('includes/brands-slider.php'); ?>

<!-- Footer -->
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