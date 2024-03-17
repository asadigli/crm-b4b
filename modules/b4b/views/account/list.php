<div class="card filter">
  <div class="card-body">
    <div class="row">
        <div class="col-md-2">
          <label><?= lang("Date from") ?></label>
          <input class="form-control ui-autocomplete-input" type="date" value="<?= $this->input->get("start_date") ?: date("Y-m-d", strtotime(date("Y-m-d"). ' -1 months')) ?>" name="start_date" autocomplete="off">
        </div>
        <div class="col-md-2">
          <label><?= lang("Date to") ?></label>
          <input class="form-control ui-autocomplete-input" type="date" value="<?= $this->input->get("end_date") ?: date("Y-m-d") ?>" name="end_date" autocomplete="off">
        </div>
        <div class="col-md-2">
          <label><?= lang("Brand code") ?></label>
          <input class="form-control ui-autocomplete-input" type="text" value="<?= $this->input->get("brand_code") ?>" name="brand_code" autocomplete="off">
        </div>
        <div class="col-md-2">
          <label><?= lang("Brand") ?></label>
          <select class="form-select custom-select" name="brand" data-value="<?= $this->input->get("brand") ?>">
          </select>
        </div>
        <div class="col-md-2">
          <label><?= lang("Original code") ?></label>
          <input class="form-control ui-autocomplete-input" type="text" value="<?= $this->input->get("oem_code") ?>" name="oem_code" autocomplete="off">
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
              <div class="box-header">
                <div class="d-flex justify-content-between">
                  <div class="d-flex">
                    <div class="me-3"><b data-role="content-result-count"> 0 </b> <?= strtolower(lang("Result")) ?></div>
                    <div class="me-3"><b data-role="content-result-time"> 0 </b> <?= strtolower(lang("Sec")) ?></div>
                    <div class="me-3"><?= lang("Whole left") ?>: <b data-role="total-left"> 0 </b></div>
                    <div class="me-3"><?= lang("Whole sale") ?>: <b data-role="total-entry"> 0 </b></div>
                    <div class="me-3"><?= lang("Whole payment") ?>: <b data-role="total-exit"> 0 </b></div>
                    <div class="text-danger me-3"><?= lang("Left debt") ?>: <b data-role="total-left-amount"> 0 </b></div>
                  </div>
                  <div class="">
                    <a data-role="excel-export" class="link" href="javascript:void(0)"><?= lang("Excel export") ?></a>
                  </div>
                </div>
              </div>
              <div class="box-body">
                  <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                      <tbody>
                        <tr>
                          <th>#</th>
                          <th><?= lang("Date") ?></th>
                          <th><?= lang("Invoice") ?></th>
                          <th><?= lang("Description") ?></th>
                          <th><?= lang("Payment type") ?></th>
                          <th><?= lang("Warehouse") ?></th>
                          <?php if (Auth::currentAccountCurrency() !== "AZN"): ?>
                            <th width="25px" ><?= lang("Currency rate") ?></th>
                          <?php endif; ?>
                          <th><?= lang("Sale") ?></th>
                          <th><?= lang("Payment") ?></th>
                          <th><?= lang("Left") ?></th>
                        </tr>
                      </tbody>
                      <tbody data-role="table-list">
                      </tbody>
                    </table>
                  </div>
              </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
</section>
<div class="load-more d-none" id="load_more_div">
  <a data-role="load-more" href="javascript:void(0)"><?= lang("Load more") ?></a>
</div>
