<?php
session_start();
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
    exit();
} else {
    date_default_timezone_set('Asia/Kolkata');
    $currentTime = date('d-m-Y h:i:s A', time());

    if(isset($_GET['del']) && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        mysqli_query($con, "DELETE FROM products WHERE id = '$id'");
        $_SESSION['delmsg'] = "Product deleted !!";
        echo "<script>alert('Product deleted successfully');</script>";
        echo "<script>window.location.href='manage-products.php'</script>";
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Manage Products | Triple A ShopOnline</title>
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
                min-width: 900px;
            }
            .search-box {
                width: 100%;
            }
        }
    </style>
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
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Manage Products</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Products</li>
                        </ol>
                    </nav>
                </div>

                <div class="data-card">
                    <div class="card-header-custom">
                        <span><i class="fas fa-boxes me-2"></i> Products List</span>
                        <input type="text" id="searchInput" class="search-box" placeholder="Search product name...">
                    </div>
                    <div class="table-responsive">
                        <table class="table-custom" id="productsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Subcategory</th>
                                    <th>Company</th>
                                    <th>Creation Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php
                                // FIXED: Use correct column names from your database
                                $query = mysqli_query($con, "SELECT products.id, products.productName, products.productCompany, products.postingDate, 
                                                                    category.categoryName, subcategory.subcategoryName AS subcategory 
                                                            FROM products 
                                                            JOIN category ON category.id = products.category 
                                                            JOIN subcategory ON subcategory.id = products.subCategory");
                                if (!$query) {
                                    // Show database error for debugging (remove in production)
                                    echo '<tr><td colspan="7" class="text-center text-danger">Database error: ' . mysqli_error($con) . '</td></tr>';
                                } else if (mysqli_num_rows($query) > 0) {
                                    $cnt = 1;
                                    while($row = mysqli_fetch_assoc($query)) {
                                ?>
                                <tr class="product-row">
                                    <td><?php echo $cnt++; ?></td>
                                    <td class="product-name"><?php echo htmlspecialchars($row['productName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['categoryName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['subcategory']); ?></td>
                                    <td><?php echo htmlspecialchars($row['productCompany']); ?></td>
                                    <td><?php echo htmlspecialchars($row['postingDate']); ?></td>
                                    <td>
                                        <a href="edit-products.php?id=<?php echo $row['id']; ?>" class="btn-action" title="Edit"><i class="fas fa-edit"></i></a>
                                        <a href="manage-products.php?id=<?php echo $row['id']; ?>&del=delete" class="btn-action" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                } else {
                                    echo '<tr><td colspan="7" class="text-center">No products found. <a href="add-product.php" class="btn-action">Add one now</a></td></tr>';
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
                const rows = document.querySelectorAll('#tableBody .product-row');
                rows.forEach(row => {
                    const productName = row.querySelector('.product-name')?.innerText.toLowerCase() || '';
                    if(productName.includes(filter)) {
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