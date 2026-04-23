<?php 
session_start();
include_once('includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(strlen($_SESSION["aid"]) == 0) {   
    header('location: logout.php');
    exit();
}

$error_messages = [];
$success_message = "";

// Check connection
if (!$con) {
    $error_messages[] = "Database connection failed: " . mysqli_connect_error();
} else {
    // Check if 'category' table exists
    $table_check = mysqli_query($con, "SHOW TABLES LIKE 'category'");
    if(mysqli_num_rows($table_check) == 0) {
        $error_messages[] = "Table 'category' does not exist. Please create it.";
    } else {
        // Check if 'createdBy' column exists – if not, add it automatically
        $col_check = mysqli_query($con, "SHOW COLUMNS FROM category LIKE 'createdBy'");
        if(mysqli_num_rows($col_check) == 0) {
            $alter_sql = "ALTER TABLE category ADD COLUMN createdBy INT(11) DEFAULT NULL AFTER categoryDescription";
            if(mysqli_query($con, $alter_sql)) {
                // Column added successfully
            } else {
                $error_messages[] = "Failed to add 'createdBy' column: " . mysqli_error($con);
            }
        }
        
        // Get actual column names (case‑insensitive mapping)
        $columns = [];
        $col_result = mysqli_query($con, "DESCRIBE category");
        while($col = mysqli_fetch_assoc($col_result)) {
            $columns[strtolower($col['Field'])] = $col['Field'];
        }
        
        // Map expected columns to actual names
        $col_map = [];
        $expected = ['categoryname', 'categorydescription', 'createdby'];
        foreach($expected as $exp) {
            if(isset($columns[$exp])) {
                $col_map[$exp] = $columns[$exp];
            } else {
                $error_messages[] = "Required column '$exp' not found. Found: " . implode(', ', array_values($columns));
            }
        }
    }
}

// For Adding categories
if(isset($_POST['submit']) && empty($error_messages)) {
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $createdby = intval($_SESSION["aid"]);
    
    if(empty($category)) $error_messages[] = "Category name is required.";
    if(empty($description)) $error_messages[] = "Category description is required.";
    
    // Check for duplicate
    if(empty($error_messages)) {
        $check_sql = "SELECT id FROM category WHERE `{$col_map['categoryname']}` = ?";
        $check_stmt = mysqli_prepare($con, $check_sql);
        if($check_stmt) {
            mysqli_stmt_bind_param($check_stmt, "s", $category);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            if(mysqli_stmt_num_rows($check_stmt) > 0) {
                $error_messages[] = "Category already exists. Please use a different name.";
            }
            mysqli_stmt_close($check_stmt);
        } else {
            $error_messages[] = "Database check failed: " . mysqli_error($con);
        }
    }
    
    // Insert using actual column names (excluding creationDate, it auto‑populates)
    if(empty($error_messages)) {
        $sql = "INSERT INTO category (`{$col_map['categoryname']}`, `{$col_map['categorydescription']}`, `{$col_map['createdby']}`) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        
        if($stmt) {
            mysqli_stmt_bind_param($stmt, "ssi", $category, $description, $createdby);
            if(mysqli_stmt_execute($stmt)) {
                echo "<script>
                        alert('Category added successfully');
                        window.location.href='manage-categories.php';
                      </script>";
                exit();
            } else {
                $error_messages[] = "Database error: " . mysqli_error($con);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error_messages[] = "Failed to prepare statement: " . mysqli_error($con);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Add Category | Triple A ShopOnline</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #FCF9F5; color: #2A2826; margin: 0; padding: 0; overflow-x: hidden; }
        .form-card { background: white; border-radius: 24px; border: 1px solid #EFE8E2; padding: 1.8rem; box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
        .form-label { font-weight: 600; font-size: 0.85rem; color: #4A4440; margin-bottom: 0.5rem; }
        .form-control { border-radius: 20px; border: 1px solid #E0D6CE; padding: 0.6rem 1rem; }
        .form-control:focus { border-color: #C47A5E; box-shadow: 0 0 0 3px rgba(196,122,94,0.1); }
        .btn-submit { background: #C47A5E; border: none; border-radius: 40px; padding: 0.5rem 2rem; font-weight: 600; color: white; transition: 0.2s; }
        .btn-submit:hover { background: #A85E44; }
        .breadcrumb-custom { background: transparent; padding: 0; }
        .breadcrumb-custom .breadcrumb-item a { color: #C47A5E; text-decoration: none; }
        .alert-custom { border-radius: 20px; font-size: 0.9rem; }
        .container-fluid.px-0 { padding-left: 0 !important; padding-right: 0 !important; }
        .row.g-0 { margin-left: 0; margin-right: 0; }
        [class*="col-"] { padding-left: 0; padding-right: 0; }
        @media (max-width: 768px) { .form-card { margin: 0 12px; } }
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
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Add Category</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Category</li>
                        </ol>
                    </nav>
                </div>

                <?php if(!empty($error_messages)): ?>
                    <div class="alert alert-danger alert-custom">
                        <strong>Errors:</strong><br>
                        <?php foreach($error_messages as $err): ?>
                            • <?php echo nl2br(htmlspecialchars($err)); ?><br>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if($success_message): ?>
                    <div class="alert alert-success alert-custom">
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>

                <div class="form-card">
                    <form method="post">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Category Name</label>
                                <input type="text" name="category" class="form-control" placeholder="Enter category name" 
                                       value="<?php echo isset($_POST['category']) ? htmlspecialchars($_POST['category']) : ''; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Enter category description" required><?php 
                                    echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; 
                                ?></textarea>
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