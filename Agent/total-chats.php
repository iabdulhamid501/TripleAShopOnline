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

// Fetch all chat sessions (open + closed) for this agent
$query = "SELECT cs.id as session_id, cs.order_id, cs.product_size, cs.status, cs.created_at as chat_started,
                 u.name as customer_name, u.email as customer_email,
                 o.orderDate, o.orderStatus,
                 p.productName, p.productImage1
          FROM chat_sessions cs
          JOIN users u ON cs.customer_id = u.id
          JOIN orders o ON cs.order_id = o.id
          JOIN products p ON o.productId = p.id
          WHERE cs.agent_id = ?
          ORDER BY cs.updated_at DESC";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $agent_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Total Chats | Triple A ShopOnline</title>
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
        .data-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
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
        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 12px;
        }
        .btn-view {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0.3rem 1rem;
            font-weight: 500;
            font-size: 0.75rem;
            color: white;
            text-decoration: none;
            transition: 0.2s;
            display: inline-block;
        }
        .btn-view:hover {
            background: #A85E44;
        }
        .badge-open {
            background: #2E7D32;
            color: white;
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .badge-closed {
            background: #6c757d;
            color: white;
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
        }
        .breadcrumb-custom .breadcrumb-item a {
            color: #C47A5E;
            text-decoration: none;
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 16px;
            }
            .table-custom {
                min-width: 700px;
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
                <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Total Chats</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-custom">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Total Chats</li>
                    </ol>
                </nav>
            </div>

            <div class="data-card">
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Size</th>
                                <th>Started</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(mysqli_num_rows($result) > 0) {
                            $cnt = 1;
                            while($row = mysqli_fetch_assoc($result)) {
                                $img_path = "../admin/productimages/" . $row['order_id'] . "/" . $row['productImage1'];
                                if(!file_exists($img_path) || empty($row['productImage1'])) {
                                    $img_path = "../admin/productimages/placeholder.jpg";
                                }
                                $status_class = ($row['status'] == 'open') ? 'badge-open' : 'badge-closed';
                                $status_text = ($row['status'] == 'open') ? 'Open' : 'Closed';
                        ?>
                            <tr>
                                <td><?php echo $cnt++; ?></td>
                                <td><img src="<?php echo $img_path; ?>" class="product-img" alt="Product"></td>
                                <td>#<?php echo $row['order_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['customer_name']); ?><br><small><?php echo htmlspecialchars($row['customer_email']); ?></small></td>
                                <td><?php echo htmlspecialchars($row['productName']); ?></td>
                                <td><?php echo htmlspecialchars($row['product_size'] ?: 'N/A'); ?></td>
                                <td><?php echo date('d M Y H:i', strtotime($row['chat_started'])); ?></td>
                                <td><span class="<?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                <td><a href="chat.php?session_id=<?php echo $row['session_id']; ?>" class="btn-view"><?php echo ($row['status'] == 'open') ? 'Join Chat' : 'View Transcript'; ?></a></td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo '<tr><td colspan="9" class="text-center">No chat sessions found.<?td?>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <?php include('includes/footer.php'); ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>