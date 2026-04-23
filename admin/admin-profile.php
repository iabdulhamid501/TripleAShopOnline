<?php 
session_start();
include_once('includes/config.php');
if(strlen($_SESSION["aid"])==0) {   
    header('location:logout.php');
} else {

    // Update admin profile (contact number)
    if(isset($_POST['submit']))
    {
        $contactno = $_POST['cnumber'];
        $id = intval($_SESSION["aid"]);
        // Use correct table name 'admin'
        $sql = mysqli_query($con, "UPDATE admin SET contactNumber='$contactno' WHERE id='$id'");
        if($sql) {
            echo "<script>alert('Profile Updated successfully');</script>";
            echo "<script>window.location.href='admin-profile.php'</script>";
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
    <title>Admin Profile | Triple A ShopOnline</title>
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
        .profile-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4A4440;
        }
        .form-control {
            border-radius: 20px;
            border: 1px solid #E0D6CE;
            padding: 0.6rem 1rem;
        }
        .form-control:focus {
            border-color: #C47A5E;
            box-shadow: 0 0 0 3px rgba(196,122,94,0.1);
        }
        .btn-update {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0.5rem 2rem;
            font-weight: 600;
            color: white;
            transition: 0.2s;
        }
        .btn-update:hover {
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
        /* Ensure no gaps */
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
        /* Add some padding for main content on small screens */
        @media (max-width: 768px) {
            .profile-card {
                margin: 0 12px;
            }
        }
    </style>
</head>
<body>

<?php include_once('includes/header.php'); ?>

<!-- Main wrapper: no horizontal padding, no gutter -->
<div class="container-fluid px-0">
    <div class="row g-0">
        <!-- Sidebar column - flush left -->
        <div class="col-md-3 col-lg-2">
            <?php include_once('includes/sidebar.php'); ?>
        </div>
        <!-- Main content column -->
        <div class="col-md-9 col-lg-10">
            <div class="px-4 pt-3"> <!-- Add padding only inside content area -->
                <div class="d-flex justify-content-between align-items-center mt-2 mb-4">
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Admin Profile</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </nav>
                </div>

                <div class="profile-card">
                    <?php
                    $id = intval($_SESSION["aid"]);
                    $query = mysqli_query($con, "SELECT * FROM admin WHERE id='$id'");
                    if(mysqli_num_rows($query) > 0) {
                        $row = mysqli_fetch_array($query);
                    ?>
                    <form method="post">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Username (used for login)</label>
                                <input type="text" value="<?php echo htmlspecialchars($row['username']); ?>" name="username" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Number</label>
                                <input type="text" value="<?php echo htmlspecialchars($row['contactNumber']); ?>" name="cnumber" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Registration Date</label>
                                <input type="text" value="<?php echo htmlspecialchars($row['creationDate']); ?>" name="regdate" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Profile Last Updated</label>
                                <input type="text" value="<?php echo htmlspecialchars($row['updationDate']); ?>" name="updatedate" class="form-control" readonly>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <button type="submit" name="submit" class="btn-update">Update Profile <i class="fas fa-save ms-2"></i></button>
                            </div>
                        </div>
                    </form>
                    <?php 
                    } else {
                        echo '<div class="alert alert-warning">Admin profile not found.</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>