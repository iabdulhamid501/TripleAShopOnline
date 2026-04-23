<?php
session_start();
include_once('includes/config.php');
if(strlen($_SESSION["aid"]) == 0) { header('location: logout.php'); exit(); }
$id = intval($_GET['id']);
$query = mysqli_query($con, "SELECT * FROM delivery WHERE id=$id");
$row = mysqli_fetch_assoc($query);
if(isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    if(!empty($password)) {
        $password = md5($password);
        $stmt = mysqli_prepare($con, "UPDATE delivery SET username=?, fullname=?, email=?, password=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "ssssi", $username, $fullname, $email, $password, $id);
    } else {
        $stmt = mysqli_prepare($con, "UPDATE delivery SET username=?, fullname=?, email=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sssi", $username, $fullname, $email, $id);
    }
    mysqli_stmt_execute($stmt);
    echo "<script>alert('Updated'); window.location='manage-delivery.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Edit Delivery Staff | Triple A ShopOnline</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
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
        .admin-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .admin-content-wrapper {
            display: flex;
            flex: 1;
        }
        .main-content {
            flex: 1;
            padding: 20px 24px;
            background: #FCF9F5;
        }
        .form-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 1.8rem;
            max-width: 700px;
            margin: 0;
        }
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4A4440;
            margin-bottom: 0.5rem;
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
            color: white;
        }
        .btn-cancel {
            background: #E0D6CE;
            border: none;
            border-radius: 40px;
            padding: 0.5rem 2rem;
            font-weight: 600;
            color: #2A2826;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .btn-cancel:hover {
            background: #d0c4ba;
            color: #2A2826;
        }
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
            margin-bottom: 1.5rem;
        }
        .breadcrumb-custom .breadcrumb-item a {
            color: #C47A5E;
            text-decoration: none;
        }
        .page-title {
            font-family: 'Instrument Serif', serif;
            font-size: 1.8rem;
            margin-bottom: 0;
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 16px;
            }
            .form-card {
                padding: 1.2rem;
            }
        }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <!-- Header (full width) -->
    <?php include_once('includes/header.php'); ?>
    
    <div class="admin-content-wrapper">
        <!-- Sidebar (left) -->
        <?php include_once('includes/sidebar.php'); ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <h1 class="page-title">Edit Delivery Staff</h1>
            </div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="manage-delivery.php">Delivery Staff</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>

            <div class="form-card">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Username *</label>
                        <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($row['username']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($row['fullname']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password (leave blank to keep unchanged)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="submit" class="btn-update">Update</button>
                        <a href="manage-delivery.php" class="btn-cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Footer (full width) -->
    <?php include_once('includes/footer.php'); ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>