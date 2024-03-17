<?php
  $this->page_title = $title;
  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
?>

<div class="page-container" data-role="product-add-container" >
	<div class="container">
		<div class="mobile-margin-changer mb-4 mt-5">
			<div class="page-card-header tr-none position-relative">
				<div class="cs_whc_title px-3 py-3 mb-0 d-flex align-items-center justify-content-between">
					<h1><?php echo $title; ?></h1>
				</div>
			</div>
			<div class="page-card-body mobile-p-0 pt-0">
				<div class="container-shadow p-3">
						<!-- DESKTOP VERSION -->
            <?php if (isset($product)): ?>
              <input type="hidden" data-name="product-id" value="<?php echo $product; ?>">
            <?php endif; ?>
							<!-- 1 -->
								<!-- <h5 class="mb-4"><strong><?php echo $title; ?></strong></h5> -->
                <div class="row">
                  <div class="col-md-6 col-12">
                    <div class="form-group">
                      <input type="text" data-name="product-name" class="form-control" minlength="6" autocomplete="off"
                                  data-error="<?php echo lang("Please enter valid product name"); ?>"
                                  <?php echo isset($data["prod_name"]) ? 'value="'.$data["prod_name"].'"' : ""; ?>
    											         placeholder="<?php echo lang("Product_name"); ?>..." value="" required>
                      <small class="text-danger d-none"></small>
    								</div>
                    <div class="form-group">
                      <input type="text" data-name="brand-code" class="form-control" minlength="6" autocomplete="off"
                        <?php echo isset($data["brand_code"]) ? 'value="'.$data["brand_code"].'"' : ""; ?>
    											         placeholder="Brand kod..." required>
                      <small class="text-danger d-none"></small>
    								</div>
                    <div class="form-group">
                      <input type="text" data-name="product-oem" class="form-control" minlength="6" autocomplete="off"
                            <?php echo isset($data["OEM"]) ? 'value="'.$data["OEM"].'"' : ""; ?>
    											         placeholder="OEM..." required>
                      <small class="text-danger d-none"></small>
    								</div>
                    <div class="form-group">
                      <input type="number" step="0.01" data-name="product-price" class="form-control" autocomplete="off"
                              <?php echo isset($data["price"]) ? 'value="'.$data["price"].'"' : ""; ?>
    											         placeholder="<?php echo lang("Price"); ?>...">
                      <small class="text-danger d-none"></small>
    								</div>
                    <div class="form-group">
                      <input type="number" step="0.01" data-name="product-quantity" class="form-control" autocomplete="off"
                              <?php echo isset($data["quantity"]) ? 'value="'.$data["quantity"].'"' : ""; ?>
                                   placeholder="<?php echo lang("Quantity"); ?>...">
                      <small class="text-danger d-none"></small>
                    </div>
                    <div class="form-group">
                      <select data-name="product-group" class="form-control">
                        <option value=""<?php echo isset($data["group_id"]) && $data["group_id"] === "" ? " selected" : ""; ?>>- <?php echo lang("Choose group"); ?> -</option>
                        <option value="1"<?php echo isset($data["group_id"]) && $data["group_id"] === "1" ? " selected" : ""; ?>><?php echo lang("Engine oils"); ?></option>
                        <option value="2"<?php echo isset($data["group_id"]) && $data["group_id"] === "2" ? " selected" : ""; ?>><?php echo lang("Gear oils"); ?></option>
                        <option value="3"<?php echo isset($data["group_id"]) && $data["group_id"] === "3" ? " selected" : ""; ?>><?php echo lang("Antifreeze"); ?></option>
                        <option value="4"<?php echo isset($data["group_id"]) && $data["group_id"] === "4" ? " selected" : ""; ?>><?php echo lang("Fuel additives"); ?></option>
                        <option value="5"<?php echo isset($data["group_id"]) && $data["group_id"] === "5" ? " selected" : ""; ?>><?php echo lang("Battery"); ?></option>
                      </select>
                      <small class="text-danger d-none"></small>
                    </div>
                  </div>
                  <div class="col-md-6 col-12">
                    <div class="form-group">
                      <select disabled data-name="product-brand" class="form-control" >
                        <option value="">- <?php echo lang("Choose product brand"); ?> -</option>
                      </select>
                      <small class="text-danger d-none"></small>
                    </div>
                    <div class="form-group">
                      <select disabled data-name="product-category" class="form-control" <?php echo isset($data["first_category_id"]) ? 'data-val="'.$data["first_category_id"].'"' : ""; ?>>
                        <option value="">- <?php echo lang("Choose category"); ?> -</option>
                      </select>
                      <small class="text-danger d-none"></small>
                    </div>
                    <div class="form-group">
                      <select disabled data-name="product-second-category" class="form-control" <?php echo isset($data["second_category_id"]) ? 'data-val="'.$data["second_category_id"].'"' : ""; ?>>
                        <option value="">- <?php echo lang("Choose second category"); ?> -</option>
                      </select>
                      <small class="text-danger d-none"></small>
                    </div>
                    <div class="form-group">
                      <textarea class="form-control" placeholder="Qısa təsvir..." maxlength="500"
                            data-name="product-short-description"><?php echo isset($data["short_description"]) ? $data["short_description"] : ""; ?></textarea>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Ətraflı məlumat..."
                            data-name="product-description"><?php echo isset($data["description"]) ? $data["description"] : ""; ?></textarea>
                    </div>
                  </div>
                </div>

                <hr>
                <div class="row">
                  <div class="col-sm">
                    <label class="chck_container m-0">
                      <?php echo lang("Status"); ?>
                      <input type="checkbox" data-role="product-status"<?php echo isset($data["status"]) && $data["status"] === "1" ? " checked" : ""; ?>>
                      <span class="checkmark"></span>
                    </label>
                  </div>
                  <div class="col-sm">
                    <label class="chck_container m-0">
                      <?php echo lang("Home product"); ?>
                      <input type="checkbox" data-role="is-home-product"<?php echo isset($data["home_product"]) && $data["home_product"] === "1" ? " checked" : ""; ?>>
                      <span class="checkmark"></span>
                    </label>
                  </div>
                </div>
                <hr>

                <div id="storeImageSection">
                  <input type="file" id="storeImageUpload" name="image" multiple="">
                  <div id="prodImgPreview"></div>
                  <div id="prodImgUploadBtn">
	                   <!-- <button class="btn btn-primary" type="button"><em class="fa fa-save"></em> <?php echo lang("Add"); ?> </button> -->
                  </div>
                </div>

                <div class="d-flex justify-content-end">
                  <button class="def-btn px-4" data-role="add-product"
                              style="width:240px" data-text="<?php echo lang("Save"); ?>" ><?php echo lang("Add"); ?></button>
                </div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
$this->extraJSBefore .= '<script src="https://cdn.ckeditor.com/ckeditor5/27.0.0/classic/ckeditor.js"></script>';
$this->extraJS .= '<script type="module" data-role="page-js" src="'.assets("js/pvt/product.add.89mOyVoJ3yyZmcdzPqpuIKqDu4rhFf.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/foot');
 ?>
