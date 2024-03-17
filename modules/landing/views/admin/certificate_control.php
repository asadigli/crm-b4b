<?php
  $this->page_title = $title;
  $this->load->view('layouts/admin/head');
  $this->load->view('layouts/admin/menu');
?>

<div class="container">
	<div class="page-card-inside my-4">
		<div class="right-side-page-card">
			<div class="page-card-header">
				<h4 class="mb-3"><?php echo lang("Certificate control"); ?></h4>
			</div>
			<div class="page-card-body">
				<div class="container-shadow mb-4">
          <div class="form-group">
            <input type="text" class="form-control" data-role="certificate-name">
          </div>
					<!-- <div class="form-group m-0">
						<input type="file" class="m-0" data-role="certificate-file">
					</div> -->


          <div class="col-sm-12 p-0">
						<div class="file-upload-container">
							<div class="file-upload">
								<input type="file" data-role="certificate-file" accept="image/*">
								<div>
									<em class="fas fa-cloud-upload-alt"></em>
									<span><?php echo lang("Add image"); ?></span>
								</div>
							</div>
							<div class="file-area d-none" data-role="certificate-preview-container">
								<div class="line"><img src=""></div>
							</div>
						</div>
					</div>
          <div class="d-flex justify-content-end mt-3">
  					<button class="def-btn" type="button" data-role="add-new-certificate">
  						<?php echo lang("Add"); ?>
  					</button>
  				</div>


				</div>
				<div class="container-shadow">
					<div class="table-wrapper">
					<table class="table table-striped" width="100%">
						<thead>
							<tr>
                				<th>ID</th>
								<th class="th-sm"><?php echo lang("Name"); ?></th>
								<th class="th-sm"><?php echo lang("Image"); ?></th>
								<th class="th-sm"><?php echo lang("Description"); ?></th>
								<th class="th-sm text-right">#</th>
							</tr>
						</thead>
						<tbody id="certificate_list">
							<?php for ($i=0; $i < 5; $i++) { ?>
               				 <tr class="load">
                  				<td>------</td>
                  				<td>------</td>
                  				<td>------</td>
                  				<td>------</td>
  								<td>------</td>
  							</tr>
              				<?php } ?>
						</tbody>
					</table>
					</div>
					<div class="d-flex justify-content-end mt-5">
						<button class="def-btn"><?php echo lang("Add"); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
$this->extraJS .= '<script type="module" data-role="page-js" src="'.assets("js/pvt/certificate.edit.3FwCIdnBw1OAXcglSVBVzr5jVUB5OS.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/admin/foot')

?>
