<?php 
session_start();
error_reporting(0);
include_once('includes/config.php');
if(strlen($_SESSION["aid"])==0) {   
    header('location:logout.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Sales Report | Triple A ShopOnline</title>
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
        .form-card, .result-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 1.8rem;
            margin-bottom: 1.5rem;
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
        .btn-submit {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0.5rem 2rem;
            font-weight: 600;
            color: white;
            transition: 0.2s;
        }
        .btn-submit:hover {
            background: #A85E44;
        }
        .report-header {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2A2826;
            margin-bottom: 1rem;
            text-align: center;
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
            .form-card, .result-card {
                margin: 0 12px 1.5rem;
            }
            .table-custom {
                min-width: 500px;
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
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Sales Report (Between Dates)</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sales Report</li>
                        </ol>
                    </nav>
                </div>

                <!-- Date Range Form -->
                <div class="form-card">
                    <form method="post">
                        <div class="row g-4">
                            <div class="col-md-5">
                                <label class="form-label">From Date</label>
                                <input type="date" name="fromdate" class="form-control" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">To Date</label>
                                <input type="date" name="todate" class="form-control" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" name="submit" class="btn-submit w-100">Generate Report</button>
                            </div>
                        </div>
                    </form>
                </div>

                <?php if (isset($_POST['submit'])) { 
                    $fdate = $_POST['fromdate'];
                    $tdate = $_POST['todate'];
                    // Calculate total amount per month/year by summing (quantity * productPrice + shippingCharge)
                    $query = mysqli_query($con, "SELECT 
                        MONTH(orders.orderDate) AS ordermonth, 
                        YEAR(orders.orderDate) AS orderyear,
                        SUM((orders.quantity * products.productPrice) + products.shippingCharge) AS totalamount
                    FROM orders 
                    JOIN products ON products.id = orders.productId 
                    WHERE DATE(orders.orderDate) BETWEEN '$fdate' AND '$tdate'
                    GROUP BY ordermonth, orderyear
                    ORDER BY orderyear DESC, ordermonth DESC");
                    
                    if (!$query) {
                        echo '<div class="result-card text-center text-danger">Database error: ' . mysqli_error($con) . '</div>';
                    } else if (mysqli_num_rows($query) > 0) {
                        $gamount = 0;
                ?>
                <div class="result-card">
                    <div class="report-header">
                        <i class="fas fa-chart-line"></i> Sales Report from <?php echo htmlspecialchars($fdate); ?> to <?php echo htmlspecialchars($tdate); ?>
                    </div>
                    <div class="table-responsive">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Month - Year</th>
                                    <th>Total Sales (₦)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cnt = 1;
                                while($row = mysqli_fetch_assoc($query)) {
                                    $total = $row['totalamount'];
                                    $gamount += $total;
                                ?>
                                <tr>
                                    <td><?php echo $cnt++; ?></td>
                                    <td><?php echo $row['ordermonth'] . ' - ' . $row['orderyear']; ?></td>
                                    <td>₦<?php echo number_format($total, 2); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr style="background:#FCF9F5; font-weight:bold;">
                                    <th colspan="2">Grand Total</th>
                                    <th>₦<?php echo number_format($gamount, 2); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <?php 
                    } else {
                        echo '<div class="result-card text-center">No sales found between the selected dates.</div>';
                    }
                } ?>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>