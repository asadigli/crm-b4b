<?php
  $this->page_title = $title;
  $this->load->view('layouts/admin/head');
  $this->load->view('layouts/admin/menu');
?>

<div class="container">
	<div class="page-card-inside my-4">
		<div class="right-side-page-card">
		<div class="page-card-header">
				<h4 class="mb-3"><?= lang("Brand list"); ?></h4>
			</div>
			<div class="page-card-body container-shadow p-4" id="">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>No.</th>
              <th><?= lang("Image"); ?> </th>
							<th><?= lang("Name"); ?> </th>
							<th><?= lang("Description"); ?> </th>
							<th>#</th>
						</tr>
					</thead>
					<tbody id="brand_container_v2">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php
$this->extraJS .= '<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>';
$this->extraJS .= '<script type="module" src="'.assets("js/pvt/brand/list.rIfXZsnnK0aQyqzzPu6hTJvw13oGiQ.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/admin/foot')
?>
