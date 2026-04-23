<?php 
session_start();
include_once('includes/config.php');
error_reporting(0);

if(strlen($_SESSION["aid"]) == 0) {   
    header('location: logout.php');
    exit();
} else {

    // ========== AUTO-REPAIR SUB-CATEGORY TABLE ==========
    // Ensure required columns exist
    
    // Check if 'subcategoryName' column exists; if not, add it (copy data from 'subcategory' if needed)
    $col_check = mysqli_query($con, "SHOW COLUMNS FROM subcategory LIKE 'subcategoryName'");
    if(mysqli_num_rows($col_check) == 0) {
        // First check if old 'subcategory' column exists
        $old_col = mysqli_query($con, "SHOW COLUMNS FROM subcategory LIKE 'subcategory'");
        if(mysqli_num_rows($old_col) > 0) {
            // Rename 'subcategory' to 'subcategoryName' to preserve data
            mysqli_query($con, "ALTER TABLE subcategory CHANGE subcategory subcategoryName VARCHAR(255) DEFAULT NULL");
        } else {
            // Just add the column
            mysqli_query($con, "ALTER TABLE subcategory ADD subcategoryName VARCHAR(255) DEFAULT NULL AFTER categoryid");
        }
    }
    
    // Check if 'createdBy' column exists
    $col_check2 = mysqli_query($con, "SHOW COLUMNS FROM subcategory LIKE 'createdBy'");
    if(mysqli_num_rows($col_check2) == 0) {
        mysqli_query($con, "ALTER TABLE subcategory ADD createdBy INT(11) DEFAULT NULL AFTER subcategoryName");
    }
    
    // ========== ADD SUB-CATEGORY ==========
    $error = '';
    if(isset($_POST['submit'])) {
        $category = intval($_POST['category']);
        $subcat = trim($_POST['subcategory']);
        $createdby = intval($_SESSION["aid"]);
        
        if(empty($category)) {
            $error = "Please select a category.";
        } elseif(empty($subcat)) {
            $error = "Sub-category name is required.";
        } else {
            // Check for duplicate subcategory under same category
            $check_sql = "SELECT id FROM subcategory WHERE categoryid = ? AND subcategoryName = ?";
            $check_stmt = mysqli_prepare($con, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "is", $category, $subcat);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            if(mysqli_stmt_num_rows($check_stmt) > 0) {
                $error = "Sub-category already exists under this category.";
            } else {
                // Insert using prepared statement
                $sql = "INSERT INTO subcategory (categoryid, subcategoryName, createdBy) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "isi", $category, $subcat, $createdby);
                if(mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('Sub-Category added successfully');</script>";
                    echo "<script>window.location.href='manage-subcategories.php';</script>";
                    exit();
                } else {
                    $error = "Database error: " . mysqli_error($con);
                }
                mysqli_stmt_close($stmt);
            }
            mysqli_stmt_close($check_stmt);
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
    <title>Add Sub-Category | Triple A ShopOnline</title>
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
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Add Sub-Category</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Sub-Category</li>
                        </ol>
                    </nav>
                </div>

                <div class="form-card">
                    <form method="post">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Category Name</label>
                                <select name="category" class="form-select" required>
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
                                <label class="form-label">Sub-Category Name</label>
                                <input type="text" name="subcategory" class="form-control" placeholder="Enter sub-category name" 
                                       value="<?php echo isset($_POST['subcategory']) ? htmlspecialchars($_POST['subcategory']) : ''; ?>" required>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <button type="submit" name="submit" class="btn-submit">Submit <i class="fas fa-plus-circle ms-2"></i></button>
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