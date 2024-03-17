<div class="breadcrumb-header justify-content-between mb-2">
	<div class="my-auto">
		<div class="d-flex justify-content-between align-items-center">
			<h4 class="content-title mb-0 my-auto"><?= lang("Products import") ?></h4>
      <div class="d-flex align-items-center">
        <div class="pr-1 mb-xl-0 me-4">
          <a href="<?= path_local("assets/manager/templates/importStoreProductsTemplate.xls") ?>?v=20" class="link" ><?= lang("Download excel template") ?></a>
        </div>
        <div style="max-height:44px;" data-toggle="tooltip" data-placement="left" data-original-title="<?= lang("Add excel") ?>">
          <button type="button" class="btn btn-info btn-icon card-title" data-bs-toggle="modal" data-bs-target="#add-modal" data-role="open-add-modal">
      			<i class="fa-solid fa-file-import"></i>
          </button>
        </div>
      </div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
	  <div class="card">
	    <div class="card-body">
				<div class="row">
					<div class="col-3">
		 				 <div class="form-group m-0 d-flex">
		 					 <input data-role="search-keyword" type="text" class="form-control" placeholder="<?= lang("Search") ?>">
		 				 </div>
		 		 </div>
				 <div class="col-2 mb-3">
					 <div class="form-group m-0">
						 <select data-role="select-brands" name="brands" class="form-control custom-select">
							 <option value=""><?= lang("All brands") ?></option>
						 </select>
					 </div>
				 </div>
					<div class="col-2">
						<input type="date" name="" max="<?= date("Y-m-d", strtotime('-1 month')) ?>" data-role="select-start-date" value="<?= date("Y-m-d", strtotime('-1 month')) ?>" class="form-control" />
					</div>
					<div class="col-2">
						<input type="date" name="" max="<?= date('Y-m-d') ?>" data-role="select-end-date" value="<?= date("Y-m-d") ?>" class="form-control" />
					</div>
					<div class="col-3">
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
	<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
			<div class="card-header">
				<div class="d-flex justify-content-between">
					<div><b data-role="content-result-count" > 0 </b> <?= strtolower(lang("Result")) ?></div>
					<?php if (false): ?>
						<div><b data-role="content-result-time" > 0 </b> <?= strtolower(lang("Sec")) ?></div>
					<?php endif; ?>
				</div>
			</div>
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped" style="width:100%;">
            <thead>
              <tr>
								<th style="width:3%" class="border-bottom-0">#</th>
								<th style="width:30%" class="border-bottom-0"><?= lang("Description") ?></th>
								<th style="width:25%" class="border-bottom-0"><?= lang("Brand") ?></th>
								<th style="width:15%" class="border-bottom-0"><?= lang("Product count") ?></th>
								<th style="width:15%" class="border-bottom-0"><?= lang("Brand price rate") ?></th>
								<th style="width:10%" class="border-bottom-0"><?= lang("System user") ?></th>
								<th style="width:15%" class="border-bottom-0"><?= lang("Date") ?></th>
								<th style="width:1%" class="border-bottom-0"></th>
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

<?php $this->load->view("components/add_modal") ?>
