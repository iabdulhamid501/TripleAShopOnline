<?php
session_start();
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
    exit();
} else {
    date_default_timezone_set('Asia/Kolkata');
    $currentTime = date('d-m-Y h:i:s A', time());

    if(isset($_POST['submit'])) {
        $oldpassword = md5($_POST['password']);
        $newpassword = md5($_POST['newpassword']);
        $sql = mysqli_query($con, "SELECT password FROM admin WHERE password='$oldpassword' AND username='".$_SESSION['alogin']."'");
        $num = mysqli_fetch_array($sql);
        if($num > 0) {
            $con = mysqli_query($con, "UPDATE admin SET password='$newpassword', updationDate='$currentTime' WHERE username='".$_SESSION['alogin']."'");
            $_SESSION['msg'] = "Password Changed Successfully !!";
        } else {
            $_SESSION['msg'] = "Old Password not match !!";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Change Password | Triple A ShopOnline</title>
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
        .alert-custom {
            background: #F7EAE8;
            border-left: 4px solid #C47A5E;
            border-radius: 16px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: #A55;
        }
        .alert-success-custom {
            background: #E8F5E9;
            border-left: 4px solid #2E7D32;
            color: #2E7D32;
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
    <script type="text/javascript">
        function valid() {
            if(document.chngpwd.password.value == "") {
                alert("Current Password Field is Empty !!");
                document.chngpwd.password.focus();
                return false;
            } else if(document.chngpwd.newpassword.value == "") {
                alert("New Password Field is Empty !!");
                document.chngpwd.newpassword.focus();
                return false;
            } else if(document.chngpwd.confirmpassword.value == "") {
                alert("Confirm Password Field is Empty !!");
                document.chngpwd.confirmpassword.focus();
                return false;
            } else if(document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
                alert("Password and Confirm Password Field do not match !!");
                document.chngpwd.confirmpassword.focus();
                return false;
            }
            return true;
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
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Change Password</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Change Password</li>
                        </ol>
                    </nav>
                </div>

                <div class="form-card">
                    <?php if(isset($_POST['submit'])) { ?>
                        <div class="alert-custom <?php echo (strpos($_SESSION['msg'], 'Successfully') !== false) ? 'alert-success-custom' : ''; ?>">
                            <i class="fas <?php echo (strpos($_SESSION['msg'], 'Successfully') !== false) ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?> me-2"></i>
                            <?php echo htmlentities($_SESSION['msg']); ?>
                        </div>
                    <?php } ?>
                    <form class="row g-4" name="chngpwd" method="post" onSubmit="return valid();">
                        <div class="col-md-6">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter current password" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" name="newpassword" class="form-control" placeholder="Enter new password" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirmpassword" class="form-control" placeholder="Confirm new password" required>
                        </div>
                        <div class="col-12 text-center mt-3">
                            <button type="submit" name="submit" class="btn-submit">Update Password <i class="fas fa-key ms-2"></i></button>
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