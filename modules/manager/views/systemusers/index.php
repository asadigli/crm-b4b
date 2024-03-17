<div class="row">
	<div class="col-lg-12">
	  <div class="card">
	    <div class="card-body">
	      <div class="row">
	        <div class="col-md-6 col-12">
				<div class="form-group mb-md-0 mb-3">
					<input data-role="search-filter" type="text" class="form-control" placeholder="<?= lang("Search") ?>" value="<?= $this->input->get("keyword") ?: "" ?>">
				</div>
	        </div>
			<div class="col-md-6 col-12 d-flex justify-content-end">
				<div data-toggle="tooltip" data-placement="left" title="<?= lang("Add") ?>">
					<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#addUser" data-role="open-add-modal">
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
				<?php if (false): ?>
					<div><b data-role="content-result-time" > 0 </b> <?= strtolower(lang("Sec")) ?></div>
				<?php endif; ?>
			</div>
		</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped" style="width:100%;">
            <thead>
              <tr>
                <th style="width:1%;" >#</th>
				<th><?= lang("Photo") ?></th>
				<th><?= lang("Order group") ?> </th>
				<th><?= lang("Name") ?> </th>
				<th><?= lang("Surname") ?></th>
				<th><?= lang("Email") ?></th>
				<th><?= lang("Phone") ?></th>
                <th><?= lang("Dashboard") ?></th>
                <?php if (false): ?>
                	<th><?= lang("Group") ?></th>
                <?php endif; ?>
                <th><?= lang("Role") ?></th>
                <th><?= lang("Blocked") ?></th>
				<th><?= lang("Operations") ?></th>
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
</div>
<?php $this->view("components/add_modal") ?>
<?php $this->view("components/add_group") ?>
