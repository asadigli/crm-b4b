<div class="row">
	<div class="col-lg-12">
	  <div data-role="main-title" class="box m-0">
	    <div class="box-body p-2">
	      <div class="row box-top-info form-group mb-0">
            <div class="col-md-9 d-flex flex-wrap">
              <div style="font-size:1rem;font-weight:600;" class="me-3 d-flex align-items-center">
                <?= lang("Product count") ?>: <span class="ms-2" data-role="product-total-count"> 0 </span>
              </div>
              <div style="font-size:1rem;font-weight:600;" class="me-3 d-flex align-items-center">
                <?= lang("Choosen product count") ?>: <span class="ms-2" data-role="product-choosen-total-count"> 0 </span>
              </div>
              <div style="font-size:1rem;font-weight:600;" class="me-3 d-flex align-items-center">
                <?= lang("Total price") ?>: <span class="ms-2" data-role="product-whole-total-price"> 0 </span>
              </div>
              <div style="font-size:1rem;font-weight:600;" class="me-3 d-flex align-items-center">
                <?= lang("Choosed total price") ?>: <span class="ms-2" data-role="product-choosen-whole-total-price"> 0 </span>
              </div>
            </div>
            <div class="col-md-3 d-flex justify-content-end">
              <button data-toggle="tooltip" data-placement="left" title="<?= lang("Refresh") ?>" data-role="refresh-table" class="btn btn-info" disabled>
                <i class="fa-solid fa-rotate"></i>
              </button>
            </div>
          </div>
	    </div>
	  </div>
	</div>
</div>

<div class="row mt-4">
	<div class="col-lg-12 grid-margin stretch-box">
    <div class="box">
			<div class="box-header">
        <div data-role="cart-btns" class="row mb-2">

        </div>
			</div>
      <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped" style="width:100%;">
            <thead>
              <tr>
                <th style="width:1%;">#</th>
                <th style="width:1%;" ><input class="c-pointer" data-role="check-all" type="checkbox" checked></th>
                <th style="width:6%;" ><?= lang("Brand") ?></th>
                <th style="width:6%;" ><?= lang("Brand code") ?></th>
                <th style="width:6%;" ><?= lang("OEM") ?></th>
                <th style="width:6%;" ><?= lang("Product name") ?></th>
                <th style="width:3%;" ><?= lang("Day") ?></th>
								<th style="width:8%;" ><?= lang("Note") ?></th>
                <th style="width:6%;" ><?= lang("Date") ?></th>
								<th style="width:3%;" ><?= lang("Baku") ?></th>
								<th style="width:3%;" ><?= lang("stock_baku_2") ?></th>
								<th style="width:3%;" ><?= lang("Ganja") ?></th>
								<th style="width:6%;" ><?= lang("Quantity") ?></th>
                <th style="width:6%;" ><?= lang("Price") ?></th>
								<th style="width:6%;" ><?= lang("Total") ?></th>
              <?php if (false): ?>
              	  <th style="width:6%;" ><?= lang("Currency") ?></th>
              <?php endif; ?>
                <th style="width:1%;" ></th>
              </tr>
            </thead>
            <tbody data-role="table-list">

            </tbody>
          </table>

        </div>
      <div class="box-footer">
        <div data-role="cart-btns" class="row">

        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
	<div class="col-lg-12">
	  <div data-role="main-title" class="box m-0">
	    <div class="box-body p-2">
	      <div class="row box-top-info form-group mb-0">
            <div class="col-md-9 d-flex flex-wrap">
              <div style="font-size:1rem;font-weight:600;" class="me-3 d-flex align-items-center">
                <?= lang("Product count") ?>: <span class="ms-2" data-role="product-total-count"> 0 </span>
              </div>
              <div style="font-size:1rem;font-weight:600;" class="me-3 d-flex align-items-center">
                <?= lang("Choosen product count") ?>: <span class="ms-2" data-role="product-choosen-total-count"> 0 </span>
              </div>
              <div style="font-size:1rem;font-weight:600;" class="me-3 d-flex align-items-center">
                <?= lang("Total price") ?>: <span class="ms-2" data-role="product-whole-total-price"> 0 </span>
              </div>
              <div style="font-size:1rem;font-weight:600;" class="me-3 d-flex align-items-center">
                <?= lang("Choosed total price") ?>: <span class="ms-2" data-role="product-choosen-whole-total-price"> 0 </span>
              </div>
            </div>
            <div class="col-md-3 d-flex justify-content-end">
              <button data-toggle="tooltip" data-placement="left" title="<?= lang("Refresh") ?>" data-role="refresh-table" class="btn btn-info" disabled>
                <i class="fa-solid fa-rotate"></i>
              </button>
            </div>
          </div>
	    </div>
	  </div>
	</div>
</div>
