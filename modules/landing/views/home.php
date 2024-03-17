<?php
// $this->headCSS .= '<link src="'.assets("css/owl.theme.default.css").'" rel="stylesheet">';
// $this->metaDesc = lang("Seo_home_description");
// $this->metaKeys = lang("Seo_home_keywords");
$this->pageType = "auto parts store";
$this->load->view('layouts/head');
$this->load->view('layouts/menu');
?>

<!-- WALLPAPER SECTION START -->
<section class="wallp-section">
	<div class="owl-carousel">
		<!-- 1 -->
		<div class="overlay-texts first" style="background-image: url('<?= assets("img/slide-1.jpg") ?>')">
			<div class="line d-flex">
				<div class="left-side w-50 d-flex justify-content-end">
					<h1><?= lang("slide text 1")?></h1>
				</div>
				<div class="right-side w-50 d-flex jcfs">
					<h1><?= lang("slide text 2")?></h1>
				</div>
			</div>
			<div class="line d-flex ">
				<div class="left-side w-50 d-flex justify-content-end">
					<h1><?= lang("slide text 3")?></h1>
				</div>
				<div class="right-side w-50 d-flex jcfs">
					<h1><?= lang("slide text 4")?></h1>
				</div>
			</div>
		</div>
		<!-- 2 -->
		<div class="overlay-texts" style="background-image: url('<?= assets("img/slide-4.jpg") ?>')">
			<div class="line d-flex">
				<div class="left-side w-50 d-flex justify-content-end">
					<h1><?= lang("slide text 5")?></h1>
				</div>
				<div class="right-side w-50 d-flex jcfs">
					<h1><?= lang("slide text 6")?></h1>
				</div>
			</div>
			<div class="line d-flex ">
				<div class="left-side w-50 d-flex justify-content-end">
					<h1><?= lang("slide text 7")?></h1>
				</div>
				<div class="right-side w-50 d-flex jcfs">
					<h1><?= lang("slide text 8")?></h1>
				</div>
			</div>
		</div>
		<!-- 3 -->
		<div class="overlay-texts" style="background-image: url('<?= assets("img/slide-5.jpg") ?>')">
			<div class="line d-flex">
				<div class="left-side w-50 d-flex justify-content-end">
					<h1><?= lang("slide text 9")?></h1>
				</div>
				<div class="right-side w-50 d-flex jcfs">
					<h1><?= lang("slide text 10")?></h1>
				</div>
			</div>
			<div class="line d-flex">
				<div class="left-side w-50 d-flex justify-content-end">
					<h1><?= lang("slide text 11")?></h1>
				</div>
				<div class="right-side w-50 d-flex jcfs">
					<h1><?= lang("slide text 12")?></h1>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- WALLPAPER SECTION END -->

<section class="featured-boxes" class="mb-5">
	<div class="container d-flex justify-content-center">
		<div class="section-title">
			<h2><?= lang("Customer text 1") ?></h2>
		</div>
		<button class="def-btn popBtn" data-id="be-partner"><?= lang("Want to be a partner") ?></button>
	</div>
	<div class="inner-section">
		<div class="container">
			<div class="row justify-content-center m-0">
				<div class="col-md-3 col-6">
					<div class="card" data-aos="zoom-in-up">
						<div class="card-img-top">
							<?= readSVG("icons/green-icon") ?>
						</div>
						<div class="card-body">
							<p><?= lang("Customer text 8"); ?></p>
						</div>
						<div class="card-footer">
							<p><?= lang("Customer text 9"); ?></p>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-6">
					<div class="card" data-aos="zoom-in-up">
						<div class="card-img-top">
							<?= readSVG("icons/yellow-icon") ?>
						</div>
						<div class="card-body">
							<p><?= lang("Customer text 4"); ?></p>
						</div>
						<div class="card-footer">
							<p><?= lang("Customer text 5"); ?></p>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-6">
					<div class="card" data-aos="zoom-in-up">
						<div class="card-img-top">
							<?= readSVG("icons/purple-icon") ?>
						</div>
						<div class="card-body">
							<p><?= lang("Customer text 6"); ?></p>
						</div>
						<div class="card-footer">
							<p><?= lang("Customer text 7") ?></p>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-6">
					<div class="card" data-aos="zoom-in-up">
						<div class="card-img-top">
							<?= readSVG("icons/pink-icon") ?>
						</div>
						<div class="card-body">
							<p><?= lang("Customer text 43"); ?></p>
						</div>
						<div class="card-footer">
							<p><?= lang("Customer text 44"); ?></p>
							<p><?= lang("Customer text 45"); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- BG MAP SECTION START-->
