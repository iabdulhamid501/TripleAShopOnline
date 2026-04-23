<?php
session_start();
include_once('./includes/config.php');
error_reporting(0);

if(strlen($_SESSION["aid"]) == 0) {   
    header('location: logout.php');
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$title = $slug = $content = $image = $author = '';

if($id > 0) {
    $stmt = mysqli_prepare($con, "SELECT * FROM blog WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $post = mysqli_fetch_assoc($res);
    if($post) {
        $title = $post['title'];
        $slug = $post['slug'];
        $content = $post['content'];
        $image = $post['image'];
        $author = $post['author'];
    }
    mysqli_stmt_close($stmt);
}

if(isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $slug = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($title)));
    $content = $_POST['content'];
    $author = trim($_POST['author']);
    
    // Handle image upload
    $image_name = $image;
    if($_FILES['image']['name']) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = time() . '.' . $ext;
        $target_dir = "../assets/images/blog/";
        if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image_name);
    }
    
    if($id > 0) {
        $stmt = mysqli_prepare($con, "UPDATE blog SET title=?, slug=?, content=?, image=?, author=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sssssi", $title, $slug, $content, $image_name, $author, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        $stmt = mysqli_prepare($con, "INSERT INTO blog (title, slug, content, image, author) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $title, $slug, $content, $image_name, $author);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    echo "<script>alert('Post saved'); window.location='manage-blog.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title><?php echo $id ? 'Edit' : 'Add'; ?> Blog Post | Triple A ShopOnline</title>
    <!-- Google Fonts + Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CKEditor 5 (free, no API key required) -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
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
                margin: 0 12px;
            }
        }
        /* CKEditor styling */
        .ck-editor__editable {
            min-height: 300px;
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
                    <h1 class="h2" style="font-family: 'Instrument Serif', serif;"><?php echo $id ? 'Edit' : 'Add New'; ?> Blog Post</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="manage-blog.php">Blog Management</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $id ? 'Edit' : 'Add'; ?> Post</li>
                        </ol>
                    </nav>
                </div>

                <div class="form-card">
                    <form method="post" enctype="multipart/form-data">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label">Title *</label>
                                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Author *</label>
                                <input type="text" name="author" class="form-control" value="<?php echo htmlspecialchars($author); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Featured Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <?php if($image): ?>
                                    <div class="mt-2">
                                        <img src="../assets/images/blog/<?php echo htmlspecialchars($image); ?>" width="80" class="rounded">
                                        <small class="text-muted ms-2">Current image</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Content *</label>
                                <textarea name="content" id="blogContent"><?php echo htmlspecialchars($content); ?></textarea>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <button type="submit" name="submit" class="btn-submit"><?php echo $id ? 'Update Post' : 'Publish Post'; ?> <i class="fas fa-save ms-2"></i></button>
                                <a href="manage-blog.php" class="btn-submit" style="background:#E0D6CE; color:#2A2826; text-decoration:none;">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('./includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#blogContent'))
        .catch(error => {
            console.error(error);
        });
</script>
</body>
</html>