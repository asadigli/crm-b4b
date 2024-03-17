<?php
  $this->page_title = $name;
  $this->headCSS .= '<link href="'.assets('css/libs/select2.min.css').'">';
  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
?>

<div class="container">
	<div class="row cs_main_products" style="min-height:708px;position:relative;">
		<div class="col-md-3 filter-section">
			<div class="search-page-filter load" data-role="search-filter">
				<div class="filter-title"> Filter </div>
				<div class="form-group">
					<select class="js-example-basic-single" data-role="brand">
						<option value="">-- <?php echo lang("Brand"); ?> --</option>
					</select>
				</div>
				<div class="form-group">
					<select class="js-example-basic-single" data-role="category">
						<option value="">-- <?php echo lang("Category"); ?> --</option>
					</select>
				</div>
				<div class="form-group">
					<select class="js-example-basic-single" data-role="subcat">
						<option value="">-- <?php echo lang("Sub category"); ?> -- </option>
					</select>
				</div>

				<div class="form-group">
					<a href="javascript:void(0)" class="btn btn-default" id="filter_group">Filter</a>
				</div>
			</div>
		</div>


		<div class="col-md-9">
			<div class="search-page-inner load">
				<div class="top-side d-flex align-items-center justify-content-between">
					<div class="left d-flex align-items-center">
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
					</div>
					<div class="right">
						<div class="inputGroup">
							<select class="tb_select" id="sort_by">
                <option value=""<?php if (!$this->input->get('sort_by')){ echo " selected";} ?>><?php echo lang('Default_filter'); ?></option>
                <option value="price_high_to_low"<?php if ($this->input->get('sort_by') === 'price_high_to_low'){ echo " selected";} ?>><?php echo lang('Price_high_to_low'); ?></option>
								<option value="price_low_to_high"<?php if ($this->input->get('sort_by') === 'price_high_to_low'){ echo " selected";} ?>><?php echo lang('Price_low_to_high'); ?></option>
							</select>
						</div>
					</div>
				</div>
				<div class="bottom-side">
					<div class="top-side-tab-pane">
						<p class="result">
							<?php echo sprintf(lang('Search_result_text'),$name); ?>.
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
			<div class="row my-5" id="group_products" data-id="<?php echo $id; ?>" >
        <?php
        $prods = $this->session->userdata("group_prods_count") ?: 6;
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
                <img class="cs_product_gallery_img" src="<?php echo assets("img/no_photo.png"); ?>"
                    alt="----" title="----">
              </div>
              <div class="card-body pt-0 pb-3 px-3">
                <h4 class="card-title mb-0 mt-3"><a title="----">---- ---- ---- ---- ----</a></h4>
                <div class="row d-flex align-items-center">
                  <div class="col-md-8 cs_card_price"><span>----- --</span></div>
                  <div class="col-md-4 d-flex justify-content-end">
                    <a class="add_to_cart position-sticky" href="javascript:void(0)">
                      <img alt="">
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>


      </div>
		</div>
	</div>
</div>

<?php
$this->extraJS .= '<script src="'.assets('js/libs/select2.min.js').'"></script>';
$this->load->view('layouts/foot');
 ?>
