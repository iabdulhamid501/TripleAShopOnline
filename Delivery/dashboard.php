<?php
session_start();
if(!isset($_SESSION['delivery_id'])) { 
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

$delivery_id = intval($_SESSION['delivery_id']);

// Ensure orders table has delivery_id column (if not already)
$con->query("ALTER TABLE orders ADD COLUMN IF NOT EXISTS delivery_id INT(11) DEFAULT NULL AFTER orderStatus");

// Fetch statistics for this delivery person
$assigned_orders = 0;
$delivered_orders = 0;
$pending_orders = 0;
$out_for_delivery = 0;

$assigned_res = $con->query("SELECT COUNT(*) as cnt FROM orders WHERE delivery_id = $delivery_id");
if($assigned_res) { $assigned_orders = $assigned_res->fetch_assoc()['cnt']; }

$delivered_res = $con->query("SELECT COUNT(*) as cnt FROM orders WHERE delivery_id = $delivery_id AND orderStatus = 'Delivered'");
if($delivered_res) { $delivered_orders = $delivered_res->fetch_assoc()['cnt']; }

$pending_res = $con->query("SELECT COUNT(*) as cnt FROM orders WHERE delivery_id = $delivery_id AND (orderStatus IS NULL OR orderStatus = '' OR orderStatus = 'Pending')");
if($pending_res) { $pending_orders = $pending_res->fetch_assoc()['cnt']; }

$out_res = $con->query("SELECT COUNT(*) as cnt FROM orders WHERE delivery_id = $delivery_id AND orderStatus = 'Out For Delivery'");
if($out_res) { $out_for_delivery = $out_res->fetch_assoc()['cnt']; }

// Fetch assigned orders for the table (including product price and shipping)
$orders_query = "SELECT o.id, o.orderDate, o.orderStatus, o.quantity, o.productId, p.productPrice, p.shippingCharge,
                        u.name as customer_name, u.email, u.contactno
                 FROM orders o
                 JOIN users u ON o.userId = u.id
                 JOIN products p ON p.id = o.productId
                 WHERE o.delivery_id = $delivery_id
                 ORDER BY o.orderDate DESC";
$orders_result = $con->query($orders_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Delivery Dashboard | Triple A ShopOnline</title>
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
                <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Delivery Dashboard</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb veloria-breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>

            <!-- Stat Cards Row (Order Statistics) -->
            <div class="row g-4 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card bg-veloria-primary text-white h-100">
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
                    <div class="card stat-card bg-veloria-info text-white h-100">
                        <div class="card-body">
                            <div class="stat-title">Out for Delivery</div>
                            <div class="stat-number"><?php echo $out_for_delivery; ?></div>
                        </div>
                        <div class="card-footer">
                            <a href="out-for-delivery.php" class="text-white stretched-link">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card bg-veloria-warning text-white h-100">
                        <div class="card-body">
                            <div class="stat-title">Pending Delivery</div>
                            <div class="stat-number"><?php echo $pending_orders; ?></div>
                        </div>
                        <div class="card-footer">
                            <a href="pending-delivery.php" class="text-white stretched-link">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card bg-veloria-success text-white h-100">
                        <div class="card-body">
                            <div class="stat-title">Delivered</div>
                            <div class="stat-number"><?php echo $delivered_orders; ?></div>
                        </div>
                        <div class="card-footer">
                            <a href="delivered-orders.php" class="text-white stretched-link">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Links Row -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card stat-card bg-veloria-dark text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-comments fa-2x mb-2"></i>
                            <h5 class="card-title">Chat with Admin</h5>
                            <a href="chat-admin.php" class="btn btn-light mt-2">Open Chat</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card stat-card bg-veloria-dark text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user-tie fa-2x mb-2"></i>
                            <h5 class="card-title">Chat with Agent</h5>
                            <a href="chat-agent.php" class="btn btn-light mt-2">Open Chat</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Orders Table -->
            <div class="card" style="border-radius:24px; border:1px solid #EFE8E2;">
                <div class="card-header" style="background:white; font-weight:600;">Assigned Orders</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Amount (₦)</th>
                                    <th>Status</th>
                                    <th>Order Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if($orders_result && $orders_result->num_rows > 0): ?>
                                <?php while($row = $orders_result->fetch_assoc()): 
                                    $total = ($row['quantity'] * $row['productPrice']) + $row['shippingCharge'];
                                ?>
                                <tr>
                                    <td>#<?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['customer_name']); ?><br><small><?php echo htmlspecialchars($row['email']); ?></small></td>
                                    <td>₦<?php echo number_format($total, 2); ?></td>
                                    <td><?php echo htmlspecialchars($row['orderStatus'] ?: 'Pending'); ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['orderDate'])); ?></td>
                                    <td><a href="update-order-status.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary" style="background:#C47A5E; border-radius:40px;">Update Status</a></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center">No orders assigned to you.<?td?>
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