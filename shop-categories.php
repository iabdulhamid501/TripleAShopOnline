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
    <meta name="description" content="Browse all product categories at Triple A ShopOnline" />
    <meta name="author" content="Triple A ShopOnline" />
    <title>Shop Categories | Triple A ShopOnline</title>

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
        /* Categories Header */
        .categories-header {
            background: linear-gradient(105deg, #EFE6DF 0%, #FCF9F5 100%);
            border-radius: 32px;
            padding: 48px 32px;
            margin: 32px 0 48px;
            text-align: center;
        }
        .categories-header h1 {
            font-size: 2.8rem;
            margin-bottom: 8px;
            color: #2A2826;
        }
        .categories-header p {
            font-size: 1rem;
            color: #5F5A56;
        }
        /* Category Grid */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 28px;
            margin: 40px 0;
        }
        .category-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.25s ease;
            border: 1px solid #F0E9E3;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            text-align: center;
            padding: 28px 20px;
        }
        .category-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 28px -12px rgba(0,0,0,0.1);
            border-color: #DECFC4;
        }
        .category-icon {
            font-size: 3rem;
            color: #C47A5E;
            margin-bottom: 16px;
        }
        .category-card h3 {
            font-size: 1.4rem;
            margin-bottom: 12px;
            color: #2A2826;
        }
        .category-card p {
            font-size: 0.85rem;
            color: #7A726C;
            margin-bottom: 20px;
        }
        .btn-view {
            background: #F5F0EB;
            border: none;
            border-radius: 40px;
            padding: 8px 24px;
            font-weight: 600;
            font-size: 0.8rem;
            text-decoration: none;
            color: #2A2826;
            display: inline-block;
            transition: 0.2s;
        }
        .btn-view:hover {
            background: #C47A5E;
            color: white;
        }
        @media (max-width: 768px) {
            .categories-header h1 {
                font-size: 2rem;
            }
            .category-grid {
                gap: 16px;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }
    </style>
</head>
<body>

<!-- VELORIA Header (using your converted includes) -->
<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <!-- Breadcrumb -->
    <div class="veloria-breadcrumb">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><i class="fas fa-chevron-right"></i></li>
            <li class="active">Shop Categories</li>
        </ul>
    </div>

    <!-- Categories Header -->
    <div class="categories-header">
        <h1><i class="fas fa-th-large" style="color:#C47A5E;"></i> Shop Categories</h1>
        <p>Browse our product collections by category – find exactly what you're looking for.</p>
    </div>

    <!-- Category Grid -->
    <div class="category-grid">
        <?php
        $query = mysqli_query($con, "select category.id as catid, category.categoryName from category");
        if(mysqli_num_rows($query) > 0) {
            while($row = mysqli_fetch_array($query)) {
        ?>
        <div class="category-card">
            <div class="category-icon">
                <i class="fas fa-tag"></i>
            </div>
            <h3><?php echo htmlspecialchars($row['categoryName']); ?></h3>
            <p>Explore <?php echo htmlspecialchars($row['categoryName']); ?> – quality products at great prices.</p>
            <a href="categorywise-products.php?cid=<?php echo $row['catid']; ?>" class="btn-view">View Products <i class="fas fa-arrow-right"></i></a>
        </div>
        <?php 
            }
        } else {
            echo '<div class="no-product" style="grid-column:1/-1; text-align:center; padding:60px;">No categories found.</div>';
        }
        ?>
    </div>

    <!-- Brand Slider -->
    <?php include('includes/brands-slider.php'); ?>
</div>

<!-- Footer -->
<?php include('includes/footer.php'); ?>

<!-- Scripts (preserve for Owl Carousel, etc.) -->
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
    });
</script>
</body>
</html>