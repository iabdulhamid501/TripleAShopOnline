<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Correct admin session variable
if(strlen($_SESSION["aid"]) == 0) {   
    header('location: logout.php');
    exit();
}

date_default_timezone_set('Asia/Kolkata');
$currentTime = date('d-m-Y h:i:s A', time());

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ========== UPDATE CATEGORY (Prepared Statement) ==========
if(isset($_POST['submit'])) {
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    
    $update_stmt = mysqli_prepare($con, "UPDATE category SET categoryName=?, categoryDescription=?, updationDate=? WHERE id=?");
    mysqli_stmt_bind_param($update_stmt, "sssi", $category, $description, $currentTime, $id);
    if(mysqli_stmt_execute($update_stmt)) {
        echo "<script>alert('Category updated successfully');</script>";
        echo "<script>window.location.href='edit-category.php?id=$id';</script>";
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
    <title>Edit Category | Triple A ShopOnline</title>
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
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Edit Category</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="manage-categories.php">Categories</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Category</li>
                        </ol>
                    </nav>
                </div>

                <?php
                // Fetch category details using prepared statement
                $select_stmt = mysqli_prepare($con, "SELECT * FROM category WHERE id = ?");
                mysqli_stmt_bind_param($select_stmt, "i", $id);
                mysqli_stmt_execute($select_stmt);
                $result = mysqli_stmt_get_result($select_stmt);
                if(mysqli_num_rows($result) == 0) {
                    echo '<div class="form-card text-center">Category not found. <a href="manage-categories.php" class="btn-submit">Back to Categories</a></div>';
                    exit;
                }
                $row = mysqli_fetch_assoc($result);
                mysqli_stmt_close($select_stmt);
                ?>
                <div class="form-card">
                    <form method="post">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Category Name</label>
                                <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($row['categoryName']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($row['categoryDescription']); ?></textarea>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <button type="submit" name="submit" class="btn-submit">Update Category <i class="fas fa-save ms-2"></i></button>
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