<?php
session_start();
include_once('includes/config.php');
if(strlen($_SESSION["aid"]) == 0) { 
    header('location: logout.php'); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Manage Delivery Staff | Triple A ShopOnline</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
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
        /* Card & table styling */
        .stat-card, .data-card {
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            background: white;
            overflow: hidden;
        }
        .btn-custom {
            background: #C47A5E;
            border-radius: 40px;
            color: white;
            padding: 0.4rem 1.2rem;
            font-weight: 500;
            border: none;
            transition: 0.2s;
        }
        .btn-custom:hover {
            background: #A85E44;
            color: white;
        }
        .btn-outline-custom {
            border-radius: 40px;
            border: 1px solid #C47A5E;
            color: #C47A5E;
            padding: 0.3rem 1rem;
            font-size: 0.8rem;
        }
        .btn-outline-custom:hover {
            background: #C47A5E;
            color: white;
        }
        .table thead th {
            background: #F4EFEA;
            border-bottom: none;
            font-weight: 600;
        }
        .table td {
            vertical-align: middle;
        }
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
            margin-bottom: 1.5rem;
        }
        .breadcrumb-custom .breadcrumb-item a {
            color: #C47A5E;
            text-decoration: none;
        }
        .page-title {
            font-family: 'Instrument Serif', serif;
            font-size: 1.8rem;
            margin-bottom: 0;
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 16px;
            }
            .table-responsive {
                font-size: 0.85rem;
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
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <h1 class="page-title">Manage Delivery Staff</h1>
                <a href="add-delivery.php" class="btn btn-custom"><i class="fas fa-plus me-1"></i> Add Delivery Staff</a>
            </div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Delivery Staff</li>
                </ol>
            </nav>

            <div class="data-card card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $query = mysqli_query($con, "SELECT * FROM delivery ORDER BY id DESC");
                            if(mysqli_num_rows($query) > 0):
                                while($row = mysqli_fetch_assoc($query)):
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td>
                                        <a href="edit-delivery.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-custom btn-sm me-1"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="delete-delivery.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this delivery staff?')"><i class="fas fa-trash-alt"></i> Delete</a>
                                    </td>
                                </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">No delivery staff found.</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer (full width) -->
    <?php include_once('includes/footer.php'); ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>