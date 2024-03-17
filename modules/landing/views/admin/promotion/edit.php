<?php
  $this->page_title = $data["title"];
  $this->load->view('layouts/admin/head');
  $this->load->view('layouts/admin/menu');
?>

<div class="container">
	<div class="page-card-inside my-4">
		<div class="right-side-page-card">
			<div class="page-card-header">
				<h4 class="mb-3">
          <?= lang("Promotion control"); ?>
        </h4>
			</div>
			<form action="/admin/promotion/<?= $id; ?>/edit-action" method="POST" class="page-card-body container-shadow p-4">
        <input type="hidden" value="promotion" data-name="promotion-type">
				<div class="form-group d-flex mb-4">
					<label for="brand" class="col-sm-3 p-0 col-form-label">
              Aksiya adı
          </label>
					<div class="col-sm-9 input-group p-0">
						<input type="text" class="form-control h-100" value="<?= $data["title"]; ?>"
                      placeholder="---" data-name="promotion-title">
					</div>
				</div>
				<div class="form-group d-flex">
					<label for="brand" class="col-sm-3 col-form-label p-0">
              Aksiya ətraflı
          </label>
					<div class="col-sm-9 p-0">
						<div id="promotion_description"><?= $data["details"]; ?></div>
					</div>
				</div>
				<div class="d-flex my-5">
					<label for="brand" class="col-sm-3 p-0 col-form-label"><?= lang("Tags"); ?></label>
					<div class="col-sm-9 p-0">
						<div class="bs-example">
							<input type="text" value="<?= implode(",",$data["tags"]); ?>" data-role="tagsinput" class="w-100" />
						</div>
					</div>
				</div>
        <div class="d-flex my-5">
					<label for="brand" class="col-sm-3 p-0 col-form-label"><?= lang("Status"); ?></label>
					<div class="col-sm-9 p-0">
            <input type="radio" data-role="is_active_promotion" name="is_active" value="1"<?= $data["status"] ? " checked" : ""; ?>/> Aktiv
            <input type="radio" data-role="is_active_promotion" name="is_active" value="0"<?= !$data["status"] ? " checked" : ""; ?>/> Aktiv deyil
					</div>
				</div>
        <div class="d-flex my-5">
          <label for="brand" class="col-sm-3 col-form-label p-0">
              Aksiyanın şəkilləri
          </label>
					<div class="col-sm-9 p-0 exist-image-items">
              <?php foreach ($data["images"] as $key => $img){
                    if (isset($img["small"]) && $img["small"]){ ?>
                  <div class="exist-image-item">
                    <span><em class="fa fa-times" data-role="delete-exist-image"></em></span>
                    <img data-src=<?= substr($img["small"], strrpos($img["small"], '/') + 1); ?> src="<?= $img["small"]; ?>">
                  </div>
                <?php }} ?>
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
									<span><?= lang("Add image"); ?></span>
								</div>
							</div>
							<div class="file-area<?= !$data["images"] ? " d-none" : ""; ?>" data-role="promotion-preview-container">
							</div>
						</div>
					</div>
				</div>
				<div class="d-flex justify-content-end">
					<button class="def-btn" type="button" data-role="edit-promotion">
						<?= lang("Update"); ?>
					</button>
				</div>
			</form>
		</div>
	</div>

</div>

<?php
$this->extraJS = '<script src="//cdn.ckeditor.com/ckeditor5/28.0.0/classic/ckeditor.js"></script>';
$this->extraJS .= '<script type="module" src="'.assets("js/pvt/promotion/edit.LtI17JKQaO6IDhdUhcF4Ot1c4ZjhRX.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/admin/foot')
?>
