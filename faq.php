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
    <meta name="description" content="Frequently Asked Questions – Triple A ShopOnline">
    <meta name="author" content="Triple A ShopOnline">
    <title>FAQ | Triple A ShopOnline</title>

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
        .faq-header {
            background: linear-gradient(105deg, #EFE6DF 0%, #FCF9F5 100%);
            border-radius: 32px;
            padding: 48px 32px;
            margin: 32px 0 48px;
            text-align: center;
        }
        .faq-header h1 {
            font-size: 2.8rem;
            font-family: 'Instrument Serif', serif;
            margin-bottom: 12px;
        }
        .faq-header p {
            font-size: 1rem;
            color: #5F5A56;
        }
        .faq-accordion .accordion-item {
            background: white;
            border: 1px solid #EFE8E2;
            border-radius: 24px !important;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .faq-accordion .accordion-button {
            background: white;
            font-weight: 600;
            color: #2A2826;
            padding: 1.2rem 1.5rem;
            font-size: 1rem;
            border: none;
            box-shadow: none;
        }
        .faq-accordion .accordion-button:not(.collapsed) {
            background: #FCF9F5;
            color: #C47A5E;
        }
        .faq-accordion .accordion-button:focus {
            box-shadow: none;
            border-color: transparent;
        }
        .faq-accordion .accordion-body {
            padding: 1rem 1.5rem 1.5rem;
            color: #4A4440;
            font-size: 0.9rem;
            line-height: 1.6;
            border-top: 1px solid #F0E9E3;
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
            .faq-header h1 {
                font-size: 2rem;
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
            <li class="breadcrumb-item active" aria-current="page">FAQ</li>
        </ol>
    </nav>

    <!-- FAQ Header -->
    <div class="faq-header">
        <h1><i class="fas fa-question-circle" style="color:#C47A5E;"></i> Frequently Asked Questions</h1>
        <p>Find answers to the most common questions about shopping with us.</p>
    </div>

    <!-- FAQ Accordion -->
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="accordion faq-accordion" id="faqAccordion">

                <!-- Question 1 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            How do I place an order?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Simply browse our products, click "Add to Cart" on the items you like, then go to your cart and proceed to checkout. Fill in your address, choose a payment method, and confirm your order. You will receive an email confirmation.
                        </div>
                    </div>
                </div>

                <!-- Question 2 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            What payment methods do you accept?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We accept Cash on Delivery (COD), Internet Banking, Debit/Credit Cards, and Paystack (cards, bank transfer, USSD). All payments are secure.
                        </div>
                    </div>
                </div>

                <!-- Question 3 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            How long does shipping take?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Standard delivery usually takes 3–7 business days, depending on your location. You will receive a tracking link once your order is dispatched.
                        </div>
                    </div>
                </div>

                <!-- Question 4 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Can I return or exchange a product?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, we offer a 30-day return policy for unused items in original packaging. Please contact our support team to initiate a return or exchange.
                        </div>
                    </div>
                </div>

                <!-- Question 5 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            How do I track my order?
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            After logging into your account, go to "Order History". Click the "Track" button next to your order to see real‑time status updates.
                        </div>
                    </div>
                </div>

                <!-- Question 6 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSix">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            Is my personal information secure?
                        </button>
                    </h2>
                    <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Absolutely. We use SSL encryption and never store your full payment details. Your data is used only to process orders and improve your experience.
                        </div>
                    </div>
                </div>

                <!-- Question 7 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSeven">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            Do you offer international shipping?
                        </button>
                    </h2>
                    <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Currently, we only ship within Nigeria. We are working on expanding to other countries soon.
                        </div>
                    </div>
                </div>

                <!-- Question 8 -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingEight">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            How can I contact customer support?
                        </button>
                    </h2>
                    <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            You can reach us via email at <a href="mailto:tripleashoponline@gmail.com">tripleashoponline@gmail.com</a> or call us at (234) 09064823328. Our support team is available Monday–Friday, 9am–6pm.
                        </div>
                    </div>
                </div>

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