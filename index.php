<?php
session_start();
error_reporting(0);
include('includes/config.php');

// ========== CART ADDITION HANDLER (SECURE) ==========
if(isset($_GET['action']) && $_GET['action'] == "add" && isset($_GET['id'])){
    $id = intval($_GET['id']);
    
    // Check if product exists and is in stock using prepared statement
    $stmt = mysqli_prepare($con, "SELECT id, productPrice, productAvailability FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) != 0){
        $row_p = mysqli_fetch_assoc($result);
        if($row_p['productAvailability'] == 'In Stock'){
            if(isset($_SESSION['cart'][$id])){
                $_SESSION['cart'][$id]['quantity']++;
            } else {
                $_SESSION['cart'][$id] = array("quantity" => 1, "price" => $row_p['productPrice']);
            }
            echo "<script>alert('Product has been added to the cart');</script>";
        } else {
            echo "<script>alert('Sorry, this product is out of stock');</script>";
        }
    } else {
        echo "<script>alert('Invalid product ID');</script>";
    }
    mysqli_stmt_close($stmt);
    echo "<script type='text/javascript'> document.location ='my-cart.php'; </script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="eCommerce, Shopping Portal">
    <meta name="robots" content="all">

    <title> Triple A ShopOnline</title>

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
        /* ========== VELORIA GLOBAL STYLES ========== */
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
        h1, h2, h3, h4, .logo, .hero-title, .section-title {
            font-family: 'Instrument Serif', serif;
            font-weight: 500;
            letter-spacing: -0.01em;
        }
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 24px;
        }
        /* Hero Banner */
        .veloria-hero {
            background: linear-gradient(105deg, #EFE6DF 0%, #FCF9F5 100%);
            border-radius: 28px;
            padding: 48px 40px;
            margin-bottom: 40px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
        }
        /* Adjust hero layout to accommodate video carousel */
    .veloria-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        padding: 2rem 5%;
        background: #FCF9F5;
        flex-wrap: wrap;
    }
    .hero-text {
        flex: 1;
        min-width: 280px;
    }
    .hero-text h3 {
        font-size: 1rem;
        letter-spacing: 4px;
        color: #C47A5E;
        margin-bottom: 0.5rem;
    }
    .hero-text h1 {
        font-family: 'Instrument Serif', serif;
        font-size: 3rem;
        line-height: 1.2;
        color: #2A2826;
        margin-bottom: 1rem;
    }
    .hero-text p {
        font-size: 1rem;
        color: #4A4440;
        margin-bottom: 1.5rem;
    }
    .btn-primary {
        background: #C47A5E;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 40px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: 0.2s;
    }
    .btn-primary:hover {
        background: #A85E44;
        transform: translateY(-2px);
    }
    .hero-video-carousel {
        flex: 1;
        min-width: 280px;
        position: relative;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
        background: #000;
    }
    .video-container {
        position: relative;
        width: 100%;
        padding-bottom: 100%; /* 1:1 aspect ratio – adjust as needed */
        background: #1a1a1a;
    }
    .carousel-video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 0.8s ease-in-out;
    }
    .carousel-video.active {
        opacity: 1;
        z-index: 1;
    }
    .carousel-dots {
        position: absolute;
        bottom: 12px;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        gap: 8px;
        z-index: 2;
    }
    .dot {
        width: 8px;
        height: 8px;
        background: rgba(255,255,255,0.5);
        border-radius: 50%;
        cursor: pointer;
        transition: 0.2s;
    }
    .dot.active {
        background: #C47A5E;
        width: 24px;
        border-radius: 4px;
    }
    @media (max-width: 768px) {
        .veloria-hero {
            flex-direction: column;
            text-align: center;
        }
        .hero-text h1 {
            font-size: 2.2rem;
        }
        .hero-video-carousel {
            width: 100%;
        }
    }
        .btn-primary {
            background: #C47A5E;
            border: none;
            padding: 12px 32px;
            border-radius: 40px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            display: inline-block;
            text-decoration: none;
        }
        .btn-primary:hover {
            background: #A85E44;
            transform: translateY(-2px);
        }
        /* Info boxes */
        .info-row {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            margin: 32px 0 48px;
        }
        .info-box {
            flex: 1;
            background: white;
            border-radius: 20px;
            padding: 20px 18px;
            display: flex;
            align-items: center;
            gap: 16px;
            border: 1px solid #EFE8E2;
            transition: 0.2s;
        }
        .info-box i {
            font-size: 2rem;
            color: #C47A5E;
        }
        .info-box h4 {
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
        }
        .info-box p {
            font-size: 0.75rem;
            color: #7A726C;
            margin: 0;
        }
        /* Product Grid – updated to 4 columns on large screens */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            flex-wrap: wrap;
            margin: 20px 0 28px;
        }
        .section-title {
            font-size: 2rem;
            font-weight: 500;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 28px;
            margin-bottom: 50px;
        }
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
        }
        @media (max-width: 480px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
        }
        .product-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.25s ease;
            border: 1px solid #F0E9E3;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 28px -12px rgba(0, 0, 0, 0.1);
            border-color: #DECFC4;
        }
        .product-img {
            width: 100%;
            aspect-ratio: 1 / 1.1;
            object-fit: cover;
        }
        .product-info {
            padding: 18px;
        }
        .product-cat {
            font-size: 0.7rem;
            text-transform: uppercase;
            color: #C47A5E;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }
        .product-title {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 8px;
        }
        .product-title a {
            color: #2A2826;
            text-decoration: none;
        }
        .product-title a:hover {
            color: #C47A5E;
        }
        .price {
            font-weight: 700;
            font-size: 1.2rem;
            color: #2A2826;
            margin: 10px 0;
        }
        .old-price {
            font-size: 0.8rem;
            color: #AFA49B;
            text-decoration: line-through;
            margin-left: 8px;
            font-weight: 400;
        }
        .add-btn {
            width: 100%;
            background: #F5F0EB;
            border: none;
            border-radius: 40px;
            padding: 10px;
            font-weight: 600;
            font-size: 0.8rem;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 8px;
            text-align: center;
            display: inline-block;
            text-decoration: none;
            color: #2A2826;
        }
        .add-btn:hover:not(:disabled) {
            background: #C47A5E;
            color: white;
        }
        .add-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .out-of-stock {
            background: #F7EAE8;
            color: #A67C7C;
            cursor: default;
        }
        @media (max-width: 768px) {
            .veloria-hero {
                flex-direction: column;
                padding: 32px;
            }
            .hero-text h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body oncontextmenu="return false;">

<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <div class="row" style="margin: 40px 0;">
        <!-- Sidebar (categories) -->
        <div class="col-md-3 sidebar">
            <?php include('includes/side-menu.php'); ?>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Hero Banner with Video Carousel -->
<div class="veloria-hero">
    <div class="hero-text">
        <h3>WELCOME TO</h3>
        <h1>Triple A ShopOnline<br>We sell everything</h1>
        <p>From fashion to electronics, homewares to sports – your one‑stop destination for quality and convenience.</p>
        <a href="#products" class="btn-primary">Shop Now →</a>
    </div>
    <div class="hero-video-carousel">
        <div class="video-container" id="videoContainer">
            <video class="carousel-video active" autoplay muted loop playsinline>
                <source src="carousel/carousel1.mp4" type="video/mp4" oncontextmenu="return false;">
            </video>
            <video class="carousel-video" muted loop playsinline>
                <source src="carousel/carousel2.mp4" type="video/mp4" oncontextmenu="return false;">
            </video>
            <video class="carousel-video" muted loop playsinline>
                <source src="carousel/carousel3.mp4" type="video/mp4" oncontextmenu="return false;">
            </video>
            <video class="carousel-video" muted loop playsinline>
                <source src="carousel/carousel4.mp4" type="video/mp4">
            </video>
            <video class="carousel-video" muted loop playsinline>
                <source src="carousel/carousel5.mp4" type="video/mp4">
            </video>
            <video class="carousel-video" muted loop playsinline>
                <source src="carousel/carousel6.mp4" type="video/mp4">
            </video>
            <video class="carousel-video" muted loop playsinline>
                <source src="carousel/carousel7.mp4" type="video/mp4">
            </video>
        </div>
        <!-- Optional navigation dots -->
        <div class="carousel-dots" id="carouselDots"></div>
    </div>
</div>

            <!-- Info Boxes -->
            <div class="info-row">
                <div class="info-box"><i class="fas fa-truck"></i><div><h4>Fast & free</h4><p>Free shipping over ₦350,000</p></div></div>
                <div class="info-box"><i class="fas fa-rotate-left"></i><div><h4>3 days return</h4><p>No questions asked</p></div></div>
                <div class="info-box"><i class="fas fa-gem"></i><div><h4>Quality assured</h4><p>Authentic products</p></div></div>
            </div>

            <!-- Products Section -->
            <div class="section-header" id="products">
                <h2 class="section-title">All Products</h2>
                <span style="font-size:0.8rem; color:#C47A5E;">Timeless & minimal</span>
            </div>
            <div class="product-grid">
                <?php
                // Fetch products with category names using LEFT JOIN
                $query = "SELECT p.*, c.categoryName 
                          FROM products p 
                          LEFT JOIN category c ON p.category = c.id 
                          ORDER BY p.id DESC";
                $ret = mysqli_query($con, $query);
                if(mysqli_num_rows($ret) > 0){
                    while ($row = mysqli_fetch_assoc($ret)) {
                        // Determine image path (try subfolder first, then fallback to direct)
                        $image_path = "admin/productimages/" . $row['id'] . "/" . $row['productImage1'];
                        if(!file_exists($image_path) || empty($row['productImage1'])){
                            $image_path = "admin/productimages/placeholder.jpg";
                        }
                        $category_name = !empty($row['categoryName']) ? htmlentities($row['categoryName']) : 'Uncategorized';
                        $product_price = number_format($row['productPrice']);
                        $old_price = !empty($row['productPriceBeforeDiscount']) ? number_format($row['productPriceBeforeDiscount']) : '';
                ?>
                <div class="product-card">
                    <a href="product-details.php?pid=<?php echo htmlentities($row['id']); ?>">
                        <img class="product-img" src="<?php echo $image_path; ?>" alt="<?php echo htmlentities($row['productName']); ?>">
                    </a>
                    <div class="product-info">
                        <div class="product-cat"><?php echo $category_name; ?></div>
                        <div class="product-title"><a href="product-details.php?pid=<?php echo htmlentities($row['id']); ?>"><?php echo htmlentities($row['productName']); ?></a></div>
                        <div class="price">
                            ₦<?php echo $product_price; ?>
                            <?php if($old_price): ?>
                            <span class="old-price">₦<?php echo $old_price; ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if($row['productAvailability'] == 'In Stock'): ?>
                        <a href="index.php?action=add&id=<?php echo $row['id']; ?>" class="add-btn">Add to Cart</a>
                        <?php else: ?>
                        <div class="add-btn out-of-stock">Out of Stock</div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    echo '<div class="col-12 text-center">No products found.</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Brand Slider -->
    <?php include('includes/brands-slider.php'); ?>
</div>

<!-- Footer -->
<?php include('includes/footer.php'); ?>

<!-- Scripts -->
<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/echo.min.js"></script>
<script src="assets/js/wow.min.js"></script>
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

        // Hero video carousel logic
        (function() {
        const videos = document.querySelectorAll('.carousel-video');
        if (!videos.length) return;
        
        let currentIndex = 0;
        const totalVideos = videos.length;
        let interval;
        
        // Create dots
        const dotsContainer = document.getElementById('carouselDots');
        if (dotsContainer) {
            for (let i = 0; i < totalVideos; i++) {
                const dot = document.createElement('div');
                dot.classList.add('dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => {
                    clearInterval(interval);
                    showVideo(i);
                    startInterval();
                });
                dotsContainer.appendChild(dot);
            }
        }
        
        function showVideo(index) {
            // Remove active class from all videos and dots
            videos.forEach((video, i) => {
                video.classList.remove('active');
                if (i === index) {
                    video.classList.add('active');
                    // Ensure video plays (some browsers need manual play after visibility)
                    video.play().catch(e => console.log("Autoplay prevented:", e));
                } else {
                    video.pause();
                }
            });
            const dots = document.querySelectorAll('.dot');
            dots.forEach((dot, i) => {
                if (i === index) dot.classList.add('active');
                else dot.classList.remove('active');
            });
            currentIndex = index;
        }
        
        function nextVideo() {
            let next = (currentIndex + 1) % totalVideos;
            showVideo(next);
        }
        
        function startInterval() {
            if (interval) clearInterval(interval);
            interval = setInterval(nextVideo, 8000); // switch every 8 seconds
        }
        
        // Start with first video playing
        showVideo(0);
        startInterval();
        
        // Pause interval when user hovers over carousel (optional)
        const container = document.querySelector('.hero-video-carousel');
        if (container) {
            container.addEventListener('mouseenter', () => clearInterval(interval));
            container.addEventListener('mouseleave', startInterval);
        }
    })();
    });
</script>
</body>
</html>