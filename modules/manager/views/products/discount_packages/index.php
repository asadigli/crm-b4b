<div class="breadcrumb-header justify-content-between mb-2">
	<div class="my-auto">
		<div class="d-flex justify-content-between align-items-center">
			<h4 class="content-title mb-0 my-auto"><?= lang("Discount packages") ?></h4>
		</div>
	</div>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-3 col-12">
              <div class="form-group mb-md-0 mb-3 d-flex">
                <input data-role="search-keyword" name="keyword" placeholder="<?= lang("Search") ?>" type="text" class="form-control" placeholder="<?= lang("Search") ?>" >
              </div>
          </div>
          <div class="col-md-9 col-12">
            <div class="d-flex justify-content-end">
                <button type="button" data-role="search-filter" class="btn btn-primary" >
                  <i class="mdi mdi-magnify mr-2"></i>
                  <?= lang("Search") ?>
                </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  <div class="row">
    <div class="col-12">
      <div class="box">
        <div class="box-header">
          <div class="d-flex justify-content-between">
            <div><b data-role="content-result-count"> 0 </b> <?= strtolower(lang("Result")) ?></div>
            <div><b data-role="content-result-time"> 0 </b> <?= strtolower(lang("Sec")) ?></div>
          </div>
        </div>
        <div class="table-responsive-md">
          <table data-role="main-table" class="table table-bordered table-hover table-striped">
            <thead>
              <tr data-role="filter-col-header" >
                <th scope="col" style="width:1%;">#</th>
                <th scope="col" style="width:7%;" >
                  <?= lang("Code") ?>
                </th>
                <th scope="col" style="width:30%;" >
                  <?= lang("Name") ?>
                </th>
								<th scope="col" style="width:10%;" >
									<?= lang("Discount rate") ?>
								</th>
                <th scope="col" style="width:10%;" >
                  <?= lang("Total last purchase price") ?>
                </th>
								<th scope="col" style="width:10%;" >
									<?= lang("Product count") ?>
								</th>
                <th scope="col" style="width:8%;" >
                  <?= lang("Date") ?>
                </th>
                <th scope="col" style="width:1%;"> </th>
              </tr>
            </thead>
            <tbody data-role="table-list">

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
