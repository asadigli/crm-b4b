
  <div class="card filter">
    <div class="card-body">
      <div class="row">
          <div class="col-md-2">
            <label><?= lang("Date from") ?></label>
            <input class="form-control ui-autocomplete-input" max="<?= date("Y-m-d") ?>" type="date" value="<?= $this->input->get("start_date") ?: date("Y-m") ."-01" ?>" name="start_date" autocomplete="off">
          </div>
          <div class="col-md-2">
            <label><?= lang("Date to") ?></label>
            <input class="form-control ui-autocomplete-input" max="<?= date("Y-m-d") ?>" type="date" value="<?= $this->input->get("end_date") ?: date("Y-m-d") ?>" name="end_date" autocomplete="off">
          </div>
          <div class="col-md-2">
            <label><?= lang("Brand code") ?></label>
            <input class="form-control ui-autocomplete-input" placeholder="<?= lang("Brand code") ?>..." type="text" value="<?= $this->input->get("brand_code") ?>" name="brand_code" autocomplete="off">
          </div>
          <div class="col-md-2">
            <label><?= lang("Brand") ?></label>
            <select class="form-select custom-select" name="brand" data-value="<?= $this->input->get("brand") ?>">
            </select>
          </div>
          <div class="col-md-2">
            <label><?= lang("Original code") ?></label>
            <input class="form-control ui-autocomplete-input" placeholder="<?= lang("Original code") ?>..." type="text" value="<?= $this->input->get("oem_code") ?>" name="oem_code" autocomplete="off">
          </div>
          <div class="col-md-2 d-flex justify-content-end align-items-end">
            <button class="btn btn-primary border-0" data-role="search"><?= lang("Search") ?></button>
          </div>
      </div>
    </div>
  </div>

  <section class="content">
      <div class="row">
          <div class="col-12">
              <div class="box">
                <div class="box-header">
                  <div class="d-flex justify-content-between">
                    <div class="d-flex">
                      <div class="me-3"><b data-role="content-result-count"> 0 </b> <?= strtolower(lang("Result")) ?></div>
                      <div class="me-3"><?= lang("Total purchase") ?>: <b data-role="total-exit"> 0 </b></div>
                    </div>
                    <div><b data-role="content-result-time"> 0 </b> <?= strtolower(lang("Sec")) ?></div>
                  </div>
                </div>
                <!-- <div class="box-body"> -->
                    <div class="table-responsive">
                      <table class="table table-bordered mb-0">
                        <tbody>
                          <tr>
                            <th>#</th>
                            <th><?= lang("Date") ?></th>
                            <th><?= lang("Invoice") ?></th>
                            <th><?= lang("Company") ?></th>
                            <th><?= lang("Customer code") ?></th>
                            <th><?= lang("Description") ?></th>
                            <th><?= lang("Warehouse") ?></th>
                            <th width="25px" ><?= lang("Currency rate") ?></th>
                            <th><?= lang("Amount") ?></th>
                          </tr>
                        </tbody>
                        <tbody data-role="table-list">
                        </tbody>
                      </table>
                    </div>
                <!-- </div> -->
              </div>
          </div>
      </div>
  </section>

  <div class="load-more d-none" data-role="load-more-container"  id="load_more_div">
    <a data-role="load-more" href="javascript:void(0)"><?= lang("Load more") ?></a>
  </div>
