<style>
	/* VELORIA modern brands section – clean, elegant, minimal */
	.veloria-brands {
		background: #FCF9F5;
		padding: 48px 0;
		border-top: 1px solid #EDE5DE;
		border-bottom: 1px solid #EDE5DE;
		font-family: 'Inter', sans-serif;
	}
	.veloria-brands .section-title {
		font-family: 'Instrument Serif', serif;
		font-size: 2rem;
		font-weight: 500;
		color: #2A2826;
		text-align: center;
		margin-bottom: 36px;
		position: relative;
		letter-spacing: -0.3px;
	}
	.veloria-brands .section-title:after {
		content: '';
		display: block;
		width: 70px;
		height: 2px;
		background: #C47A5E;
		margin: 10px auto 0;
	}
	.veloria-brands .logo-slider-inner {
		padding: 0 15px;
	}
	.veloria-brands .brand-slider .item {
		text-align: center;
		padding: 8px;
	}
	.veloria-brands .brand-slider .item .image {
		display: block;
		background: #FFFFFF;
		border: 1px solid #EFE8E2;
		border-radius: 20px;
		padding: 16px;
		transition: all 0.25s ease;
		box-shadow: 0 2px 8px rgba(0,0,0,0.02);
	}
	.veloria-brands .brand-slider .item .image:hover {
		border-color: #C47A5E;
		box-shadow: 0 12px 24px rgba(0,0,0,0.06);
		transform: translateY(-4px);
	}
	.veloria-brands .brand-slider .item img {
		max-width: 100%;
		filter: grayscale(20%);
		opacity: 0.85;
		transition: all 0.3s;
		margin: 0 auto;
	}
	.veloria-brands .brand-slider .item .image:hover img {
		filter: grayscale(0%);
		opacity: 1;
	}
	/* Owl Carousel navigation styling */
	.veloria-brands .owl-nav {
		position: absolute;
		top: 50%;
		width: 100%;
		margin-top: -20px;
	}
	.veloria-brands .owl-prev,
	.veloria-brands .owl-next {
		position: absolute;
		width: 40px;
		height: 40px;
		background: #C47A5E !important;
		color: #2A2826 !important;
		border-radius: 50% !important;
		font-size: 20px !important;
		line-height: 40px !important;
		text-align: center;
		opacity: 0.7;
		transition: all 0.2s;
	}
	.veloria-brands .owl-prev {
		left: -20px;
	}
	.veloria-brands .owl-next {
		right: -20px;
	}
	.veloria-brands .owl-prev:hover,
	.veloria-brands .owl-next:hover {
		opacity: 1;
		background: #DBA87A !important;
		transform: scale(1.05);
	}
	@media (max-width: 767px) {
		.veloria-brands .section-title {
			font-size: 1.8rem;
		}
		.veloria-brands .owl-prev {
			left: -10px;
		}
		.veloria-brands .owl-next {
			right: -10px;
		}
	}
</style>

<div class="veloria-brands">
	<div id="brands-carousel" class="logo-slider wow fadeInUp">
		<h3 class="section-title">Our Brands</h3>
		<div class="logo-slider-inner">	
			<div id="brand-slider" class="owl-carousel brand-slider custom-carousel owl-theme">
				<div class="item">
					<a href="./pages/aoc.php" class="image">
						<img src="brandsimage/aoc.jpg" alt="AOC" onerror="this.src='assets/images/placeholder-brand.png'">
					</a>	
				</div>
				<div class="item">
					<a href="./pages/bajaj.php" class="image">
						<img src="brandsimage/bajaj.jpg" alt="Bajaj" onerror="this.src='assets/images/placeholder-brand.png'">
					</a>	
				</div>
				<div class="item">
					<a href="./pages/blackberry.php" class="image">
						<img src="brandsimage/blackberry.jpg" alt="BlackBerry" onerror="this.src='assets/images/placeholder-brand.png'">
					</a>	
				</div>
				<div class="item">
					<a href="./pages/canon.php" class="image">
						<img src="brandsimage/canon.jpg" alt="Canon" onerror="this.src='assets/images/placeholder-brand.png'">
					</a>	
				</div>
				<div class="item">
					<a href="./pages/compas.php" class="image">
						<img src="brandsimage/compas.jpg" alt="Compas" onerror="this.src='assets/images/placeholder-brand.png'">
					</a>	
				</div>
				<div class="item">
					<a href="./pages/daikin.php" class="image">
						<img src="brandsimage/daikin.jpg" alt="Daikin" onerror="this.src='assets/images/placeholder-brand.png'">
					</a>	
				</div>
				<div class="item">
					<a href="./pages/dell.php" class="image">
						<img src="brandsimage/dell.jpg" alt="Dell" onerror="this.src='assets/images/placeholder-brand.png'">
					</a>	
				</div>
				<div class="item">
					<a href="./pages/samsung.php" class="image">
						<img src="brandsimage/samsung.jpg" alt="Samsung" onerror="this.src='assets/images/placeholder-brand.png'">
					</a>	
				</div>
				<div class="item">
					<a href="./pages/hcl.php" class="image">
						<img src="brandsimage/hcl.jpg" alt="HCL" onerror="this.src='assets/images/placeholder-brand.png'">
					</a>	
				</div>
				<div class="item">
					<a href="./pages/sony.php" class="image">
						<img src="brandsimage/sony.jpg" alt="Sony" onerror="this.src='assets/images/placeholder-brand.png'">
					</a>	
				</div>
				<div class="item">
					<a href="./pages/voltas.php" class="image">
						<img src="brandsimage/voltas.jpg" alt="Voltas" onerror="this.src='assets/images/placeholder-brand.png'">
					</a>	
				</div>
				<div class="item">
					<a href="./pages/lg.php" class="image">
						<img src="brandsimage/lg.jpg" alt="LG" onerror="this.src='assets/images/placeholder-brand.png'">
					</a>	
				</div>
				<div class="item">
					<a href="./pages/lenovo.php" class="image">
						<img src="brandsimage/lenovo.jpg" alt="Lenovo" onerror="this.src='assets/images/placeholder-brand.png'">
					</a>	
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Owl Carousel initialization (if not already present in your page) -->
<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
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