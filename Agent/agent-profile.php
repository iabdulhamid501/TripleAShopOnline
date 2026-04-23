<?php
session_start();
if(!isset($_SESSION['agent_id'])) { 
    header('location: index.php'); 
    exit(); 
}

// Include database config
if(file_exists('includes/config.php')) {
    include('includes/config.php');
} else {
    die('Configuration file not found.');
}

if(!$con) {
    die('Database connection failed: ' . mysqli_connect_error());
}

$agent_id = intval($_SESSION['agent_id']);
$message = '';
$error = '';

// Fetch current agent data
$stmt = mysqli_prepare($con, "SELECT username, fullname, email FROM agents WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $agent_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$agent = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if(!$agent) {
    header('location: logout.php');
    exit();
}

// Handle profile update
if(isset($_POST['update_profile'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if(empty($fullname) || empty($email)) {
        $error = "Full name and email are required.";
    } else {
        if(!empty($password)) {
            $hashed_password = md5($password);
            $update_stmt = mysqli_prepare($con, "UPDATE agents SET fullname = ?, email = ?, password = ? WHERE id = ?");
            mysqli_stmt_bind_param($update_stmt, "sssi", $fullname, $email, $hashed_password, $agent_id);
        } else {
            $update_stmt = mysqli_prepare($con, "UPDATE agents SET fullname = ?, email = ? WHERE id = ?");
            mysqli_stmt_bind_param($update_stmt, "ssi", $fullname, $email, $agent_id);
        }
        
        if(mysqli_stmt_execute($update_stmt)) {
            $message = "Profile updated successfully!";
            // Refresh agent data
            $agent['fullname'] = $fullname;
            $agent['email'] = $email;
        } else {
            $error = "Update failed. Please try again.";
        }
        mysqli_stmt_close($update_stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Agent Profile | Triple A ShopOnline</title>
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
        .profile-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 2rem;
            max-width: 600px;
            margin: 0 auto;
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
        .btn-save {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0.5rem 2rem;
            font-weight: 600;
            color: white;
            transition: 0.2s;
        }
        .btn-save:hover {
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
            border-radius: 20px;
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 16px;
            }
            .profile-card {
                margin: 0;
            }
        }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <?php include('includes/header.php'); ?>
    
    <div class="admin-content-wrapper">
        <?php include('includes/sidebar.php'); ?>
        
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Agent Profile</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-custom">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>

            <div class="profile-card">
                <?php if($message): ?>
                    <div class="alert alert-success alert-custom"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                <?php if($error): ?>
                    <div class="alert alert-danger alert-custom"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($agent['username']); ?>" disabled>
                        <small class="text-muted">Username cannot be changed</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($agent['fullname']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($agent['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Change Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control" placeholder="New password">
                    </div>
                    <div class="text-center">
                        <button type="submit" name="update_profile" class="btn-save">Update Profile <i class="fas fa-save ms-2"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php include('includes/footer.php'); ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>