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
    <meta name="description" content="Contact Triple A ShopOnline – get in touch with us" />
    <meta name="author" content="Triple A ShopOnline" />
    <title>Contact Us | Triple A ShopOnline</title>

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
        h1, h2, h3, h4 {
            font-family: 'Instrument Serif', serif;
            font-weight: 500;
            letter-spacing: -0.01em;
        }
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 24px;
        }
        /* Breadcrumb */
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
        /* Contact Hero */
        .contact-hero {
            background: linear-gradient(105deg, #EFE6DF 0%, #FCF9F5 100%);
            border-radius: 32px;
            padding: 48px 32px;
            margin: 32px 0 48px;
            text-align: center;
        }
        .contact-hero h1 {
            font-size: 2.8rem;
            margin-bottom: 8px;
            color: #2A2826;
        }
        .contact-hero p {
            font-size: 1rem;
            color: #5F5A56;
            max-width: 600px;
            margin: 0 auto;
        }
        /* Contact Info Grid */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 28px;
            margin: 40px 0;
        }
        .contact-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 28px 24px;
            text-align: center;
            transition: 0.2s;
        }
        .contact-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.05);
            border-color: #DECFC4;
        }
        .contact-icon {
            font-size: 2.5rem;
            color: #C47A5E;
            margin-bottom: 16px;
        }
        .contact-card h3 {
            font-size: 1.3rem;
            margin-bottom: 12px;
        }
        .contact-card p {
            color: #4A4440;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }
        .contact-card a {
            color: #C47A5E;
            text-decoration: none;
        }
        .contact-card a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .contact-hero h1 {
                font-size: 2rem;
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
    <!-- Breadcrumb -->
    <div class="veloria-breadcrumb">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><i class="fas fa-chevron-right"></i></li>
            <li class="active">Contact Us</li>
        </ul>
    </div>

    <!-- Contact Hero -->
    <div class="contact-hero">
        <h1><i class="fas fa-envelope" style="color:#C47A5E;"></i> Get in Touch</h1>
        <p>We’d love to hear from you! Reach out with any questions, feedback, or support requests.</p>
    </div>

    <!-- Contact Information Cards -->
    <div class="contact-grid">
        <div class="contact-card">
            <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
            <h3>Our Address</h3>
            <p>No 3 Anthony Ukpocrescent,</p>
            <p>National Assembly Quarters Zone A,</p>
            <p>Federal Capital Territory,<br> Abuja, Nigeria</p>
        </div>
        <div class="contact-card">
            <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
            <h3>Phone Numbers</h3>
            <p>+91 12345 7890</p>
            <p>+91 11 2233 4455</p>
        </div>
        <div class="contact-card">
            <div class="contact-icon"><i class="fas fa-envelope"></i></div>
            <h3>Email Us</h3>
            <p><a href="mailto:info@tripleashoponline.com">info@tripleashoponline.com</a></p>
            <p><a href="mailto:support@tripleashoponline.com">support@tripleashoponline.com</a></p>
        </div>
    </div>

    <!-- Brand Slider -->
    <?php include('includes/brands-slider.php'); ?>
</div>

<!-- Footer -->
<?php include('includes/footer.php'); ?>

<!-- Scripts (preserve for Owl Carousel) -->
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