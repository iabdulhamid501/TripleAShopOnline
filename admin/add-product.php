<?php 
session_start();
include_once('includes/config.php');
error_reporting(0);

if(strlen($_SESSION["aid"]) == 0) {   
    header('location: logout.php');
    exit();
} else {

    // ========== ADD PRODUCT ==========
    $error = '';
    if(isset($_POST['submit'])) {
        // Sanitize and validate inputs
        $category = intval($_POST['category']);
        $subcat = intval($_POST['subcategory']);
        $productname = trim($_POST['productName']);
        $productcompany = trim($_POST['productCompany']);
        $productprice = floatval($_POST['productprice']);
        $productpricebd = !empty($_POST['productpricebd']) ? floatval($_POST['productpricebd']) : 0;
        $productdescription = trim($_POST['productDescription']);
        $productscharge = floatval($_POST['productShippingcharge']);
        $productavailability = $_POST['productAvailability'];
        
        // Validation
        if(empty($category) || empty($subcat) || empty($productname) || empty($productcompany) || empty($productprice) || empty($productavailability)) {
            $error = "Please fill all required fields.";
        }
        
        // File upload handling with extension validation
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        $upload_errors = [];
        
        $image_names = ['productimage1', 'productimage2', 'productimage3'];
        $uploaded_files = [];
        
        foreach($image_names as $key => $field) {
            if(isset($_FILES[$field]) && $_FILES[$field]['error'] == 0) {
                $file_name = $_FILES[$field]['name'];
                $file_tmp = $_FILES[$field]['tmp_name'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                
                if(!in_array($file_ext, $allowed_ext)) {
                    $upload_errors[] = "Image " . ($key+1) . " has invalid extension. Allowed: " . implode(', ', $allowed_ext);
                } else {
                    // Generate unique filename
                    $new_filename = md5($file_name . time() . $key) . '.' . $file_ext;
                    $uploaded_files[$field] = $new_filename;
                }
            } else {
                $upload_errors[] = "Image " . ($key+1) . " is required.";
            }
        }
        
        if(empty($error) && empty($upload_errors)) {
            // Insert product using prepared statement (without images first to get ID)
            $sql = "INSERT INTO products (category, subCategory, productName, productCompany, productPrice, 
                     productPriceBeforeDiscount, productDescription, shippingCharge, productAvailability, 
                     productImage1, productImage2, productImage3, postingDate) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = mysqli_prepare($con, $sql);
            // Temporary placeholders for images (will be updated after folder creation)
            $temp_img1 = $temp_img2 = $temp_img3 = '';
            mysqli_stmt_bind_param($stmt, "iissddsdssss", 
                $category, $subcat, $productname, $productcompany, $productprice, 
                $productpricebd, $productdescription, $productscharge, $productavailability,
                $temp_img1, $temp_img2, $temp_img3);
            
            if(mysqli_stmt_execute($stmt)) {
                $product_id = mysqli_insert_id($con);
                mysqli_stmt_close($stmt);
                
                // Create product folder inside admin/productimages/
                $target_dir = "productimages/" . $product_id . "/";
                if(!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
                // Move uploaded files and update database with correct paths
                $final_paths = [];
                foreach($image_names as $idx => $field) {
                    $new_filename = $uploaded_files[$field];
                    $destination = $target_dir . $new_filename;
                    if(move_uploaded_file($_FILES[$field]['tmp_name'], $destination)) {
                        $final_paths[$field] = $new_filename;
                    } else {
                        $upload_errors[] = "Failed to upload image " . ($idx+1);
                    }
                }
                
                if(empty($upload_errors)) {
                    // Update product with actual image names
                    $update_sql = "UPDATE products SET productImage1 = ?, productImage2 = ?, productImage3 = ? WHERE id = ?";
                    $update_stmt = mysqli_prepare($con, $update_sql);
                    mysqli_stmt_bind_param($update_stmt, "sssi", 
                        $final_paths['productimage1'], $final_paths['productimage2'], $final_paths['productimage3'], $product_id);
                    mysqli_stmt_execute($update_stmt);
                    mysqli_stmt_close($update_stmt);
                    
                    echo "<script>alert('Product added successfully');</script>";
                    echo "<script>window.location.href='manage-products.php';</script>";
                    exit();
                } else {
                    // Delete the product if image move failed
                    mysqli_query($con, "DELETE FROM products WHERE id = $product_id");
                    $error = implode("\\n", $upload_errors);
                }
            } else {
                $error = "Database error: " . mysqli_error($con);
            }
        } else {
            $error = !empty($error) ? $error : implode("\\n", $upload_errors);
        }
        
        if($error) {
            echo "<script>alert('$error');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Add Product | Triple A ShopOnline</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
                margin: 0 12px;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

<?php include_once('includes/header.php'); ?>

<div class="container-fluid px-0">
    <div class="row g-0">
        <div class="col-md-3 col-lg-2">
            <?php include_once('includes/sidebar.php'); ?>
        </div>
        <div class="col-md-9 col-lg-10">
            <div class="px-4 pt-3">
                <div class="d-flex justify-content-between align-items-center mt-2 mb-4">
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Add Product</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Product</li>
                        </ol>
                    </nav>
                </div>

                <div class="form-card">
                    <form method="post" enctype="multipart/form-data">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Category Name</label>
                                <select name="category" id="category" class="form-select" onChange="getSubcat(this.value);" required>
                                    <option value="">Select Category</option>
                                    <?php 
                                    $query = mysqli_query($con, "SELECT id, categoryName FROM category ORDER BY categoryName");
                                    while($row = mysqli_fetch_assoc($query)) {
                                        $selected = (isset($_POST['category']) && $_POST['category'] == $row['id']) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>>
                                        <?php echo htmlspecialchars($row['categoryName']); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sub Category</label>
                                <select name="subcategory" id="subcategory" class="form-select" required>
                                    <option value="">Select Sub Category</option>
                                    <?php 
                                    // If category was preselected, load subcategories
                                    if(isset($_POST['category']) && !empty($_POST['category'])) {
                                        $cat_id = intval($_POST['category']);
                                        $sub_q = mysqli_query($con, "SELECT id, subcategoryName FROM subcategory WHERE categoryid = $cat_id");
                                        while($sub = mysqli_fetch_assoc($sub_q)) {
                                            $sel_sub = (isset($_POST['subcategory']) && $_POST['subcategory'] == $sub['id']) ? 'selected' : '';
                                            echo "<option value='{$sub['id']}' $sel_sub>" . htmlspecialchars($sub['subcategoryName']) . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="productName" class="form-control" placeholder="Enter product name" 
                                       value="<?php echo isset($_POST['productName']) ? htmlspecialchars($_POST['productName']) : ''; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Product Company</label>
                                <input type="text" name="productCompany" class="form-control" placeholder="Enter company name" 
                                       value="<?php echo isset($_POST['productCompany']) ? htmlspecialchars($_POST['productCompany']) : ''; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price Before Discount (₦)</label>
                                <input type="number" step="0.01" name="productpricebd" class="form-control" placeholder="e.g., 5000" 
                                       value="<?php echo isset($_POST['productpricebd']) ? htmlspecialchars($_POST['productpricebd']) : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Selling Price (₦)</label>
                                <input type="number" step="0.01" name="productprice" class="form-control" placeholder="e.g., 3999" 
                                       value="<?php echo isset($_POST['productprice']) ? htmlspecialchars($_POST['productprice']) : ''; ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Product Description</label>
                                <textarea name="productDescription" class="form-control" rows="5" placeholder="Enter product description"><?php echo isset($_POST['productDescription']) ? htmlspecialchars($_POST['productDescription']) : ''; ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Shipping Charge (₦)</label>
                                <input type="number" step="0.01" name="productShippingcharge" class="form-control" placeholder="e.g., 500" 
                                       value="<?php echo isset($_POST['productShippingcharge']) ? htmlspecialchars($_POST['productShippingcharge']) : ''; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Availability</label>
                                <select name="productAvailability" class="form-select" required>
                                    <option value="">Select</option>
                                    <option value="In Stock" <?php echo (isset($_POST['productAvailability']) && $_POST['productAvailability'] == 'In Stock') ? 'selected' : ''; ?>>In Stock</option>
                                    <option value="Out of Stock" <?php echo (isset($_POST['productAvailability']) && $_POST['productAvailability'] == 'Out of Stock') ? 'selected' : ''; ?>>Out of Stock</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Featured Image (JPG, PNG, GIF, WEBP, BMP)</label>
                                <input type="file" name="productimage1" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp,image/bmp" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Image 2</label>
                                <input type="file" name="productimage2" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp,image/bmp" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Image 3</label>
                                <input type="file" name="productimage3" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp,image/bmp" required>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <button type="submit" name="submit" class="btn-submit">Add Product <i class="fas fa-plus-circle ms-2"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>