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
    <meta name="description" content="Privacy Policy – How Triple A ShopOnline protects your personal information">
    <meta name="author" content="Triple A ShopOnline">
    <title>Privacy Policy | Triple A ShopOnline</title>

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
        .last-updated {
            font-size: 0.85rem;
            color: #7A726C;
            text-align: center;
            margin-top: 1rem;
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
            <li class="breadcrumb-item active" aria-current="page">Privacy Policy</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-shield-alt" style="color:#C47A5E;"></i> Privacy Policy</h1>
        <p>Your privacy is important to us. Learn how we collect, use, and protect your information.</p>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="policy-card">
                <h2>Introduction</h2>
                <p>Triple A ShopOnline ("we", "us", "our") respects your privacy and is committed to protecting your personal data. This privacy policy explains how we handle your information when you visit our website, make a purchase, or interact with our services.</p>
                <p>Please read this policy carefully. By using our website, you agree to the collection and use of information in accordance with this policy.</p>

                <h2>Information We Collect</h2>
                <p>We may collect the following types of information:</p>
                <ul>
                    <li><strong>Personal Identification Information:</strong> Name, email address, phone number, shipping/billing address, payment details.</li>
                    <li><strong>Order Information:</strong> Products purchased, order history, preferences.</li>
                    <li><strong>Technical Data:</strong> IP address, browser type, device information, pages visited, time spent on site.</li>
                    <li><strong>Cookies and Tracking Technologies:</strong> We use cookies to enhance your experience and analyse site traffic.</li>
                </ul>

                <h2>How We Use Your Information</h2>
                <p>We use your information for the following purposes:</p>
                <ul>
                    <li>To process and fulfill your orders (payment, shipping, customer support).</li>
                    <li>To communicate with you about order status, promotions, and updates (you may opt out of marketing emails).</li>
                    <li>To improve our website, products, and services.</li>
                    <li>To prevent fraud and ensure security of transactions.</li>
                    <li>To comply with legal obligations.</li>
                </ul>

                <h2>Sharing Your Information</h2>
                <p>We do not sell or rent your personal data to third parties. However, we may share your information with:</p>
                <ul>
                    <li><strong>Service Providers:</strong> Payment processors, shipping couriers, IT support – only to the extent necessary to complete your transaction.</li>
                    <li><strong>Legal Authorities:</strong> When required by law or to protect our rights and safety.</li>
                    <li><strong>Business Transfers:</strong> In the event of a merger or acquisition, your data may be transferred.</li>
                </ul>

                <h2>Cookies and Tracking</h2>
                <p>We use cookies to remember your preferences, analyse site usage, and personalise content. You can disable cookies in your browser settings, but some features of our site may not function properly.</p>
                <p>Third-party services (e.g., Paystack, Google Analytics) may also set cookies. Refer to their respective privacy policies for details.</p>

                <h2>Data Security</h2>
                <p>We implement industry-standard security measures (SSL encryption, firewalls, access controls) to protect your data. However, no method of transmission over the internet is 100% secure. While we strive to protect your personal information, we cannot guarantee absolute security.</p>

                <h2>Your Rights</h2>
                <p>Depending on your location, you may have the following rights regarding your personal data:</p>
                <ul>
                    <li>Access – Request a copy of the data we hold about you.</li>
                    <li>Correction – Request corrections to inaccurate or incomplete data.</li>
                    <li>Deletion – Request deletion of your data (subject to legal retention requirements).</li>
                    <li>Objection – Opt out of marketing communications.</li>
                    <li>Data Portability – Receive your data in a structured, machine-readable format.</li>
                </ul>
                <p>To exercise any of these rights, please contact us at <a href="mailto:tripleashoponline@gmail.com">tripleashoponline@gmail.com</a>.</p>

                <h2>Data Retention</h2>
                <p>We retain your personal data only as long as necessary to fulfill the purposes outlined in this policy, unless a longer retention period is required by law (e.g., for tax or legal records).</p>

                <h2>Children's Privacy</h2>
                <p>Our website is not intended for children under 13. We do not knowingly collect personal information from children. If you believe we have inadvertently collected such data, please contact us immediately.</p>

                <h2>Third-Party Links</h2>
                <p>Our website may contain links to external sites (e.g., payment gateways, social media). We are not responsible for the privacy practices of those sites. We encourage you to read their privacy policies.</p>

                <h2>Changes to This Policy</h2>
                <p>We may update this privacy policy from time to time. Any changes will be posted on this page with an updated "Last Updated" date. We encourage you to review this policy periodically.</p>

                <h2>Contact Us</h2>
                <p>If you have any questions about this privacy policy or how we handle your data, please contact us:</p>
                <ul>
                    <li><i class="fas fa-envelope"></i> <a href="mailto:tripleashoponline@gmail.com">tripleashoponline@gmail.com</a></li>
                    <li><i class="fas fa-phone-alt"></i> (234) 09064823328</li>
                    <li><i class="fas fa-map-marker-alt"></i> FCT Abuja, Nigeria</li>
                </ul>
                <div class="last-updated">Last Updated: April 10, 2026</div>
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