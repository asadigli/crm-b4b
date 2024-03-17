<div class="row">
	<div class="col-lg-12">
	  <div class="card">
	    <div class="card-body">
	      <div class="row align-items-center">
	        <div class="col-md-6 col-9 pe-0">
	            <div class="form-group mb-0">
	              <input data-role="search-filter" type="text" class="form-control" placeholder="<?= lang("Search") ?>" value="<?= $this->input->get("keyword") ?: "" ?>">
	            </div>
	        </div>
			<div class="col-md-6 col-3 d-flex justify-content-end">
				<div data-toggle="tooltip" data-placement="left" data-original-title="<?= lang("Add") ?>">
					<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addConfig" data-role="open-add-modal">
						<i class="fa-solid fa-plus"></i>
					</button>
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
						<div class="ms-2"><b data-role="content-result-time" > 0 </b> <?= strtolower(lang("Sec")) ?></div>
				</div>
			</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped" style="width:100%;">
            <thead>
              <tr>
                <th style="width:1%;" >#</th>
								<th><?= lang("Resource") ?> </th>
								<th><?= lang("Type") ?></th>
								<th><?= lang("Group") ?></th>
								<th><?= lang("Key") ?></th>
                <th><?= lang("Value") ?></th>
                <th><?= lang("Is active") ?></th>
								<th><?= lang("Operations") ?></th>
              </tr>
            </thead>
            <tbody data-role="table-list">

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->view("components/add_modal") ?>
