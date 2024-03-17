<div class="card">
  <div class="card-body">
    <div class="row">
        <div class="col-md-2 col-6 mb-3 mb-md-0">
          <label><?= lang("Date from") ?></label>
          <input class="form-control ui-autocomplete-input" type="date" value="<?= $this->input->get("start_date") ?: date("Y-m-d") ?>" name="start_date" autocomplete="off">
        </div>
        <div class="col-md-2 col-6 mb-3 mb-md-0">
          <label><?= lang("Date to") ?></label>
          <input class="form-control ui-autocomplete-input" type="date" value="<?= $this->input->get("end_date") ?: date("Y-m-d") ?>" name="end_date" autocomplete="off">
        </div>
        <div class="col-md-2 mb-3 mb-md-0">
          <label><?= lang("Brand code") ?></label>
          <input class="form-control ui-autocomplete-input" type="text" value="<?= $this->input->get("brand_code") ?>" name="brand_code" autocomplete="off">
        </div>
        <div class="col-md-2 mb-3 mb-md-0">
          <label><?= lang("Brand") ?></label>
          <select class="form-select custom-select" name="brand" data-value="<?= $this->input->get("brand") ?>">
          </select>
        </div>
        <div class="col-md-2 mb-3 mb-md-0">
          <label><?= lang("Customer") ?></label>
          <select class="form-select custom-select" name="customer" data-value="<?= $this->input->get("customer") ?>">
          </select>
        </div>
        <div class="col-md-2 d-flex justify-content-end align-items-end">
          <button class="btn btn-primary border-0" data-role="search"><?= lang("Search") ?></button>
        </div>
    </div>
  </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
              <div class="box-header p-2">
                <div class="d-flex justify-content-between">
                  <div class="d-flex">
                    <div class="me-3"><b data-role="content-result-count"> 0 </b> <?= strtolower(lang("Result")) ?></div>
                    <div class="me-3"><?= lang("Total sale amount") ?> : <b data-role="content-total-sale-amount"> 0 </b></div>
                  </div>
                  <div><b data-role="content-result-time"> 0 </b> <?= strtolower(lang("Sec")) ?></div>
                </div>
              </div>
              <div class="">
                  <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                      <tbody>
                        <tr>
                          <th>#</th>
                          <th><?= lang("Brand code") ?></th>
                          <th><?= lang("Original code") ?></th>
                          <th><?= lang("Brand") ?></th>
                          <th><?= lang("Customer") ?></th>
                          <th><?= lang("Description") ?></th>
                          <th><?= lang("Quantity") ?></th>
                          <th><?= lang("Invoice") ?></th>
                          <th><?= lang("Sale price") ?></th>
                          <th><?= lang("Sale amount") ?></th>
                          <th><?= lang("Purchase price") ?></th>
                          <th><?= lang("Date") ?></th>
                        </tr>
                      </tbody>
                      <tbody data-role="table-list">
                      </tbody>
                    </table>
                  </div>
              </div>
            </div>
            <!-- Load more link is here -->
            <div class="load-more d-none" id="load_more_div">
              <a data-role="load-more" href="javascript:void(0)"><?= lang("Load more") ?></a>
            </div>
            <!-- Load more link is here -->
        </div>
    </div>
    <!-- /.row -->
</section>

<!-- /.content -->
