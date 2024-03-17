<section class="content">
  <div class="row">
  	<div class="col-lg-12">
  	  <div class="card">
  	    <div class="card-body">
  	      <div class="row">
  	        <div class="col-md-3">
  	            <div class="form-group d-flex">
  	              <input data-role="search-keyword" placeholder="<?= lang("Search with code..") ?>" type="text" class="form-control" placeholder="<?= lang("Search") ?>" value="<?= $url_params["keyword"] ?>" >
  	            </div>
  	        </div>
  					<div class="col-md-2">
  						<div class="form-group mb-md-0 mb-3">
  							<select data-role="select-brand" name="brands" class="form-control custom-select">
  								<option value=""><?= lang("All brands") ?></option>
  							</select>
  						</div>
  					</div>
            <div class="col-md-2">
              <div class="form-group mb-md-0 mb-3">
                <select data-role="select-car-brand" name="car_brands" class="custom-select">
                  <option><?= lang("All car brands") ?></option>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group mb-md-0 mb-3">
                <select data-role="select-product-resource" name="product_resources" class="custom-select">
                  <option><?= lang("All product resources") ?></option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="d-flex justify-content-end">
                  <button type="button" data-role="search-filter" class="btn btn-primary" >
                    <i class="mdi mdi-magnify mr-2"></i>
                    <?= lang("Search") ?>
                  </button>
              </div>
            </div>
  	      </div>
          <div class="row">
            <div class="col-md-3 d-flex align-items-center ms-3">
              <div class="input-group row">
                <div class="col-1 col-12 m-0 p-0">
                  <div class="form-check" data-toggle="tooltip" data-placement="bottom" title="<?= lang("Show only choosen warehouse") ?>">
                    <label class="form-check-label text-muted">
                      <input style="width:18px;height:18px;" data-role="only-warehouse" name="only_warehouse" type="checkbox" class="form-check-input c-pointer" <?= $url_params["only_warehouse"] ? "checked" : "" ?>>
                    </label>
                  </div>
                </div>
                <div class="col-md-5 col-12 mb-md-0 mb-3 p-0">
                  <select data-role="select-warehouse" name="warehouse_id" class="form-control custom-select2 rounded-0 rounded-start">
                    <option value=""><?= lang("Warehouses") ?></option>
                  </select>
                </div>
                <div class="col-md-3 col-6 m-0 p-0">
                  <input
  										name="min_search_quantity"
  										value="<?= is_null($url_params["min_search_quantity"]) ? "" : $url_params["min_search_quantity"] ?>"
  										class="form-control rounded-0"
  										style="background-image:none;"
  										placeholder="min."
                      autocomplete="off"
  									>
                </div>
                <div class="col-md-3 col-6 m-0 p-0">
                  <input
  										name="max_search_quantity"
  										value="<?= is_null($url_params["max_search_quantity"]) ? "" : $url_params["max_search_quantity"] ?>"
  										class="form-control rounded-0 rounded-end"
  										style="background-image:none;"
  										placeholder="maks."
                      autocomplete="off"
  									>
                </div>
              </div>
            </div>
            <div class="col-md-2 d-flex align-items-center mt-2" data-toggle="tooltip" data-placement="bottom" title="<?= lang("Dead stock description") ?>">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-transparent pr-2 border" >
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input style="width:18px;height:18px;" data-role="is-dead-stock" name="is_dead_stock" type="checkbox" class="form-check-input c-pointer" <?= $url_params["is_dead_stock"] ? "checked" : "" ?>>
                      </label>
                    </div>
                  </span>
                </div>
                <input style="max-width:50px;" type="number" value="<?= $url_params["dead_stock"] ?: 180 ?>" class="form-control rounded-end" placeholder="<?= lang("Day") ?>" data-role="dead-stock" name="dead_stock" >
                <label class="form-label text-muted mt-3 ms-1" for=""><?= lang("Dead stock") ?></label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <button type="button" data-role="hide-price" class="btn btn-primary disabled" >
                <i class="mdi mdi-magnify mr-2"></i>
                <?= lang("Hide prices with no AZN price") ?>
              </button>
            </div>
            <div class="col-6 d-flex justify-content-end">
              <button type="button" data-role="apply-discount" class="btn btn-primary disabled" >
                <i class="mdi mdi-magnify mr-2"></i>
                <?= lang("Apply discount") ?>
              </button>
            </div>
          </div>
  	    </div>
  	  </div>
  	</div>
  </div>

  <div class="row d-flex justify-content-between align-items-center">
    <div class="col-12 d-flex justify-content-end">
      <a class="link" data-role="excel-export" href="javascript:void(0)"><?= lang("Excel export") ?> <i class="fa-solid fa-file-export"></i></a>
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
          <table data-role="main-table" class="table mb-0 table-bordered">
            <thead>
              <tr data-role="filter-col-header" >
                <th scope="col" style="width:1%;">#</th>
                <th data-role="filter-col" data-name="brand_name" scope="col" style="width:7%;" >
                  <?= lang("Brand") ?>
                  <?php if ($url_params["filter"] === FILTER_BRAND_NAME_ASC): ?>
                    <i data-role="filter-icon" class="fa-solid fa-arrow-up-short-wide sort"></i>
                  <?php elseif ($url_params["filter"] === FILTER_BRAND_NAME_DESC): ?>
                    <i data-role="filter-icon" class="fa-solid fa-arrow-down-short-wide sort"></i>
                  <?php endif; ?>
                </th>
                <th scope="col" style="width:7%;" >
                  <?= lang("Brand code") ?>
                </th>
                <th scope="col" style="width:7%;" >
                  <?= lang("OEM") ?>
                </th>
                <th data-role="filter-col" data-name="product_name" scope="col" style="width:10%;" >
                  <?= lang("Product name") ?>
                  <?php if ($url_params["filter"] === FILTER_PRODUCT_NAME_ASC): ?>
                    <i data-role="filter-icon" class="fa-solid fa-arrow-up-short-wide sort"></i>
                  <?php elseif ($url_params["filter"] === FILTER_PRODUCT_NAME_DESC): ?>
                    <i data-role="filter-icon" class="fa-solid fa-arrow-down-short-wide sort"></i>
                  <?php endif; ?>
                </th>
                <th scope="col" style="width:8%;" >
                  <?= lang("Description") ?>
                </th>
                <th scope="col" >
                  <?= lang("Purchase price") ?>
                </th>
                <th scope="col" style="width:10%;" >
                  <?= lang("Model") ?>
                </th>
                <th data-role="col" data-name="comment" scope="col" style="width:4%;" >
                  <?= lang("Comment") ?>
                </th>
                <th data-role="filter-col" data-name="stock_baku" scope="col" style="width:5%;" >
                  <?= lang("Baku") ?>
                  <?php if ($url_params["filter"] === FILTER_STOCK_BAKU_ASC): ?>
                    <i data-role="filter-icon" class="fa-solid fa-arrow-up-short-wide sort"></i>
                  <?php elseif ($url_params["filter"] === FILTER_STOCK_BAKU_DESC): ?>
                    <i data-role="filter-icon" class="fa-solid fa-arrow-down-short-wide sort"></i>
                  <?php endif; ?>
                </th>
                <th data-role="filter-col" data-name="stock_baku_2" scope="col" style="width:5%;" >
                  <?= lang("stock_baku_2") ?>
                  <?php if ($url_params["filter"] === FILTER_STOCK_BAKU_2_ASC): ?>
                    <i data-role="filter-icon" class="fa-solid fa-arrow-up-short-wide sort"></i>
                  <?php elseif ($url_params["filter"] === FILTER_STOCK_BAKU_2_DESC): ?>
                    <i data-role="filter-icon" class="fa-solid fa-arrow-down-short-wide sort"></i>
                  <?php endif; ?>
                </th>
                <th data-role="filter-col" data-name="stock_ganja" scope="col" style="width:5%;" >
                  <?= lang("Ganja") ?>
                  <?php if ($url_params["filter"] === FILTER_STOCK_GANJA_ASC): ?>
                    <i data-role="filter-icon" class="fa-solid fa-arrow-up-short-wide sort"></i>
                  <?php elseif ($url_params["filter"] === FILTER_STOCK_GANJA_DESC): ?>
                    <i data-role="filter-icon" class="fa-solid fa-arrow-down-short-wide sort"></i>
                  <?php endif; ?>
                </th>
                <th scope="col" style="width:1%;" >
                  <div data-toggle="tooltip" data-placement="top" title="<?= lang("Is new from warehouse") ?>">
                   <i class="fa-solid fa-boxes-packing"></i>
                  </div>
                </th>
                <th scope="col" style="width:2%;" >
                  <?= lang("Day") ?>
                </th>
                <th scope="col" style="width:1%;" >

                </th>
                <th data-role="filter-col" data-name="price_eur" scope="col" style="width:10%;" >
                  <?= lang("Price") ?> <sup>
                </th>
                <th data-role="col" data-name="price-offer" scope="col" style="width:4%;" >
                  <?= lang("Price offer") ?>
                </th>
                <th scope="col" style="width:9%;">
                  <?= lang("Discount") ?>
                </th>
                <th scope="col">
                  <div data-toggle="tooltip" data-placement="top" title="<?= lang("Last sale date") ?>">
                    <i class="fa-regular fa-calendar-days"></i>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody data-role="table-list">
              <tr>
                <td style="padding:0;margin:0;" colspan="200">
                  <div class="d-flex justify-content-center" >
                    <div style="margin:0.3rem 0.8rem; width:80%;text-align:center;color: #676689!important;background-color : transparent !important;border:none !important;" class="alert alert-warning text-warning fade show" role="alert">
                        <strong><?= lang("Enter filter parameters for product search") ?></strong>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="load-more d-none" data-role="load-more-container"  id="load_more_div">
            <a data-role="load-more" href="javascript:void(0)"><?= lang("Load more") ?></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php $this->load->view("products/comments_modal.php") ?>
<?php $this->load->view("products/price_offer_modal.php") ?>
