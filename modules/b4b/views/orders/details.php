
<?php if ($details) : ?>
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <?php if ($details["invoice"]["code"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p><?= lang("Order code") ?></p>
                  <h5><?= $details["invoice"]["code"] ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($details["invoice"]["depo_name"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p><?= lang("Warehouse") ?></p>
                  <h5><?= $details["invoice"]["depo_name"] ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($details["invoice"]["status"]) : ?>
              <?php $sts = $details["invoice"]["status"]; ?>
              <div class="col-4 invoice-title mt-2">
                  <p><?= lang("Status") ?></p>
                  <h5>
                    <span class="badge badge-<?= $sts === STATUS_PENDING ? "info" :
                                                ($sts === STATUS_CONFIRMED ? "confirmed" :
                                                  ($sts === STATUS_SHIPPED ? "success" :
                                                    ($sts === STATUS_CANCELED ? "danger" :
                                                      ($sts === STATUS_ON_THE_WAY ? "byorder" :
                                                        ($sts === STATUS_PARTIALLY_SHIPPED ? "warning" : "primary"))))) ?>">
                      <?= lang($details["invoice"]["status"]) ?>
                    </span>
                  </h5>
              </div>
            <?php endif; ?>
            <?php if ($details["invoice"]["product_count"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p><?= lang("Product count") ?></p>
                  <h5><?= number_format($details["invoice"]["product_count"] ?: 0, 0,",",".") ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($details["invoice"]["amount"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p><?= lang("Amount") ?></p>
                  <h5><?= number_format($details["invoice"]["amount"] ?: 0, 2,",",".") ?> <?= $details["invoice"]["currency"] ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($details["invoice"]["operation_date"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p><?= lang("Date") ?></p>
                  <h5><?= date("d-m-Y H:i:s", strtotime($details["invoice"]["operation_date"])) ?></h5>
              </div>
            <?php endif; ?>
            <?php if (trim($details["invoice"]["comment"])) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p><?= lang("Comment") ?></p>
                  <em><?= $details["invoice"]["comment"] ?></em>
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

                    	<div class="table-responsive-md">
  											<table data-role="main-table" class="table mb-0 table-bordered">
  												<thead>
  													<tr>
  													<th scope="col" style="width:1%;">#</th>
  													<th scope="col" style="width:10%;" ><?= lang("Brand code") ?></th>
  													<th scope="col" style="width:10%;" ><?= lang("Original code") ?></th>
  													<th scope="col" style="width:10%;" ><?= lang("Brand") ?></th>
  													<th scope="col" style="width:20%;" ><?= lang("Product name") ?></th>
  									         <?php if (false): ?>
				                           <th scope="col" style="width:5%;" ><?= lang("Description") ?></th>
                             <?php endif; ?>
                            <th scope="col" style="width:5%;" ><?= lang("Day") ?></th>
                            <th scope="col" style="width:5%;" ><?= lang("Quantitiy") ?></th>
                            <th scope="col" style="width:10%;" ><?= lang("Price") ?></th>
  													<th scope="col" style="width:10%;" ><?= lang("Amount") ?></th>
  													</tr>
  												</thead>
  												<tbody data-role="table-list">
                            <?php foreach ($details["invoice"]["list"] as $key => $item): ?>
                              <tr>
                                <td><?= ++$key ?></td>
                                <td><?= $item["brand"]["code"] ?></td>
                                <td><?= $item["brand"]["org_code"] ?></td>
                                <td><?= $item["brand"]["name"] ?></td>
                                <td><?= $item["name"] ?></td>
                                <td><?= $item["delivery_time"] ?></td>
                                <td><?= number_format($item["quantity"] ?: 0, 0,",",".") ?></td>
                                <td><?= $item["price"] ? number_format($item["price"], 2,",",".") : null ?> <?= $item["price"] ? $item["currency"] : null ?></td>
                                <td><?= number_format($item["total_price"] ?: 0, 2,",",".") ?> <?= $item["currency"] ?></td>
                              </tr>
                            <?php endforeach; ?>
  												</tbody>
  											</table>
  												<?= $this->load->view("layouts/components/loaders/table_loader") ?>
  											</div>
              </div>
          </div>
      </div>
  </section>

<?php else : ?>
  <?= lang("No invoice infoirmation found") ?>
<?php endif; ?>
