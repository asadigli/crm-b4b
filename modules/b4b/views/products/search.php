<div class="slide_head">
  <div class="row m-0">
      <div class="col-md-4 pl-0 slide_head_col">
        <div class="search_row">
          <div class="row align-items-center border-bottom mx-0">
            <div class="col-6 d-flex align-items-center ps-0">
              <div class="custom-control custom-checkbox-tab wh-100">
                <input
                  type="radio"
                  name="search_type"
                  data-role="search-type"
                  id="filter1"
                  value="<?= SEARCH_TYPE_FULL ?>"
                  class=""
                  <?= $url_params["search_type"] ? ($url_params["search_type"] === SEARCH_TYPE_FULL ? "checked" : "") : "checked"  ?>
                  >
                <label for="filter1" class="wh-100 text-center"><?= lang("Extended search") ?></label>
              </div>
            </div>
            <div class="col-6 pe-0">
              <div class="custom-control custom-checkbox-tab wh-100">
                <input
                  type="radio"
                  name="search_type"
                  data-role="search-type"
                  id="filter2"
                  value="<?= SEARCH_TYPE_QUICK ?>"
                  class=""
                  <?= $url_params["search_type"] ? ($url_params["search_type"] === SEARCH_TYPE_QUICK ? "checked" : "") : "" ?>
                  >
                <label for="filter2" class="wh-100 text-center"><?= lang("Brand and OEM code") ?></label>
              </div>
            </div>
          </div>
          <div class="row align-items-center m-0">
            <div class="col-3">
              <span><?= lang("Search") ?></span>
            </div>
            <div class="col-9">
              <input
                class="form-control ui-autocomplete-input"
                type="text"
                data-role="search-keyword"
                placeholder="<?= lang("Search with code") ?>"
                name="keyword"
                autocomplete="off"
                value="<?= $url_params["keyword"] ?>"
                >
            </div>
          </div>
          <div class="row align-items-center m-0">
            <div class="col-3">
                <span><?= lang("Brand") ?></span>
            </div>
            <div class="col-9">
              <div class="form-group m-0">
                <select data-role="select-brand" name="brands" class="custom-select">
                  <option><?= lang("all_brands") ?></option>
                </select>
              </div>
            </div>
          </div>
          <div class="row align-items-center m-0">
            <div class="col-3">
                <span><?= lang("Car brands") ?></span>
            </div>
            <div class="col-9">
              <div class="form-group m-0">
                <select data-role="select-car-brand" name="car_brands" class="custom-select">
                  <option><?= lang("Choose car brand") ?></option>
                </select>
              </div>
            </div>
          </div>
          <div class="row align-items-center m-0">
            <div class="col-3"></div>
            <div class="col-9">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" id="discount_products" data-role="show-discount">
                <label for="discount_products"><span><?= lang("Discount products") ?></span></label>
              </div>
            </div>
          </div>
          <button data-role="search-filter" class="btn btn-primary search_button border-0">
              <?= lang("Search") ?>
          </button>
        </div>
      </div>
      <div class="col-md-8 pr-0 slide_head_col">
        <section class="customer-logos owl-carousel" data-role="products-carousel">
              <?php foreach ($brand_sliders as $key => $item): ?>
                <a class="customer-logo-item" <?= $item["is_clickable"] ? "target='_blank'" : "" ?> href="<?= $item["url"] ?>" tabindex="0">
                  <div class="head_banner">
                    <div class="banner_type">
                      <?php if ($item["title"]): ?>
                        <div class="head_banner_type"><?= $item["title"] ?></div>
                      <?php endif; ?>
                    </div>
                    <div class="banner_bg text-center">
                      <img src="<?= path_local($item["image"] ?: "assets/globals/image/no-image.png")  ?>">
                    </div>
                    <div>
                      <?= date("Y-m-d", strtotime($item["updated_at"])) ?>
                    </div>
                    <div class="head_banner_text text-center">
                      <?= $item["description"] ?>
                    </div>
                  </div>
                </a>
              <?php endforeach; ?>
        </section>
      </div>
  </div>
</div>

