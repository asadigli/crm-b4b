<?php
  $this->page_title = $title;
  $this->headCSS = '<link href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" rel="stylesheet">';
  $this->load->view('layouts/admin/head');
  $this->load->view('layouts/admin/menu');
?>
<div class="container">
	<div class="right-side-page-card my-4">
		<div class="page-card-header">
			<h4 class="mb-3"><?= lang("Product_edit"); ?></h4>
		</div>
		<div class="page-card-body container-shadow">
			<div class="p-3">
				<div class="row">
          <input type="hidden" value="<?= $id; ?>" data-name="product-id">
					<div class="col-md-6 col-12">
						<div class="form-group">
							<input type="text" data-name="product-name" minlength="6" autocomplete="off"
								data-error="Etibarlı məhsul adı daxil edin (min. 2 simbol)"
								placeholder="<?= lang("Product_name"); ?>..." value="<?= $data["prod_name"]; ?>" required="">
							<small class="text-danger d-none"></small>
						</div>
						<div class="form-group">
							<input type="text" data-name="brand-code" minlength="6" autocomplete="off"
								placeholder="<?= lang("Brand_code"); ?>..." value="<?= $data["brand_code"]; ?>" required="">
							<small class="text-danger d-none"></small>
						</div>
						<div class="form-group">
							<input type="text" data-name="product-oem" minlength="6" autocomplete="off"
								placeholder="<?= lang("OEM_code"); ?>..." value="<?= $data["OEM"]; ?>" required="">
							<small class="text-danger d-none"></small>
						</div>
						<div class="form-group">
							<input type="number" step="0.01" data-name="product-price" autocomplete="off"
								placeholder="<?= lang("Price"); ?>..." value="<?= $data["price"]; ?>">
							<small class="text-danger d-none"></small>
						</div>
						<div class="form-group">
							<input type="number" step="0.01" data-name="product-quantity" autocomplete="off"
								placeholder="<?= lang("Quantity"); ?>..." value="<?= $data["quantity"]; ?>">
							<small class="text-danger d-none"></small>
						</div>
						<div class="form-group">
							<select data-name="product-group">
								<option value=""<?= !$data["group_id"] ? " selected" : ""; ?>>- <?= lang("Choose group"); ?> -</option>
								<option value="1"<?= (int)$data["group_id"] === 1 ? " selected" : ""; ?>><?= lang("Engine oils"); ?></option>
								<option value="2"<?= (int)$data["group_id"] === 2 ? " selected" : ""; ?>><?= lang("Gear oils"); ?></option>
								<option value="3"<?= (int)$data["group_id"] === 3 ? " selected" : ""; ?>><?= lang("Antifreeze"); ?></option>
								<option value="4"<?= (int)$data["group_id"] === 4 ? " selected" : ""; ?>><?= lang("Fuel additives"); ?></option>
								<option value="5"<?= (int)$data["group_id"] === 5 ? " selected" : ""; ?>><?= lang("Battery"); ?></option>
							</select>
							<small class="text-danger d-none"></small>
						</div>
					</div>
					<div class="col-md-6 col-12">
						<div class="form-group">
							<select disabled="" data-name="product-brand"<?= $data["brand_id"] ? ' data-val="'.$data["brand_id"].'"' : ""; ?>>
								<option value="">- <?= lang("Choose product brand"); ?> -</option>
							</select>
							<small class="text-danger d-none"></small>
						</div>
						<div class="form-group">
							<select disabled="" data-name="product-category"<?= $data["first_category_id"] ? ' data-val="'.$data["first_category_id"].'"' : ""; ?>>
								<option value="">- <?= lang("Choose category"); ?> -</option>
							</select>
							<small class="text-danger d-none"></small>
						</div>
						<div class="form-group">
							<select disabled="" data-name="product-second-category"<?= $data["second_category_id"] ? ' data-val="'.$data["second_category_id"].'"' : ""; ?>>
								<option value="">- <?= lang("Choose second category"); ?> -</option>
							</select>
							<small class="text-danger d-none"></small>
						</div>
						<div class="form-group">
							<textarea placeholder="Qısa təsvir..." maxlength="500"
								data-name="product-short-description"><?= $data["short_description"]; ?></textarea>
						</div>
						<div class="form-group">
							<textarea placeholder="Ətraflı məlumat..." data-name="product-description"><?= $data["description"]; ?></textarea>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-sm">
						<label class="chck">
							<input type="checkbox"<?= $data["status"] ? ' checked="checked"' : ""; ?> data-name="product-status">
							<span class="checkmark"></span>
							<p><?= lang("Status"); ?></p>
						</label>
					</div>
					<div class="col-sm">
						<label class="chck">
							<input type="checkbox"<?= $data["home_product"] ? ' checked="checked"' : ""; ?>  data-name="is-home-product">
							<span class="checkmark"></span>
							<p><?= lang("Home product"); ?></p>
						</label>
					</div>
				</div>
				<hr>
        <div class="row my-4 ml-2">
          <label for="">
              Məhsul şəkilləri
          </label>
					<div class="col-sm-12 exist-image-items">
              <?php foreach ($data["images"] as $key => $img){
                    if (isset($img["small"]) && $img["small"]){ ?>
                  <div class="exist-image-item">
                    <span><em class="fa fa-times" data-role="delete-exist-image"></em></span>
                    <img data-src=<?= substr($img["small"], strrpos($img["small"], '/') + 1); ?> src="<?= $img["small"]; ?>">
                  </div>
                <?php }} ?>
          </div>
        </div>
        <hr>
				<div class="file-upload-container">
					<div class="file-upload">
						<input type="file" data-role="product-image-container" accept="image/*" multiple>
						<div>
							<em class="fas fa-cloud-upload-alt"></em>
							<span><?= lang("Add image"); ?></span>
						</div>
					</div>
          <div class="file-area d-none" data-role="product-preview-container">

            <div class="line"><img src=""></div>

          </div>
				</div>
				<div class="d-flex justify-content-end mt-3">
					<button class="def-btn px-4" data-role="edit-product" data-text="<?= lang("Update"); ?>">
					<?= lang("Update"); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>


<?php
$this->extraJS .= '<script type="module" src="'.assets("js/pvt/product/edit.89mOyVoJ3yyZmcdzPqpuIKqDu4rhFf.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/admin/foot')
?>
