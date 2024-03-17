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
					<?php echo $this->session->userdata("name")." ".$this->session->userdata("surname"); ?>
				</h4>
			</div>
			<div class="page-card-body container-shadow p-4">
				<nav>
					<div class="nav nav-tabs" role="tablist">
						<!-- 1 -->
						<button class="nav-link active" id="product-features-tab" data-bs-toggle="tab"
							data-bs-target="#nav-product-features" type="button" role="tab"
							aria-controls="nav-product-features"
							aria-selected="true"><?php echo lang("İnformation") ?></button>
						<!-- 2 -->
						<button class="nav-link" id="compatible-cars-tab" data-bs-toggle="tab"
							data-bs-target="#nav-compatible-cars" type="button" role="tab"
							aria-controls="nav-compatible-cars"
							aria-selected="false"><?php echo lang("Password") ?></button>
					</div>
				</nav>
				<div class="tab-content" id="nav-tabContent">
					<!-- 1 -->
					<div class="tab-pane fade show active" id="nav-product-features" role="tabpanel"
						aria-labelledby="product-features-tab">
						<div class="inner-tab" id="productDescription"
							data-text="<?php echo lang("No information"); ?>">
							<form action="">
								<div class="form-group">
									<label for=""> <?php echo lang("Name") ?></label>
									<input type="text" placeholder="">
								</div>

                                <div class="form-group">
									<label for=""> <?php echo lang("Surname") ?></label>
									<input type="text" placeholder="">
								</div>

                                <div class="d-flex justify-content-end">
									<button class="btn"><?php echo lang("Save") ?></button>
								</div>
							</form>
						</div>
					</div>
					<!-- 2 -->
					<div class="tab-pane fade" id="nav-compatible-cars" role="tabpanel"
						aria-labelledby="compatible-cars-tab">
						<div class="inner-tab">
							<form action="">
								<div class="form-group">
									<label for=""> <?php echo lang("New password") ?></label>
									<input type="text" placeholder="">
								</div>

								<div class="form-group">
									<label for=""> <?php echo lang("Confirm_password") ?></label>
									<input type="text" placeholder="">
								</div>

								<div class="form-group">
									<label for=""> <?php echo lang("Old password") ?></label>
									<input type="text" placeholder="">
								</div>

								<div class="d-flex justify-content-end">
									<button class="btn"><?php echo lang("Save") ?></button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<?php
$this->extraJS .= '<script src="//cdn.ckeditor.com/ckeditor5/28.0.0/classic/ckeditor.js"></script>';
$this->extraJS .= '<script type="module" src="'.assets("js/pvt/news.edit.LtI17JKQaO6IDhdUhcF4Ot1c4ZjhRX.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/admin/foot')
?>
