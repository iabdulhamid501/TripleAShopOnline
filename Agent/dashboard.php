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

// Check database connection
if(!$con) {
    die('Database connection failed: ' . mysqli_connect_error());
}

$agent_id = intval($_SESSION['agent_id']);

// Ensure tables exist
$con->query("CREATE TABLE IF NOT EXISTS `chat_sessions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `customer_id` int(11) NOT NULL,
    `agent_id` int(11) NOT NULL,
    `order_id` int(11) NOT NULL,
    `product_size` varchar(50) DEFAULT NULL,
    `status` enum('open','closed') DEFAULT 'open',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$con->query("CREATE TABLE IF NOT EXISTS `chat_messages` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `session_id` int(11) NOT NULL,
    `sender_type` enum('customer','agent') NOT NULL,
    `sender_id` int(11) NOT NULL,
    `message` text NOT NULL,
    `is_read` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Fetch stats using simple queries (agent_id is integer, safe)
$active_chats = 0;
$total_chats = 0;
$assigned_orders = 0;
$resolved_chats = 0;

$active_res = $con->query("SELECT COUNT(*) as cnt FROM chat_sessions WHERE agent_id = $agent_id AND status = 'open'");
if($active_res) { $active_chats = $active_res->fetch_assoc()['cnt']; }

$total_res = $con->query("SELECT COUNT(*) as cnt FROM chat_sessions WHERE agent_id = $agent_id");
if($total_res) { $total_chats = $total_res->fetch_assoc()['cnt']; }

$assigned_res = $con->query("SELECT COUNT(DISTINCT order_id) as cnt FROM chat_sessions WHERE agent_id = $agent_id");
if($assigned_res) { $assigned_orders = $assigned_res->fetch_assoc()['cnt']; }

$resolved_res = $con->query("SELECT COUNT(*) as cnt FROM chat_sessions WHERE agent_id = $agent_id AND status = 'closed'");
if($resolved_res) { $resolved_chats = $resolved_res->fetch_assoc()['cnt']; }

// Fetch active chat sessions
$query_sql = "SELECT cs.*, u.name as customer_name, p.productName 
    FROM chat_sessions cs 
    JOIN users u ON cs.customer_id = u.id 
    JOIN orders o ON cs.order_id = o.id
    JOIN products p ON o.productId = p.id
    WHERE cs.agent_id = $agent_id AND cs.status = 'open' 
    ORDER BY cs.updated_at DESC";
$query_result = $con->query($query_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Agent Dashboard | Triple A ShopOnline</title>
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
        .stat-card {
            border: none;
            border-radius: 24px;
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.08);
        }
        .stat-card .card-body {
            padding: 1.5rem;
        }
        .stat-card .stat-title {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.8;
        }
        .stat-card .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            line-height: 1.2;
        }
        .stat-card .card-footer {
            background: rgba(0,0,0,0.03);
            border-top: 1px solid rgba(255,255,255,0.1);
            padding: 0.75rem 1.5rem;
        }
        .stat-card .card-footer a {
            text-decoration: none;
            font-weight: 500;
            font-size: 0.8rem;
        }
        .bg-veloria-primary { background: #C47A5E; }
        .bg-veloria-dark { background: #2A2826; }
        .bg-veloria-success { background: #2E7D32; }
        .bg-veloria-warning { background: #E65100; }
        .bg-veloria-info { background: #1E88E5; }
        .veloria-breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 1.5rem;
        }
        .veloria-breadcrumb .breadcrumb-item a {
            color: #C47A5E;
            text-decoration: none;
        }
        .table-custom {
            width: 100%;
            border-collapse: collapse;
        }
        .table-custom th,
        .table-custom td {
            padding: 12px 10px;
            border-bottom: 1px solid #F0E9E3;
            vertical-align: middle;
        }
        .table-custom th {
            background: #FCF9F5;
            font-weight: 600;
            color: #4A4440;
            font-size: 0.85rem;
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 16px;
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
                <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Agent Dashboard</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb veloria-breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>

            <!-- Stat Cards Row -->
            <div class="row g-4 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card bg-veloria-primary text-white h-100">
                        <div class="card-body">
                            <div class="stat-title">Active Chats</div>
                            <div class="stat-number"><?php echo $active_chats; ?></div>
                        </div>
                        <div class="card-footer">
                            <a href="active-chats.php" class="text-white stretched-link">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card bg-veloria-info text-white h-100">
                        <div class="card-body">
                            <div class="stat-title">Total Chats</div>
                            <div class="stat-number"><?php echo $total_chats; ?></div>
                        </div>
                        <div class="card-footer">
                            <a href="total-chats.php" class="text-white stretched-link">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card bg-veloria-warning text-white h-100">
                        <div class="card-body">
                            <div class="stat-title">Assigned Orders</div>
                            <div class="stat-number"><?php echo $assigned_orders; ?></div>
                        </div>
                        <div class="card-footer">
                            <a href="assigned-orders.php" class="text-white stretched-link">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card bg-veloria-success text-white h-100">
                        <div class="card-body">
                            <div class="stat-title">Resolved Chats</div>
                            <div class="stat-number"><?php echo $resolved_chats; ?></div>
                        </div>
                        <div class="card-footer">
                            <a href="resolved-chats.php" class="text-white stretched-link">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Chats Table -->
            <div class="card" style="border-radius:24px; border:1px solid #EFE8E2;">
                <div class="card-header" style="background:white; font-weight:600;">Active Chat Sessions</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-custom">
                            <thead>
                                <tr><th>Customer</th><th>Product</th><th>Size</th><th>Started</th><th>Action</th></tr>
                            </thead>
                            <tbody>
                            <?php if($query_result && $query_result->num_rows > 0): ?>
                                <?php while($row = $query_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['productName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['product_size'] ?: 'N/A'); ?></td>
                                    <td><?php echo $row['created_at']; ?></td>
                                    <td><a href="chat.php?session_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary" style="background:#C47A5E; border-radius:40px;">Join Chat</a></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center">No active chat sessions.<?td?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include('includes/footer.php'); ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>