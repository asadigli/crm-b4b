<?php
// $this->extraCSS = '';
$this->page_title = $title;
$this->load->view('layouts/admin/head');
$this->load->view('layouts/admin/menu');
?>
<style media="screen">
	.image-preview {
		position: relative;
		margin-top: 20px
	}

	.image-preview em {
		position: absolute;
		color: white;
		top: 10px;
		left: 10px
	}

	.image-preview img {}
	.ck-editor__editable_inline {
		min-height: 240px;
	}
</style>

<div class="container">
	<div class="page-card-inside my-4">
		<div class="right-side-page-card">
			<div class="page-card-header">
				<h4 class="mb-3"><?php echo lang("Brand control"); ?></h4>
			</div>
			<div class="page-card-body container-shadow p-4">

				<div class="d-flex mb-3">
					<label class="col-sm-3 col-form-label p-0">Brend adı</label>
					<div class="col-sm-9 p-0">
						<input type="text" class="form-control" data-name="brand-name">
					</div>
				</div>

				<div class="d-flex mb-3">
					<label class="col-sm-3 col-form-label p-0">Brend haqqında</label>
					<div class="col-sm-9 p-0">
						<div id="editor"></div>
					</div>
				</div>
				<div class="d-flex mb-3">
					<label class="col-form-label col-sm-3 p-0"><?=lang("Image")?></label>
					<div id="file_uploader"></div>
				</div>

				<div class="d-flex justify-content-end">
					<button class="def-btn" type="button" data-role="add-new-brand">
						<?php echo lang("Add"); ?>
					</button>
				</div>
			</div>
		</div>
	</div>

</div>
<script src="//cdn.ckeditor.com/ckeditor5/28.0.0/classic/ckeditor.js"></script>

<?php
$this->extraJS .= '<script type="module" src="'.assets("js/pvt/brand/add.rIfXZsnnK0aQyqzzPu6hTJvw13oGiQ.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/admin/foot')
?>
