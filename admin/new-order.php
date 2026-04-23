<?php 
session_start();
include_once('includes/config.php');
error_reporting(0);

if(strlen($_SESSION["aid"]) == 0) {   
    header('location: logout.php');
    exit();
}

// Count new orders (pending payment)
$new_orders_query = mysqli_query($con, "SELECT COUNT(*) as new_count FROM orders WHERE paymentMethod IS NULL");
$new_orders_result = mysqli_fetch_assoc($new_orders_query);
$new_orders_count = $new_orders_result['new_count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Manage Orders | Triple A ShopOnline</title>
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
        .data-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
        .data-card .card-header-custom {
            font-family: 'Instrument Serif', serif;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            border-left: 4px solid #C47A5E;
            padding-left: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .search-box {
            background: #F5F0EB;
            border: none;
            border-radius: 40px;
            padding: 0.4rem 1rem;
            width: 250px;
            font-size: 0.85rem;
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
        .table-custom tr,
        .table-custom td,
        .table-custom th,
        .table-custom img,
        .order-img,
        .btn-action,
        .badge-pending,
        .badge-paid,
        .badge-awaiting {
            transition: none !important;
            transform: none !important;
            animation: none !important;
        }
        .table-custom tr:hover {
            background-color: transparent !important;
            transform: none !important;
        }
        .btn-action {
            color: #C47A5E;
            text-decoration: none;
            display: inline-block;
        }
        .btn-action:hover {
            color: #A85E44;
        }
        .badge-pending {
            background: #E65100;
            color: white;
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
        }
        .badge-paid {
            background: #2E7D32;
            color: white;
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
        }
        .badge-awaiting {
            background: #E65100;
            color: white;
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
        }
        .order-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 12px;
            background: #F5F0EB;
            display: block;
        }
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
        }
        .breadcrumb-custom .breadcrumb-item a {
            color: #C47A5E;
            text-decoration: none;
        }
        .new-orders-alert {
            border-radius: 40px;
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            background: #C47A5E20;
            border-left: 4px solid #C47A5E;
            color: #2A2826;
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
            .data-card {
                margin: 0 12px;
                overflow-x: auto;
            }
            .table-custom {
                min-width: 1000px;
            }
            .search-box {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<?php include_once('includes/header.php'); ?>

<div class="container-fluid px-0">
    <div class="row g-0">
        <div class="col-md-3 col-lg-2">
            <?php include_once('includes/sidebar.php'); ?>
        </div>
        <div class="col-md-9 col-lg-10">
            <div class="px-4 pt-3">
                <div class="d-flex justify-content-between align-items-center mt-2 mb-4">
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Manage Orders</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">All Orders</li>
                        </ol>
                    </nav>
                </div>

                <?php if($new_orders_count > 0): ?>
                <div class="new-orders-alert">
                    <i class="fas fa-bell"></i> You have <strong><?php echo $new_orders_count; ?></strong> new order(s) awaiting payment processing.
                </div>
                <?php endif; ?>

                <div class="data-card">
                    <div class="card-header-custom">
                        <span><i class="fas fa-shopping-cart me-2"></i> Order Details</span>
                        <input type="text" id="searchInput" class="search-box" placeholder="Search by order number or name...">
                    </div>
                    <div class="table-responsive">
                        <table class="table-custom" id="ordersTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Order No.</th>
                                    <th>Customer Name</th>
                                    <th>Order Amount (₦)</th>
                                    <th>Order Date</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php
                                // FIXED: Show ALL orders EXCEPT those with processed statuses
                                $query = "
                                    SELECT 
                                        order_totals.id, 
                                        order_totals.orderDate, 
                                        order_totals.name, 
                                        order_totals.totalAmount,
                                        order_totals.paymentMethod,
                                        first_img.pid AS product_id,
                                        first_img.image AS productImage
                                    FROM (
                                        SELECT o.id, o.orderDate, u.name, SUM(o.quantity * p.productPrice) as totalAmount, o.paymentMethod
                                        FROM orders o
                                        JOIN users u ON u.id = o.userId
                                        JOIN products p ON p.id = o.productId
                                        WHERE (o.orderStatus NOT IN ('Packed', 'Dispatched', 'In Transit', 'Out For Delivery', 'Delivered', 'Cancelled')
                                               OR o.orderStatus IS NULL)
                                        GROUP BY o.id, o.orderDate, u.name, o.paymentMethod
                                    ) AS order_totals
                                    LEFT JOIN (
                                        SELECT o2.id, MIN(p2.id) as pid, p2.productImage1 as image
                                        FROM orders o2
                                        JOIN products p2 ON p2.id = o2.productId
                                        GROUP BY o2.id
                                    ) AS first_img ON first_img.id = order_totals.id
                                    ORDER BY order_totals.id DESC
                                ";
                                $result = mysqli_query($con, $query);
                                
                                if (!$result) {
                                    echo '<tr><td colspan="9" class="text-center text-danger">Database error: ' . mysqli_error($con) . '您</a></td></tr>';
                                } else if (mysqli_num_rows($result) > 0) {
                                    $cnt = 1;
                                    while($row = mysqli_fetch_assoc($result)) {
                                        $orderAmount = isset($row['totalAmount']) ? $row['totalAmount'] : 0;
                                        $productId = $row['product_id'];
                                        $imageFile = $row['productImage'];
                                        $paymentMethod = !empty($row['paymentMethod']) ? htmlspecialchars($row['paymentMethod']) : 'Not Selected';
                                        
                                        // Determine status based on payment method (this is optional – you can also show the actual orderStatus)
                                        if(empty($row['paymentMethod'])) {
                                            $badgeClass = 'badge-pending';
                                            $badgeText = 'Pending';
                                        } elseif($row['paymentMethod'] == 'COD') {
                                            $badgeClass = 'badge-pending';
                                            $badgeText = 'Pending';
                                        } elseif($row['paymentMethod'] == 'Internet Banking') {
                                            $badgeClass = 'badge-awaiting';
                                            $badgeText = 'Awaiting Transfer';
                                        } elseif($row['paymentMethod'] == 'Debit/Credit card' || $row['paymentMethod'] == 'Paystack') {
                                            $badgeClass = 'badge-paid';
                                            $badgeText = 'Paid';
                                        } else {
                                            $badgeClass = 'badge-pending';
                                            $badgeText = 'Pending';
                                        }
                                        
                                        // Corrected image path (inside Admin folder)
                                        $imagePath = "productimages/placeholder.jpg";
                                        if(!empty($productId) && !empty($imageFile)) {
                                            $testPath = "productimages/" . $productId . "/" . $imageFile;
                                            if(file_exists($testPath)) {
                                                $imagePath = $testPath;
                                            }
                                        }
                                ?>
                                <tr class="order-row">
                                    <td><?php echo $cnt++; ?></td>
                                    <td><img src="<?php echo $imagePath; ?>" class="order-img" alt="Product" onerror="this.src='productimages/placeholder.jpg'"></td>
                                    <td class="order-number">ORD-<?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td>₦<?php echo number_format($orderAmount, 2); ?></td>
                                    <td><?php echo htmlspecialchars($row['orderDate']); ?></td>
                                    <td><?php echo $paymentMethod; ?></td>
                                    <td><span class="<?php echo $badgeClass; ?>"><?php echo $badgeText; ?></span></td>
                                    <td>
                                        <a href="order-details.php?oid=<?php echo $row['id']; ?>" class="btn-action" title="View Order Details">
                                            <i class="fas fa-file-alt fa-lg"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                } else {
                                    echo '<tr><td colspan="9" class="text-center">No orders found.</a></td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if(searchInput) {
            searchInput.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#tableBody .order-row');
                rows.forEach(row => {
                    const orderNumber = row.querySelector('.order-number')?.innerText.toLowerCase() || '';
                    const customerName = row.cells[3]?.innerText.toLowerCase() || '';
                    if(orderNumber.includes(filter) || customerName.includes(filter)) {
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