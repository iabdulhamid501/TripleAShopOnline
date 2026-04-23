<?php
// Preserve all original PHP exactly as given – no changes to embedded PHP
// (Session, cart query, user welcome message, etc. remain untouched)
// Note: session_start() should be called at the top of each page before including this file
?>

<!-- ========== VELORIA MODERN NAVIGATION (matches main-header and menu-bar) ========== -->
<style>
	/* VELORIA navigation – clean, minimal, elegant */
	.veloria-nav {
		font-family: 'Inter', sans-serif;
		background: #2A2826; /* deep charcoal */
		border-bottom: 2px solid #C47A5E; /* warm terracotta accent */
		box-shadow: 0 2px 8px rgba(0,0,0,0.05);
	}
	.veloria-nav .navbar {
		margin-bottom: 0;
		border: none;
		background: transparent;
		padding: 0;
	}
	.veloria-nav .navbar-brand {
		font-family: 'Instrument Serif', serif;
		font-size: 1.6rem;
		font-weight: 500;
		color: #F0E7E0 !important;
		padding: 12px 0;
		margin-right: 30px;
		letter-spacing: -0.5px;
		line-height: 1.2;
	}
	.veloria-nav .navbar-brand:after {
		content: '';
		display: block;
		width: 40px;
		height: 2px;
		background: #C47A5E;
		margin-top: 4px;
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
	/* Dropdown menus */
	.veloria-nav .dropdown-menu {
		background: #2A2826;
		border: 1px solid #C47A5E;
		border-radius: 0 0 12px 12px;
		box-shadow: 0 6px 14px rgba(0,0,0,0.15);
		padding: 8px 0;
		margin-top: 0;
	}
	.veloria-nav .dropdown-menu .dropdown-item {
		color: #E3DCD5 !important;
		padding: 8px 20px;
		font-size: 0.8rem;
		font-weight: 400;
		border-bottom: 1px solid #3F3A36;
	}
	.veloria-nav .dropdown-menu .dropdown-item:hover,
	.veloria-nav .dropdown-menu .dropdown-item:focus {
		background: #3F3A36;
		color: #C47A5E !important;
	}
	.veloria-nav .dropdown-divider {
		border-top-color: #3F3A36;
	}
	/* Welcome message styling */
	.veloria-nav .welcome-msg {
		color: #C47A5E;
		font-size: 0.8rem;
		font-weight: 500;
		margin-right: 20px;
		display: inline-flex;
		align-items: center;
		padding: 8px 0;
	}
	/* Cart button – modern VELORIA style */
	.veloria-nav .btn-cart-european {
		background: #C47A5E;
		border: none;
		border-radius: 40px;
		padding: 8px 20px;
		font-weight: 600;
		color: #2A2826 !important;
		text-transform: uppercase;
		font-size: 0.75rem;
		letter-spacing: 0.5px;
		transition: all 0.2s;
		margin-left: 10px;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		text-decoration: none;
	}
	.veloria-nav .btn-cart-european:hover {
		background: #DBA87A;
		color: #1E1E1E !important;
		transform: translateY(-1px);
	}
	.veloria-nav .btn-cart-european .badge {
		background: #2A2826 !important;
		color: #F0E7E0 !important;
		margin-left: 4px;
		border-radius: 30px;
		padding: 2px 8px;
		font-size: 0.7rem;
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
	/* Responsive adjustments */
	@media (max-width: 991px) {
		.veloria-nav .navbar-nav .nav-link {
			padding: 10px 15px !important;
		}
		.veloria-nav .welcome-msg {
			padding: 10px 15px;
			margin-right: 0;
		}
		.veloria-nav .btn-cart-european {
			margin: 10px 15px;
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
	<!-- Navigation (Bootstrap 5 structure preserved) -->
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<div class="container px-4 px-lg-5">
			<a class="navbar-brand" href="index.php">Triple A <br> ShopOnline</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
					<li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
					<li class="nav-item"><a class="nav-link" href="about-us.php">About</a></li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
							<li><a class="dropdown-item" href="index.php">All Products</a></li>
							<li><hr class="dropdown-divider" /></li>
							<li><a class="dropdown-item" href="shop-categories.php">CategoryWise</a></li>
						</ul>
					</li>
					<?php if(isset($_SESSION['id']) && $_SESSION['id'] == 0): ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Users</a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
							<li><a class="dropdown-item" href="login.php">Login</a></li>
							<li><hr class="dropdown-divider" /></li>
							<li><a class="dropdown-item" href="signup.php">Sign Up</a></li>
						</ul>
					</li>
					<li class="nav-item"><a class="nav-link" href="admin/">Admin</a></li>
					<?php elseif(isset($_SESSION['id']) && $_SESSION['id'] != 0): ?>
					<li class="nav-item"><a class="nav-link" href="my-wishlist.php">My Wishlist</a></li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">My Account</a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
							<li><a class="dropdown-item" href="my-orders.php">Orders</a></li>
							<li><hr class="dropdown-divider" /></li>
							<li><a class="dropdown-item" href="my-profile.php">Profile</a></li>
							<li><hr class="dropdown-divider" /></li>
							<li><a class="dropdown-item" href="change-password.php">Change Password</a></li>
							<li><hr class="dropdown-divider" /></li>
							<li><a class="dropdown-item" href="manage-addresses.php">Adresses</a></li>
							<li><hr class="dropdown-divider" /></li>
							<li><a class="dropdown-item" href="logout.php">Logout</a></li>
						</ul>
					</li>
					<?php else: ?>
					<!-- If session not set (guest), show login/register -->
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Account</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="login.php">Login</a></li>
							<li><a class="dropdown-item" href="signup.php">Sign Up</a></li>
						</ul>
					</li>
					<?php endif; ?>  
					<li class="nav-item"><a class="nav-link" href="contact-us.php">Contact us</a></li> 
				</ul>  
				<?php if(isset($_SESSION['id']) && $_SESSION['id'] != 0 && isset($_SESSION['username'])): ?>
				<span class="welcome-msg"><strong>Welcome:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
				<?php endif; ?>
				<form class="d-flex">
					<?php 
					// Safe cart count for both logged-in and guest users
					$cartcount = 0;
					if(isset($_SESSION['id']) && $_SESSION['id'] != 0) {
						$uid = $_SESSION['id'];
						$ret = mysqli_query($con, "select sum(productQty) as qtyy from cart where userId='$uid'");
						if($ret && $result = mysqli_fetch_array($ret)) {
							$cartcount = (int)$result['qtyy'];
						}
					} else {
						// For guests, count from session cart (if you use session cart)
						if(!empty($_SESSION['cart'])) {
							foreach($_SESSION['cart'] as $item) {
								$cartcount += $item['quantity'];
							}
						}
					}
					?>
					<a class="btn-cart-european" href="my-cart.php">
						<i class="bi-cart-fill me-1"></i>
						Cart
						<span class="badge"><?php echo $cartcount; ?></span>
					</a>
				</form>
			</div>
		</div>
	</nav>
</div>

<!-- Ensure Bootstrap Icons (if needed) – keep original bi-cart-fill -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<!-- CRITICAL: Bootstrap 5 JS Bundle with Popper (for dropdowns, toggles, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>