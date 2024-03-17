<?php
  $this->page_title = lang('Search_result');
  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
?>

<!-- BREADCRUMB START -->
<div class="container">
	<div class="breadcrumb">
		<a href="/"><?php echo lang('Home'); ?></a>
		<a href="" class="active"><?php echo lang('Search_result'); ?></a>
	</div>
</div>
<!-- BREADCRUMB END -->

<!-- PAGETITE START -->
<div class="page-title">
	<div class="container">
		<h1><?php echo lang('Search_result') ?></h1>
	</div>
</div>
<!-- PAGETITE END -->

<section class="main-section product-search">

	<!-- FILTER CONTAINER MOBILE START-->
	<div class="container">
		<div class="filter-line m-v">
			<div class="filter-btn">
				<img src="<?php echo assets("img/icons/icon 2.png") ?>" alt="">
				<p><?php echo lang("Filter") ?></p>
			</div>

		</div>
	</div>

	<div class="filter-overlay"></div>
	<div class="filter-container">
		<div class="sidebar">
			<div class="sidebar-header">
				<div class="tit">
					<img src="<?php echo assets("img/icons/icon 2.png") ?>" alt="">
					<p><?php echo lang("Filter"); ?></p>
				</div>
				<div class="buttons d-flex aic justify-content-around">
					<button class="outline-btn"
						data-role="reset-filter"><?php echo lang("Reset") ?></button>
					<button class="btn" data-role="filter-products"><?php echo lang("Filter") ?></button>
				</div>
			</div>
			<!-- line 1-->
			<div class="sidebar-line">
				<h3 class="sidebar-title"><?php echo lang("Product search") ?></h3>
				<div class="form-group">
					<input type="text" placeholder="<?php echo lang("Search") ?>"
						data-role="product-search">
					<img src="<?php echo assets("img/icons/search.svg") ?>" alt="">
				</div>
				<div class="sidebar-line-inner d-flex flex-column">
					<label class="radiobtn">
						<input type="radio" name="mb-product-group" data-val=""
							<?php echo !$this->input->get("group") ? " checked" : ""; ?>>
						<span class="checkmark"></span>
						<p><?php echo lang("All"); ?></p>
					</label>
					<?php foreach (array_keys($menus) as $index => $item_val): ?>
					<label class="radiobtn">
						<input type="radio" name="mb-product-group"
							<?php echo (int)$this->input->get("group") === (int)$menus[$item_val]["id"] ? " checked" : ""; ?>
							data-val="<?php echo $menus[$item_val]["id"]; ?>"
							data-role="<?php echo $menus[$item_val]["slug"]; ?>">
						<span class="checkmark"></span>
						<p><?php echo lang($item_val) ?></p>
					</label>
					<?php endforeach; ?>
				</div>
			</div>
			<!-- line 2-->
			<div class="sidebar-line">
				<h3 class="sidebar-title"><?php echo lang("Brand") ?></h3>
				<div class="form-group">
					<input type="search" placeholder="<?php echo lang("Search") ?>"
						data-role="mb-filter-brand-search">
				</div>
				<div class="sidebar-line-inner d-flex flex-column" data-role="mb-filter-brand-list">
					<?php for ($i=0; $i < 10; $i++) { ?>
					<label class="chck">
						<input type="checkbox" value="">
						<span class="checkmark"></span>
						<p data-role="title">----</p>
					</label>
					<?php } ?>
				</div>
			</div>
			<!-- line 3-->
			<div class="sidebar-line">
				<h3 class="sidebar-title"><?php echo lang("Category") ?></h3>
				<div class="form-group">
					<input type="search" placeholder="<?php echo lang("Search") ?>"
						data-role="mb-filter-category-search">
				</div>
				<div class="sidebar-line-inner d-flex flex-column" data-role="mb-filter-category-list">
					<?php for ($i=0; $i < 5; $i++) { ?>
					<label class="chck">
						<input type="checkbox" value="">
						<span class="checkmark"></span>
						<p data-role="title">----</p>
					</label>
					<?php } ?>
				</div>
			</div>
			<!-- line 4 -->
			<div class="sidebar-line">
				<h3 class="sidebar-title"><?php echo lang("Other category") ?></h3>
				<div class="form-group">
					<input type="search" placeholder="<?php echo lang("Search") ?>"
						data-role="mb-filter-second-category-search">
				</div>
				<div class="sidebar-line-inner d-flex flex-column" data-role="mb-filter-second-category-list">
					<?php for ($i=0; $i < 5; $i++) { ?>
					<label class="chck">
						<input type="checkbox" value="">
						<span class="checkmark"></span>
						<p data-role="title">----</p>
					</label>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<!-- FILTER CONTAINER MOBILE END -->


	<!-- FILTER CONTAINER START-->
	<div class="container">
		<div class="row m-0">
			<div class="col-lg-3 col-md-4 d-none d-md-flex pl-0">
				<div class="sidebar load" role="product-filter">
					<!-- line 1-->
					<div class="sidebar-line p-0">
						<h3 class="sidebar-title"><?php echo lang("Product search") ?></h3>
						<div class="form-group">
							<input type="text" placeholder="<?php echo lang("Search") ?>"
								data-role="product-search">
							<img src="<?php echo assets("img/icons/search.svg") ?>" alt="">
						</div>
					</div>
					<div class="sidebar-line p-0">
						<div class="sidebar-line-inner d-flex flex-column">
							<label class="radiobtn">
								<input type="radio" data-val=""
									<?php echo !$this->input->get("group") ? " checked" : ""; ?> name="product-group">
								<span class="checkmark"></span>
								<p><?php echo lang("All"); ?></p>
							</label>
							<?php foreach (array_keys($menus) as $index => $item_val): ?>
							<label class="radiobtn">
								<input type="radio"
									<?php echo (int)$this->input->get("group") === (int)$menus[$item_val]["id"] ? " checked" : ""; ?>
									data-val="<?php echo $menus[$item_val]["id"]; ?>"
									data-role="<?php echo $menus[$item_val]["slug"]; ?>" name="product-group">
								<span class="checkmark"></span>
								<p><?php echo lang($item_val) ?></p>
							</label>
							<?php endforeach; ?>
						</div>
					</div>
					<!-- line 2-->
					<div class="sidebar-line">
						<h3 class="sidebar-title"><?php echo lang("Brand") ?></h3>
						<div class="form-group">
							<input type="search" placeholder="<?php echo lang("Search") ?>"
								data-role="filter-brand-search">
						</div>
						<div class="sidebar-line-inner d-flex flex-column" data-role="filter-brand-list">
							<?php for ($i=0; $i < 5; $i++) { ?>
							<label class="chck">
								<input type="checkbox">
								<span class="checkmark"></span>
								<p data-role="title">------</p>
							</label>
							<?php } ?>
						</div>
					</div>
					<!-- line 3-->
					<div class="sidebar-line">
						<h3 class="sidebar-title"><?php echo lang("Category") ?></h3>
						<div class="form-group">
							<input type="search" placeholder="<?php echo lang("Search") ?>"
								data-role="filter-category-search">
						</div>
						<div class="sidebar-line-inner d-flex flex-column" data-role="filter-category-list">
							<?php for ($i=0; $i < 5; $i++) { ?>
							<label class="chck">
								<input type="checkbox" value="">
								<span class="checkmark"></span>
								<p data-role="title">-----</p>
							</label>
							<?php } ?>
						</div>
					</div>
					<!-- line 4-->
					<div class="sidebar-line">
						<h3 class="sidebar-title"><?php echo lang("Other category") ?></h3>
						<div class="form-group">
							<input type="search" placeholder="<?php echo lang("Search") ?>"
								data-role="filter-second-category-search">
						</div>
						<div class="sidebar-line-inner d-flex flex-column" data-role="filter-second-category-list">
							<?php for ($i=0; $i < 5; $i++) { ?>
							<label class="chck">
								<input type="checkbox" value="">
								<span class="checkmark"></span>
								<p data-role="title">-----</p>
							</label>
							<?php } ?>
						</div>
					</div>
					<div class="buttons d-flex aic justify-content-around">
						<button class="outline-btn"
							data-role="reset-filter"><?php echo lang("Reset") ?></button>
						<button class="btn"
							data-role="filter-products"><?php echo lang("Filter") ?></button>
					</div>
				</div>
			</div>
			<div class="col-lg-9 col-md-8 p-0">
				<!-- PRODUCT NOT FOUND -->
				<div class="empty-text d-none">
					<h4><?php echo lang("No product found") ?></h4>
				</div>
				<!-- PRODUCT NOT FOUND -->
				<div class="row row-1 m-0 load" id="search_products">
					<?php for ($i=0; $i < 8; $i++) { ?>
					<!-- col-lg-3 col-md-6 col-12 -->
					<div class="col-lg-4 col-md-6 col-12">
						<div class="product-card">
							<a href="#" class="product-card-top">
								<img src="">
							</a>
							<div class="product-card-body">
								<a href="#">
									<h4>----</h4>
								</a>
								<p>--- --- --- --</p>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
				<div data-role="pagination"></div>
			</div>
		</div>
	</div>
	<!-- FILTER CONTAINER END -->
</section>

<?php
  $this->extraJS .= '<script type="module" src="'.assets("js/search.js",$this->config->item("is_production")).'"></script>';
  $this->load->view('layouts/foot');
?>
