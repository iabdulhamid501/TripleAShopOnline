<style>
	/* VELORIA modern checkout progress sidebar */
	.veloria-checkout-progress {
		font-family: 'Inter', sans-serif;
	}
	.veloria-checkout-progress .checkout-progress-sidebar {
		background: #FFFFFF;
		border: 1px solid #EFE8E2;
		border-radius: 24px;
		box-shadow: 0 8px 20px rgba(0, 0, 0, 0.02);
		padding: 0;
		overflow: hidden;
	}
	.veloria-checkout-progress .panel {
		background: transparent;
		border: none;
		box-shadow: none;
		margin-bottom: 0;
	}
	.veloria-checkout-progress .panel-heading {
		background: transparent;
		border-bottom: 2px solid #C47A5E;
		padding: 20px 24px;
	}
	.veloria-checkout-progress .unicase-checkout-title {
		font-family: 'Instrument Serif', serif;
		font-size: 1.5rem;
		font-weight: 500;
		color: #2A2826;
		margin: 0;
		letter-spacing: -0.3px;
	}
	.veloria-checkout-progress .panel-body {
		padding: 20px 24px;
	}
	.veloria-checkout-progress .nav-checkout-progress {
		list-style: none;
		padding: 0;
		margin: 0;
	}
	.veloria-checkout-progress .nav-checkout-progress li {
		margin-bottom: 12px;
		border-bottom: 1px solid #F0E9E3;
		padding-bottom: 12px;
	}
	.veloria-checkout-progress .nav-checkout-progress li:last-child {
		border-bottom: none;
		margin-bottom: 0;
		padding-bottom: 0;
	}
	.veloria-checkout-progress .nav-checkout-progress li a {
		color: #4A4440;
		font-size: 0.95rem;
		font-weight: 500;
		text-decoration: none;
		transition: all 0.2s ease;
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 5px 0;
	}
	.veloria-checkout-progress .nav-checkout-progress li a:hover {
		color: #C47A5E;
		transform: translateX(4px);
	}
	.veloria-checkout-progress .nav-checkout-progress li a:before {
		content: '→';
		font-family: 'Inter', sans-serif;
		color: #C47A5E;
		font-weight: 400;
		opacity: 0.8;
		font-size: 0.9rem;
	}
	/* Responsive */
	@media (max-width: 767px) {
		.veloria-checkout-progress .unicase-checkout-title {
			font-size: 1.3rem;
		}
		.veloria-checkout-progress .panel-heading {
			padding: 16px 20px;
		}
		.veloria-checkout-progress .panel-body {
			padding: 16px 20px;
		}
	}
</style>

<div class="veloria-checkout-progress">
	<div class="col-md-4">
		<!-- checkout-progress-sidebar -->
		<div class="checkout-progress-sidebar">
			<div class="panel-group">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="unicase-checkout-title">Your Checkout Progress</h4>
					</div>
					<div class="panel-body">
						<ul class="nav nav-checkout-progress list-unstyled">
							<li><a href="my-account.php">My Account</a></li>
							<li><a href="bill-ship-addresses.php">Shipping / Billing Address</a></li>
							<li><a href="order-history.php">Order History</a></li>
							<li><a href="pending-orders.php">Payment Pending Order</a></li>
						</ul>		
					</div>
				</div>
			</div>
		</div> 
		<!-- checkout-progress-sidebar -->				
	</div>
</div>

<!-- Ensure Google Fonts (Inter & Instrument Serif) are loaded – usually in main header -->
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">