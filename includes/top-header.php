<?php 
//session_start();
// No changes to embedded PHP – all original logic preserved
?>

<!-- ========== VELORIA MODERN TOP BAR (matches header style) ========== -->
<style>
	/* VELORIA top bar – clean, minimal, elegant */
	.veloria-top-bar {
		font-family: 'Inter', sans-serif;
		background: #2A2826; /* deep charcoal */
		border-bottom: 1px solid #3F3A36;
		color: #E3DCD5;
	}
	.veloria-top-bar .top-bar {
		padding: 8px 0;
		background: transparent;
		border: none;
	}
	.veloria-top-bar .header-top-inner {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
	}
	.veloria-top-bar .cnt-account ul,
	.veloria-top-bar .cnt-block ul {
		margin: 0;
		padding: 0;
		list-style: none;
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		gap: 20px;
	}
	.veloria-top-bar .cnt-account ul li,
	.veloria-top-bar .cnt-block ul li {
		margin: 0;
	}
	.veloria-top-bar .cnt-account ul li a,
	.veloria-top-bar .cnt-block ul li a {
		color: #E3DCD5;
		font-size: 0.8rem;
		font-weight: 500;
		text-decoration: none;
		transition: all 0.2s ease;
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 4px 0;
	}
	.veloria-top-bar .cnt-account ul li a:hover,
	.veloria-top-bar .cnt-block ul li a:hover {
		color: #C47A5E;
	}
	.veloria-top-bar .cnt-account ul li a i {
		color: #C47A5E;
		font-size: 0.85rem;
	}
	/* Welcome message – subtle accent */
	.veloria-top-bar .cnt-account ul li:first-child a {
		color: #C47A5E;
		font-weight: 600;
	}
	/* Track Order button – modern pill style */
	.veloria-top-bar .cnt-block .dropdown-toggle {
		background: #3F3A36;
		padding: 6px 16px;
		border-radius: 40px;
		border: 1px solid #C47A5E;
		font-weight: 500;
	}
	.veloria-top-bar .cnt-block .dropdown-toggle:hover {
		background: #C47A5E;
		color: #2A2826;
	}
	.veloria-top-bar .cnt-block .dropdown-toggle:hover .key {
		color: #2A2826;
	}
	/* Responsive */
	@media (max-width: 767px) {
		.veloria-top-bar .header-top-inner {
			flex-direction: column;
			text-align: center;
			gap: 8px;
		}
		.veloria-top-bar .cnt-account ul,
		.veloria-top-bar .cnt-block ul {
			justify-content: center;
			gap: 16px;
		}
	}
</style>

<div class="veloria-top-bar">
	<div class="top-bar animate-dropdown">
		<div class="container">
			<div class="header-top-inner">
				<div class="cnt-account">
					<ul class="list-unstyled">
						<?php if(strlen($_SESSION['login'])): ?>
							<li><a href="#"><i class="fas fa-user"></i> Welcome - <?php echo htmlentities($_SESSION['username']); ?></a></li>
						<?php endif; ?>
						<li><a href="my-account.php"><i class="fas fa-user-circle"></i> My Account</a></li>
						<li><a href="my-wishlist.php"><i class="fas fa-heart"></i> Wishlist</a></li>
						<li><a href="my-cart.php"><i class="fas fa-shopping-bag"></i> My Cart</a></li>
						<?php if(strlen($_SESSION['login']) == 0): ?>
							<li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
						<?php else: ?>
							<li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
						<?php endif; ?>
					</ul>
				</div><!-- /.cnt-account -->

				<div class="cnt-block">
					<ul class="list-unstyled list-inline">
						<li class="dropdown dropdown-small">
							<a href="track-orders.php" class="dropdown-toggle">
								<span class="key"><i class="fas fa-map-marker-alt"></i> Track Order</span>
							</a>
						</li>
					</ul>
				</div>
				
				<div class="clearfix"></div>
			</div><!-- /.header-top-inner -->
		</div><!-- /.container -->
	</div><!-- /.header-top -->
</div><!-- /.veloria-top-bar -->

<!-- Ensure Font Awesome is loaded (already in main-header, but safe to include) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">