<section class="bg-map d-flex align-items-center"
	style="background-image: url('<?= assets("img/xerite.svg"); ?>');">
	<div class="container-fluid">
		<div class="line-bg-map">
			<div class="card-bg-map">
				<img src="<?= assets("img/green-icon-".$this->local.".svg"); ?>" alt="">
			</div>
			<div class="card-bg-map">
				<img src="<?= assets("img/lightgreen-icon-".$this->local.".svg"); ?>" alt="">
			</div>
			<div class="card-bg-map">
				<img src="<?= assets("img/yellow-icon-".$this->local.".svg"); ?>" alt="">
			</div>
			<div class="card-bg-map">
				<img src="<?= assets("img/to parts.svg"); ?>" alt="">
			</div>
		</div>
	</div>
</section>
<!-- BG MAP SECTION END-->


<!-- FEATURED ICONS START -->
<section class="featured-icons">
	<div class="container d-flex justify-content-center">
		<div class="section-title">
			<h2><?= lang("Customer text 2") ?></h2>
		</div>
	</div>
	<div class="container">
		<div class="row m-0">
			<div class="col-4 d-flex justify-content-center" data-aos="zoom-in-up">
				<div class="card d-flex flex-column align-items-center left-card">
					<div class="icon-card-top">
						<img src="<?= assets("img/product.svg"); ?>" alt="">
					</div>
					<div class="card-body">
						<h6>
							<p>90000</p><span>+</span>
						</h6>
						<p><?= lang("Customer text 14") ?></p>
					</div>
				</div>
			</div>
			<div class="col-4 d-flex justify-content-center" data-aos="zoom-in-up">
				<div class="card d-flex flex-column align-items-center center-card">
					<div class="icon-card-top">
						<img src="<?= assets("img/ok.svg"); ?>" alt="">
					</div>
					<div class="card-body">
						<h6>
							<p>25</p><span>+</span>
						</h6>
						<p><?= lang("Customer text 16") ?></p>
					</div>
				</div>
			</div>
			<div class="col-4 d-flex justify-content-center" data-aos="zoom-in-up">
				<div class="card d-flex flex-column align-items-center right-card">
					<div class="icon-card-top">
						<img src="<?= assets("img/price-tags.svg"); ?>" alt="">
					</div>
					<div class="card-body">
						<h6>
							<p>60</p><span>+</span>
						</h6>
						<p><?= lang("Brand") ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- FEATURED ICONS END -->

<!-- CARD CAROUSEL START -->
<section class="card-carousel load" id="home_pg_products">
	<div class="container d-flex justify-content-center">
		<div class="fake-card-line">
			<?php for ($i=0; $i < 4; $i++) { ?>
			<div class="product-card">
				<div class="product-card-top">
					<img src="" alt="">
				</div>
				<div class="product-card-body">
					<a href="#">
						<h4>-------</h4>
					</a>
					<p>------------------------------</p>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</section>
<!-- CARD CAROUSEL START -->

<!-- MINI CARD CAROUSEL START -->
<section class="mini-card-carousel" id="home_brands">
	<div class="container">
		<div class="section-title">
			<h2><?= lang("Customer text 40") ?></h2>
		</div>
	</div>
	<div class="container-sm">
		<div class="fake-card-line load" data-role="home-br-list">
			<?php for ($i=0; $i < 5; $i++) { ?>
			<div class="brand-card">
				<img src="">
			</div>
			<?php } ?>
		</div>
	</div>
</section>
<!-- MINI CARD CAROUSEL END -->

<?php
//   $this->extraBeforeJS .= '<script type="module" src="'.assets("js/owl.carousel.js").'"></script>';
  $this->load->view('layouts/foot');
?>
