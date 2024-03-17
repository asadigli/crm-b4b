<?php
  $this->page_title = $title;
  $this->load->view('layouts/admin/head');
  $this->load->view('layouts/admin/menu');
  // $this->load->view('layouts/admin/about');
  $this->load->view('layouts/admin/create');
?>

<div class="container">
	<div class="page-card-inside my-4">
		<div class="right-side-page-card">
			<div class="page-card-header">
				<h4 class="mb-3"><?php echo lang("Create"); ?></h4>
			</div>
			<div class="page-card-body container-shadow p-4">
				<div class="line-button d-flex justify-content-between align-items-left">
					<select class="h-50" data-role="data-lang">
						<option value="az"<?php echo !$this->input->get("data-lang") || $this->input->get("data-lang") === "az" ? " selected" : ""; ?>>AZ</option>
            <option value="en"<?php echo $this->input->get("data-lang") === "en" ? " selected" : ""; ?>>EN</option>
            <option value="ru"<?php echo $this->input->get("data-lang") === "ru" ? " selected" : ""; ?>>RU</option>
						<option value="tr"<?php echo $this->input->get("data-lang") === "tr" ? " selected" : ""; ?>>TR</option>
					</select>
					<button class="def-btn popBtn" data-id="add-faq-popup"><em class="fas fa-plus"></em></button>
				</div>
				<div class="tab">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active" id="about-page-tab" data-bs-toggle="tab" data-bs-target="#about-page"
								type="button" role="tab" aria-controls="about-page"
								aria-selected="true"><?php echo lang("About_us")?></button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faq-list"
                        type="button" role="tab" aria-controls="faq-list" aria-selected="false">
                            <?php echo lang("FAQ")?>
              </button>
						</li>
					</ul>
					<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade show active p-3" id="about-page" role="tabpanel"
                  data-btn-text="<?php echo lang("Update"); ?>"  aria-labelledby="about-page-tab">
						</div>
						<div class="tab-pane fade p-3" id="faq-list" role="tabpanel" aria-labelledby="faq-tab">
							<table class="table table-striped" id="faq_list">
								<thead>
									<tr>
										<th class="w-50"><?php echo lang("Title"); ?> </th>
										<th class="w-50"><?php echo lang("Description"); ?> </th>
										<th class="text-center w-25">#</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td></td>
										<td></td>
										<td>
											<span class="popBtn" data-id="a"><i class="fas fa-edit"></i></span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<style>
	.ck-editor__editable_inline {
		min-height: 150px;
	}
</style>

<?php
$this->extraJS = '<script src="//cdn.ckeditor.com/ckeditor5/28.0.0/classic/ckeditor.js"></script>';
$this->extraJS .= '<script type="module" src="'.assets("js/pvt/faqAbout.edit.asdERsdfWsdfsdf.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/admin/foot');
?>
