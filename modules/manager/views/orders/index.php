<div class="sm sm-blue sm-second row d-flex mx-0">
    <div class="col-xl-7 col-lg-5">
      <div class="row">
        <div class="col-xl-5 col-12 mobile-scroll-x d-flex ps-0">

          <?php if ($order_groups): ?>
            <?php $i=0; foreach ($order_groups as $key => $item): ?>
              <?php if (in_array((int)$item["id"],$order_groups_ids)): ?>
                <div class="nav-item">
                    <a data-role="order-group-link"
                      data-id="<?= $item["id"] ?>"
                      data-default-start-date="<?= $item["default_start_date"] ? date("Y-m-d", strtotime($item["default_start_date"])) : date("Y-m-d") ?>"
                      class="nav-link <?= $url_params["group_id"] ? ($url_params["group_id"] === $item["id"] ? "current" : "") : (!$i ? "current" : "") ?>" href="javascript:void(0)">
                      <span class="menu-title"><?= $item["name"] ?></span>
                    </a>
                </div>
              <?php endif; ?>

            <?php $i++; endforeach; ?>
          <?php endif; ?>
        </div>
        <div class="col-xl-7 col-12 d-flex align-items-center justify-content-center mt-3 mt-xl-0">
          <div class="me-1"><?= lang("Total") ?>: <span class="me-1" style="font-weight:500;" data-role="total-order-amount">0.00 <?= CURRENCY_EUR ?></span></div>
          <span class="me-1">|</span><div class="me-1"><b data-role="content-result-count"> 0 </b> <?= strtolower(lang("Result")) ?></div>
          <span class="me-1">|</span><div data-role="content-result-confirmed" class="me-1"><b data-role="content-result-confirmed-count"> 0 </b> <?= strtolower(lang("Confirmed result")) ?></div>
          <span class="me-1">|</span><div class="me-1"><b data-role="content-result-time"> 0 </b> <?= strtolower(lang("Sec")) ?></div>
        </div>
      </div>
    </div>
    <div class="col-xl-5 col-lg-7">
      <div class="row align-items-center justify-content-end mt-3 mt-lg-0">

        <?php if ($order_statuses): ?>
            <div class="col-md-3 mb-3 mb-md-0">
              <select class="custom-select" data-role="order-statuses">
                <option value="" ><?= lang("All statuses") ?></option>
                <?php foreach ($order_statuses as $key => $item): ?>
                  <option value="<?= $item ?>" <?= $url_params["status"] === $item ? "checked" : "" ?>><?= lang($item) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
        <?php endif; ?>


        <div class="col-md-3 col-6 d-flex justify-content-end align-items-center">
          <input type="date" name="" max="<?= date("Y-m-d") ?>" data-role="select-start-date" value="<?=
            $url_params["start_date"] ?: (isset($order_groups[$url_params["group_id"]]["default_start_date"]) ?
              date('Y-m-d', strtotime(str_replace('.', '/', $order_groups[$url_params["group_id"]]["default_start_date"]))) :
              date("Y-m-d") )
            ?>" class="form-control" />
        </div>
        <div class="col-md-3 col-6 d-flex justify-content-end align-items-center">
          <input type="date" name="" max="<?= date('Y-m-d') ?>" data-role="select-end-date" value="<?=$url_params["end_date"] ?: date("Y-m-d")  ?>" class="form-control" />
        </div>
        <div class="col-md-3 mt-3 mt-md-0">
          <div class="d-flex justify-content-end">

              <button
                data-bs-toggle="modal"
                data-bs-target="#folders-list"
                data-role="open-folders-list-modal"
                class="btn btn-info">
                <i class="fa-regular fa-folder-open"></i>
              </button>

              <button data-toggle="tooltip" data-placement="left" title="<?= lang("Search") ?>" data-role="search-filter" class="btn btn-info ms-3">
                <i class="fa-solid fa-search"></i>
              </button>
          </div>
          <div data-role="folder-param" >
            <span data-role="folder-param-name" class="d-flex align-items-center" ></span>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="custom-tab d-flex mx-0" data-role="orders-main">
    <div data-role="orders-list-parent" class="custom-tab-sidebar col-md-3 p-0">
        <div class="mini-searchbox d-flex border-bottom p-2">
          <input class="form-control" data-role="search-keyword" type="text" placeholder="<?= lang("Search with order codes and etc") ?>.." value="<?= $url_params["keyword"] ?: "" ?>">
          <?php if (Auth::isDeveloper()): ?>
            <div class="custom-control custom-checkbox" data-toggle="tooltip" data-placement="top" title="<?= lang("All dates") ?>">
              <input data-role="no-date-filter" type="checkbox" id="all-dates" <?= $url_params["no_date_filter"] ? "checked" : "" ?>>
              <label for="all-dates"></label>
            </div>
          <?php endif; ?>
        </div>

        <div data-role="orders-list" class="custom-tab-sidebar-list">

        </div>
    </div>
    <div data-role="order-details" class="custom-tab-main col-md-9 px-0">
        <div data-role="order-detail-component" class="row justify-content-between m-0 d-none">
          <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div class="row align-items-center wh-100 mb-3 mx-0">
              <div class="col-md-2 ps-0">
                <h4 data-role="order-details-code" class="m-0"></h4>
                <input type="hidden" data-role="order-id"  name="" value="">
              </div>

              <div data-role="order-status" class="col-md-5 ps-0">

              </div>

              <div class="col-md-5 d-flex justify-content-end pe-0 align-items-center">
                <p data-role="transfer-order" class="link me-4 mb-0"><?= lang("Transfer order") ?></p>
                <select class="custom-select" data-role="order-edit-statuses">

                </select>
                <p data-role="edit-order-status" class="link ms-3 mb-0"><?= lang("Edit status") ?></p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
              <h5 class="table-mini-title"><?= lang("Customer information") ?></h5>
              <div class="table-responsive">
                  <table class="table table-bordered">
                      <tbody data-role="entry-information" >
                        <tr>
                          <td><?= lang("Company") ?>:</td>
                          <td data-name="name" ></td>
                        </tr>
                        <tr>
                          <td><?= lang("Email") ?>:</td>
                          <td  data-name="email" ></td>
                        </tr>
                        <?php if (false): ?>
                          <tr>
                            <td><?= lang("Phone") ?>:</td>
                            <td  data-name="phone" ></td>
                          </tr>
                        <?php endif; ?>
                        <tr>
                          <td><?= lang("Entry comment") ?>:</td>
                          <td  data-name="comment" ></td>
                        </tr>
                      </tbody>
                  </table>
              </div>

              <h5 class="table-mini-title"><?= lang("Account information") ?></h5>
              <div class="table-responsive">
                  <table class="table table-bordered">
                      <tbody data-role="account-list">
                        <tr>
                          <td><?= lang("Last payment date") ?>:</td>
                          <td data-name="payment-date" ></td>
                        </tr>
                        <tr>
                          <td><?= lang("Last payment amount") ?>:</td>
                          <td data-name="payment-amount" ></td>
                        </tr>
                        <tr>
                          <td><?= lang("Left amount debt") ?>:</td>
                          <td data-name="left-debt"></td>
                        </tr>
                      </tbody>
                  </table>
              </div>
          </div>
          <div class="col-md-5">
              <h5 class="table-mini-title"><?= lang("Payment dates") ?></h5>
              <div class="table-responsive">
                  <table class="table table-bordered">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th><?= lang("Date") ?></th>
                              <th><?= lang("Operation") ?></th>
                              <th><?= lang("Amount") ?></th>
                          </tr>
                      </thead>
                      <tbody data-role="last-payment-list">

                      </tbody>
                  </table>
              </div>
          </div>
          <div class="col-12">
              <div class="d-flex wh-100 d-flex justify-content-between align-items-center mb-3">
              <h5 class="table-mini-title m-0"><?= lang("Order") ?></h5>
                <div class="d-flex">
                  <div class="me-2" data-toggle="tooltip" data-placement="left" data-bs-original-title="<?= lang("check_columns_for_copy") ?>">
                    <a href="javascript:void(0)" data-type="standart" class="link" data-role="copy-brand-codes" style="font-weight:bold;" ><?= lang("Copy") ?></a>
                    <span class="copy"><?= lang("Copied!") ?></span>
                  </div>
                  <div class="me-2" data-toggle="tooltip" data-placement="left" data-bs-original-title="<?= lang("check_columns_for_copy") ?>">
                    <a href="javascript:void(0)" data-type="excel" class="link" data-role="copy-brand-codes" style="font-weight:bold;" ><?= lang("Excel Copy") ?></a>
                    <span class="copy"><?= lang("Copied!") ?></span>
                  </div>
                </div>
              </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead data-role="order-details-header" >
                      <tr>
                        <th scope="col" style="width:1%;">#</th>
                        <th class="table-checkbox" scope="col" style="width:10%;">

                          <div class="custom-control custom-checkbox ms-2">
                            <input
                                data-role="copy-details-checkbox"
                                data-index="1"
                                data-order="<?= isset($url_params["copy_check_indexes"]["1"]) ? $url_params["copy_check_indexes"]["1"] : "" ?>"
                                type="checkbox"
                                id="select_copy"
                                <?= isset($url_params["copy_check_indexes"]["1"]) ? "checked" : "" ?>
                            >
                            <label for="select_copy"><?= lang("Brand") ?>
                              <span data-role="order-circle" class="ms-2" ></span>
                            </label>
                          </div>

                        </th>
                        <th class="table-checkbox" scope="col">

                          <div class="custom-control custom-checkbox ms-2">
                            <input
                                data-role="copy-details-checkbox"
                                data-index="2"
                                data-order="<?= isset($url_params["copy_check_indexes"]["2"]) ? $url_params["copy_check_indexes"]["2"] : "" ?>"
                                type="checkbox"
                                id="select_copy0"
                                <?= isset($url_params["copy_check_indexes"]["2"]) ? "checked" : "" ?>
                                >
                            <label for="select_copy0"><?= lang("Day") ?>
                              <span data-role="order-circle" class="ms-2" ></span>
                            </label>
                          </div>

                        </th>
                        <th class="table-checkbox" scope="col" style="width:15%;">

                          <div class="custom-control custom-checkbox ms-2">
                            <input
                                data-role="copy-details-checkbox"
                                data-index="3"
                                data-order="<?= isset($url_params["copy_check_indexes"]["3"]) ? $url_params["copy_check_indexes"]["3"] : "" ?>"
                                type="checkbox"
                                id="select_copy1"
                                <?= isset($url_params["copy_check_indexes"]["3"]) ? "checked" : "" ?>
                            >
                            <label for="select_copy1"><?= lang("Model") ?>
                              <span data-role="order-circle" class="ms-2" ></span>
                            </label>
                          </div>

                        </th>
                        <th class="table-checkbox" scope="col" style="width:10%;">

                          <div class="custom-control custom-checkbox ms-2">
                            <input
                                data-role="copy-details-checkbox"
                                data-index="4"
                                data-order="<?= isset($url_params["copy_check_indexes"]["4"]) ? $url_params["copy_check_indexes"]["4"] : "" ?>"
                                type="checkbox"
                                id="select_copy2"
                                <?= isset($url_params["copy_check_indexes"]["4"]) ? "checked" : "" ?>
                                >
                            <label for="select_copy2"><?= lang("OEM") ?>
                              <span data-role="order-circle" class="ms-2" ></span>
                            </label>
                          </div>

                        </th>
                        <th class="table-checkbox" scope="col" style="width:10%;">

                          <div class="custom-control custom-checkbox ms-2">
                            <input
                                data-role="copy-details-checkbox"
                                data-index="5"
                                data-order="<?= isset($url_params["copy_check_indexes"]["5"]) ? $url_params["copy_check_indexes"]["5"] : "" ?>"
                                type="checkbox"
                                id="select_copy3"
                                <?= isset($url_params["copy_check_indexes"]["5"]) ? "checked" : "" ?>
                                >
                            <label for="select_copy3"><?= lang("Baku") ?>
                              <span data-role="order-circle" class="ms-2" ></span>
                            </label>
                          </div>

                        </th>

                        <th class="table-checkbox" scope="col" style="width:10%;">
                          <div class="custom-control custom-checkbox ms-2">
                            <input
                                data-role="copy-details-checkbox"
                                data-index="6"
                                data-order="<?= isset($url_params["copy_check_indexes"]["6"]) ? $url_params["copy_check_indexes"]["6"] : "" ?>"
                                type="checkbox"
                                id="select_copy4"
                                <?= isset($url_params["copy_check_indexes"]["6"]) ? "checked" : "" ?>
                                >
                            <label for="select_copy4"><?= lang("stock_baku_2") ?>
                              <span data-role="order-circle" class="ms-2" ></span>
                            </label>
                          </div>

                        </th>

                        <th class="table-checkbox" scope="col" style="width:10%;">

                          <div class="custom-control custom-checkbox ms-2">
                            <input
                                data-role="copy-details-checkbox"
                                data-index="7"
                                data-order="<?= isset($url_params["copy_check_indexes"]["7"]) ? $url_params["copy_check_indexes"]["7"] : "" ?>"
                                type="checkbox"
                                id="select_copy5"
                                <?= isset($url_params["copy_check_indexes"]["7"]) ? "checked" : "" ?>
                                >
                            <label for="select_copy5"><?= lang("Ganja") ?>
                              <span data-role="order-circle" class="ms-2" ></span>
                            </label>
                          </div>

                        </th>
                        <th class="table-checkbox" scope="col" style="width:10%;">

                          <div class="custom-control custom-checkbox ms-2">
                            <input
                                data-role="copy-details-checkbox"
                                data-index="8"
                                data-order="<?= isset($url_params["copy_check_indexes"]["8"]) ? $url_params["copy_check_indexes"]["8"] : (isset($url_params["copy_check_indexes"]) && !$url_params["copy_check_indexes"] ? "1" : "") ?>"
                                type="checkbox"
                                id="select_copy6"
                                <?= isset($url_params["copy_check_indexes"]["8"]) ? "checked" :
                                (isset($url_params["copy_check_indexes"]) && !$url_params["copy_check_indexes"] ? "checked" : "")
                                ?>
                                >
                            <label for="select_copy6"><?= lang("Brand code") ?>
                              <span data-role="order-circle" class="ms-2" ></span>
                            </label>
                          </div>

                        </th>
                        <th class="table-checkbox" scope="col" style="width:7%;">

                          <div class="custom-control custom-checkbox ms-2">
                            <input
                                data-role="copy-details-checkbox"
                                data-index="9"
                                data-order="<?= isset($url_params["copy_check_indexes"]["9"]) ? $url_params["copy_check_indexes"]["9"] : (isset($url_params["copy_check_indexes"]) && !$url_params["copy_check_indexes"] ? "2" : "") ?>"
                                type="checkbox"
                                id="select_copy7"
                                <?= isset($url_params["copy_check_indexes"]["9"]) ? "checked" :
                                (isset($url_params["copy_check_indexes"]) && !$url_params["copy_check_indexes"] ? "checked" : "")
                                ?>
                                    >
                            <label for="select_copy7"><?= lang("Quantity") ?>
                              <span data-role="order-circle" class="ms-2" ></span>
                            </label>
                          </div>

                        </th>
                        <th class="table-checkbox" scope="col" style="width:10%;">

                          <div class="custom-control custom-checkbox ms-2">
                            <input
                                data-role="copy-details-checkbox"
                                data-index="10"
                                data-order="<?= isset($url_params["copy_check_indexes"]["10"]) ? $url_params["copy_check_indexes"]["10"] : "" ?>"
                                type="checkbox"
                                id="select_copy8"
                                <?= isset($url_params["copy_check_indexes"]["10"]) ? "checked" : "" ?>
                                    >
                            <label for="select_copy8"><?= lang("Price") ?>
                              <span data-role="order-circle" class="ms-2" ></span>
                            </label>
                          </div>

                        </th>
                        <th class="table-checkbox" scope="col" style="width:10%;">

                          <div class="custom-control custom-checkbox ms-2">
                            <input
                                data-role="copy-details-checkbox"
                                data-index="11"
                                data-order="<?= isset($url_params["copy_check_indexes"]["11"]) ? $url_params["copy_check_indexes"]["11"] : "" ?>"
                                  type="checkbox"
                                  id="select_copy9"
                                  <?= isset($url_params["copy_check_indexes"]["11"]) ? "checked" : "" ?>
                                    >
                            <label for="select_copy9"><?= lang("Amount") ?>
                              <span data-role="order-circle" class="ms-2" ></span>
                            </label>
                          </div>

                        </th>
                      </tr>
                    </thead>
                    <tbody data-role="order-details-list" >

                    </tbody>
                    <tfoot data-role="order-details-list-footer">
                    </tfoot>
                </table>
            </div>
          </div>
        </div>
    </div>
</div>

<?php $this->load->view("components/folders_list_modal") ?>
<?php $this->load->view("components/folders_add_modal") ?>
