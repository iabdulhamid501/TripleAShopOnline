<?php
session_start();
include_once('includes/config.php');
if(strlen($_SESSION["aid"]) == 0) { 
    header('location: logout.php'); 
    exit(); 
}

// Assign delivery
if(isset($_POST['assign'])) {
    $order_id = intval($_POST['order_id']);
    $delivery_id = intval($_POST['delivery_id']);
    $update = mysqli_prepare($con, "UPDATE orders SET delivery_id = ? WHERE id = ?");
    mysqli_stmt_bind_param($update, "ii", $delivery_id, $order_id);
    mysqli_stmt_execute($update);
    echo "<script>alert('Order assigned to delivery staff');</script>";
}

// Fetch orders without assigned delivery and not delivered/cancelled
$orders_query = "SELECT o.id, o.orderDate, o.orderStatus, u.name, o.paymentMethod 
                 FROM orders o 
                 JOIN users u ON o.userId = u.id 
                 WHERE (o.delivery_id IS NULL OR o.delivery_id = 0)
                   AND o.orderStatus NOT IN ('Delivered', 'Cancelled')
                 GROUP BY o.id";
$orders = mysqli_query($con, $orders_query);

// Fetch all delivery staff
$delivery_staff = mysqli_query($con, "SELECT id, username, fullname FROM delivery ORDER BY username");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Assign Delivery | Admin Dashboard</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* VELORIA Admin Global (same as dashboard) */
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
        .veloria-card {
            border: none;
            border-radius: 24px;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: all 0.2s;
        }
        .veloria-card .card-header {
            background: transparent;
            border-bottom: 1px solid #F0E9E3;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .veloria-card .card-body {
            padding: 1.5rem;
        }
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
        .btn-veloria-primary {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            color: white;
        }
        .btn-veloria-primary:hover {
            background: #B0694B;
            color: white;
        }
        .form-label {
            font-weight: 500;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        .form-select, .form-control {
            border-radius: 12px;
            border: 1px solid #E2DAD4;
            padding: 0.6rem 1rem;
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
    <!-- Header (full width) -->
    <?php include_once('includes/header.php'); ?>
    
    <div class="admin-content-wrapper">
        <!-- Sidebar (left) -->
        <?php include_once('includes/sidebar.php'); ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Assign Delivery</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb veloria-breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Assign Delivery</li>
                    </ol>
                </nav>
            </div>

            <!-- Assign Delivery Card -->
            <div class="card veloria-card">
                <div class="card-header">
                    <i class="fas fa-truck me-2"></i> Assign Order to Delivery Staff
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Select Order</label>
                                <select name="order_id" class="form-select" required>
                                    <option value="">-- Choose Order --</option>
                                    <?php while($ord = mysqli_fetch_assoc($orders)): ?>
                                        <option value="<?php echo $ord['id']; ?>">
                                            Order #<?php echo $ord['id']; ?> - <?php echo htmlspecialchars($ord['name']); ?> (<?php echo $ord['orderStatus']; ?>)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Assign to Delivery Staff</label>
                                <select name="delivery_id" class="form-select" required>
                                    <option value="">-- Choose Delivery --</option>
                                    <?php while($del = mysqli_fetch_assoc($delivery_staff)): ?>
                                        <option value="<?php echo $del['id']; ?>">
                                            <?php echo htmlspecialchars($del['username'] . ' (' . $del['fullname'] . ')'); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" name="assign" class="btn btn-veloria-primary">
                                <i class="fas fa-check-circle me-1"></i> Assign Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <?php include_once('includes/footer.php'); ?>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>