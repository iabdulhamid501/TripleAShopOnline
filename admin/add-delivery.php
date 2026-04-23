<?php
session_start();
include_once('includes/config.php');
if(strlen($_SESSION["aid"]) == 0) { 
    header('location: logout.php'); 
    exit(); 
}

if(isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $password = md5($_POST['password']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    
    // Check for duplicate username using prepared statement
    $check_stmt = mysqli_prepare($con, "SELECT id FROM delivery WHERE username = ?");
    mysqli_stmt_bind_param($check_stmt, "s", $username);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    if(mysqli_stmt_num_rows($check_stmt) > 0) {
        echo "<script>alert('Username already exists');</script>";
    } else {
        $insert_stmt = mysqli_prepare($con, "INSERT INTO delivery (username, password, fullname, email) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($insert_stmt, "ssss", $username, $password, $fullname, $email);
        if(mysqli_stmt_execute($insert_stmt)) {
            echo "<script>alert('Delivery staff added'); window.location='manage-delivery.php';</script>";
        } else {
            echo "<script>alert('Error adding delivery staff. Please try again.');</script>";
        }
        mysqli_stmt_close($insert_stmt);
    }
    mysqli_stmt_close($check_stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Add Delivery Staff | Triple A ShopOnline</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #FCF9F5;
            color: #2A2826;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Ensure header and footer are full width */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Fix sidebar and content alignment */
        .content-row {
            flex: 1;
            display: flex;
            width: 100%;
        }

        .sidebar-col {
            background: #fff;
            border-right: 1px solid #EFE8E2;
        }

        .main-col {
            background: #FCF9F5;
            padding: 1.5rem 2rem !important;
        }

        /* Form card styling */
        .form-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 1.8rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            max-width: 900px;
            margin: 0 auto;
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

        .btn-submit {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0.5rem 2rem;
            font-weight: 600;
            color: white;
            transition: 0.2s;
            display: inline-block;
            text-decoration: none;
        }

        .btn-submit:hover {
            background: #A85E44;
            color: white;
        }

        .btn-cancel {
            background: #E0D6CE;
            color: #2A2826;
        }

        .btn-cancel:hover {
            background: #d0c4ba;
            color: #2A2826;
        }

        .breadcrumb-custom {
            background: transparent;
            padding: 0;
        }

        .breadcrumb-custom .breadcrumb-item a {
            color: #C47A5E;
            text-decoration: none;
        }

        .page-title {
            font-family: 'Instrument Serif', serif;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-col {
                padding: 1rem !important;
            }
            .form-card {
                margin: 0;
                padding: 1.2rem;
            }
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <?php include_once('includes/header.php'); ?>
    
    <div class="content-row">
        <!-- Sidebar Column (Fixed width on large screens) -->
        <div class="sidebar-col col-md-3 col-lg-2">
            <?php include_once('includes/sidebar.php'); ?>
        </div>
        
        <!-- Main Content Column -->
        <div class="main-col col-md-9 col-lg-10">
            <!-- Breadcrumb and header area -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <h1 class="page-title">Add Delivery Staff</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-custom">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="manage-delivery.php">Delivery Staff</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Delivery</li>
                    </ol>
                </nav>
            </div>

            <!-- Form Card -->
            <div class="form-card">
                <form method="post">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Username *</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="fullname" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-12 text-center mt-3">
                            <button type="submit" name="submit" class="btn-submit me-2">Save Delivery Staff <i class="fas fa-save ms-2"></i></button>
                            <a href="manage-delivery.php" class="btn-submit btn-cancel">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php include_once('includes/footer.php'); ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>