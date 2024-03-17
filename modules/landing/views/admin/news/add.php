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
          <?= lang("News control"); ?>
        </h4>
			</div>
			<div class="page-card-body container-shadow p-4">
        <input type="hidden" value="news" data-name="news-type">


        <div class="d-flex mb-4">
					<label for="data-lang" class="col-sm-3 p-0 col-form-label">
              <?= lang("Language"); ?>
          </label>
					<div class="col-sm-9  p-0">
            <select class="form-control" data-role="data-lang">
  						<option value="az"<?= !$this->input->get("data-lang") || $this->input->get("data-lang") === "az" ? " selected" : ""; ?>>AZ</option>
              <option value="en"<?= $this->input->get("data-lang") === "en" ? " selected" : ""; ?>>EN</option>
              <option value="ru"<?= $this->input->get("data-lang") === "ru" ? " selected" : ""; ?>>RU</option>
  						<option value="tr"<?= $this->input->get("data-lang") === "tr" ? " selected" : ""; ?>>TR</option>
  					</select>
					</div>
				</div>

				<div class="d-flex mb-4">
          <label for="brand" class="col-sm-3 col-form-label p-0">
              Xəbər adı
          </label>
					<div class="col-sm-9 p-0">
						<input type="text" class="form-control h-100" placeholder="---" data-name="news-title">
					</div>
				</div>
				<div class="d-flex">
					<label for="brand" class="col-sm-3 col-form-label p-0">
              Xəbər ətraflı
          </label>
					<div class="col-sm-9 p-0">
						<div id="news_description"></div>
					</div>
				</div>
				<div class="d-flex my-5">
					<label for="brand" class="col-sm-3 p-0 col-form-label"><?= lang("Tags"); ?></label>
					<div class="col-sm-9 p-0">
						<div class="bs-example">
							<input type="text" value="" data-role="tagsinput" class="w-100" placeholder="<?= lang("Enter tag name"); ?>"/>
						</div>
					</div>
				</div>
        <div class="d-flex my-5">
					<label for="brand" class="col-sm-3 p-0 col-form-label"><?= lang("Status"); ?></label>
					<div class="col-sm-9 p-0">
            <input type="radio" data-role="is_active_news" name="is_active" value="1" checked/> Aktiv
            <input type="radio" data-role="is_active_news" name="is_active" value="0"/> Aktiv deyil
					</div>
				</div>
        <div class="d-flex mb-4">
          <label for="brand" class="col-sm-3 col-form-label p-0">
              Şəkil linki
          </label>
					<div class="col-sm-9 p-0">
						<input type="url" class="form-control h-100" placeholder="http://" data-name="news-image-link">
					</div>
				</div>
				<div class="d-flex mt-5">
					<label for="brand" class="col-sm-3 col-form-label p-0">
              Xəbər şəkil
          </label>
					<div class="col-sm-9 p-0">
						<div class="file-upload-container">
							<div class="file-upload">
								<input type="file" data-role="news-image-container" multiple accept="image/*">
								<div>
									<em class="fas fa-cloud-upload-alt"></em>
									<span><?= lang("Add image"); ?></span>
								</div>
							</div>
							<div class="file-area d-none" data-role="news-preview-container">

								<div class="line"><img src=""></div>

							</div>
						</div>
					</div>
				</div>
				<div class="d-flex justify-content-end mt-3">
					<button class="def-btn" type="button" data-role="add-new-news">
						<?= lang("Add"); ?>
					</button>
				</div>
			</div>
		</div>
	</div>

</div>

<?php
$this->extraJS .= '<script src="//cdn.ckeditor.com/ckeditor5/28.0.0/classic/ckeditor.js"></script>';
$this->extraJS .= '<script type="module" src="'.assets("js/pvt/news/add.LtI17JKQaO6IDhdUhcF4Ot1c4ZjhRX.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/admin/foot')
?>
