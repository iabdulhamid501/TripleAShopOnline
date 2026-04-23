<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Correct admin session variable
if(strlen($_SESSION["aid"]) == 0) {   
    header('location: logout.php');
    exit();
}

$pid = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ========== UPDATE PRODUCT (Prepared Statement) ==========
if(isset($_POST['submit'])) {
    $category = intval($_POST['category']);
    $subcat = intval($_POST['subcategory']);
    $productname = trim($_POST['productName']);
    $productcompany = trim($_POST['productCompany']);
    $productprice = floatval($_POST['productprice']);
    $productpricebd = !empty($_POST['productpricebd']) ? floatval($_POST['productpricebd']) : 0;
    $productdescription = trim($_POST['productDescription']);
    $productscharge = floatval($_POST['productShippingcharge']);
    $productavailability = $_POST['productAvailability'];
    
    $update_stmt = mysqli_prepare($con, "UPDATE products SET category=?, subCategory=?, productName=?, productCompany=?, 
                                         productPrice=?, productDescription=?, shippingCharge=?, productAvailability=?, 
                                         productPriceBeforeDiscount=? WHERE id=?");
    mysqli_stmt_bind_param($update_stmt, "iissdsssdi", $category, $subcat, $productname, $productcompany, 
                            $productprice, $productdescription, $productscharge, $productavailability, $productpricebd, $pid);
    if(mysqli_stmt_execute($update_stmt)) {
        echo "<script>alert('Product updated successfully');</script>";
        echo "<script>window.location.href='edit-products.php?id=$pid';</script>";
    } else {
        echo "<script>alert('Update failed: " . mysqli_error($con) . "');</script>";
    }
    mysqli_stmt_close($update_stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Edit Product | Triple A ShopOnline</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery (for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #FCF9F5;
            color: #2A2826;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .form-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 1.8rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4A4440;
            margin-bottom: 0.5rem;
        }
        .form-control, .form-select {
            border-radius: 20px;
            border: 1px solid #E0D6CE;
            padding: 0.6rem 1rem;
            background: white;
        }
        .form-control:focus, .form-select:focus {
            border-color: #C47A5E;
            box-shadow: 0 0 0 3px rgba(196,122,94,0.1);
        }
        .btn-submit {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0.5rem 2rem;
            font-weight: 600;
            color: white;
            transition: 0.2s;
        }
        .btn-submit:hover {
            background: #A85E44;
        }
        .product-image-thumb {
            width: 100px;
            border-radius: 12px;
            border: 1px solid #EFE8E2;
            padding: 4px;
            background: white;
        }
        .btn-action {
            color: #C47A5E;
            text-decoration: none;
            font-size: 0.85rem;
        }
        .btn-action:hover {
            color: #A85E44;
        }
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
        }
        .breadcrumb-custom .breadcrumb-item a {
            color: #C47A5E;
            text-decoration: none;
        }
        .container-fluid.px-0 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .row.g-0 {
            margin-left: 0;
            margin-right: 0;
        }
        [class*="col-"] {
            padding-left: 0;
            padding-right: 0;
        }
        @media (max-width: 768px) {
            .form-card {
                margin: 0 12px 1.5rem;
            }
        }
    </style>
    <script>
        function getSubcat(val) {
            if(val) {
                $.ajax({
                    type: "POST",
                    url: "get_subcat.php",
                    data: 'cat_id='+val,
                    success: function(data){
                        $("#subcategory").html(data);
                    }
                });
            } else {
                $("#subcategory").html('<option value="">Select Sub Category</option>');
            }
        }
    </script>
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="container-fluid px-0">
    <div class="row g-0">
        <div class="col-md-3 col-lg-2">
            <?php include('includes/sidebar.php'); ?>
        </div>
        <div class="col-md-9 col-lg-10">
            <div class="px-4 pt-3">
                <div class="d-flex justify-content-between align-items-center mt-2 mb-4">
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Edit Product</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="manage-products.php">Products</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
                        </ol>
                    </nav>
                </div>

                <?php
                // Fetch product details with proper column names
                $query = "SELECT p.*, c.categoryName as catname, c.id as cid, 
                                 s.subcategoryName as subcatname, s.id as subcatid 
                          FROM products p
                          JOIN category c ON c.id = p.category
                          JOIN subcategory s ON s.id = p.subCategory
                          WHERE p.id = ?";
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt, "i", $pid);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result) == 0) {
                    echo '<div class="form-card text-center">Product not found. <a href="manage-products.php" class="btn-submit">Back to Products</a></div>';
                    exit;
                }
                $row = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
                ?>
                <div class="form-card">
                    <form method="post" enctype="multipart/form-data">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select name="category" id="category" class="form-select" onChange="getSubcat(this.value);" required>
                                    <option value="<?php echo $row['cid']; ?>"><?php echo htmlspecialchars($row['catname']); ?></option>
                                    <?php
                                    $cat_query = mysqli_query($con, "SELECT id, categoryName FROM category ORDER BY categoryName");
                                    while($cat = mysqli_fetch_assoc($cat_query)) {
                                        if($cat['id'] != $row['cid']) {
                                            echo '<option value="'.$cat['id'].'">'.htmlspecialchars($cat['categoryName']).'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sub Category</label>
                                <select name="subcategory" id="subcategory" class="form-select" required>
                                    <option value="<?php echo $row['subcatid']; ?>"><?php echo htmlspecialchars($row['subcatname']); ?></option>
                                    <?php
                                    // Load subcategories for the current category
                                    $sub_query = mysqli_query($con, "SELECT id, subcategoryName FROM subcategory WHERE categoryid = ".$row['cid']);
                                    while($sub = mysqli_fetch_assoc($sub_query)) {
                                        if($sub['id'] != $row['subcatid']) {
                                            echo '<option value="'.$sub['id'].'">'.htmlspecialchars($sub['subcategoryName']).'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="productName" class="form-control" value="<?php echo htmlspecialchars($row['productName']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Product Company</label>
                                <input type="text" name="productCompany" class="form-control" value="<?php echo htmlspecialchars($row['productCompany']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price Before Discount (₦)</label>
                                <input type="number" step="0.01" name="productpricebd" class="form-control" value="<?php echo htmlspecialchars($row['productPriceBeforeDiscount']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Selling Price (₦)</label>
                                <input type="number" step="0.01" name="productprice" class="form-control" value="<?php echo htmlspecialchars($row['productPrice']); ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Product Description</label>
                                <textarea name="productDescription" class="form-control" rows="5"><?php echo htmlspecialchars($row['productDescription']); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Shipping Charge (₦)</label>
                                <input type="number" step="0.01" name="productShippingcharge" class="form-control" value="<?php echo htmlspecialchars($row['shippingCharge']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Availability</label>
                                <select name="productAvailability" class="form-select" required>
                                    <option value="<?php echo htmlspecialchars($row['productAvailability']); ?>" selected><?php echo htmlspecialchars($row['productAvailability']); ?></option>
                                    <option value="In Stock">In Stock</option>
                                    <option value="Out of Stock">Out of Stock</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Product Image 1</label><br>
                                <?php
                                $img1_path = "productimages/".$pid."/".$row['productImage1'];
                                if(!file_exists($img1_path) || empty($row['productImage1'])) {
                                    $img1_path = "productimages/placeholder.jpg";
                                }
                                ?>
                                <img src="<?php echo $img1_path; ?>" class="product-image-thumb" alt="Image 1">
                                <br><a href="update-image1.php?id=<?php echo $row['id']; ?>" class="btn-action">Change Image</a>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Product Image 2</label><br>
                                <?php
                                $img2_path = "productimages/".$pid."/".$row['productImage2'];
                                if(!file_exists($img2_path) || empty($row['productImage2'])) {
                                    $img2_path = "productimages/placeholder.jpg";
                                }
                                ?>
                                <img src="<?php echo $img2_path; ?>" class="product-image-thumb" alt="Image 2">
                                <br><a href="update-image2.php?id=<?php echo $row['id']; ?>" class="btn-action">Change Image</a>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Product Image 3</label><br>
                                <?php
                                $img3_path = "productimages/".$pid."/".$row['productImage3'];
                                if(!file_exists($img3_path) || empty($row['productImage3'])) {
                                    $img3_path = "productimages/placeholder.jpg";
                                }
                                ?>
                                <img src="<?php echo $img3_path; ?>" class="product-image-thumb" alt="Image 3">
                                <br><a href="update-image3.php?id=<?php echo $row['id']; ?>" class="btn-action">Change Image</a>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <button type="submit" name="submit" class="btn-submit">Update Product <i class="fas fa-save ms-2"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>