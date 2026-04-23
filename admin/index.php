<?php
session_start();
error_reporting(0);
include("include/config.php");

if(isset($_POST['submit']))
{
	$username = $_POST['username'];
	$password = md5($_POST['password']);
	$ret = mysqli_query($con, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
	$num = mysqli_fetch_array($ret);
	if($num > 0)
	{
		// Set both session variables for compatibility
		$_SESSION['alogin'] = $_POST['username'];  // kept for backward compatibility
		$_SESSION['aid']    = $_POST['username'];  // this is what dashboard.php checks
		$_SESSION['id']     = $num['id'];          // admin ID
		
		header("location:dashboard.php");
		exit();
	}
	else
	{
		$_SESSION['errmsg'] = "Invalid username or password";
		header("location:index.php");
		exit();
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Admin Login | Triple A ShopOnline</title>
    
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #FCF9F5 0%, #EFE6DF 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container {
            width: 100%;
            max-width: 450px;
        }
        .login-card {
            background: white;
            border-radius: 32px;
            box-shadow: 0 20px 35px -10px rgba(0,0,0,0.1);
            overflow: hidden;
            border: 1px solid #EFE8E2;
        }
        .login-header {
            background: #2A2826;
            padding: 28px 24px;
            text-align: center;
            border-bottom: 3px solid #C47A5E;
        }
        .login-header h2 {
            font-family: 'Instrument Serif', serif;
            font-size: 1.8rem;
            font-weight: 500;
            color: #F0E7E0;
            margin: 0;
        }
        .login-header p {
            color: #B8ADA5;
            font-size: 0.8rem;
            margin-top: 6px;
        }
        .login-body {
            padding: 32px 28px;
        }
        .form-group {
            margin-bottom: 24px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.85rem;
            color: #4A4440;
            margin-bottom: 8px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            font-size: 0.9rem;
            border: 1px solid #E0D6CE;
            border-radius: 20px;
            background: #FFFFFF;
            transition: 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .form-group input:focus {
            outline: none;
            border-color: #C47A5E;
            box-shadow: 0 0 0 3px rgba(196,122,94,0.1);
        }
        .btn-login {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 0.9rem;
            color: white;
            width: 100%;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-login:hover {
            background: #A85E44;
            transform: translateY(-2px);
        }
        .error-message {
            background: #F7EAE8;
            border-left: 4px solid #C47A5E;
            padding: 12px 16px;
            border-radius: 16px;
            margin-bottom: 24px;
            font-size: 0.85rem;
            color: #A55;
        }
        .footer-links {
            text-align: center;
            margin-top: 24px;
            font-size: 0.75rem;
            color: #7A726C;
        }
        .footer-links a {
            color: #C47A5E;
            text-decoration: none;
        }
        .footer-links a:hover {
            text-decoration: underline;
        }
        @media (max-width: 500px) {
            .login-body {
                padding: 24px 20px;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h2><i class="fas fa-shield-alt"></i> Admin Portal</h2>
            <p>Triple A ShopOnline – Secure Access</p>
        </div>
        <div class="login-body">
            <?php if(isset($_SESSION['errmsg']) && $_SESSION['errmsg'] != ""): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i> <?php echo htmlentities($_SESSION['errmsg']); ?>
            </div>
            <?php 
                $_SESSION['errmsg'] = "";
            endif; ?>
            <form method="post">
                <div class="form-group">
                    <label for="inputEmail">Username</label>
                    <input type="text" id="inputEmail" name="username" placeholder="Enter your username" required autofocus>
                </div>
                <div class="form-group">
                    <label for="inputPassword">Password</label>
                    <input type="password" id="inputPassword" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" name="submit" class="btn-login">Sign In <i class="fas fa-arrow-right"></i></button>
            </form>
            <div class="footer-links">
                <a href="../index.php"><i class="fas fa-store"></i> Back to Shop</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>