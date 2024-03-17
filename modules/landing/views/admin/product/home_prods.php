<?php
  $this->page_title = lang('Home_page_products');
  $this->load->view('layouts/admin/head');
  $this->load->view('layouts/admin/menu');
?>

<div class="container">
	<div class="page-card-inside my-4">
		<div class="page-card-header">
			<h4 class="mb-3"><?php echo lang('Home_page_products'); ?></h4>
		</div>
		<div class="page-card-body">
			<div class="row d-flex justify-content-between">
				<div class="col-sm-12">
					<div class="container-shadow">
						<!-- page card body -->
						<div class="page-card-body">
							<!-- OLD DESIGN -->
							<div class="table-wrapper">
								<table class="w-100">
									<thead>
										<tr>
											<th scope="col"><?php echo 'No.'; ?></th>
											<th scope="col"><?php echo lang('Product_name'); ?></th>
											<th scope="col">OEM / Brend kod</th>
											<th scope="col"><?php echo lang('Price'); ?></th>
                      <th>#</th>
										</tr>
									</thead>
									<tbody id="homeProducts" class="connected-sortable droppable-area1"></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<?php
$this->extraJSBefore = '<script src="'.assets('js/jquery/jquery-migrate-3.0.0.min.js').'"></script>';
$this->extraJSBefore .= '<script src="'.assets('js/jquery/jquery-ui.min.js').'"></script>';
$this->extraJS = '<script type="module" data-role="page-js" src="'.assets("js/pvt/product/home.list.Lgje13hjtdftertvcbyYwerc4ZjhRX.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/admin/foot')
?>
