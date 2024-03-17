<?php
  $this->page_title = $title;
  $this->load->view('layouts/admin/head');
  $this->load->view('layouts/admin/menu');
?>

<div class="container">
	<div class="page-card-inside my-4">
		<div class="right-side-page-card">
			<div class="page-card-header">
				<h4 class="mb-3"><?php echo lang("Category control"); ?></h4>
			</div>
			<div class="page-card-body container-shadow" id="mn_category_list">
				<div class="form-group">
					<select data-role="product-groups" name="productGroup" disabled>
						<?php foreach (array_keys($groups) as $key => $item): ?>
						<option value="<?php echo $groups[$item]["id"]; ?>"
							<?php if($this->input->get("group") == $groups[$item]["id"]) {  echo " selected"; } ?>>
							<?php echo lang($item); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="d-flex align-items-center mt-3">
					<p class="mr-2">Göstər:</p>
					<label class="radiobtn mr-4 mb-0">
						<input type="radio" name="catType"
							<?php if(!$this->input->get("type") || $this->input->get("type") === 'brand') { echo "checked"; } ?>
							value="brand">
						<?php echo lang('Brand'); ?>
						<span class="checkmark"></span>
					</label>

					<label class="radiobtn mr-4 mb-0">
						<input type="radio" name="catType"
							<?php if($this->input->get("type") === 'category') { echo "checked"; } ?> value="category">
						<?php echo lang('Category'); ?>
						<span class="checkmark"></span>
					</label>

					<label class="radiobtn mr-4 mb-0">
						<input type="radio" name="catType"
							<?php if($this->input->get("type") === 'second_category') { echo "checked"; } ?>
							value="second_category">
						<?php echo lang('Sub category'); ?>
						<span class="checkmark"></span>
					</label>

				</div>
				<p class="mt-3" data-text="Yüklənir" data-role="loading-text"></p>
				<hr>
				<a href="#" data-type="brand"
					   style="margin-right: 17px;padding-right: 20px" data-toggle="modal"
					        data-target="#categoryModel"><?php echo lang("Add new brand"); ?></a>
				<a href="#" data-type="category"
					   style="margin-right: 17px;padding-right: 20px" data-toggle="modal"
					        data-target="#categoryModel"><?php echo lang("Add new category"); ?></a>
				<a href="#" data-type="second_category" data-toggle="modal"
					   data-target="#categoryModel"><?php echo lang("Add new sub-category"); ?></a>
				<hr>
				<table class="table table-striped" id="catlisttable">
					<thead>
						<tr>
							<th>No.</th>
							<th><?php echo lang("Name"); ?> </th>
							<th>#</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="modal fade" id="categoryModel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="categoryTitle"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="category_type" value="">
					<div class="form-group">
						<input type="text" name="new_category_name" placeholder="Ad...">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-role="add-new-category">
						<?php echo lang('Add'); ?>
					</button>
				</div>
			</div>
		</div>
	</div>

</div>

<?php
$this->extraJS = '<script type="module" src="'.assets("js/pvt/product/category/list.Lt7ZWDXaBwbt6iiuQAVBVU8BqL22uLzH.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/admin/foot');
?>
