<?php 
session_start();
error_reporting(0);
include_once('includes/config.php');

$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
$category_name = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="Browse products by category at Triple A ShopOnline" />
    <meta name="author" content="Triple A ShopOnline" />
    <title>Category Products | Triple A ShopOnline</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <!-- Owl Carousel (required for brand slider) -->
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
        .category-hero {
            background: linear-gradient(105deg, #EFE6DF 0%, #FCF9F5 100%);
            border-radius: 32px;
            padding: 48px 32px;
            margin: 32px 0 48px;
            text-align: center;
        }
        .category-hero h1 {
            font-size: 2.8rem;
            margin-bottom: 8px;
            color: #2A2826;
        }
        .category-hero p {
            font-size: 1rem;
            color: #5F5A56;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 28px;
            margin: 40px 0;
        }
        .product-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.25s ease;
            border: 1px solid #F0E9E3;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 28px -12px rgba(0,0,0,0.1);
            border-color: #DECFC4;
        }
        .product-img {
            width: 100%;
            aspect-ratio: 1 / 1.1;
            object-fit: cover;
        }
        .product-info {
            padding: 18px;
            text-align: center;
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
            margin: 8px 0;
        }
        .old-price {
            font-size: 0.8rem;
            color: #AFA49B;
            text-decoration: line-through;
            margin-right: 8px;
            font-weight: 400;
        }
        .btn-view {
            display: inline-block;
            background: #F5F0EB;
            border: none;
            border-radius: 40px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 0.75rem;
            text-decoration: none;
            color: #2A2826;
            transition: 0.2s;
            margin-top: 12px;
        }
        .btn-view:hover {
            background: #C47A5E;
            color: white;
        }
        .no-products {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 24px;
            font-size: 1.2rem;
            color: #7A726C;
        }
        @media (max-width: 768px) {
            .category-hero h1 {
                font-size: 2rem;
            }
            .product-grid {
                gap: 16px;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }
    </style>
</head>
<body>

<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <?php 
    if($cid > 0) {
        // Fetch category name using prepared statement
        $cat_stmt = mysqli_prepare($con, "SELECT id, categoryName FROM category WHERE id = ?");
        mysqli_stmt_bind_param($cat_stmt, "i", $cid);
        mysqli_stmt_execute($cat_stmt);
        $cat_result = mysqli_stmt_get_result($cat_stmt);
        
        if(mysqli_num_rows($cat_result) > 0) {
            $cat_row = mysqli_fetch_assoc($cat_result);
            $category_name = htmlspecialchars($cat_row['categoryName']);
    ?>
    <!-- Category Hero -->
    <div class="category-hero">
        <h1><?php echo $category_name; ?></h1>
        <p>Explore our curated collection of <?php echo $category_name; ?> – quality and style delivered to your doorstep.</p>
    </div>

    <!-- Products Grid -->
    <div class="product-grid">
        <?php 
        // Fetch products using prepared statement
        $prod_stmt = mysqli_prepare($con, "SELECT id, productImage1, productName, productPriceBeforeDiscount, productPrice FROM products WHERE category = ? ORDER BY id DESC");
        mysqli_stmt_bind_param($prod_stmt, "i", $cid);
        mysqli_stmt_execute($prod_stmt);
        $prod_result = mysqli_stmt_get_result($prod_stmt);
        
        if(mysqli_num_rows($prod_result) > 0) {
            while($row = mysqli_fetch_assoc($prod_result)) {
                // Build correct image path with fallback
                $image_path = "admin/productimages/" . $row['id'] . "/" . $row['productImage1'];
                if(!file_exists($image_path) || empty($row['productImage1'])) {
                    $image_path = "admin/productimages/placeholder.jpg";
                }
        ?>
        <div class="product-card">
            <a href="product-details.php?pid=<?php echo $row['id']; ?>">
                <img class="product-img" src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($row['productName']); ?>" onerror="this.src='admin/productimages/placeholder.jpg'">
            </a>
            <div class="product-info">
                <div class="product-title">
                    <a href="product-details.php?pid=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['productName']); ?></a>
                </div>
                <div class="price">
                    <?php if(!empty($row['productPriceBeforeDiscount'])): ?>
                    <span class="old-price">₦<?php echo number_format($row['productPriceBeforeDiscount'], 2); ?></span>
                    <?php endif; ?>
                    <span>₦<?php echo number_format($row['productPrice'], 2); ?></span>
                </div>
                <a href="product-details.php?pid=<?php echo $row['id']; ?>" class="btn-view">View Details</a>
            </div>
        </div>
        <?php 
            }
        } else {
        ?>
        <div class="no-products">No products found in this category.</div>
        <?php } 
        mysqli_stmt_close($prod_stmt);
        } else {
            echo '<div class="no-products">Category not found.</div>';
        }
        mysqli_stmt_close($cat_stmt);
    } else {
        echo '<div class="no-products">Invalid category.</div>';
    }
    ?>

    <!-- Brand Slider -->
    <?php include('includes/brands-slider.php'); ?>
</div>

<?php include('includes/footer.php'); ?>

<!-- jQuery (required for Owl Carousel) -->
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