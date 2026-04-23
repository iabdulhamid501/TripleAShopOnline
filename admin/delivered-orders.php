<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Correct admin session variable
if(strlen($_SESSION["aid"]) == 0) {   
    header('location: logout.php');
    exit();
}

date_default_timezone_set('Asia/Kolkata');
$currentTime = date('d-m-Y h:i:s A', time());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Delivered Orders | Triple A ShopOnline</title>
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
            margin-bottom: 1.5rem;
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
        .btn-details {
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
        .btn-details:hover {
            background: #A85E44;
        }
        .badge-delivered {
            background: #2e7d32;
            color: white;
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
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
                margin: 0 12px 1.5rem;
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
    <script language="javascript" type="text/javascript">
        var popUpWin=0;
        function popUpWindow(URLStr, left, top, width, height) {
            if(popUpWin) {
                if(!popUpWin.closed) popUpWin.close();
            }
            popUpWin = open(URLStr,'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width='+600+',height='+600+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
        }
    </script>
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="container-fluid px-0">
    <div class="row g-0">
        <div class="col-md-3 col-lg-2">
            <?php include('includes/sidebar.php'); ?>
        </div>
        <div class="col-md-9 col-lg-10">
            <div class="px-4 pt-3">
                <div class="d-flex justify-content-between align-items-center mt-2 mb-4">
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Delivered Orders</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Delivered Orders</li>
                        </ol>
                    </nav>
                </div>

                <div class="data-card">
                    <div class="card-header-custom">
                        <span><i class="fas fa-check-circle me-2"></i> Delivered Order Details</span>
                        <input type="text" id="searchInput" class="search-box" placeholder="Search by name, email or product...">
                    </div>
                    <div class="table-responsive">
                        <table class="table-custom" id="ordersTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order No.</th>
                                    <th>Customer Name</th>
                                    <th>Email / Contact</th>
                                    <th>Products</th>
                                    <th>Order Amount (₦)</th>
                                    <th>Order Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php
                                // Prepare statement to fetch delivered orders, grouped by order, with total amount and product list
                                $sql = "SELECT 
                                            o.id AS order_id, 
                                            o.orderDate, 
                                            o.orderStatus, 
                                            u.name AS username,
                                            u.email AS useremail,
                                            u.contactno AS usercontact,
                                            SUM(o.quantity * p.productPrice) + SUM(p.shippingCharge) AS total_amount,
                                            GROUP_CONCAT(DISTINCT p.productName SEPARATOR ', ') AS products
                                        FROM orders o
                                        JOIN users u ON u.id = o.userId
                                        JOIN products p ON p.id = o.productId
                                        WHERE o.orderStatus = 'Delivered'
                                        GROUP BY o.id, o.orderDate, o.orderStatus, u.name, u.email, u.contactno
                                        ORDER BY o.id DESC";
                                $stmt = mysqli_prepare($con, $sql);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);
                                
                                if (!$result) {
                                    echo '<tr><td colspan="9" class="text-center text-danger">Database error: ' . mysqli_error($con) . '</td></tr>';
                                } else if (mysqli_num_rows($result) > 0) {
                                    $cnt = 1;
                                    while($row = mysqli_fetch_assoc($result)) {
                                        $total_amount = isset($row['total_amount']) ? $row['total_amount'] : 0;
                                ?>
                                <tr class="order-row">
                                    <td><?php echo $cnt++; ?></td>
                                    <td class="order-number">ORD-<?php echo htmlspecialchars($row['order_id']); ?></td>
                                    <td class="customer-name"><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td class="customer-contact"><?php echo htmlspecialchars($row['useremail']); ?> / <?php echo htmlspecialchars($row['usercontact']); ?></td>
                                    <td><?php echo htmlspecialchars($row['products']); ?></td>
                                    <td>₦<?php echo number_format($total_amount, 2); ?></td>
                                    <td><?php echo htmlspecialchars($row['orderDate']); ?></td>
                                    <td><span class="badge-delivered"><?php echo htmlspecialchars($row['orderStatus']); ?></span></td>
                                    <td>
                                        <a href="order-details.php?oid=<?php echo $row['order_id']; ?>" class="btn-details" target="_blank">Details</a>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                    mysqli_stmt_close($stmt);
                                } else {
                                    echo '<tr><td colspan="9" class="text-center">No delivered orders found. --</a></td></tr>';
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

<?php include('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if(searchInput) {
            searchInput.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#tableBody .order-row');
                rows.forEach(row => {
                    const orderNo = row.querySelector('.order-number')?.innerText.toLowerCase() || '';
                    const name = row.querySelector('.customer-name')?.innerText.toLowerCase() || '';
                    const contact = row.querySelector('.customer-contact')?.innerText.toLowerCase() || '';
                    const products = row.cells[4]?.innerText.toLowerCase() || '';
                    if(orderNo.includes(filter) || name.includes(filter) || contact.includes(filter) || products.includes(filter)) {
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