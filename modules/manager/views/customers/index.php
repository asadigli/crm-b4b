<div class="row">
	<div class="col-lg-12">
		<div class="card filter">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-md-2">
						<input type="text"  data-role="search-keyword" value="<?= $url_params["keyword"] ?: ""  ?>" placeholder="<?= lang("Search") ?>" class="form-control" />
					</div>
					<div class="col-md-1">
						<input type="date" data-role="due_date" value="<?= $this->input->get("due_date") ? date("Y-m-d",strtotime($this->input->get("due_date"))) : date("Y-m-d") ?>" class="form-control" />
					</div>
					<div class="col-md-2">
						<div class="form-group m-0">
							<select data-role="select-city" name="cities" class="form-control custom-select">
								<option value=""><?= lang("All cities") ?></option>
							</select>
						</div>
					</div>
					<div class="col-md-1">
						<div class="form-group m-0">
							<select data-role="select-currency" name="currencies" class="form-control custom-select">
								<option value=""><?= lang("All currencies") ?></option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
		        <div class="form-group m-0">
		          <select class="custom-select" data-role="search_by_debts">
		            <option value=""><?= lang("All") ?></option>
								<option value="is_no_debt"><?= lang("By no debt") ?></option>
		            <option value="is_negative_debt"><?= lang("By negative debt") ?></option>
		            <option value="is_positive_debt" ><?= lang("By positive debt") ?></option>
		          </select>
		        </div>
		      </div>
					<div class="col-md-1">
		        <div class="form-group m-0">
		          <select class="custom-select" data-role="customer_type">
		            <option value=""><?= lang("All") ?></option>
								<option value="211"<?= $this->input->get("customer_type") === "211" ? " selected" : ""; ?>>211</option>
								<option value="311"<?= $this->input->get("customer_type") === "311" ? " selected" : ""; ?>>311</option>
		            <option value="531"<?= $this->input->get("customer_type") === "531" ? " selected" : ""; ?>>531</option>
		          </select>
		        </div>
		      </div>
					<div class="col-md-2">
		        <div class="form-group m-0">
		          <select class="custom-select" data-role="status">
								<option value=""><?= lang("All customers") ?></option>
		            <option value="active"<?= (!$this->input->get("status") || $this->input->get("status") === "active") ? " selected" : ""; ?>><?= lang("Active customers") ?></option>
								<option value="not_active"<?= $this->input->get("status") === "not_active" ? " selected" : ""; ?>><?= lang("Not active customers") ?></option>
		          </select>
		        </div>
		      </div>
					<!-- <div class="col-md-1">
						<div class="custom-control custom-checkbox">
               <input type="checkbox" name="is_active" id="is_active">
               <label for="is_active"><?= lang("Active customers") ?></label>
             </div>
					</div> -->

					<div class="col-md-1 d-flex justify-content-end">
						<button type="button" data-role="search-filter" class="btn btn-primary" ><i class="fa-solid fa-magnifying-glass me-2"></i><?= lang("Search") ?></button>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-lg-2 col-md-3 col-6 form-group" data-toggle="tooltip" data-placement="bottom" title="<?= lang("Inactive customers description") ?>">
							<label class="form-label ml-1" for=""><?= lang("Inactive customers") ?></label>
							<div class="input-group mb-3 mb-lg-0">
								<div class="input-group-prepend">
									<span class="input-group-text bg-transparent pr-2 border" >
										<div class="form-check">
											<label class="form-check-label text-muted">
												<input data-role="is-inactive-customers" name="is_inactive_customers" type="checkbox" class="form-check-input c-pointer" <?= $url_params["is_inactive_customers"] ? "checked" : "" ?>>
											</label>
										</div>
									</span>
								</div>
								<input style="max-width:50px;padding: 5px;" type="number" value="<?= $url_params["inactive_customers"] ?: 180 ?>" class="form-control" placeholder="<?= lang("Day") ?>" data-role="inactive-customers" name="inactive_customers" >

							</div>
						</div>

				</div>
			</div>
		</div>
	</div>
</div>


<section class="content">
	<div class="row d-flex justify-content-between align-items-center">
		<div class="col-12 d-flex justify-content-end">
			<a class="link avh-disable" data-role="excel-export" href="javascript:void(0)"><?= lang("Excel export") ?> <i class="fa-solid fa-file-export"></i></a>
		</div>
	</div>

    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header">
                  <div class="d-flex justify-content-between">
										<div>
											<b data-role="content-result-count"> 0 </b> <?= strtolower(lang("Result")) ?>
											|
											<b data-role="content-result-tla"> 0 </b> <?= lang("EURO") ?>
										</div>
                    <div><b data-role="content-result-time"> 0 </b> <?= strtolower(lang("Sec")) ?></div>
                  </div>
                </div>
				<div class="table-responsive-md">
					<table class="table mb-0 table-bordered">
						<thead>
							<tr>
							<th scope="col" style="width:1%;">#</th>
							<th scope="col" style="width:10%;" ><?= lang("Name") ?></th>
							<th scope="col" style="width:10%;" ><?= lang("Code") ?></th>
							<th scope="col" style="width:10%;" ><?= lang("Contact") ?></th>
							<th scope="col" style="width:3%;" ><?= lang("Currency") ?></th>
							<th scope="col" style="width:5%;" ><?= lang("City") ?></th>
							<th scope="col" style="width:5%;" ><?= lang("Monthly sale amount") ?></th>
							<th scope="col" style="width:5%;" ><?= lang("Monthly payment amount") ?></th>
							<th scope="col" style="width:5%;" ><?= lang("AVA ID") ?></th>
              <th scope="col" style="width:10%;" ><?= lang("Sale") ?></th>
              <th scope="col" style="width:10%;" ><?= lang("Payment") ?></th>
              <th scope="col" style="width:10%;" ><?= lang("Last sale date") ?></th>
              <th scope="col" style="width:10%;" ><?= lang("Last payment date") ?></th>
							<th scope="col" style="width:15%;" ><?= lang("Left amount") ?></th>
							<th scope="col" style="width:5%;" ><?= lang("Block") ?></th>
							</tr>
						</thead>
						<tbody data-role="table-list" id="customer_list_tbody">

						</tbody>
					</table>
					<?= $this->load->view("layouts/components/loaders/table_loader") ?>
				</div>
				<div class="load-more d-none" id="load_more_div">
					<a data-role="load-more" href="javascript:void(0)"><?= lang("Load more") ?></a>
				</div>

				</div>
        </div>
    </div>
</section>
