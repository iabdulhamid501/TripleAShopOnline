<style>
	/* VELORIA modern footer – elegant, minimal, consistent */
	.veloria-footer {
		background: #1F1D1B;
		color: #CBC3BC;
		font-family: 'Inter', sans-serif;
		border-top: 3px solid #C47A5E;
		padding-top: 48px;
		margin-top: 64px;
	}
	.veloria-footer .module-title {
		font-family: 'Instrument Serif', serif;
		font-size: 1.3rem;
		font-weight: 500;
		color: #F0E7E0;
		margin-bottom: 20px;
		letter-spacing: -0.3px;
		position: relative;
		padding-bottom: 8px;
	}
	.veloria-footer .module-title:after {
		content: '';
		position: absolute;
		left: 0;
		bottom: 0;
		width: 50px;
		height: 2px;
		background: #C47A5E;
	}
	.veloria-footer .logo h3 {
		font-family: 'Instrument Serif', serif;
		font-size: 1.8rem;
		font-weight: 500;
		color: #F0E7E0;
		margin: 0 0 12px;
		position: relative;
		display: inline-block;
	}
	.veloria-footer .logo h3:after {
		content: '';
		display: block;
		width: 50px;
		height: 2px;
		background: #C47A5E;
		margin-top: 6px;
	}
	.veloria-footer .about-us {
		color: #B8ADA5;
		line-height: 1.7;
		font-size: 0.85rem;
		margin-top: 12px;
	}
	.veloria-footer .contact-timing table {
		background: transparent;
		color: #B8ADA5;
		width: 100%;
	}
	.veloria-footer .contact-timing td {
		border: none;
		padding: 8px 0;
		font-size: 0.85rem;
	}
	.veloria-footer .contact-timing td.pull-right {
		text-align: right;
		color: #C47A5E;
		font-weight: 500;
	}
	.veloria-footer .contact-information .media {
		margin-bottom: 16px;
		display: flex;
		align-items: flex-start;
		gap: 12px;
	}
	.veloria-footer .contact-information .pull-left {
		min-width: 32px;
	}
	.veloria-footer .contact-information .icon {
		color: #C47A5E;
		font-size: 1.1rem;
	}
	.veloria-footer .contact-information .media-body {
		color: #B8ADA5;
		font-size: 0.85rem;
	}
	.veloria-footer .contact-information a {
		color: #C47A5E;
		transition: color 0.2s;
		text-decoration: none;
	}
	.veloria-footer .contact-information a:hover {
		color: #DBA87A;
	}
	.veloria-footer .module-body {
		margin-top: 16px;
	}
	.veloria-footer .links-social {
		padding-bottom: 30px;
	}
	/* Footer lists */
	.footer-links {
		list-style: none;
		padding: 0;
		margin: 0;
	}
	.footer-links li {
		margin-bottom: 10px;
	}
	.footer-links a {
		color: #B8ADA5;
		text-decoration: none;
		font-size: 0.85rem;
		transition: color 0.2s;
	}
	.footer-links a:hover {
		color: #C47A5E;
		padding-left: 5px;
	}
	.social-links {
		display: flex;
		gap: 16px;
		margin-top: 20px;
		flex-wrap: wrap;
	}
	.social-links a {
		color: #B8ADA5;
		font-size: 1.4rem;
		transition: all 0.2s;
	}
	.social-links a:hover {
		color: #C47A5E;
		transform: translateY(-3px);
	}
	/* Responsive */
	@media (max-width: 767px) {
		.veloria-footer .col-xs-12 {
			margin-bottom: 32px;
		}
		.veloria-footer {
			padding-top: 32px;
		}
	}
</style>

