<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Code user Registration
if(isset($_POST['submit']))
{
    $name=$_POST['fullname'];
    $email=$_POST['emailid'];
    $contactno=$_POST['contactno'];
    $password=md5($_POST['password']);
    $query=mysqli_query($con,"insert into users(name,email,contactno,password) values('$name','$email','$contactno','$password')");
    if($query)
    {
        echo "<script>alert('You are successfully register');</script>";
    }
    else{
        echo "<script>alert('Not register something went wrong');</script>";
    }
}

// Code for User login
if(isset($_POST['login']))
{
    $email=$_POST['email'];
    $password=md5($_POST['password']);
    $query=mysqli_query($con,"SELECT * FROM users WHERE email='$email' and password='$password'");
    $num=mysqli_fetch_array($query);
    if($num>0)
    {
        $extra="my-cart.php";
        $_SESSION['login']=$_POST['email'];
        $_SESSION['id']=$num['id'];
        $_SESSION['username']=$num['name'];
        $uip=$_SERVER['REMOTE_ADDR'];
        $status=1;
        $log=mysqli_query($con,"insert into userlog(userEmail,userip,status) values('".$_SESSION['login']."','$uip','$status')");
        header("location:my-cart.php");
        exit();
    }
    else
    {
        $extra="login.php";
        $email=$_POST['email'];
        $uip=$_SERVER['REMOTE_ADDR'];
        $status=0;
        $log=mysqli_query($con,"insert into userlog(userEmail,userip,status) values('$email','$uip','$status')");
        header("location:login.php");
        $_SESSION['errmsg']="Invalid email id or Password";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="Login or create an account at Triple A ShopOnline">
    <meta name="author" content="Triple A ShopOnline">
    <meta name="keywords" content="login, signup, authentication">
    <meta name="robots" content="all">
    <title>Login / Sign Up | Triple A ShopOnline</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <!-- Owl Carousel (for brand slider) -->
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/owl.theme.css">
    
    <style>
        /* VELORIA global styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FCF9F5;
            color: #2A2826;
            line-height: 1.5;
        }
        h1, h2, h3, h4 {
            font-family: 'Instrument Serif', serif;
            font-weight: 500;
            letter-spacing: -0.01em;
        }
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 24px;
        }
        /* Breadcrumb */
        .veloria-breadcrumb {
            background: transparent;
            padding: 16px 0;
            margin: 0 0 24px;
        }
        .veloria-breadcrumb ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 0.85rem;
        }
        .veloria-breadcrumb li a {
            color: #C47A5E;
            text-decoration: none;
        }
        .veloria-breadcrumb li.active {
            color: #7A726C;
        }
        /* Auth Cards */
        .auth-card {
            background: white;
            border-radius: 28px;
            border: 1px solid #EFE8E2;
            padding: 32px;
            height: 100%;
            box-shadow: 0 8px 20px rgba(0,0,0,0.02);
        }
        .auth-card h4 {
            font-size: 1.6rem;
            margin-bottom: 8px;
            color: #2A2826;
        }
        .auth-card .subtitle {
            color: #7A726C;
            font-size: 0.9rem;
            margin-bottom: 24px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4A4440;
            margin-bottom: 6px;
            display: block;
        }
        .form-group label span {
            color: #C47A5E;
        }
        .form-control {
            width: 100%;
            padding: 12px 16px;
            font-size: 0.9rem;
            border: 1px solid #E0D6CE;
            border-radius: 20px;
            background: #FFFFFF;
            transition: 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .form-control:focus {
            outline: none;
            border-color: #C47A5E;
            box-shadow: 0 0 0 3px rgba(196,122,94,0.1);
        }
        .btn-auth {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 12px 24px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: 0.2s;
            cursor: pointer;
        }
        .btn-auth:hover {
            background: #A85E44;
            transform: translateY(-2px);
        }
        .forgot-password {
            color: #C47A5E;
            text-decoration: none;
            font-size: 0.8rem;
            float: right;
        }
        .forgot-password:hover {
            text-decoration: underline;
        }
        .error-message {
            color: #A55;
            font-size: 0.85rem;
            margin-bottom: 16px;
            display: block;
        }
        #user-availability-status1 {
            font-size: 0.75rem;
            margin-top: 5px;
            display: block;
        }
        .checklist {
            margin-top: 20px;
        }
        .checklist label {
            font-size: 0.85rem;
            color: #5F5A56;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }
        @media (max-width: 768px) {
            .auth-card {
                padding: 24px;
                margin-bottom: 24px;
            }
        }
    </style>
    <script type="text/javascript">
        function valid() {
            if(document.register.password.value != document.register.confirmpassword.value) {
                alert("Password and Confirm Password Field do not match !!");
                document.register.confirmpassword.focus();
                return false;
            }
            return true;
        }
        function userAvailability() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "check_availability.php",
                data: 'email=' + $("#email").val(),
                type: "POST",
                success: function(data) {
                    $("#user-availability-status1").html(data);
                    $("#loaderIcon").hide();
                },
                error: function() {}
            });
        }
    </script>
