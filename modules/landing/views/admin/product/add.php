<?php
  $this->page_title = $title;
  $this->headCSS = '<link href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" rel="stylesheet">';
  $this->load->view('layouts/admin/head');
  $this->load->view('layouts/admin/menu');
?>
<div class="container">
	<div class="right-side-page-card my-4">
		<div class="page-card-header">
			<h4 class="mb-3"><?php echo lang("Add_product"); ?></h4>
		</div>
		<div class="page-card-body container-shadow">
			<div class="p-3">
				<div class="row">
					<div class="col-md-6 col-12">
						<div class="form-group">
							<input type="text" data-name="product-name" minlength="6" autocomplete="off"
								data-error="Etibarlı məhsul adı daxil edin (min. 2 simbol)"
								placeholder="<?php echo lang("Product_name"); ?>..." value="" required="">
							<small class="text-danger d-none"></small>
						</div>
						<div class="form-group">
							<input type="text" data-name="brand-code" minlength="6" autocomplete="off"
								placeholder="<?php echo lang("Brand_code"); ?>..." required="">
							<small class="text-danger d-none"></small>
						</div>
						<div class="form-group">
							<input type="text" data-name="product-oem" minlength="6" autocomplete="off"
								placeholder="<?php echo lang("OEM_code"); ?>..." required="">
							<small class="text-danger d-none"></small>
						</div>
						<div class="form-group">
							<input type="number" step="0.01" data-name="product-price" autocomplete="off"
								placeholder="<?php echo lang("Price"); ?>...">
							<small class="text-danger d-none"></small>
						</div>
						<div class="form-group">
							<input type="number" step="0.01" data-name="product-quantity" autocomplete="off"
								placeholder="<?php echo lang("Quantity"); ?>...">
							<small class="text-danger d-none"></small>
						</div>
						<div class="form-group">
							<select data-name="product-group">
								<option value="">- <?php echo lang("Choose group"); ?> -</option>
								<option value="1"><?php echo lang("Engine oils"); ?></option>
								<option value="2"><?php echo lang("Gear oils"); ?></option>
								<option value="3"><?php echo lang("Antifreeze"); ?></option>
								<option value="4"><?php echo lang("Fuel additives"); ?></option>
								<option value="5"><?php echo lang("Battery"); ?></option>
							</select>
							<small class="text-danger d-none"></small>
						</div>
					</div>
					<div class="col-md-6 col-12">
						<div class="form-group">
							<select disabled="" data-name="product-brand">
								<option value="">- <?php echo lang("Choose product brand"); ?> -</option>
							</select>
							<small class="text-danger d-none"></small>
						</div>
						<div class="form-group">
							<select disabled="" data-name="product-category">
								<option value="">- <?php echo lang("Choose category"); ?> -</option>
							</select>
							<small class="text-danger d-none"></small>
						</div>
						<div class="form-group">
							<select disabled="" data-name="product-second-category">
								<option value="">- <?php echo lang("Choose second category"); ?> -</option>
							</select>
							<small class="text-danger d-none"></small>
						</div>
						<div class="form-group">
							<textarea placeholder="Qısa təsvir..." maxlength="500"
								data-name="product-short-description"></textarea>
						</div>
						<div class="form-group">
							<textarea placeholder="Ətraflı məlumat..." data-name="product-description"></textarea>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-sm">
						<label class="chck">
							<input type="checkbox" checked="checked" data-name="product-status">
							<span class="checkmark"></span>
							<p><?php echo lang("Status"); ?></p>
						</label>
					</div>
					<div class="col-sm">
						<label class="chck">
							<input type="checkbox" data-name="is-home-product">
							<span class="checkmark"></span>
							<p><?php echo lang("Home product"); ?></p>
						</label>
					</div>
				</div>
				<hr>
				<div class="file-upload-container">
					<div class="file-upload">
						<input type="file" data-role="product-image-container" accept="image/*" multiple>
						<div>
							<em class="fas fa-cloud-upload-alt"></em>
							<span><?php echo lang("Add image"); ?></span>
						</div>
					</div>
          <div class="file-area d-none" data-role="product-preview-container">

            <div class="line"><img src=""></div>

          </div>
				</div>
				<div class="d-flex justify-content-end mt-3">
					<button class="def-btn px-4" data-role="add-product" data-text="<?php echo lang("Add"); ?>">
					<?php echo lang("Add"); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>


<?php
$this->extraJS .= '<script type="module" src="'.assets("js/pvt/product/add.89mOyVoJ3yyZmcdzPqpuIKqDu4rhFf.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/admin/foot')
?>
