<?php
  $this->headCSS = "<link href='//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css' rel='stylesheet'>";
  $this->page_title = lang('Product list');
  $this->load->view('layouts/admin/head');
  $this->load->view('layouts/admin/menu');
?>

<div class="container">
	<div class="page-card-inside my-4">
		<div class="right-side-page-card">
			<div class="page-card-header">
				<h4 class="mb-3"><?php echo lang("Product list"); ?></h4>
			</div>
			<div class="page-card-body container-shadow">
				<div class="form-group mb-5">
					<div class="row align-items-center m-0">
						<div class="col-sm d-flex align-items-center pl-0">
							<div class="form-group m-0">
								<select class="m-0" data-name="prod_brand">
									<option value="">- <?php echo lang("Choose product brand"); ?> -
									</option>
								</select>
							</div>
						</div>
						<div class="col-sm d-flex align-items-center">
							<div class="form-group m-0">
								<input class="m-0" type="text" placeholder="Brend kod..."
									      value="<?php echo $this->input->get("keyword"); ?>" data-name="br_keyword">
							</div>
						</div>
						<div class="col-sm d-flex justify-content-end pr-0">
							<button class="btn h-100" id="filter-products" type="button">
								<?php echo lang("Search"); ?>
							</button>
						</div>
					</div>

					<p class="mt-3 d-none" data-text="Yüklənir" data-role="loading-text"></p>
				</div>

				<table id="mn_product_list" class="table table-sm" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th class="th-sm">
								<label class="chck m-0">
									<input type="checkbox" data-role="select-all">
									<span class="checkmark"></span>
								</label>
							</th>
							<th class="th-sm"><?php echo lang("Product_name"); ?></th>
              <th class="th-sm"><?php echo lang("Brand"); ?></th>
              <th class="th-sm"><?php echo lang("Home product"); ?></th>
							<th class="th-sm"><?php echo lang("Status"); ?></th>
              <th>#</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>

        <div data-role="pagination"></div>

				<div class="update-all-popup d-none" data-role="show-selected-ones">
					<p class="mb-3"> <strong>0</strong> seçilib</p>
					<div class="row d-flex align-items-center">
						<div class="col-3">
							<select class="form-control" data-name="group-id">
								<option value="">- <?php echo lang("Choose group"); ?> -</option>
								<option value="1"><?php echo lang("Engine oils"); ?></option>
								<option value="2"><?php echo lang("Gear oils"); ?></option>
								<option value="3"><?php echo lang("Antifreeze"); ?></option>
								<option value="4"><?php echo lang("Fuel additives"); ?></option>
								<option value="5"><?php echo lang("Battery"); ?></option>
							</select>
						</div>
						<div class="col-3">
							<select class="form-control" data-name="brand-id" disabled>
								<option value=""> - <?php echo lang("Choose product brand"); ?> - </option>
							</select>
						</div>
						<div class="col-2">
							<select class="form-control" data-name="category-id" disabled>
								<option value=""> - <?php echo lang("Choose category"); ?> - </option>
							</select>
						</div>
						<div class="col-2">
							<select class="form-control" data-name="second-category-id" disabled>
								<option value=""> - <?php echo lang("Choose second category"); ?> -
								</option>
							</select>
						</div>
						<div class="col-2">
							<div class="w-100 d-flex justify-content-center">
								<button type="button" class="def-btn h-100 px-4"
									data-role="update-product-all"><?php echo lang("Update"); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
// $this->extraJS .= '<script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>';
// $this->extraJS .= '<script>$("#mn_product_list").dataTable({
//   bPaginate: false,
//   searching: false,
//   bDestroy: true
// })</script>';
$this->extraJS .= '<script type="module" src="'.assets("js/pvt/product/list.89mOyVoJ3yyZmcdzPqpuIKqDu4rhFf.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/admin/foot')
?>
