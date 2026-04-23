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

// Fetch all orders assigned to this delivery person
// Now includes full shipping address from users table
$query = "SELECT o.id, o.orderDate, o.orderStatus, o.quantity, o.productId, 
                 p.productPrice, p.shippingCharge, p.productName,
                 u.name as customer_name, u.email, u.contactno,
                 u.shippingAddress, u.shippingCity, u.shippingState, u.shippingPincode
          FROM orders o
          JOIN users u ON o.userId = u.id
          JOIN products p ON p.id = o.productId
          WHERE o.delivery_id = $delivery_id
          ORDER BY o.orderDate DESC";
$result = $con->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Assigned Orders | Triple A ShopOnline</title>
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
        .table-custom td {
            font-size: 0.85rem;
        }
        .btn-action {
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
        .btn-action:hover {
            background: #A85E44;
        }
        .search-box {
            background: #F5F0EB;
            border: none;
            border-radius: 40px;
            padding: 0.4rem 1rem;
            width: 250px;
            font-size: 0.85rem;
        }
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
        }
        .breadcrumb-custom .breadcrumb-item a {
            color: #C47A5E;
            text-decoration: none;
        }
        .contact-info {
            font-size: 0.75rem;
            line-height: 1.3;
            color: #4A4440;
        }
        .contact-info i {
            width: 1.2rem;
            color: #C47A5E;
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 16px;
            }
            .table-custom {
                min-width: 900px;
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
                <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Assigned Orders</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-custom">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Assigned Orders</li>
                    </ol>
                </nav>
            </div>

            <div class="data-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span><i class="fas fa-boxes"></i> Orders assigned to you</span>
                    <input type="text" id="searchInput" class="search-box" placeholder="Search by order ID or customer...">
                </div>
                <div class="table-responsive">
                    <table class="table-custom" id="ordersTable">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Amount (₦)</th>
                                <th>Status</th>
                                <th>Order Date</th>
                                <th>Contact & Shipping Info</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                        <?php if($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): 
                                $total = ($row['quantity'] * $row['productPrice']) + $row['shippingCharge'];
                                $status = htmlspecialchars($row['orderStatus'] ?: 'Pending');
                                $badge_class = ($status == 'Delivered') ? 'bg-success' : (($status == 'Out For Delivery') ? 'bg-info' : 'bg-warning');
                                
                                // Build full shipping address from user's saved address
                                $shipping_parts = [];
                                if(!empty($row['shippingAddress'])) $shipping_parts[] = htmlspecialchars($row['shippingAddress']);
                                if(!empty($row['shippingCity'])) $shipping_parts[] = htmlspecialchars($row['shippingCity']);
                                if(!empty($row['shippingState'])) $shipping_parts[] = htmlspecialchars($row['shippingState']);
                                if(!empty($row['shippingPincode'])) $shipping_parts[] = htmlspecialchars($row['shippingPincode']);
                                $shipping_address = !empty($shipping_parts) ? implode(', ', $shipping_parts) : 'No shipping address provided';
                                
                                $contact = htmlspecialchars($row['contactno'] ?? 'N/A');
                                $email = htmlspecialchars($row['email'] ?? 'N/A');
                            ?>
                            <tr>
                                <td class="order-id">#<?php echo $row['id']; ?></td>
                                <td class="customer-name"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['productName']); ?> (x<?php echo $row['quantity']; ?>)</td>
                                <td>₦<?php echo number_format($total, 2); ?></td>
                                <td><span class="badge <?php echo $badge_class; ?>"><?php echo $status; ?></span></td>
                                <td><?php echo date('d M Y', strtotime($row['orderDate'])); ?></td>
                                <td class="contact-info">
                                    <i class="fas fa-envelope"></i> <?php echo $email; ?><br>
                                    <i class="fas fa-phone-alt"></i> <?php echo $contact; ?><br>
                                    <i class="fas fa-map-marker-alt"></i> <?php echo $shipping_address; ?>
                                </td>
                                <td><a href="update-order-status.php?id=<?php echo $row['id']; ?>" class="btn-action">Update Status</a></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center">No orders assigned to you yet.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <?php include('includes/footer.php'); ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if(searchInput) {
            searchInput.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#tableBody tr');
                rows.forEach(row => {
                    const orderId = row.querySelector('.order-id')?.innerText.toLowerCase() || '';
                    const customerName = row.querySelector('.customer-name')?.innerText.toLowerCase() || '';
                    if(orderId.includes(filter) || customerName.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
</body>
</html>