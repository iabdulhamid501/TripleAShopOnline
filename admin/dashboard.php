<?php 
session_start();
include_once('includes/config.php');
if(strlen($_SESSION["aid"])==0) {   
    header('location:logout.php');
} else { 
    // Dashboard Counts
    $ret = mysqli_query($con, "SELECT 
        COUNT(id) AS totalorders,
        SUM(CASE WHEN orderStatus = '' OR orderStatus IS NULL THEN 1 ELSE 0 END) AS neworders,
        SUM(CASE WHEN orderStatus = 'Packed' THEN 1 ELSE 0 END) AS packedorders,
        SUM(CASE WHEN orderStatus = 'Dispatched' THEN 1 ELSE 0 END) AS dispatchedorders,
        SUM(CASE WHEN orderStatus = 'In Transit' THEN 1 ELSE 0 END) AS intransitorders,
        SUM(CASE WHEN orderStatus = 'Out For Delivery' THEN 1 ELSE 0 END) AS outfdorders,
        SUM(CASE WHEN orderStatus = 'Delivered' THEN 1 ELSE 0 END) AS deliveredorders,
        SUM(CASE WHEN orderStatus = 'Cancelled' THEN 1 ELSE 0 END) AS cancelledorders
        FROM orders");
    $results = mysqli_fetch_assoc($ret);
    $torders       = (int)($results['totalorders'] ?? 0);
    $norders       = (int)($results['neworders'] ?? 0);
    $porders       = (int)($results['packedorders'] ?? 0);
    $dtorders      = (int)($results['dispatchedorders'] ?? 0);
    $intorders     = (int)($results['intransitorders'] ?? 0);
    $otforders     = (int)($results['outfdorders'] ?? 0);
    $deliveredorders = (int)($results['deliveredorders'] ?? 0);
    $cancelledorders = (int)($results['cancelledorders'] ?? 0);

    // Registered Users Count
    $ret1 = mysqli_query($con, "SELECT COUNT(id) AS totalusers FROM users");
    $results1 = mysqli_fetch_assoc($ret1);
    $tregusers = (int)($results1['totalusers'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Admin Dashboard | Triple A ShopOnline</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* VELORIA Admin Global (fully matching agent dashboard style) */
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
        .bg-veloria-danger { background: #C62828; }
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
        /* Optional table styling (kept for consistency) */
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
    <!-- Admin Header (full width) -->
    <?php include_once('includes/header.php'); ?>
    
    <div class="admin-content-wrapper">
        <!-- Sidebar (left, no extra spacing) -->
        <?php include_once('includes/sidebar.php'); ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Dashboard</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb veloria-breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>

            <!-- First Row: Total, New, Packed, Dispatched -->
            <div class="row g-4 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card bg-veloria-primary text-white h-100">
                        <div class="card-body">
                            <div class="stat-title">Total Orders</div>
                            <div class="stat-number"><?php echo $torders; ?></div>
                        </div>
                        <div class="card-footer">
                            <a href="all-orders.php" class="text-white stretched-link">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card bg-veloria-danger text-white h-100">
                        <div class="card-body">
                            <div class="stat-title">New Orders</div>
                            <div class="stat-number"><?php echo $norders; ?></div>
                        </div>
                        <div class="card-footer">
                            <a href="new-order.php" class="text-white stretched-link">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card bg-veloria-warning text-white h-100">
                        <div class="card-body">
                            <div class="stat-title">Packed Orders</div>
                            <div class="stat-number"><?php echo $porders; ?></div>
                        </div>
                        <div class="card-footer">
                            <a href="packed-orders.php" class="text-white stretched-link">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card bg-veloria-info text-white h-100">
                        <div class="card-body">
                            <div class="stat-title">Dispatched Orders</div>
                            <div class="stat-number"><?php echo $dtorders; ?></div>
                        </div>
                        <div class="card-footer">
                            <a href="dispatched-orders.php" class="text-white stretched-link">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Row -->
            <div class="row g-4 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card bg-veloria-warning text-white h-100">
                        <div class="card-body">
                            <div class="stat-title">In Transit Orders</div>
                            <div class="stat-number"><?php echo $intorders; ?></div>
                        </div>
                        <div class="card-footer">
                            <a href="intransit-orders.php" class="text-white stretched-link">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card bg-veloria-primary text-white h-100">
                        <div class="card-body">
                            <div class="stat-title">Out for Delivery</div>
                            <div class="stat-number"><?php echo $otforders; ?></div>
                        </div>
                        <div class="card-footer">
                            <a href="outfordelivery-orders.php" class="text-white stretched-link">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card bg-veloria-success text-white h-100">
                        <div class="card-body">
                            <div class="stat-title">Delivered Orders</div>
                            <div class="stat-number"><?php echo $deliveredorders; ?></div>
                        </div>
                        <div class="card-footer">
                            <a href="delivered-orders.php" class="text-white stretched-link">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card bg-veloria-dark text-white h-100">
                        <div class="card-body">
                            <div class="stat-title">Registered Users</div>
                            <div class="stat-number"><?php echo $tregusers; ?></div>
                        </div>
                        <div class="card-footer">
                            <a href="registered-users.php" class="text-white stretched-link">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Third Row: Cancelled Orders -->
            <div class="row g-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card bg-veloria-danger text-white h-100">
                        <div class="card-body">
                            <div class="stat-title">Cancelled Orders</div>
                            <div class="stat-number"><?php echo $cancelledorders; ?></div>
                        </div>
                        <div class="card-footer">
                            <a href="cancelled-orders.php" class="text-white stretched-link">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer (full width) -->
    <?php include_once('includes/footer.php'); ?>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>