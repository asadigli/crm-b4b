
<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-md-2 col-12">
        <div class="form-group mb-md-0 mb-3">
          <input data-role="search-filter" type="text" class="form-control" placeholder="<?= lang("Search") ?>" value="<?= $this->input->get("keyword")  ?: ""?>">
        </div>
      </div>
      <div class="col-md-2 col-12">
        <div class="form-group mb-md-0 mb-3">
          <select class="custom-select" data-role="sort_by">
            <option value=""><?= lang("Sort by") ?></option>
            <option value="by_is_online"><?= lang("By online entries") ?></option>
            <option value="by_latest" selected><?= lang("Latest added") ?></option>
          </select>
        </div>
      </div>
      <div class="col-md-2 col-12">
        <div class="form-group mb-md-0 mb-3">
          <select class="custom-select" data-role="search_by_blocks">
            <option value="" selected><?= lang("All") ?></option>
            <option value="by_is_blocked"><?= lang("By blocked") ?></option>
            <option value="by_is_not_blocked" ><?= lang("By not blocked") ?></option>
          </select>
        </div>
      </div>
      <div class="col-md-6 d-flex justify-content-end mt-3 mt-md-0">
        <div data-toggle="tooltip" class="me-2" data-placement="left" title="<?= lang("Add") ?>">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEntry" data-role="open-add-modal">
            <i class="fa-solid fa-plus"></i>
          </button>
        </div>
        <div data-toggle="tooltip" data-placement="left" title="<?= lang("Search") ?>">
          <button type="button" class="btn btn-primary" data-role="search-btn">
            <i class="fa-solid fa-search"></i>
          </button>
        </div>
      </div>
      <?php if (false): ?>
        <button class="btn btn-primary ml-3"><?= lang("Search") ?></button>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="row">
	<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
			<div class="card-header">
				<div class="d-flex justify-content-between">
					<div class="me-2"><b data-role="content-result-count" > 0 </b> <?= strtolower(lang("Result")) ?></div>
					<div><b data-role="content-result-time" > 0 </b> <?= strtolower(lang("Sec")) ?></div>
				</div>
			</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped" style="width:100%;">
            <thead>
              <tr>
                <th>#</th>
                <th><?= lang("Avatar") ?></th>
								<th style="min-width:200px !important;"><?= lang("Customer") ?></th>
                <th><?= lang("Code") ?></th>
								<th style="min-width:200px !important;"><?= lang("Name") ?>
                  <br> <?= lang("Email") ?>
                  <br> <?= lang("Company phone") ?></th>
                <th><?= lang("Company curator") ?></th>
                <th><?= lang("Responsible person") ?></th>
                <th><?= lang("City") ?></th>
                <th><?= lang("Company depo") ?></th>
                <th><?= lang("Entry limit") ?></th>
                <th><?= lang("Stock show") ?></th>
                <th><?= lang("Product show") ?></th>
                <th><?= lang("Block") ?></th>
                <th><?= lang("Online") ?></th>
								<th><em data-toggle="tooltip" data-placement="top" title="<?= lang("Added date") ?>" class="fa-solid fa-clock"></em></th>
								<th><?= lang("Operations") ?></th>
              </tr>
            </thead>
            <tbody data-role="entries-list" data-base-url="<?= $this->config->item("b4b_url") ?>">

            </tbody>
          </table>
          <div class="load-more d-none" data-role="load-more-container"  id="load_more_div">
            <a data-role="load-more" href="javascript:void(0)"><?= lang("Load more") ?></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->view("components/add_modal") ?>
<?php $this->view("components/edit_modal") ?>
<?php $this->view("components/add_customer") ?>
