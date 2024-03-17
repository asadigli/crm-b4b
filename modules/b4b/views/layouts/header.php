<header class="main-header header-b4b">
    <div class="header-top-line">
      <div class="inner-line">
        <div class="row align-items-center m-0">
          <div class="col-md-3">
            <?php $delivery_dates = Services::configs(["group" => "delivery_date"], true); ?>
            <?php if ($delivery_dates && is_array($delivery_dates)): ?>
              <div class="text-tooltip d-none d-md-block">
                <p class="m-0">
                  <i class="fa-regular fa-clock me-2"></i>
                  <?= lang("Shipping text") ?>
                </p>
                  <div class="dropdown-text-tooltip">
                    <?php foreach ($delivery_dates as $key => $date): ?>
                      <?php if (is_array($date)): ?>
                        <?php if (in_array($key,["monday","tuesday","wednesday","thursday","friday","saturday","sunday"])): ?>
                          <div class="row">
                            <div class="col-2 text-bold">
                              <?= lang(ucfirst($key)) ?>
                            </div>
                            <div class="col-10">
                              <?= implode(" , ",$date)?>
                            </div>
                          </div>
                        <?php else: ?>
                          <hr>
                          <span> <strong><?= implode(" , ",$date)?></strong></span>
                        <?php endif; ?>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </div>
              </div>
            <?php endif; ?>
          </div>
          <div class="col-md-3">
            <?php $b4b_header_return_text = Services::configs(["group" => "other"], true); ?>
            <?php if ($b4b_header_return_text && is_array($b4b_header_return_text)): ?>
              <div class="text-tooltip d-none d-md-block">
                <p class="m-0">
                  <i class="fa-solid fa-circle-info me-2"></i>
                  <?= lang("Return text") ?>
                </p>
                  <div class="dropdown-text-tooltip">
                    <?php foreach ($b4b_header_return_text as $key => $item): ?>
                        <?php if ($key === B4B_HEADER_RETURN_INFO_TEXT): ?>
                          <?php foreach ($item as $sub_key => $sub_item): ?>
                            <span> <strong><?= $sub_item ?></strong></span>
                          <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                  </div>
              </div>
            <?php endif; ?>
          </div>
          <div class="col-md-6 d-flex justify-content-end align-items-center">
            <?php if (Auth::customers()): ?>
              <div class="form-group custom-select-mini mb-0 me-3">
                <select data-role="change-entry-customer" class="custom-select">
                  <?php foreach (Auth::customers() as $key => $customer): ?>
                    <option
                          value="<?= $customer["remote_id"] ?>"
                          <?php if ($customer["is_current"]): ?>
                            <?= "data-role='current-account-currency'" ?>
                            <?= "data-currency='". $customer["currency_name"] ."'" ?>
                            <?= "selected" ?>
                          <?php endif; ?>
                          >
                            <?= $customer["name"] ?> (<?= $customer["remote_id"] ?>) <?= $customer["currency_name"] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            <?php endif; ?>

            <div class="user-box">
              <div class="user-box-main-side top p-0">
                <div class="user-box-content">
                  <span><?= Auth::name() ?></span>
                </div>
                <img src="<?= assets("image/avatar-user-png.png", true) ?>" alt="">
              </div>
              <div class="user-box-dropdown">
                <div class="user-box-main-side bottom">
                  <div class="user-box-content">
                    <h6><?= Auth::name() ?></h6>
                    <span><?= Auth::email() ?> - <?= Auth::entry_limits("used_limit") . "/" .Auth::entry_limits() ?></span>
                  </div>
                </div>
                <div class="user-box-list">
                  <a href="javascript:void(0)">
                    <i class="fa-regular fa-user"></i>
                    <?= lang("My account") ?>
                  </a>
                  <a href="<?= path_local("auth/logout") ?>">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <?= lang("Logout") ?>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="inside-header inside-header-b4b d-flex align-items-center">
      <div class="row wh-100 m-0">
          <div class="d-flex align-items-center col-lg-3 col-6">
              <a href="<?= path_local() ?>" class="logo">
                <div class="logo-lg">
                  <span class="light-logo"> <?= readSVG("logo/customer") ?></span>
                </div>
              </a>
              <div class="header-slogan d-none d-lg-block">
                <h2><?= lang("Slogan_header") ?></h2>
              </div>
          </div>
          <div class="app-menu d-flex justify-content-end align-items-center d-lg-inline-flex d-none col-lg-4 col-6 p-0">
            <ul class="header-megamenu nav wh-100">
              <li class="btn-group wh-100">
                <div class="app-menu wh-100">
                    <div class="search-bx wh-100">
                      <form action="/products/search" method="GET" >
                        <div class="input-group">
                            <input data-role="header-search-input" autocomplete="off" maxlength="<?= limits("search_panel_char") ?>"
                                  type="search" name="keyword" class="form-control" placeholder="<?= lang("Search") ?>" aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn" type="submit" >
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                      </form>
                    </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="col-lg-5 col-6 d-flex justify-content-end align-items-center">
            <a href="<?= path_local('?only_new_products=1') ?>" target="_blank" class="new-products">
              <?= readSVG("icons/new-product") ?>
              <span><?= lang("New products from warehouses") ?></span>
            </a>
            <nav class="navbar navbar-static-top d-flex justify-content-end p-0">
                <div class="navbar-custom-menu r-side">
                    <ul class="nav navbar-nav align-items-center">
                        <li>
                          <a href="<?= path_local("cart") ?>" class="cart-mini-box" data-role="nav-cart-item">
                              <i class="fa-solid fa-cart-shopping"></i>
                            <?php if (Auth::getValue("cart_info")): ?>
                              <div class="d-flex">
                                <span class="count" data-role="nav-cart-amount" >
                                  <?= number_format(Auth::getValue("cart_info")["sale_price"],2,",",".") ?> <?= Auth::currentAccountCurrency() ?>
                                </span>
                                <?php if (Auth::getValue("cart_info")["count"]): ?>
                                  <span class="badge badge-danger" data-role="nav-cart-count" >
                                    <?= Auth::getValue("cart_info")["count"] ?>
                                  </span>
                                <?php endif; ?>
                              </div>
                            <?php endif; ?>
                          </a>
                        </li>
                        <?php if (Auth::supervisor()): ?>
                          <li class="d-none d-lg-block ms-4">
                            <div class="w-auto l-h-12 no-shadow card-account">
                              <div class="d-flex align-items-center">
                                <img src="<?= Auth::supervisor("photo") ?: assets("image/no-image.png",true) ?>" class="avatar" alt="<?= Auth::supervisor("name") ?>" />
                                <div class="lines d-flex flex-column">
                                  <?php if(Auth::supervisor("name")){?>
                                    <div class="d-flex mb-2">
                                      <i class="fa-solid fa-user"></i>
                                      <span><?= Auth::supervisor("name") ?></span>
                                    </div>
                                  <?php } ?>
                                  <?php if(Auth::supervisor("phone")){?>
                                    <div class="d-flex mb-2">
                                      <i class="fa-solid fa-phone"></i>
                                      <span><?= Auth::supervisor("phone") ?></span>
                                    </div>
                                  <?php } ?>
                                  <?php if(Auth::email()){?>
                                    <div class="d-flex">
                                      <i class="fa-solid fa-envelope"></i>
                                      <span><?= Auth::supervisor("email") ?></span>
                                    </div>
                                  <?php } ?>
                                  <?php if (Auth::supervisor("whatsapp")): ?>
                                    <a href="https://wa.me/<?= Auth::supervisor("whatsapp") ?>" target="_blank" rel="noreferrer" class="card-account-wp">
                                      <?= readSVG("icons/whatsapp") ?>
                                    </a>
                                  <?php endif; ?>
                                </div>
                              </div>
                            </div>
                          </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
            <div class="d-flex d-lg-none align-items-end">
              <input id="main-menu-state" type="checkbox" />
              <label class="main-menu-btn" for="main-menu-state">
                  <span class="main-menu-btn-icon"></span>
              </label>
            </div>
          </div>
      </div>
    </div>
    <div class="row wh-100 m-0 border-top pt-3 d-lg-none">
      <div class="col-12">
        <div class="search-bx wh-100">
          <form action="/products/search" method="GET" >
            <div class="input-group">
                <input data-role="header-search-input" autocomplete="off" maxlength="<?= limits("search_panel_char") ?>"
                      type="search" name="keyword" class="form-control" placeholder="<?= lang("Search") ?>" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit" >
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
          </form>
        </div>
      </div>
    </div>
</header>

<nav class="main-nav main-nav-b4b d-none d-lg-flex" data-role="navigation" role="navigation">
    <?php $this->view("layouts/navbar") ?>
</nav>

<button class="scroll-top btn btn-primary p-0"><i class="fas fa-arrow-up"></i></button>

<!-- Modal loader -->
<div class="modal-loader">
    <div class="inner">
        <img src="<?= assets("image/gif/loading-screen.gif", true) ?>" alt="<?= lang("Loading") ?>">
        <span><?= lang("Loading") ?></span>
    </div>
    <div class="modal-loader-overlay"></div>
</div>
<!-- Modal loader -->

<div class="content-wrapper">
    <div class="container-full">
