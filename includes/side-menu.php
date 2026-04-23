<style>
	/* VELORIA modern side menu – clean, elegant, minimal */
	.veloria-side-menu {
		font-family: 'Inter', sans-serif;
		background: #FFFFFF;
		border: 1px solid #EFE8E2;
		border-radius: 24px;
		box-shadow: 0 8px 20px rgba(0, 0, 0, 0.02);
		margin-bottom: 28px;
		overflow: hidden;
	}
	.veloria-side-menu .head {
		font-family: 'Instrument Serif', serif;
		font-size: 1.5rem;
		font-weight: 500;
		color: #2A2826;
		padding: 20px 24px;
		border-bottom: 2px solid #C47A5E;
		letter-spacing: -0.3px;
	}
	.veloria-side-menu .head i {
		color: #C47A5E;
		margin-right: 10px;
		font-size: 1.3rem;
	}
	.veloria-side-menu nav {
		padding: 8px 0;
	}
	.veloria-side-menu .nav {
		list-style: none;
		padding: 0;
		margin: 0;
	}
	.veloria-side-menu .nav > li {
		margin: 0;
		padding: 0;
	}
	.veloria-side-menu .nav > li > a {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 14px 24px;
		color: #4A4440;
		font-size: 0.95rem;
		font-weight: 500;
		text-decoration: none;
		border-bottom: 1px solid #F0E9E3;
		transition: all 0.2s ease;
		position: relative;
	}
	.veloria-side-menu .nav > li > a:hover {
		background: #FCF9F5;
		color: #C47A5E;
		padding-left: 30px;
	}
	.veloria-side-menu .nav > li > a i {
		color: #C47A5E;
		font-size: 1rem;
		width: 24px;
		text-align: center;
		transition: 0.2s;
	}
	.veloria-side-menu .nav > li > a:hover i {
		transform: translateX(3px);
	}
	/* Since PHP loop generates multiple links inside one li, we style them as block items */
	.veloria-side-menu .nav > li > a:not(:last-child) {
		border-bottom: 1px solid #F0E9E3;
	}
	/* Responsive */
	@media (max-width: 767px) {
		.veloria-side-menu .head {
			font-size: 1.3rem;
			padding: 16px 20px;
		}
		.veloria-side-menu .nav > li > a {
			padding: 12px 20px;
			font-size: 0.9rem;
		}
	}
</style>

<div class="veloria-side-menu">
	<div class="side-menu animate-dropdown outer-bottom-xs">
		<div class="head">
			<i class="fas fa-th-large"></i> Categories
		</div>        
		<nav class="yamm megamenu-horizontal" role="navigation">
			<ul class="nav">
				<li class="dropdown menu-item">
					<?php 
					// Original category query – preserved exactly
					$sql = mysqli_query($con, "select id,categoryName from category");
					while($row = mysqli_fetch_array($sql)) {
					?>
					<a href="category.php?cid=<?php echo $row['id'];?>" class="dropdown-toggle">
						<i class="fas fa-tag fa-fw"></i>
						<?php echo $row['categoryName'];?>
					</a>
					<?php } ?>
				</li>
			</ul>
		</nav>
	</div>
</div>

<!-- Font Awesome (if not already loaded) – ensure icons work -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">