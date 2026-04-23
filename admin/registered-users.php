<?php 
session_start();
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
    <title>Manage Registered Users | Triple A ShopOnline</title>
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
            text-decoration: underline;
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
                min-width: 800px;
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
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2">
            <?php include_once('includes/sidebar.php'); ?>
        </div>
        <!-- Main content -->
        <div class="col-md-9 col-lg-10">
            <div class="px-4 pt-3">
                <div class="d-flex justify-content-between align-items-center mt-2 mb-4">
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Manage Registered Users</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Users</li>
                        </ol>
                    </nav>
                </div>

                <div class="data-card">
                    <div class="card-header-custom">
                        <span><i class="fas fa-users me-2"></i> Registered Users</span>
                        <input type="text" id="searchInput" class="search-box" placeholder="Search by name or email...">
                    </div>
                    <div class="table-responsive">
                        <table class="table-custom" id="usersTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact No</th>
                                    <th>Registration Date</th>
                                    <th>Last Updated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php
                                $query = mysqli_query($con, "SELECT * FROM users");
                                if(mysqli_num_rows($query) > 0) {
                                    $cnt = 1;
                                    while($row = mysqli_fetch_assoc($query)) {
                                ?>
                                <tr class="user-row">
                                    <td><?php echo $cnt; ?></td>
                                    <td class="user-name"><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td class="user-email"><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['contactno']); ?></td>
                                    <td><?php echo htmlspecialchars($row['regDate']); ?></td>
                                    <td><?php echo htmlspecialchars($row['updationDate']); ?></td>
                                    <td>
                                        <a href="user-orders.php?uid=<?php echo $row['id']; ?>&uname=<?php echo urlencode($row['name']); ?>" class="btn-action">View Orders</a>
                                    </td>
                                </tr>
                                <?php 
                                        $cnt++;
                                    }
                                } else {
                                    echo '<tr><td colspan="7" class="text-center">No registered users found.</td></tr>';
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

<!-- Bootstrap 5 JS Bundle (includes Popper for dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Simple search filter (no external DataTables) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if(searchInput) {
            searchInput.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#tableBody .user-row');
                rows.forEach(row => {
                    const name = row.querySelector('.user-name')?.innerText.toLowerCase() || '';
                    const email = row.querySelector('.user-email')?.innerText.toLowerCase() || '';
                    if(name.includes(filter) || email.includes(filter)) {
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