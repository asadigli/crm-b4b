<?php
  $this->page_title = lang('Products');
  $this->headCSS .= '<link href="'.assets('css/libs/select2.min.css').'">';
  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
?>

<!-- MOBILE FILTER MENU -->
<div class="mobile-filter-menu-overlay"></div>
<div class="mobile-filter-menu">
	<div class="mobile-menu-toggle-inner">
		<!-- HEADER MOBILE FILTER MENU TOGGLE -->
		<div class="header-mobile-menu-toggle border-bottom">
			<div class="cancel-i">
				<img src="<?php echo assets('img/icons/cancel.svg'); ?>" />
			</div>
			<h4 class="mb-0">Filter</h4>
		</div>

		<div class="body-mobile-menu-filter mt-4">
			<!-- <div class="form-group">
				<select class="js-example-basic-single">
					<option value=""> Alt kateqoriya  </option>
				</select>
			</div> -->
			<div class="form-group">
				<select data-role="mn_carbrands" class="js-example-basic-single" id="mobile_mn_carbrands"
					multiple></select>
			</div>
			<div class="form-group">
				<select data-role="mn_productbrands" class="js-example-basic-single" id="mobile_mn_productbrands"
					multiple></select>
			</div>


			<div class="form-group">
				<a href="javascript:void(0)" class="btn btn-default w-100" data-role="filter-products">Filter</a>
			</div>
		</div>
		<!-- FOOTER MOBILE MENU TOGGLE -->
	</div>
</div>
<!-- MOBILE FILTER MENU -->

<div class="search-page">
	<div class="container">
		<div class="row cs_main_products">

			<!-- FILTER -->
			<div class="col-md-4 d-none d-md-block">
				<!-- <div class="search-page-filter load">
					<div class="filter-title"> Filter </div>

					<div class="form-group">
						<input type="text" class="form-control"
							<?php echo $this->input->get("keyword") ? ' value="'.$this->input->get("keyword").'"' : ""; ?>
							placeholder="<?php echo lang("Enter keyword"); ?>"
							data-role="search-filter-input" maxlength="20" minlength="1">
					</div>

					<div class="form-group">
						<select data-role="mn_carbrands" class="js-example-basic-single" id="mn_carbrands"
							multiple></select>
					</div>
					<div class="form-group">
						<select data-role="mn_productbrands" class="js-example-basic-single" id="mn_productbrands"
							multiple></select>
					</div>

					<div class="form-group">
						<a href="javascript:void(0)" class="btn def-btn py-1"
							data-role="filter-products">Filter</a>
					</div>
				</div> -->
				<div class="container-shadow">
					<div class="tb_accordion light-accordion p-0">
						<ul data-role="filter-cataloge" class="load">

							<?php for ($i=0; $i < 20; $i++) { ?>
                <li class="pb-0"><div class="line"><p style="width:100%">--</p></div></li>
              <?php } ?>


						</ul>
					</div>
				</div>
			</div> <!-- INNER -->
			<div class="col-md-8">
				<div class="search-page-inner load">
					<div class="header-sp-inner border-bottom">
						<div class="left-side"></div>
						<div class="right-side">
							<div class="filter-button-mobile header-sp-inner-btn">
								<a href="" class="mobile-filter-btn">
									<i class="fas fa-filter"></i>
									Filter
								</a>
							</div>
						</div>
					</div>
					<div class="top-side d-flex align-items-center justify-content-end">
						<!-- <div class="left d-flex align-items-center">
							<div class="inputs-igroup d-flex align-items-center">
								<div class="i-group">
									<input type="number" step="0.01" placeholder="Min..." id="filter_min"
										value="<?php echo $this->input->get("min"); ?>">
								</div>
								<img src="<?php echo assets('img/icons/right.svg'); ?>" alt="">
								<div class="i-group">
									<input type="number" step="0.01" placeholder="Max..." id="filter_max"
										value="<?php echo $this->input->get("max"); ?>">
								</div>
							</div>
						</div> -->
						<div class="right">
							<div class="inputGroup form-group nonesearch mb-0">
								<select class="js-example-basic-single" id="sort_by">
									<option value="" <?php if (!$this->input->get('sort_by')){ echo " selected";} ?>>
										<?php echo lang('Default_filter'); ?></option>
									<option value="price_high_to_low"
										<?php if ($this->input->get('sort_by') === 'price_high_to_low'){ echo " selected";} ?>>
										<?php echo lang('Price_high_to_low'); ?></option>
									<option value="price_low_to_high"
										<?php if ($this->input->get('sort_by') === 'price_high_to_low'){ echo " selected";} ?>>
										<?php echo lang('Price_low_to_high'); ?></option>
								</select>
							</div>
						</div>
					</div>
					<div class="bottom-side">
						<div class="top-side-tab-pane">
							<p class="result">
								<?php
                // echo sprintf(lang('Search_result_text'),$this->input->get('keyword')).".";
                 ?>
								<span class="bold search_count"></span> nəticə tapıldı
							</p>
						</div>
						<div class="second-line d-none">
							<div class="content">
								<h6></h6>
							</div>
						</div>
					</div>
					<div id="progressbar" class="d-none">
						<span id="bottom-loading"></span>
					</div>
				</div>
				<div class="row my-4" id="search_products" data-engine-id="<?php echo $engine; ?>" data-category-id="<?php echo $category; ?>">
					<?php
        		// $this->session->userdata("search_prods_count") ? $this->session->userdata("search_prods_count") :
       			 $prods = 6;
       			 for ($i=0; $i < $prods; $i++) { ?>
					<div class="col-lg-4 col-md-4 col-6 cs_product_card cs_product_main">
						<div class="card h-100 cs_product_pan load">
							<div class="">
								<a class="add_to_cart star-fa" href="javascript:void(0)">
									<em class="fa fa-star"></em>
								</a>
								<a class="add_to_wishlist star-far" href="javascript:void(0)">
									<em class="far fa-star"></em>
								</a>
							</div>
							<div class="cs_product_card_link_main text-center">
								<img class="cs_product_gallery_img"
									src="<?php echo assets("img/no_photo.png"); ?>" alt="----"
									title="----">
							</div>
							<div class="card-body pt-0 pb-3 px-3">
								<h4 class="card-title mb-0 mt-3"><a title="----">---- ---- ---- ---- ----</a>
								</h4>
								<div class="row d-flex align-items-center">
									<div class="col-md-8 cs_card_price"><span>----- --</span></div>
									<div class="col-md-4 d-flex justify-content-end">
										<a class="add_to_cart position-sticky" href="javascript:void(0)"></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>

				</div>

				<div data-role="pagination"></div>

			</div>
		</div>
	</div>
</div>
<?php
$this->extraJS .= '<script type="module" src="'.assets("js/brand.js",$this->config->item("is_production")).'"></script>';
$this->extraJS .= '<script src="'.assets('js/libs/select2.min.js').'"></script>';
$this->load->view('layouts/foot');
 ?>