<section class="content">
  <div class="row">
    <div class="col-12">
      <?php if ($this->input->get("brand")): ?>
        <div class="d-flex justify-content-end me-2 mb-1">
          <a class="link" data-role="excel-export" href="javascript:void(0)"><?= lang("Excel export") ?> <i class="fa-solid fa-file-export"></i></a>
        </div>
      <?php endif; ?>
      <div class="box">
        <ul class="nav nav-tabs customtab2" role="tablist">
          <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#products-results" role="tab" aria-selected="true">
            <span class="hidden-sm-up"><i class="ion-home"></i></span>
            <span><?= lang("Products") ?></span></a>
          </li>
          <li data-role="tecdoc-crosses" data-load="1" class="nav-item"> <a data-role="active-link" class="nav-link" data-bs-toggle="tab" href="#tecdoc-codes" role="tab" aria-selected="false">
            <span class="hidden-sm-up"><i class="ion-person"></i></span>
            <span><?= lang("Cross code references for brand") ?></span></a>
          </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div class="tab-pane active" id="products-results" role="tabpanel">
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
                    <th data-role="filter-col" data-name="product_name" scope="col" style="width:14%;" >
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
                    <th scope="col" style="width:10%;" >
                      <?= lang("Model") ?>
                    </th>
                    <th data-role="col" data-name="comment" scope="col" style="width:3%;" >
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
                    <th data-role="filter-col" data-name="cart" scope="col" style="width:8%;" >
                      <?= lang("Cart") ?>
                      <?php if ($url_params["filter"] === FILTER_CART_ASC): ?>
                        <i data-role="filter-icon" class="fa-solid fa-arrow-up-short-wide sort"></i>
                      <?php elseif ($url_params["filter"] === FILTER_CART_DESC): ?>
                        <i data-role="filter-icon" class="fa-solid fa-arrow-down-short-wide sort"></i>
                      <?php endif; ?>
                    </th>
                    <th scope="col" style="width:5%;" >
                      <?= lang("Day") ?>
                    </th>
                    <th data-role="filter-col" data-name="price_eur" scope="col" style="width:9%;" >
                      <?= lang("Price") ?>
                      <?php if ($url_params["filter"] === FILTER_PRICE_EUR_ASC): ?>
                        <i data-role="filter-icon" class="fa-solid fa-arrow-up-short-wide sort"></i>
                      <?php elseif ($url_params["filter"] === FILTER_PRICE_EUR_DESC): ?>
                        <i data-role="filter-icon" class="fa-solid fa-arrow-down-short-wide sort"></i>
                      <?php endif; ?>
                    </th>
                    <?php if (false): ?>
                      <th style="width:6%;">
                        <?= lang("Currency") ?>
                      </th>
                    <?php endif; ?>
                    <th data-role="col" data-name="price-offer" scope="col" style="width:3%;" >
                      <?= lang("Price offer") ?>
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
          <div class="tab-pane" id="tecdoc-codes" role="tabpanel">
            <div class="box-header">
              <div class="d-flex justify-content-between">
                <div class="d-flex" >
                  <div class="me-2" ><b data-role="content-cross-brand-result-count"> 0 </b> <?= (lang("Brand count")) ?></div>
                  <div class="me-2" ><b data-role="content-cross-code-result-count"> 0 </b> <?= (lang("Code count")) ?></div>
                </div>
                <div><b data-role="content-cross-result-time"> 0 </b> <?= strtolower(lang("Sec")) ?></div>
              </div>
            </div>
            <div class="table-responsive-md">
              <table data-role="main-table" class="table mb-0 table-bordered">
                <thead>
                  <tr>
                    <th scope="col" style="width:10%;"></th>
                    <th scope="col" style="width:10%;"><?= lang("Cross reference code") ?></th>
                    <th scope="col" style="width:10%;"><?= lang("Cross reference brand") ?></th>
                    <th scope="col" style="width:10%;"><?= lang("Cross reference brand code") ?></th>
                    <th scope="col" style="width:10%;"><?= lang("Group") ?></th>
                    <th scope="col" style="width:10%;"><?= lang("Part") ?></th>
                  </tr>
                </thead>
                <tbody data-role="table-cross-list">
                  <tr>
                    <td style="padding:0;margin:0;" colspan="200">
                      <div class="d-flex justify-content-center" >
                        <div style="margin:0.3rem 0.8rem; width:80%;text-align:center;color: #676689!important;background-color : transparent !important;border:none !important;" class="alert alert-warning text-warning fade show" role="alert">
                            <strong><?= lang("No result") ?></strong>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php $this->load->view("products/comments_modal.php") ?>
  <?php $this->load->view("products/price_offer_modal.php") ?>
</section>
