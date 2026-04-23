<?php
session_start();
include_once('./includes/config.php');
error_reporting(0);

if(strlen($_SESSION["aid"]) == 0) {   
    header('location: logout.php');
    exit();
}

if(isset($_GET['approve'])) {
    $cid = intval($_GET['approve']);
    mysqli_query($con, "UPDATE blog_comments SET status='approved' WHERE id=$cid");
    header('location: manage-comments.php');
    exit();
}
if(isset($_GET['delete'])) {
    $cid = intval($_GET['delete']);
    mysqli_query($con, "DELETE FROM blog_comments WHERE id=$cid");
    header('location: manage-comments.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Manage Comments | Triple A ShopOnline</title>
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
        }
    </style>
</head>
<body>

<?php include_once('./includes/header.php'); ?>

<div class="container-fluid px-0">
    <div class="row g-0">
        <div class="col-md-3 col-lg-2">
            <?php include_once('./includes/sidebar.php'); ?>
        </div>
        <div class="col-md-9 col-lg-10">
            <div class="px-4 pt-3">
                <div class="d-flex justify-content-between align-items-center mt-2 mb-4">
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;">Manage Comments</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="manage-blog.php">Blog Management</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Manage Comments</li>
                        </ol>
                    </nav>
                </div>

                <div class="data-card">
                    <div class="card-header-custom">
                        <span><i class="fas fa-comments me-2"></i> Pending Comments</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>Post</th>
                                    <th>Name</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $comments = mysqli_query($con, "SELECT c.*, b.title FROM blog_comments c JOIN blog b ON c.post_id=b.id WHERE c.status='pending' ORDER BY c.created_at DESC");
                                if(mysqli_num_rows($comments) > 0) {
                                    while($c = mysqli_fetch_assoc($comments)) {
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($c['title']); ?></td>
                                    <td><?php echo htmlspecialchars($c['user_name']); ?></td>
                                    <td><?php echo htmlspecialchars($c['comment']); ?></td>
                                    <td><?php echo htmlspecialchars($c['created_at']); ?></td>
                                    <td>
                                        <a href="manage-comments.php?approve=<?php echo $c['id']; ?>" class="btn-action" title="Approve"><i class="fas fa-check-circle"></i></a>
                                        <a href="manage-comments.php?delete=<?php echo $c['id']; ?>" class="btn-action" title="Delete" onclick="return confirm('Delete this comment?')"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                } else {
                                    echo '<tr><td colspan="5" class="text-center">No pending comments. <a href="manage-blog.php" class="btn-action">Go to Blog</a></td></tr>';
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

<?php include_once('./includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>