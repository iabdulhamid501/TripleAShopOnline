<?php
session_start();
include('includes/config.php'); // Updated path
if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
    exit();
} else {
    date_default_timezone_set('Asia/Kolkata');
    $currentTime = date('d-m-Y h:i:s A', time());

    if(isset($_POST['submit'])) {
        $category = intval($_POST['category']);
        $subcat = $_POST['subcategory'];
        $id = intval($_GET['id']);
        $sql = mysqli_query($con, "UPDATE subcategory SET categoryid='$category', subcategoryName='$subcat', updationDate='$currentTime' WHERE id='$id'");
        if($sql) {
            $_SESSION['msg'] = "Sub-Category Updated !!";
            echo "<script>alert('Sub-category updated successfully');</script>";
            echo "<script>window.location.href='edit-subcategory.php?id=$id'</script>";
        } else {
            echo "<script>alert('Update failed. Please try again.');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Edit Sub-Category | Triple A ShopOnline</title>
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
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Edit Sub-Category</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="manage-subcategories.php">Sub-Categories</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Sub-Category</li>
                        </ol>
                    </nav>
                </div>

                <?php
                $id = intval($_GET['id']);
                // Fixed query: use correct column names (subcategoryName, not subcategory)
                $query = mysqli_query($con, "SELECT subcategory.id, subcategory.categoryid, subcategory.subcategoryName, category.categoryName 
                                             FROM subcategory 
                                             JOIN category ON category.id = subcategory.categoryid 
                                             WHERE subcategory.id = '$id'");
                if (!$query || mysqli_num_rows($query) == 0) {
                    echo '<div class="form-card text-center">Sub-category not found. <a href="manage-subcategories.php" class="btn-submit">Back to Sub-Categories</a></div>';
                    exit;
                }
                $row = mysqli_fetch_assoc($query);
                $currentCatId = $row['categoryid'];
                $currentCatName = $row['categoryName'];
                $currentSubcatName = $row['subcategoryName'];
                ?>
                <div class="form-card">
                    <form method="post">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select" required>
                                    <option value="<?php echo $currentCatId; ?>"><?php echo htmlspecialchars($currentCatName); ?></option>
                                    <?php
                                    $cat_query = mysqli_query($con, "SELECT id, categoryName FROM category");
                                    while($cat = mysqli_fetch_assoc($cat_query)) {
                                        if($cat['id'] != $currentCatId) {
                                            echo '<option value="'.$cat['id'].'">'.htmlspecialchars($cat['categoryName']).'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sub-Category Name</label>
                                <input type="text" name="subcategory" class="form-control" value="<?php echo htmlspecialchars($currentSubcatName); ?>" required>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <button type="submit" name="submit" class="btn-submit">Update Sub-Category <i class="fas fa-save ms-2"></i></button>
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
<?php } ?>