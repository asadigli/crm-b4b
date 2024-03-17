<div class="breadcrumb-header justify-content-between mb-2">
	<div class="my-auto">
		<div class="d-flex justify-content-between align-items-center">
			<h4 class="content-title mb-0 my-auto"><?= lang("Products comments") ?></h4>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
	  <div class="card">
	    <div class="card-body">
				<div class="row">
					<div class="col-md-2 col-12">
		 				 <div class="form-group mb-md-0 mb-3 d-flex">
		 					 <input data-role="search-keyword" type="text" class="form-control" placeholder="<?= lang("Search") ?>">
		 				 </div>
		 		 </div>
				 <div class="col-md-2 col-12">
					 <div class="form-group mb-md-0 mb-3">
						 <select data-role="select-brands" name="brands" class="form-control custom-select">
							 <option value=""><?= lang("All brands") ?></option>
						 </select>
					 </div>
				 </div>
				 <div class="col-md-2 col-12">
					<div class="form-group mb-md-0 mb-3">
						<select data-role="select-entries" name="entries" class="form-control custom-select">
							<option value=""><?= lang("All entries") ?></option>
						</select>
					</div>
				</div>
					<div class="col-md-2 col-12">
						<div class="form-group mb-md-0 mb-3">
							<input type="date" name="" max="<?= date("Y-m-d", strtotime('-1 month')) ?>" data-role="select-start-date" value="<?= date("Y-m-d", strtotime('-1 month')) ?>" class="form-control" />
						</div>
					</div>
					<div class="col-md-2 col-12">
						<div class="form-group mb-md-0 mb-3">
							<input type="date" name="" max="<?= date('Y-m-d') ?>" data-role="select-end-date" value="<?= date("Y-m-d") ?>" class="form-control" />
						</div>
					</div>
					<div class="col-md-2 col-12">
						<div class="d-flex justify-content-end">
								<button type="button" data-role="search-filter" class="btn btn-primary" ><i class="mdi mdi-magnify mr-2"></i><?= lang("Search") ?></button>
						</div>
					</div>
				</div>
	    </div>
	  </div>
	</div>
</div>
<div class="row mt-4">
	<div class="col-12">
		<div class="box">
			<div class="box-header">
				<div class="d-flex justify-content-between">
					<div><b data-role="content-result-count"> 0 </b> <?= strtolower(lang("Result")) ?></div>
					<div><b data-role="content-result-time"> 0 </b> <?= strtolower(lang("Sec")) ?></div>
				</div>
			</div>
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped" style="width:100%;">
            <thead>
              <tr>
								<th style="width:1%" class="border-bottom-0">#</th>
								<th style="width:10%" class="border-bottom-0"><?= lang("Date") ?></th>
                <th style="width:10%" class="border-bottom-0"><?= lang("Entry name") ?></th>
								<th style="width:15%" class="border-bottom-0"><?= lang("Brand") ?></th>
								<th style="width:10%" class="border-bottom-0"><?= lang("Brand code") ?></th>
								<th style="width:13%" class="border-bottom-0"><?= lang("OEM") ?></th>
								<th style="width:10%" class="border-bottom-0"><?= lang("Product name") ?></th>
								<th style="width:10%" class="border-bottom-0"><?= lang("Stock") ?></th>
								<th style="width:6%" class="border-bottom-0"><?= lang("Sale price") ?></th>
								<th style="width:15%" class="border-bottom-0"><?= lang("Comment") ?></th>
              </tr>
            </thead>
            <tbody data-role="table-list">

            </tbody>
          </table>
					<?= $this->load->view("layouts/components/loaders/table_loader") ?>
        </div>
    </div>
  </div>
</div>
