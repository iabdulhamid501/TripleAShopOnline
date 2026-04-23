<?php
session_start();
include_once('includes/config.php');
if(strlen($_SESSION["aid"]) == 0) { 
    header('location: logout.php'); 
    exit(); 
}

// Fetch delivery staff with unread message count
$deliveries = mysqli_query($con, "SELECT d.*, 
    (SELECT COUNT(*) FROM admin_delivery_messages WHERE delivery_id = d.id AND sender_type = 'delivery' AND is_read = 0) as unread_count
    FROM delivery d ORDER BY d.id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Delivery Chats | Admin Dashboard</title>
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
        .veloria-card {
            border: none;
            border-radius: 24px;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: all 0.2s;
        }
        .veloria-card .card-header {
            background: transparent;
            border-bottom: 1px solid #F0E9E3;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .veloria-card .card-body {
            padding: 0;
        }
        .veloria-breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 1.5rem;
        }
        .veloria-breadcrumb .breadcrumb-item a {
            color: #C47A5E;
            text-decoration: none;
        }
        .veloria-breadcrumb .breadcrumb-item.active {
            color: #7A726C;
        }
        .table-custom {
            width: 100%;
            border-collapse: collapse;
        }
        .table-custom th,
        .table-custom td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #F0E9E3;
            vertical-align: middle;
        }
        .table-custom th {
            background: #FCF9F5;
            font-weight: 600;
            color: #4A4440;
            font-size: 0.85rem;
        }
        .btn-chat {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0.3rem 1.2rem;
            font-weight: 500;
            font-size: 0.8rem;
            color: white;
            text-decoration: none;
            transition: 0.2s;
            display: inline-block;
        }
        .btn-chat:hover {
            background: #A85E44;
            color: white;
        }
        .badge-unread {
            background: #C62828;
            color: white;
            border-radius: 20px;
            padding: 0.2rem 0.6rem;
            font-size: 0.7rem;
            font-weight: 600;
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 16px;
            }
            .table-custom th,
            .table-custom td {
                padding: 0.75rem 1rem;
            }
        }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <?php include_once('includes/header.php'); ?>
    
    <div class="admin-content-wrapper">
        <?php include_once('includes/sidebar.php'); ?>
        
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Delivery Chats</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb veloria-breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Delivery Chats</li>
                    </ol>
                </nav>
            </div>

            <div class="card veloria-card">
                <div class="card-header">
                    <i class="fas fa-truck me-2"></i> Conversations with Delivery Staff
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-custom">
                            <thead>
                                <tr><th>ID</th><th>Name</th><th>Unread</th><th>Action</th></tr>
                            </thead>
                            <tbody>
                            <?php while($del = mysqli_fetch_assoc($deliveries)): ?>
                                <tr>
                                    <td><?php echo $del['id']; ?></td>
                                    <td><?php echo htmlspecialchars($del['username']); ?></td>
                                    <td>
                                        <?php if($del['unread_count'] > 0): ?>
                                            <span class="badge-unread"><?php echo $del['unread_count']; ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">0</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="admin-delivery-chat.php?delivery_id=<?php echo $del['id']; ?>" class="btn-chat">
                                            <i class="fas fa-comment-dots me-1"></i> Chat
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include_once('includes/footer.php'); ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>