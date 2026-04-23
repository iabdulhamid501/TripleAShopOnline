<?php 
session_start();
include_once('includes/config.php');
error_reporting(0);

if(strlen($_SESSION["aid"]) == 0) {   
    header('location: logout.php');
    exit();
}

$userid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;
$username = 'User';

if($userid > 0) {
    // Fetch username from database to confirm it exists
    $user_query = mysqli_prepare($con, "SELECT name FROM users WHERE id = ?");
    mysqli_stmt_bind_param($user_query, "i", $userid);
    mysqli_stmt_execute($user_query);
    $user_result = mysqli_stmt_get_result($user_query);
    if(mysqli_num_rows($user_result) > 0) {
        $user_row = mysqli_fetch_assoc($user_result);
        $username = htmlspecialchars($user_row['name']);
    } else {
        $userid = 0; // invalid user
        $username = 'Unknown';
    }
    mysqli_stmt_close($user_query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>User Orders | Triple A ShopOnline</title>
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
        .btn-action {
            color: #C47A5E;
            transition: 0.2s;
            text-decoration: none;
        }
        .btn-action:hover {
            color: #A85E44;
        }
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
        }
        .breadcrumb-custom .breadcrumb-item a {
            color: #C47A5E;
            text-decoration: none;
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
                min-width: 700px;
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
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">
                        Manage <?php echo $username; ?>'s Orders
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">User Orders</li>
                        </ol>
                    </nav>
                </div>

                <div class="data-card">
                    <div class="card-header-custom">
                        <span><i class="fas fa-shopping-cart me-2"></i> All Order Details</span>
                        <input type="text" id="searchInput" class="search-box" placeholder="Search by order number or status...">
                    </div>
                    <div class="table-responsive">
                        <table class="table-custom" id="ordersTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order No.</th>
                                    <th>Order By</th>
                                    <th>Order Amount (₦)</th>
                                    <th>Order Date</th>
                                    <th>Order Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php
                                if($userid == 0) {
                                    echo '<tr><td colspan="7" class="text-center text-danger">Invalid user ID or user not found.</a></td></tr>';
                                } else {
                                    // Query to get each order with its total amount (sum of quantity * price)
                                    $sql = "SELECT o.id as orderNumber, o.orderDate, o.orderStatus, u.name,
                                                   SUM(o.quantity * p.productPrice) as totalAmount
                                            FROM orders o
                                            JOIN users u ON u.id = o.userId
                                            JOIN products p ON p.id = o.productId
                                            WHERE o.userId = ?
                                            GROUP BY o.id, o.orderDate, o.orderStatus, u.name
                                            ORDER BY o.id DESC";
                                    $stmt = mysqli_prepare($con, $sql);
                                    mysqli_stmt_bind_param($stmt, "i", $userid);
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);
                                    
                                    if(mysqli_num_rows($result) > 0) {
                                        $cnt = 1;
                                        while($row = mysqli_fetch_assoc($result)) {
                                            $orderAmount = isset($row['totalAmount']) ? $row['totalAmount'] : 0;
                                            $status = !empty($row['orderStatus']) ? htmlspecialchars($row['orderStatus']) : 'Pending';
                                ?>
                                <tr class="order-row">
                                    <td><?php echo $cnt++; ?></td>
                                    <td class="order-number">ORD-<?php echo htmlspecialchars($row['orderNumber']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td>₦<?php echo number_format($orderAmount, 2); ?></td>
                                    <td><?php echo htmlspecialchars($row['orderDate']); ?></td>
                                    <td class="order-status"><?php echo $status; ?></td>
                                    <td>
                                        <a href="order-details.php?oid=<?php echo $row['orderNumber']; ?>" class="btn-action" title="View Order Details">
                                            <i class="fas fa-file-alt fa-lg"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="7" class="text-center">No orders found for this user.</td></tr>';
                                    }
                                    mysqli_stmt_close($stmt);
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
                    const orderStatus = row.querySelector('.order-status')?.innerText.toLowerCase() || '';
                    if(orderNumber.includes(filter) || orderStatus.includes(filter)) {
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