<?php
session_start();
include_once('includes/config.php');
error_reporting(0);

// Correct admin session variable
if(strlen($_SESSION["aid"]) == 0) {   
    header('location: logout.php');
    exit();
}

$oid = isset($_GET['oid']) ? intval($_GET['oid']) : 0;

// Process order update using prepared statements
if(isset($_POST['submit2'])) {
    $status = trim($_POST['status']);
    $remark = trim($_POST['remark']);
    
    if($oid > 0 && !empty($status)) {
        // Insert into order tracking history
        $hist_stmt = mysqli_prepare($con, "INSERT INTO ordertrackhistory(orderId, status, remark) VALUES(?, ?, ?)");
        mysqli_stmt_bind_param($hist_stmt, "iss", $oid, $status, $remark);
        $hist_result = mysqli_stmt_execute($hist_stmt);
        
        // Update order status
        $update_stmt = mysqli_prepare($con, "UPDATE orders SET orderStatus = ? WHERE id = ?");
        mysqli_stmt_bind_param($update_stmt, "si", $status, $oid);
        $update_result = mysqli_stmt_execute($update_stmt);
        
        if($hist_result && $update_result) {
            echo "<script>alert('Order updated successfully...');</script>";
            echo "<script>window.location.href='updateorder.php?oid=$oid';</script>";
        } else {
            echo "<script>alert('Update failed: " . mysqli_error($con) . "');</script>";
        }
        mysqli_stmt_close($hist_stmt);
        mysqli_stmt_close($update_stmt);
    } else {
        echo "<script>alert('Invalid order ID or status.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Update Order | Triple A ShopOnline</title>
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
        .form-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 1.8rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
        .section-title {
            font-family: 'Instrument Serif', serif;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            border-left: 4px solid #C47A5E;
            padding-left: 12px;
        }
        .info-row {
            display: flex;
            flex-wrap: wrap;
            border-bottom: 1px solid #F0E9E3;
            padding: 0.75rem 0;
        }
        .info-label {
            width: 140px;
            font-weight: 600;
            color: #4A4440;
        }
        .info-value {
            flex: 1;
            color: #2A2826;
        }
        .history-table {
            width: 100%;
            border-collapse: collapse;
        }
        .history-table th,
        .history-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #F0E9E3;
            text-align: left;
        }
        .history-table th {
            background: #FCF9F5;
            font-weight: 600;
            color: #4A4440;
        }
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4A4440;
            margin-bottom: 0.5rem;
        }
        .form-control, .form-select {
            border-radius: 20px;
            border: 1px solid #E0D6CE;
            padding: 0.6rem 1rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #C47A5E;
            box-shadow: 0 0 0 3px rgba(196,122,94,0.1);
        }
        .btn-submit {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: 0.2s;
            margin-right: 0.5rem;
        }
        .btn-submit:hover {
            background: #A85E44;
        }
        .btn-secondary {
            background: #E0D6CE;
            color: #2A2826;
        }
        .btn-secondary:hover {
            background: #CEC3B9;
        }
        .delivered-badge {
            background: #2E7D32;
            color: white;
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 0.75rem;
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
            .form-card {
                margin: 0 12px 1.5rem;
            }
            .info-label {
                width: 100%;
                margin-bottom: 4px;
            }
        }
    </style>
    <script language="javascript" type="text/javascript">
        function f2() { window.close(); }
        function f3() { window.print(); }
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
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Update Order #<?php echo $oid; ?></h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="all-orders.php">All Orders</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Update Order</li>
                        </ol>
                    </nav>
                </div>

                <?php if($oid == 0) { ?>
                    <div class="form-card text-center"><p class="text-danger">Invalid order ID.</p><a href="all-orders.php" class="btn-submit">Back to Orders</a></div>
                <?php } else { 
                    // Fetch current order status from orders table
                    $status_stmt = mysqli_prepare($con, "SELECT orderStatus FROM orders WHERE id = ?");
                    mysqli_stmt_bind_param($status_stmt, "i", $oid);
                    mysqli_stmt_execute($status_stmt);
                    $status_res = mysqli_stmt_get_result($status_stmt);
                    $current_status_row = mysqli_fetch_assoc($status_res);
                    $current_status = $current_status_row['orderStatus'] ?? 'Not Processed Yet';
                    mysqli_stmt_close($status_stmt);
                    
                    // Fetch order history
                    $hist_stmt = mysqli_prepare($con, "SELECT * FROM ordertrackhistory WHERE orderId = ? ORDER BY postingDate DESC");
                    mysqli_stmt_bind_param($hist_stmt, "i", $oid);
                    mysqli_stmt_execute($hist_stmt);
                    $hist_res = mysqli_stmt_get_result($hist_stmt);
                ?>
                <!-- Order Info Card -->
                <div class="form-card">
                    <h3 class="section-title"><i class="fas fa-info-circle"></i> Order Information</h3>
                    <div class="info-row"><div class="info-label">Order ID:</div><div class="info-value"><?php echo $oid; ?></div></div>
                    <div class="info-row">
                        <div class="info-label">Current Status:</div>
                        <div class="info-value">
                            <?php if($current_status == 'Not Processed Yet'): ?>
                                <span class="badge-status" style="background:#E65100; color:white; padding:4px 12px; border-radius:40px;">Not Processed Yet</span>
                            <?php elseif($current_status == 'Delivered'): ?>
                                <span class="delivered-badge">Delivered</span>
                            <?php elseif($current_status == 'Cancelled'): ?>
                                <span class="badge-status" style="background:#d9534f; color:white; padding:4px 12px; border-radius:40px;">Cancelled</span>
                            <?php else: ?>
                                <span class="badge-status" style="background:#F5F0EB; color:#C47A5E; padding:4px 12px; border-radius:40px;"><?php echo htmlspecialchars($current_status); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Order History Card -->
                <?php if(mysqli_num_rows($hist_res) > 0) { ?>
                <div class="form-card">
                    <h3 class="section-title"><i class="fas fa-history"></i> Order History</h3>
                    <div class="table-responsive">
                        <table class="history-table">
                            <thead>
                                <tr><th>Date</th><th>Status</th><th>Remark</th></tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($hist_res)) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['postingDate']); ?></td>
                                    <td><span class="badge-status" style="background:#F5F0EB; color:#C47A5E; padding:4px 12px; border-radius:40px;"><?php echo htmlspecialchars($row['status']); ?></span></td>
                                    <td><?php echo nl2br(htmlspecialchars($row['remark'])); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php } ?>

                <!-- Update Form (only if not delivered or cancelled) -->
                <?php if($current_status == 'Delivered' || $current_status == 'Cancelled') { ?>
                <div class="form-card text-center">
                    <p><i class="fas fa-check-circle" style="color:#2E7D32; font-size:2rem;"></i></p>
                    <h4>Order <?php echo $current_status; ?></h4>
                    <p>This order has already been <?php echo strtolower($current_status); ?>. No further updates allowed.</p>
                    <a href="all-orders.php" class="btn-submit" style="background:#E0D6CE; color:#2A2826;">Back to Orders</a>
                </div>
                <?php } else { ?>
                <div class="form-card">
                    <h3 class="section-title"><i class="fas fa-edit"></i> Update Order Status</h3>
                    <form method="post">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="">Select Status</option>
                                    <option value="Packed">Packed</option>
                                    <option value="Dispatched">Dispatched</option>
                                    <option value="In Transit">In Transit</option>
                                    <option value="Out For Delivery">Out For Delivery</option>
                                    <option value="Delivered">Delivered</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Remark</label>
                                <textarea name="remark" class="form-control" rows="3" placeholder="Add remark (optional)"></textarea>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <button type="submit" name="submit2" class="btn-submit"><i class="fas fa-save"></i> Update Order</button>
                                <button type="button" class="btn-submit btn-secondary" onclick="f2();"><i class="fas fa-times"></i> Close Window</button>
                            </div>
                        </div>
                    </form>
                </div>
                <?php } 
                mysqli_stmt_close($hist_stmt);
                } ?>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>