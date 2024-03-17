<?php
  $this->page_title = $title;
  $this->load->view('layouts/admin/head');
  $this->load->view('layouts/admin/menu');
?>

<div class="container">
	<div class="page-card-inside my-4">
		<div class="right-side-page-card">
			<div class="page-card-header">
				<h4 class="mb-3"><?php echo lang("News list"); ?></h4>
			</div>
			<div class="page-card-body container-shadow p-4">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>No.</th>
							<th><?php echo lang("Name"); ?> </th>
							<th><?php echo lang("Description"); ?> </th>
							<th><?php echo lang("Date"); ?> </th>
              <th><?php echo lang("Status"); ?></th>
							<th>#</th>
						</tr>
					</thead>
					<tbody id="admin_news_list">

					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php
// $this->extraJSBefore .= '<script src="//cdn.ckeditor.com/ckeditor5/27.0.0/classic/ckeditor.js"></script>';
$this->extraJS .= '<script type="module"  src="'.assets("js/pvt/news/list.LtI17JKQaO6IDhdUhcF4Ot1c4ZjhRX.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/admin/foot')
?>
