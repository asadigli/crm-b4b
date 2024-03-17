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
              <div data-role="account-currency-name" data-name="<?= $customer["currency"] ?>" class="col-4 invoice-title mt-2">
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
                  <h5><?= $customer["monthly_sale_amount"] ? number_format($customer["monthly_sale_amount"],2,",",".") . ($customer["currency"] ? " " . $customer["currency"] : "") : "" ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($customer["monthly_payment_amount"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Monthly payment amount") ?></b></p>
                  <h5><?= $customer["monthly_payment_amount"] ? number_format($customer["monthly_payment_amount"],2,",",".") . ($customer["currency"] ? " " . $customer["currency"] : "") : "" ?></h5>
              </div>
            <?php endif; ?>
            <?php if (isset($customer["warehouse"]) && $customer["warehouse"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Warehouse") ?></b></p>
                  <h5><?= $customer["warehouse"] ?: "" ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($customer["last_sale_date"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Last sale date") ?></b></p>
                  <h5><?= $customer["last_sale_date"] ?: "" ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($customer["last_payment_date"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Last payment date") ?></b></p>
                  <h5><?= $customer["last_payment_date"] ?: "" ?></h5>
              </div>
            <?php endif; ?>

            <?php if ($customer["sale_amount"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Sale") ?></b></p>
                  <h5><?= $customer["sale_amount"] ? number_format($customer["sale_amount"],2,",",".") . ($customer["currency"] ? " " . $customer["currency"] : "") : "" ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($customer["payment_amount"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Payment") ?></b></p>
                  <h5><?= $customer["payment_amount"] ? number_format($customer["payment_amount"],2,",",".") . ($customer["currency"] ? " " . $customer["currency"] : "") : "" ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($customer["left_amount"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p class="m-0" ><b><?= lang("Left amount") ?></b></p>
                  <h5><?= $customer["left_amount"] ? number_format($customer["left_amount"],2,",",".") . ($customer["currency"] ? " " . $customer["currency"] : "") : "" ?></h5>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
      <div class="row">
          <div class="col-12">
              <div class="box">
                <div class="box-header d-flex justify-content-between align-items-start">
                  <h4><?= lang("Invoice") ?>: <?= $code ?></h4>

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
                            <th><?= lang("Product name") ?></th>
                            <th><?= lang("Description") ?></th>

                            <?php if (isset($customer["currency"]) && $customer["currency"] !== "AZN"): ?>
                                            <th><?= lang("Currency rate") ?></th>
                            <?php endif; ?>
                            <th><?= lang("Order count") ?></th>
                            <th><?= lang("Price") ?></th>
                            <th><?= lang("Amount") ?></th>
                          </tr>
                        </tbody>
                        <tbody data-role="table-list">
                          <?php
                          $sum = 0;
                          if ($list):
                            foreach ($list as $key => $item):
                              $sum += $item["total_amount"];
                              ?>
                              <tr data-id="<?= $item["id"] ?>">
                                <td><?= $key+1 ?></td>
                                <td><?= $item["brand_code"] ?></td>
                                <td><?= $item["OEM"] ?></td>
                                <td><?= $item["brand"] ?></td>
                                <td><?= $item["product_name"] ?></td>
                                <td><?= $item["description"] ?></td>
                                <?php if (isset($customer["currency"]) && $customer["currency"] !== "AZN"): ?>
                                                <td class="text-end"><?= $item["currency_rate"] ? number_format($item["currency_rate"],2,",",".") : "" ?></td>
                                <?php endif; ?>

                                <td class="text-end"><?= (int)$item["quantity"] ?></td>
                                <td class="text-end"><?= $item["amount"] ? number_format($item["amount"],2,",",".") : "" ?></td>
                                <td class="text-end"><?= $item["total_amount"] ? number_format($item["total_amount"],2,",",".") : "" ?></td>
                              </tr>
                            <?php endforeach; ?>
                            <tr>
                              <td colspan="<?= isset($customer["currency"]) && $customer["currency"] !== "AZN" ? "9" : "8" ?>" class="text-bold text-end"><?= lang("Total price") ?></td>
                              <td class="text-end text-bold"><?= $sum ? number_format($sum,2,",",".") : "" ?></td>
                            </tr>
                          <?php else: ?>
                            <tr>
                              <td style="padding:0;margin:0;" colspan="200">
                                <div class="d-flex justify-content-center" >
                                  <div class="my-3">
                                      <strong><?= lang("No result") ?></strong>
                                  </div>
                                </div>
                              </td>
                            </tr>
                          <?php endif; ?>
                        </tbody>
                      </table>
                    </div>
                </div>
              </div>
          </div>
      </div>
      <!-- /.row -->
  </section>

<?php else: ?>
  <?= lang("Customer not found") ?>
<?php endif; ?>
