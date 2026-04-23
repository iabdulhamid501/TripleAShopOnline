<?php 
session_start();
include_once('includes/config.php');
error_reporting(0);

if(strlen($_SESSION["aid"]) == 0) {   
    header('location: logout.php');
    exit();
} else {

    // ========== AUTO-REPAIR SUB-CATEGORY TABLE ==========
    // Ensure required columns exist (similar to add-subcategory.php)
    
    // Check if 'subcategoryName' column exists; if not, add it (rename old 'subcategory' if present)
    $col_check = mysqli_query($con, "SHOW COLUMNS FROM subcategory LIKE 'subcategoryName'");
    if(mysqli_num_rows($col_check) == 0) {
        $old_col = mysqli_query($con, "SHOW COLUMNS FROM subcategory LIKE 'subcategory'");
        if(mysqli_num_rows($old_col) > 0) {
            mysqli_query($con, "ALTER TABLE subcategory CHANGE subcategory subcategoryName VARCHAR(255) DEFAULT NULL");
        } else {
            mysqli_query($con, "ALTER TABLE subcategory ADD subcategoryName VARCHAR(255) DEFAULT NULL AFTER categoryid");
        }
    }
    
    // Check if 'createdBy' column exists
    $col_check2 = mysqli_query($con, "SHOW COLUMNS FROM subcategory LIKE 'createdBy'");
    if(mysqli_num_rows($col_check2) == 0) {
        mysqli_query($con, "ALTER TABLE subcategory ADD createdBy INT(11) DEFAULT NULL AFTER subcategoryName");
    }
    
    // ========== DELETE SUB-CATEGORY (SECURE) ==========
    if(isset($_GET['del']) && isset($_GET['id'])) {
        $subid = intval($_GET['id']);
        $del_stmt = mysqli_prepare($con, "DELETE FROM subcategory WHERE id = ?");
        mysqli_stmt_bind_param($del_stmt, "i", $subid);
        if(mysqli_stmt_execute($del_stmt)) {
            echo "<script>alert('Sub-Category deleted successfully');</script>";
            echo "<script>window.location.href='manage-subcategories.php';</script>";
            exit();
        } else {
            echo "<script>alert('Delete failed. Please try again.');</script>";
        }
        mysqli_stmt_close($del_stmt);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Manage Sub-Categories | Triple A ShopOnline</title>
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
            margin: 0 4px;
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
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Manage Sub-Categories</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sub-Categories</li>
                        </ol>
                    </nav>
                </div>

                <div class="data-card">
                    <div class="card-header-custom">
                        <span><i class="fas fa-tags me-2"></i> Sub-Categories List</span>
                        <input type="text" id="searchInput" class="search-box" placeholder="Search sub-category...">
                    </div>
                    <div class="table-responsive">
                        <table class="table-custom" id="subcategoriesTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sub Category</th>
                                    <th>Category</th>
                                    <th>Creation Date</th>
                                    <th>Last Updated</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php
                                // LEFT JOIN ensures subcategories without createdBy still show
                                $query = mysqli_query($con, "SELECT category.categoryName, 
                                                                    subcategory.subcategoryName AS subcatname, 
                                                                    subcategory.creationDate, 
                                                                    subcategory.updationDate, 
                                                                    subcategory.id AS subid, 
                                                                    admin.username 
                                                             FROM subcategory 
                                                             LEFT JOIN category ON subcategory.categoryid = category.id 
                                                             LEFT JOIN admin ON admin.id = subcategory.createdBy
                                                             ORDER BY subcategory.id DESC");
                                if(mysqli_num_rows($query) > 0) {
                                    $cnt = 1;
                                    while($row = mysqli_fetch_assoc($query)) {
                                        $createdBy = isset($row['username']) ? htmlspecialchars($row['username']) : 'System';
                                        $subcat_name = !empty($row['subcatname']) ? htmlspecialchars($row['subcatname']) : '—';
                                        $cat_name = !empty($row['categoryName']) ? htmlspecialchars($row['categoryName']) : '—';
                                ?>
                                <tr class="subcat-row">
                                    <td><?php echo $cnt++; ?></td>
                                    <td class="subcat-name"><?php echo $subcat_name; ?></td>
                                    <td><?php echo $cat_name; ?></td>
                                    <td><?php echo htmlspecialchars($row['creationDate']); ?></td>
                                    <td><?php echo !empty($row['updationDate']) ? htmlspecialchars($row['updationDate']) : '—'; ?></td>
                                    <td><?php echo $createdBy; ?></td>
                                    <td>
                                        <a href="edit-subcategory.php?id=<?php echo $row['subid']; ?>" class="btn-action" title="Edit"><i class="fas fa-edit"></i></a>
                                        <a href="manage-subcategories.php?id=<?php echo $row['subid']; ?>&del=delete" class="btn-action" title="Delete" onclick="return confirm('Are you sure you want to delete this sub-category?')"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                } else {
                                    echo '<tr><td colspan="7" class="text-center">No sub-categories found. <a href="add-sub-category.php" class="btn-action">Add one now</a></td></tr>';
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
                const rows = document.querySelectorAll('#tableBody .subcat-row');
                rows.forEach(row => {
                    const subcatName = row.querySelector('.subcat-name')?.innerText.toLowerCase() || '';
                    if(subcatName.includes(filter)) {
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
<?php } ?>