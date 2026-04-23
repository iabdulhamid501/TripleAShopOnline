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
    <meta name="description" content="Terms of Service – Rules and guidelines for using Triple A ShopOnline">
    <meta name="author" content="Triple A ShopOnline">
    <title>Terms of Service | Triple A ShopOnline</title>

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
        .terms-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .terms-card h2 {
            font-family: 'Instrument Serif', serif;
            font-size: 1.8rem;
            margin-bottom: 1rem;
            border-left: 4px solid #C47A5E;
            padding-left: 15px;
        }
        .terms-card h3 {
            font-weight: 600;
            font-size: 1.2rem;
            margin-top: 1.5rem;
            margin-bottom: 0.8rem;
            color: #C47A5E;
        }
        .terms-card p, .terms-card ul {
            color: #4A4440;
            line-height: 1.7;
        }
        .terms-card ul {
            padding-left: 1.5rem;
        }
        .terms-card li {
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
            .terms-card h2 {
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
            <li class="breadcrumb-item active" aria-current="page">Terms of Service</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-file-contract" style="color:#C47A5E;"></i> Terms of Service</h1>
        <p>Please read these terms carefully before using our website or placing an order.</p>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="terms-card">
                <h2>1. Acceptance of Terms</h2>
                <p>By accessing or using the Triple A ShopOnline website (the "Site"), you agree to be bound by these Terms of Service ("Terms"). If you do not agree to all of these Terms, please do not use the Site.</p>

                <h2>2. Changes to Terms</h2>
                <p>We reserve the right to update or modify these Terms at any time without prior notice. Your continued use of the Site after any changes constitutes acceptance of the new Terms. It is your responsibility to review this page periodically.</p>

                <h2>3. Account Registration</h2>
                <p>To place an order, you may be required to create an account. You agree to provide accurate, current, and complete information. You are responsible for maintaining the confidentiality of your login credentials and for all activities under your account. Notify us immediately of any unauthorized use.</p>

                <h2>4. Products and Pricing</h2>
                <ul>
                    <li>All product descriptions, images, and prices are subject to change without notice.</li>
                    <li>We strive to display accurate colors and details, but we cannot guarantee that your monitor will show them exactly.</li>
                    <li>Prices are in Nigerian Naira (₦) and include applicable taxes unless stated otherwise.</li>
                    <li>We reserve the right to correct any pricing errors and to cancel or refuse any orders affected by such errors.</li>
                </ul>

                <h2>5. Orders and Acceptance</h2>
                <p>After you place an order, you will receive an email acknowledgment. This does not constitute acceptance of your order. We reserve the right to refuse or cancel any order for any reason, including product availability, payment issues, or suspected fraud. If we cancel an order after payment, we will issue a full refund.</p>

                <h2>6. Shipping and Delivery</h2>
                <p>Shipping times and costs are provided at checkout. Estimated delivery dates are not guaranteed. We are not liable for delays caused by courier services, weather, or other events beyond our control. Risk of loss passes to you upon delivery.</p>

                <h2>7. Returns and Refunds</h2>
                <p>Please refer to our <a href="returns.php">Returns & Refunds</a> policy for detailed information. Returns must comply with the conditions stated therein.</p>

                <h2>8. Payment Methods</h2>
                <p>We accept Cash on Delivery (COD), Internet Banking, Debit/Credit Cards, and Paystack. By providing payment information, you represent that you are authorised to use the selected payment method. All transactions are processed securely; we do not store full payment details.</p>

                <h2>9. Intellectual Property</h2>
                <p>All content on the Site (text, graphics, logos, images, software, etc.) is the property of Triple A ShopOnline or its licensors and is protected by copyright, trademark, and other laws. You may not reproduce, distribute, or create derivative works without our written permission.</p>

                <h2>10. User Conduct</h2>
                <p>You agree not to:</p>
                <ul>
                    <li>Use the Site for any illegal or unauthorized purpose.</li>
                    <li>Interfere with or disrupt the Site's security or functionality.</li>
                    <li>Upload malicious code or viruses.</li>
                    <li>Harvest or collect personal information of other users.</li>
                    <li>Post false or misleading information.</li>
                </ul>

                <h2>11. Third-Party Links</h2>
                <p>The Site may contain links to third-party websites. We are not responsible for the content or practices of those sites. Your use of linked sites is at your own risk.</p>

                <h2>12. Disclaimer of Warranties</h2>
                <p>The Site and all products and services are provided "as is" without warranties of any kind, either express or implied. To the fullest extent permitted by law, we disclaim all warranties, including merchantability, fitness for a particular purpose, and non‑infringement.</p>

                <h2>13. Limitation of Liability</h2>
                <p>To the maximum extent permitted by law, Triple A ShopOnline shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of the Site or products purchased, even if advised of the possibility of such damages. Our total liability shall not exceed the amount you paid for the product giving rise to the claim.</p>

                <h2>14. Indemnification</h2>
                <p>You agree to indemnify and hold Triple A ShopOnline and its employees, affiliates, and partners harmless from any claims, damages, or expenses arising from your violation of these Terms or any applicable laws.</p>

                <h2>15. Governing Law</h2>
                <p>These Terms shall be governed by and construed in accordance with the laws of the Federal Republic of Nigeria. Any disputes arising from these Terms shall be subject to the exclusive jurisdiction of the courts of FCT Abuja.</p>

                <h2>16. Termination</h2>
                <p>We may terminate or suspend your account and access to the Site immediately, without prior notice, for conduct that violates these Terms or is harmful to other users or us.</p>

                <h2>17. Contact Us</h2>
                <p>If you have any questions about these Terms, please contact us:</p>
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