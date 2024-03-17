<?php if ($customer): ?>
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div data-role="customer-info" data-id="<?= $customer["id"] ?>"  class="card-body">
          <div class="row">
            <?php if ($customer["name"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Name") ?></b></p>
                  <h5><?= $customer["name"] ?: "" ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($customer["description"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Description") ?></b></p>
                  <h5><?= $customer["description"] ?: "" ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($customer["code"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Code") ?></b></p>
                  <h5><?= $customer["code"] ?: "" ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($customer["currency"]) : ?>
              <div data-role="account-currency-name" data-val="<?= $customer["currency"] ?>"  class="col-4 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Currency") ?></b></p>
                  <h5><?= $customer["currency"] ?: "" ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($customer["last_payment_date"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("AVA ID") ?></b></p>
                  <h5><?= $customer["remote_id"] ?: "" ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($customer["monthly_sale_amount"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Monthly sale amount") ?></b></p>
                  <h5><?= $customer["monthly_sale_amount"] ? number_format($customer["monthly_sale_amount"],2,",",".") : "" ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($customer["monthly_payment_amount"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Monthly payment amount") ?></b></p>
                  <h5><?= $customer["monthly_payment_amount"] ? number_format($customer["monthly_payment_amount"],2,",",".") : "" ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($customer["city_name"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("City") ?></b></p>
                  <h5><?= $customer["city_name"] ?: "" ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($customer["last_sale_date"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Last sale date") ?></b></p>
                  <h5><?= $customer["last_sale_date"] ? date("d-m-Y", strtotime($customer["last_sale_date"])) : "" ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($customer["last_payment_date"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Last payment date") ?></b></p>
                  <h5><?= $customer["last_payment_date"] ? date("d-m-Y", strtotime($customer["last_payment_date"])) : "" ?></h5>
              </div>
            <?php endif; ?>

            <div class="row">
              <?php if ($customer["sale_amount"]) : ?>
                <div class="col-4 invoice-title mt-2">
                    <p class="m-0" ><b><?= lang("Whole sale") ?></b></p>
                    <h5><?= $customer["sale_amount"] ? number_format($customer["sale_amount"],2,",",".") : "" ?></h5>
                </div>
              <?php endif; ?>
              <?php if ($customer["payment_amount"]) : ?>
                <div class="col-4 invoice-title mt-2">
                    <p class="m-0" ><b><?= lang("Whole payment") ?></b></p>
                    <h5><?= $customer["payment_amount"] ? number_format($customer["payment_amount"],2,",",".") : "" ?></h5>
                </div>
              <?php endif; ?>

              <?php if ($customer["left_amount"]) : ?>
                <div class="col-4 invoice-title mt-2">
                    <p class="m-0" ><b><?= lang("Left amount") ?></b></p>
                    <h5 class="text-danger" ><?= $customer["left_amount"] ? number_format($customer["left_amount"],2,",",".") : "" ?></h5>
                </div>
              <?php endif; ?>

            </div>

            <div class="row">
              <?php if ($customer["sale_amount"]) : ?>
                <div class="col-4 invoice-title mt-2">
                    <p class="m-0" ><b><?= lang("Sale") ?></b></p>
                    <h5 data-role="filter-entry-amount" ></h5>
                </div>
              <?php endif; ?>
              <?php if ($customer["payment_amount"]) : ?>
                <div class="col-4 invoice-title mt-2">
                    <p class="m-0" ><b><?= lang("Payment") ?></b></p>
                    <h5 data-role="filter-exit-amount" ></h5>
                </div>
              <?php endif; ?>
              <?php if ($customer["payment_amount"]) : ?>
                <div class="col-4 invoice-title mt-2">
                    <p class="m-0" ><b><?= lang("Balance") ?></b></p>
                    <h5 data-role="filter-balance-amount" ></h5>
                </div>
              <?php endif; ?>
            </div>
            <div class="row">

              <div class="col-3 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Max order allowed limit") ?></b></p>
                  <div class="input-group">
                    <input data-role="max-allowed-order-limit" class="form-control ui-autocomplete-input" type="text" value="<?= $customer["max_allowed_order_limit"] ? (float)$customer["max_allowed_order_limit"] : "" ?>" name="max_allowed_order_limit"   autocomplete="off">
                    <span class="input-group-text" id="basic-addon2"><?= CURRENCY_EUR ?></span>
                  </div>
              </div>

              <div class="col-3 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Max order limit") ?></b></p>
                  <div class="input-group ">
                    <input data-role="max-order-limit" class="form-control ui-autocomplete-input" type="text" value="<?= $customer["max_order_limit"] ? (float)$customer["max_order_limit"] : "" ?>" name="max_order_limit" autocomplete="off">
                    <span class="input-group-text" id="basic-addon2"><?= CURRENCY_EUR ?></span>
                  </div>
              </div>

              <div class="col-3 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Has order limit") ?></b></p>
                  <div class="custom-control custom-checkbox">
                      <input type="checkbox" data-role="has-order-limit" data-text="has_order_limit" name="has_order_limit" id="has_order_limit" <?= $customer["has_order_limit"] ? "checked" : "" ?>>
                      <label class="mt-1" for="has_order_limit"></label>
                  </div>
              </div>

            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

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

  <section class="content">
      <div class="row">
          <div class="col-12">
              <div class="box">
                <div class="box-header">
                  <div class="d-flex justify-content-between">
                    <div class="d-flex">
                      <div class="me-3"><b data-role="content-result-count"> 0 </b> <?= strtolower(lang("Result")) ?></div>
                      <div class="me-3"><?= lang("Total sale") ?>: <b data-role="total-entry"> 0 </b></div>
                      <div class="me-3"><?= lang("Total payment") ?>: <b data-role="total-exit"> 0 </b></div>
                      <div class="me-3"><?= lang("Balance") ?>: <b data-role="total-balance"> 0 </b></div>
                      <div class="me-3"><?= lang("Total left") ?>: <b data-role="total-left"> 0 </b></div>
                      <div class="me-4"><?= lang("Whole total left") ?>: <b data-role="whole-total-left"> <?= number_format($customer["left_amount"], 2,",",".") ?> </b></div>
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
                            <th><?= lang("Description") ?></th>
                            <th><?= lang("Payment type") ?></th>
                            <th><?= lang("Warehouse") ?></th>
                            <?php if (isset($customer["currency"]) && $customer["currency"] !== "AZN"): ?>
                              <th width="25px" ><?= lang("Currency rate") ?></th>
                            <?php endif; ?>
                            <th><?= lang("Sale") ?></th>
                            <th><?= lang("Payment") ?></th>
                            <th><?= lang("Balance") ?></th>
                            <th><?= lang("Left amount") ?></th>
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
      <!-- /.row -->
  </section>

  <div class="load-more d-none" data-role="load-more-container"  id="load_more_div">
    <a data-role="load-more" href="javascript:void(0)"><?= lang("Load more") ?></a>
  </div>
<?php else: ?>
  <?= lang("Customer not found") ?>
<?php endif; ?>
