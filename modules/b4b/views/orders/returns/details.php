
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
                  <h5><?= $details["invoice"]["amount"] ?> <?= $details["invoice"]["currency"] ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($details["invoice"]["operation_date"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p><?= lang("Date") ?></p>
                  <h5><?= $details["invoice"]["operation_date"] ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($details["invoice"]["comment"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p><?= lang("Comment") ?></p>
                  <h5><?= $details["invoice"]["comment"] ?></h5>
              </div>
            <?php endif; ?>
            <?php if ($details["invoice"]["reverse_invoice"]) : ?>
              <div class="col-4 invoice-title mt-2">
                  <p><?= lang("Invoice") ?></p>
                  <h5><?= $details["invoice"]["reverse_invoice"] ?></h5>
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
  													<th scope="col" style="width:10%;" ><?= lang("Product name") ?></th>
  													<th scope="col" style="width:10%;" ><?= lang("Description") ?></th>
                            <th scope="col" style="width:10%;" ><?= lang("Quantitiy") ?></th>
                            <th scope="col" style="width:10%;" ><?= lang("Price") ?></th>
  													<th scope="col" style="width:10%;" ><?= lang("Amount") ?></th>
  													</tr>
  												</thead>
  												<tbody data-role="table-list">
                            <?php foreach ($details["invoice"]["list"] as $key => $item): ?>
                              <tr>
                                <td><?= ++$key ?></td>
                                <td><?= $item["reverse_code"] ?></td>
                                <td><?= $item["reverse_specode3"] ?></td>
                                <td><?= $item["reverse_specode"] ?></td>
                                <td><?= $item["name"] ?></td>
                                <td><?= $item["description"] ?></td>
                                <td><?= number_format($item["quantity"] ?: 0, 0,",",".") ?></td>
                                <td><?= number_format($item["price"] ?: 0, 2,",",".") ?> <?= $details["invoice"]["currency"] ?></td>
                                <td><?= number_format($item["total_price"] ?: 0, 2,",",".") ?> <?= $details["invoice"]["currency"] ?></td>
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