</head>
<body oncontextmenu="return false;">

<!-- VELORIA Header -->
<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <!-- Breadcrumb -->
    <div class="veloria-breadcrumb">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><i class="fas fa-chevron-right" style="font-size: 10px;"></i></li>
            <li class="active">Authentication</li>
        </ul>
    </div>

    <div class="row g-4" style="margin: 24px 0 48px;">
        <!-- Sign In Card -->
        <div class="col-md-6">
            <div class="auth-card">
                <h4>Sign In</h4>
                <p class="subtitle">Welcome back to Triple A ShopOnline.</p>
                <form method="post">
                    <span class="error-message">
                        <?php echo htmlentities($_SESSION['errmsg']); ?>
                        <?php echo htmlentities($_SESSION['errmsg'] = ""); ?>
                    </span>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email Address <span>*</span></label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password <span>*</span></label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" required>
                    </div>
                    <div class="form-group">
                        <a href="forgot-password.php" class="forgot-password">Forgot your Password?</a>
                    </div>
                    <button type="submit" name="login" class="btn-auth">Login</button>
                </form>
            </div>
        </div>

        <!-- Sign Up Card -->
        <div class="col-md-6">
            <div class="auth-card">
                <h4>Create an Account</h4>
                <p class="subtitle">Join our community and enjoy a seamless shopping experience.</p>
                <form class="register-form" method="post" name="register" onSubmit="return valid();">
                    <div class="form-group">
                        <label for="fullname">Full Name <span>*</span></label>
                        <input type="text" class="form-control" id="fullname" name="fullname" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address <span>*</span></label>
                        <input type="email" class="form-control" id="email" onBlur="userAvailability()" name="emailid" required>
                        <span id="user-availability-status1" style="font-size:12px;"></span>
                    </div>
                    <div class="form-group">
                        <label for="contactno">Contact No. <span>*</span></label>
                        <input type="text" class="form-control" id="contactno" name="contactno" maxlength="10" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password <span>*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmpassword">Confirm Password <span>*</span></label>
                        <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" required>
                    </div>
                    <button type="submit" name="submit" class="btn-auth">Sign Up</button>
                </form>
                <div class="checklist">
                    <label><i class="fas fa-check-circle" style="color:#C47A5E;"></i> Speed your way through the checkout</label>
                    <label><i class="fas fa-check-circle" style="color:#C47A5E;"></i> Track your orders easily</label>
                    <label><i class="fas fa-check-circle" style="color:#C47A5E;"></i> Keep a record of all your purchases</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Brand Slider -->
    <?php include('includes/brands-slider.php'); ?>
</div>

<!-- Footer -->
<?php include('includes/footer.php'); ?>

<!-- Scripts -->
<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/echo.min.js"></script>
<script src="assets/js/wow.min.js"></script>
<script>
    $(document).ready(function(){
        if($('#brand-slider').length) {
            $("#brand-slider").owlCarousel({
                autoPlay: 3000,
                items: 5,
                itemsDesktop: [1199, 4],
                itemsDesktopSmall: [979, 3],
                navigation: true,
                pagination: false
            });
        }
    });
</script>
</body>
</html>