<footer id="footer" class="veloria-footer color-bg">
	<div class="links-social inner-top-sm">
		<div class="container">
			<div class="row">
				<!-- Column 1: Logo & About -->
				<div class="col-xs-12 col-sm-6 col-md-3">
					<div class="contact-info">
						<div class="footer-logo">
							<div class="logo">
								<a href="index.php">
									<h3>Shopping Portal</h3>
								</a>
							</div>
						</div>
						<div class="module-body m-t-20">
							<p class="about-us">Triple A ShopOnline<br>Quality you trust, Convenience you deserve.</p>
						</div>
					</div>
				</div>

				<!-- Column 2: Quick Links -->
				<div class="col-xs-12 col-sm-6 col-md-2">
					<div class="module-heading">
						<h4 class="module-title">Quick Links</h4>
					</div>
					<div class="module-body">
						<ul class="footer-links">
							<li><a href="index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
							<li><a href="index.php"><i class="fas fa-chevron-right"></i> Shop</a></li>
							<li><a href="about-us.php"><i class="fas fa-chevron-right"></i> About Us</a></li>
							<li><a href="contact-us.php"><i class="fas fa-chevron-right"></i> Contact</a></li>
							<li><a href="blog.php"><i class="fas fa-chevron-right"></i> Blog</a></li>
						</ul>
					</div>
				</div>

				<!-- Column 3: Support Links -->
				<div class="col-xs-12 col-sm-6 col-md-2">
					<div class="module-heading">
						<h4 class="module-title">Support</h4>
					</div>
					<div class="module-body">
						<ul class="footer-links">
							<li><a href="faq.php"><i class="fas fa-chevron-right"></i> FAQ</a></li>
							<li><a href="returns.php"><i class="fas fa-chevron-right"></i> Returns & Refunds</a></li>
							<li><a href="shipping-info.php"><i class="fas fa-chevron-right"></i> Shipping Info</a></li>
							<li><a href="privacy-policy.php"><i class="fas fa-chevron-right"></i> Privacy Policy</a></li>
							<li><a href="terms.php"><i class="fas fa-chevron-right"></i> Terms of Service</a></li>
						</ul>
					</div>
				</div>

				<!-- Column 4: Opening Hours (original) -->
				<div class="col-xs-12 col-sm-6 col-md-2">
					<div class="contact-timing">
						<div class="module-heading">
							<h4 class="module-title">Opening Time</h4>
						</div>
						<div class="module-body outer-top-xs">
							<div class="table-responsive">
								<table class="table">
									<tbody>
										<tr><td>Monday-Friday:</td><td class="pull-right">08.00AM – 06.00PM</td></tr>
										<tr><td>Saturday:</td><td class="pull-right">09.00AM – 08.00PM</td></tr>
										<tr><td>Sunday:</td><td class="pull-right">10.00AM – 08.00PM</td></tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<!-- Column 5: Contact & Social -->
				<div class="col-xs-12 col-sm-6 col-md-3">
					<div class="contact-information">
						<div class="module-heading">
							<h4 class="module-title">Get in Touch</h4>
						</div>
						<div class="module-body">
							<ul class="toggle-footer" style="">
								<li class="media">
									<div class="pull-left"><span class="icon fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-map-marker fa-stack-1x fa-inverse"></i></span></div>
									<div class="media-body"><p>FCT Abuja, Nigeria</p></div>
								</li>
								<li class="media">
									<div class="pull-left"><span class="icon fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-mobile fa-stack-1x fa-inverse"></i></span></div>
									<div class="media-body"><p>(234) 09064823328<br>(234) 08141734756</p></div>
								</li>
								<li class="media">
									<div class="pull-left"><span class="icon fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-envelope fa-stack-1x fa-inverse"></i></span></div>
									<div class="media-body"><span><a href="mailto:tripleashoponline@gmail.com">tripleashoponline@gmail.com</a></span></div>
								</li>
							</ul>
							<!-- Social Links -->
							<div class="social-links">
								<a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
								<a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
								<a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
								<a href="#" target="_blank"><i class="fab fa-youtube"></i></a>
								<a href="#" target="_blank"><i class="fab fa-whatsapp"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /.row -->
		</div><!-- /.container -->
	</div><!-- /.links-social -->
</footer>

<!-- Ensure Font Awesome and Google Fonts are loaded (already in header, but kept for safety) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">