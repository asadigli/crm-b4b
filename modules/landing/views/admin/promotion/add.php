<?php
  $this->page_title = $title;
  $this->load->view('layouts/admin/head');
  $this->load->view('layouts/admin/menu');
?>
<div class="container">
	<div class="page-card-inside my-4">
		<div class="right-side-page-card">
			<div class="page-card-header">
				<h4 class="mb-3">
          <?php echo lang("promotion control"); ?>
        </h4>
			</div>
			<div class="page-card-body container-shadow p-4">
        <div class="d-flex mb-4">
					<label for="data-lang" class="col-sm-3 p-0 col-form-label">
              <?php echo lang("Language"); ?>
          </label>
					<div class="col-sm-9  p-0">
            <select class="form-control" data-role="data-lang">
  						<option value="az"<?php echo !$this->input->get("data-lang") || $this->input->get("data-lang") === "az" ? " selected" : ""; ?>>AZ</option>
              <option value="en"<?php echo $this->input->get("data-lang") === "en" ? " selected" : ""; ?>>EN</option>
              <option value="ru"<?php echo $this->input->get("data-lang") === "ru" ? " selected" : ""; ?>>RU</option>
  						<option value="tr"<?php echo $this->input->get("data-lang") === "tr" ? " selected" : ""; ?>>TR</option>
  					</select>
					</div>
				</div>

				<div class="d-flex mb-4">
          <label for="brand" class="col-sm-3 col-form-label p-0">
              Aksiya adı
          </label>
					<div class="col-sm-9 p-0">
						<input type="text" class="form-control h-100" placeholder="---" data-name="promotion-title">
					</div>
				</div>
				<div class="d-flex">
					<label for="brand" class="col-sm-3 col-form-label p-0">
              Aksiya ətraflı
          </label>
					<div class="col-sm-9 p-0">
						<div id="promotion_description"></div>
					</div>
				</div>
				<div class="d-flex my-5">
					<label for="brand" class="col-sm-3 p-0 col-form-label"><?php echo lang("Tags"); ?></label>
					<div class="col-sm-9 p-0">
						<div class="bs-example">
							<input type="text" value="" data-role="tagsinput" class="w-100" placeholder="<?php echo lang("Enter tag name"); ?>"/>
						</div>
					</div>
				</div>
        <div class="d-flex my-5">
					<label for="brand" class="col-sm-3 p-0 col-form-label"><?php echo lang("Status"); ?></label>
					<div class="col-sm-9 p-0">
            <input type="radio" data-role="is_active_promotion" name="is_active" value="1" checked/> Aktiv
            <input type="radio" data-role="is_active_promotion" name="is_active" value="0"/> Aktiv deyil
					</div>
				</div>
				<div class="d-flex mt-5">
					<label for="brand" class="col-sm-3 col-form-label p-0">
              Aksiya şəkil
          </label>
					<div class="col-sm-9 p-0">
						<div class="file-upload-container">
							<div class="file-upload">
								<input type="file" data-role="promotion-image-container" multiple accept="image/*">
								<div>
									<em class="fas fa-cloud-upload-alt"></em>
									<span><?php echo lang("Add image"); ?></span>
								</div>
							</div>
							<div class="file-area d-none" data-role="promotion-preview-container">

								<div class="line"><img src=""></div>

							</div>
						</div>
					</div>
				</div>
				<div class="d-flex justify-content-end mt-3">
					<button class="def-btn" type="button" data-role="add-new-promotion">
						<?php echo lang("Add"); ?>
					</button>
				</div>
			</div>
		</div>
	</div>

</div>

<?php
$this->extraJS .= '<script src="//cdn.ckeditor.com/ckeditor5/28.0.0/classic/ckeditor.js"></script>';
$this->extraJS .= '<script type="module" src="'.assets("js/pvt/promotion/add.LtI17JKQaO6IDhdUhcF4Ot1c4ZjhRX.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/admin/foot')
?>
