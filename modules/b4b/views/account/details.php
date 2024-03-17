<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
              <div class="box-header d-flex justify-content-between align-items-start">
                <h4><?= lang("Invoice") ?>: <?= $code ?></h4>

                <div class="d-flex">
                  <a class="link me-2" href="javascript:void(0)" data-role="excel-export"><?= lang("Excel export") ?></a>
                  <a class="link" target="_blank" rel="nofollow noreferrer" href="<?= path_local("orders/returns") ?>"><?= lang("Link to returns") ?></a>
                </div>
                <!-- <div class="d-flex justify-content-between">
                  <div><b data-role="content-result-count"> 0 </b> <?= strtolower(lang("Result")) ?></div>
                  <div><b data-role="content-result-time"> 0 </b> <?= strtolower(lang("Sec")) ?></div>
                </div> -->
              </div>
              <div class="box-body">
                  <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="account_details_table">
                      <tbody>
                        <tr>
                          <th>#</th>
                          <th><?= lang("Brand code") ?></th>
                          <th><?= lang("Original code") ?></th>
                          <th><?= lang("Brand") ?></th>
                          <th><?= lang("Product name") ?></th>
                          <th><?= lang("Description") ?></th>
                            <?php if (Auth::currentAccountCurrency() !== "AZN"): ?>
                              <th><?= lang("Currency rate") ?></th>
                            <?php endif; ?>
                          <th><?= lang("Order count") ?></th>
                          <th><?= lang("Price") ?></th>
                          <th><?= lang("Amount") ?></th>
                          <th></th>
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
                              <?php if (Auth::currentAccountCurrency() !== "AZN"): ?>
                                  <td class="text-end"><?= $item["currency_rate"] ? number_format($item["currency_rate"],2,",",".") : "" ?></td>
                              <?php endif; ?>
                              <td class="text-end"><?= (int)$item["quantity"] ?></td>
                              <td class="text-end"><?= $item["amount"] ? number_format($item["amount"],1,",",".") : "" ?></td>
                              <td class="text-end"><?= $item["total_amount"] ? number_format($item["total_amount"],1,",",".") : "" ?></td>
                              <td class="text-end"><a data-invoice="<?= $code; ?>"
                    									data-code="<?= $item['brand_code'] ?>"
                    									data-specode="<?= $item["brand"] ?>"
                    									data-specode2="<?= $item["description"] ?>"
                    									data-specode3="<?= $item["OEM"] ?>"
                    									data-quantity="<?= $item['quantity'] ?>"
                    									data-price="<?= $item['amount'] ?>"
                    									data-name="<?= $item["product_name"] ?>"
                                 href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#returnModal" data-role="return" type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="left" title="<?= lang("Refund") ?>"><i class="fas fa-arrow-rotate-left"></i></a></td>
                            </tr>
                          <?php endforeach; ?>
                          <tr>
                            <td colspan="<?= Auth::currentAccountCurrency() !== "AZN" ? "9" : "8" ?>" class="text-bold text-end"><?= lang("Total price") ?></td>
                            <td class="text-end text-bold"><?= $sum ? number_format($sum,1,",",".") : "" ?></td>
                            <td></td>
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

<?php $this->view("account/return_modal") ?>
<!-- /.content -->
