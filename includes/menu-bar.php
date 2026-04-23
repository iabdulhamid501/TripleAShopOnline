<?php
// Preserve all original PHP exactly as given – no changes to embedded PHP
// (Category query remains untouched)
?>

<!-- ========== VELORIA MODERN NAVIGATION (fixed, with individual category links) ========== -->
<style>
	/* VELORIA navigation – clean, minimal, elegant */
	.veloria-nav {
		font-family: 'Inter', sans-serif;
		background: #2A2826;
		border-bottom: 2px solid #C47A5E;
		box-shadow: 0 2px 8px rgba(0,0,0,0.05);
	}
	.veloria-nav .navbar {
		margin-bottom: 0;
		border: none;
		background: transparent;
		padding: 0;
	}
	.veloria-nav .navbar-nav .nav-link {
		color: #E3DCD5 !important;
		font-size: 0.85rem;
		font-weight: 500;
		letter-spacing: 0.5px;
		padding: 12px 18px !important;
		text-transform: uppercase;
		transition: all 0.2s ease;
		border-bottom: 2px solid transparent;
	}
	.veloria-nav .navbar-nav .nav-link:hover,
	.veloria-nav .navbar-nav .nav-link:focus,
	.veloria-nav .navbar-nav .nav-item.active .nav-link {
		color: #C47A5E !important;
		background: transparent !important;
		border-bottom-color: #C47A5E;
	}
	/* Mobile toggle button */
	.veloria-nav .navbar-toggler {
		border-color: #C47A5E;
		background: transparent;
	}
	.veloria-nav .navbar-toggler-icon {
		background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23C47A5E' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
	}
	.veloria-nav .navbar-toggler:hover,
	.veloria-nav .navbar-toggler:focus {
		background-color: #3F3A36;
	}
	@media (max-width: 991px) {
		.veloria-nav .navbar-nav .nav-link {
			padding: 10px 15px !important;
		}
	}
	@media (max-width: 767px) {
		.veloria-nav .navbar-nav .nav-link {
			border-bottom: 1px solid #3F3A36;
		}
		.veloria-nav .navbar-nav .nav-link:hover {
			border-bottom-color: #C47A5E;
		}
	}
</style>

<div class="veloria-nav">
	<nav class="navbar navbar-expand-lg">
		<div class="container">
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="mainNavbar">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<!-- Home link -->
					<li class="nav-item">
						<a class="nav-link active" aria-current="page" href="index.php">Home</a>
					</li>
					
					<!-- Categories – each as a separate nav link -->
					<?php 
					$sql = mysqli_query($con, "select id, categoryName from category");
					while($row = mysqli_fetch_array($sql)) {
					?>
					<li class="nav-item">
						<a class="nav-link" href="category.php?cid=<?php echo $row['id']; ?>">
							<?php echo htmlspecialchars($row['categoryName']); ?>
						</a>
					</li>
					<?php } ?>
					
					<!-- Admin Login link -->
					<li class="nav-item">
						<a class="nav-link" href="admin/">Admin Login</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</div>

<!-- REMOVED duplicate Bootstrap JS – it's already loaded in main-header.php -->