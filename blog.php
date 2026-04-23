<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Ensure blog table exists
$table_check = mysqli_query($con, "SHOW TABLES LIKE 'blog'");
if(mysqli_num_rows($table_check) == 0) {
    $create_table = "CREATE TABLE `blog` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(255) NOT NULL,
        `slug` varchar(255) NOT NULL,
        `content` longtext NOT NULL,
        `image` varchar(255) DEFAULT NULL,
        `author` varchar(100) DEFAULT 'Admin',
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        UNIQUE KEY `slug` (`slug`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    mysqli_query($con, $create_table);

    // Insert sample blog posts
    $sample_posts = [
        [
            'title' => '5 Tips for Choosing the Perfect Laptop',
            'slug' => 'tips-choosing-perfect-laptop',
            'content' => '<p>Choosing a laptop can be overwhelming. Here are 5 tips to help you decide: 1) Determine your budget, 2) Choose the right processor, 3) RAM matters – at least 8GB, 4) Storage type – SSD is faster, 5) Check battery life and portability. Whether you need a laptop for work, study, or gaming, these tips will guide you to the best choice.</p>',
            'image' => 'laptop-tips.jpg',
            'author' => 'Triple A Team'
        ],
        [
            'title' => 'Top 10 Fashion Trends for 2026',
            'slug' => 'top-10-fashion-trends-2026',
            'content' => '<p>Fashion is evolving rapidly. This year we see bold colours, sustainable fabrics, oversized silhouettes, and vintage revivals. Stay ahead of the curve with our curated list of must‑have items for your wardrobe.</p>',
            'image' => 'fashion-trends.jpg',
            'author' => 'Style Editor'
        ],
        [
            'title' => 'How to Care for Your Electronics',
            'slug' => 'care-for-electronics',
            'content' => '<p>Electronics are an investment. Learn how to clean your devices, manage battery health, and protect them from dust and overheating. Proper care extends the life of your gadgets.</p>',
            'image' => 'electronics-care.jpg',
            'author' => 'Tech Expert'
        ]
    ];
    foreach($sample_posts as $post) {
        $stmt = mysqli_prepare($con, "INSERT INTO blog (title, slug, content, image, author) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $post['title'], $post['slug'], $post['content'], $post['image'], $post['author']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Fetch all blog posts
$blog_query = mysqli_query($con, "SELECT * FROM blog ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="Latest news, tips, and updates from Triple A ShopOnline">
    <meta name="author" content="Triple A ShopOnline">
    <title>Blog | Triple A ShopOnline</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <!-- Owl Carousel (for brand slider) -->
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/owl.theme.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #FCF9F5;
            color: #2A2826;
            overflow-x: hidden;
        }
        /* Prevent layout shifts */
        .page-header {
            background: linear-gradient(105deg, #EFE6DF 0%, #FCF9F5 100%);
            border-radius: 32px;
            padding: 48px 32px;
            margin: 32px 0 48px;
            text-align: center;
        }
        .page-header h1 {
            font-size: 2.8rem;
            font-family: 'Instrument Serif', serif;
            margin-bottom: 12px;
        }
        .page-header p {
            font-size: 1rem;
            color: #5F5A56;
        }
        /* Blog card – stable, no shaking */
        .blog-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            overflow: hidden;
            margin-bottom: 2rem;
            transition: box-shadow 0.2s ease;
        }
        .blog-card:hover {
            box-shadow: 0 12px 24px rgba(0,0,0,0.05);
        }
        /* Fixed aspect ratio for images – prevents reflow */
        .blog-img {
            width: 100%;
            aspect-ratio: 16 / 9;
            object-fit: cover;
            display: block;
        }
        .blog-content {
            padding: 1.5rem;
        }
        .blog-title {
            font-family: 'Instrument Serif', serif;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .blog-title a {
            color: #2A2826;
            text-decoration: none;
        }
        .blog-title a:hover {
            color: #C47A5E;
        }
        .blog-meta {
            font-size: 0.8rem;
            color: #7A726C;
            margin-bottom: 1rem;
        }
        .blog-excerpt {
            color: #4A4440;
            line-height: 1.6;
        }
        .read-more {
            display: inline-block;
            margin-top: 1rem;
            color: #C47A5E;
            font-weight: 600;
            text-decoration: none;
        }
        .read-more:hover {
            text-decoration: underline;
        }
        /* Sidebar – stable */
        .sidebar-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #EFE8E2;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .sidebar-card h3 {
            font-family: 'Instrument Serif', serif;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            border-left: 4px solid #C47A5E;
            padding-left: 12px;
        }
        .recent-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .recent-list li {
            margin-bottom: 12px;
            border-bottom: 1px solid #F0E9E3;
            padding-bottom: 10px;
        }
        .recent-list a {
            color: #2A2826;
            text-decoration: none;
            font-weight: 500;
        }
        .recent-list a:hover {
            color: #C47A5E;
        }
        .recent-date {
            font-size: 0.7rem;
            color: #7A726C;
            display: block;
        }
        /* Newsletter button */
        .btn-newsletter {
            background: #C47A5E;
            border: none;
            border-radius: 40px;
            padding: 0.5rem 1rem;
            color: white;
            font-weight: 600;
            transition: 0.2s;
        }
        .btn-newsletter:hover {
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
        /* Owl Carousel – ensure container doesn't jump */
        .brand-slider-wrapper {
            margin-top: 2rem;
        }
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }
            .blog-title {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>

<?php include('includes/top-header.php'); ?>
<?php include('includes/main-header.php'); ?>
<?php include('includes/menu-bar.php'); ?>

<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mt-4">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Blog</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-blog" style="color:#C47A5E;"></i> Our Blog</h1>
        <p>Insights, tips, and stories from the Triple A team</p>
    </div>

    <div class="row">
        <!-- Main Content: Blog Posts -->
        <div class="col-lg-8">
            <?php if(mysqli_num_rows($blog_query) > 0): ?>
                <?php while($post = mysqli_fetch_assoc($blog_query)): ?>
                    <div class="blog-card">
                        <?php
                        $img_path = "assets/images/blog/" . $post['image'];
                        if(!file_exists($img_path) || empty($post['image'])) {
                            $img_path = "assets/images/blog-placeholder.jpg";
                        }
                        ?>
                        <img src="<?php echo $img_path; ?>" class="blog-img" alt="<?php echo htmlspecialchars($post['title']); ?>" onerror="this.src='assets/images/blog-placeholder.jpg'">
                        <div class="blog-content">
                            <h2 class="blog-title"><a href="blog-details.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
                            <div class="blog-meta">
                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($post['author']); ?> &nbsp;|&nbsp;
                                <i class="fas fa-calendar-alt"></i> <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                            </div>
                            <div class="blog-excerpt">
                                <?php 
                                $excerpt = strip_tags($post['content']);
                                if(strlen($excerpt) > 150) {
                                    $excerpt = substr($excerpt, 0, 150) . '...';
                                }
                                echo nl2br(htmlspecialchars($excerpt));
                                ?>
                            </div>
                            <a href="blog-details.php?id=<?php echo $post['id']; ?>" class="read-more">Read more <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="blog-card">
                    <div class="blog-content text-center">
                        <p>No blog posts yet. Check back soon!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Recent Posts -->
            <div class="sidebar-card">
                <h3><i class="fas fa-clock"></i> Recent Posts</h3>
                <ul class="recent-list">
                    <?php
                    $recent_query = mysqli_query($con, "SELECT id, title, created_at FROM blog ORDER BY created_at DESC LIMIT 5");
                    while($recent = mysqli_fetch_assoc($recent_query)):
                    ?>
                    <li>
                        <a href="blog-details.php?id=<?php echo $recent['id']; ?>"><?php echo htmlspecialchars($recent['title']); ?></a>
                        <span class="recent-date"><?php echo date('M d, Y', strtotime($recent['created_at'])); ?></span>
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <!-- Categories (static, no database calls) -->
            <div class="sidebar-card">
                <h3><i class="fas fa-folder"></i> Categories</h3>
                <ul class="recent-list">
                    <li><a href="#">Electronics (4)</a></li>
                    <li><a href="#">Fashion (3)</a></li>
                    <li><a href="#">Home & Living (2)</a></li>
                    <li><a href="#">Tips & Guides (5)</a></li>
                </ul>
            </div>

            <!-- Newsletter Signup (non‑functional, just styling) -->
            <div class="sidebar-card">
                <h3><i class="fas fa-envelope"></i> Stay Updated</h3>
                <p>Subscribe to our newsletter for the latest posts and offers.</p>
                <form action="#" method="post" class="d-flex gap-2">
                    <input type="email" class="form-control" placeholder="Your email" required>
                    <button type="submit" class="btn-newsletter">Subscribe</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Brand Slider (horizontal, stable) -->
    <div class="brand-slider-wrapper">
        <?php include('includes/brands-slider.php'); ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- Scripts -->
<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function(){
        if($('#brand-slider').length) {
            $("#brand-slider").owlCarousel({
                autoPlay: 3000,
                items: 5,
                itemsDesktop: [1199, 4],
                itemsDesktopSmall: [979, 3],
                navigation: true,
                pagination: false
            });
        }
    });
</script>
</body>
</html>