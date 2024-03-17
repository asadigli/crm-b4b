<?php if($this->config->item("navbar")): $navs = $this->config->item("navbar");?>
  <nav class="main-nav" role="navigation">
    <!-- <div class="d-flex d-lg-none justify-content-end"> -->
      <input id="main-menu-state" type="checkbox" />
      <label class="main-menu-btn d-flex d-lg-none justify-content-end" for="main-menu-state">
        <span class="main-menu-btn-icon"></span>
      </label>
    <!-- </div> -->
    <ul id="main-menu" class="sm sm-blue">
      <?php foreach($navs as $nav): ?>
        <?php if (Auth::checkRole($nav["roles"])): ?>
            <?php if ($nav["path"] === "orders" && !Auth::allowedOrderGroups()): ?>
            <?php else: ?>
              <?php $this->load->view("layouts/components/navbar/item", ["nav" => $nav]); ?>
            <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; ?>

      <?php
        $currencies_data = Services::currencies();
        $currencies = isset($currencies_data["code"]) && $currencies_data["code"] === Status_codes::HTTP_OK ? $currencies_data["data"] : [];
      ?>

      <?php if (isset($currencies) && $currencies): ?>
      <div class="currency"  data-role="header-currency">
        <?php foreach ($currencies as $key => $item): ?>
          <?php if (in_array($item["main_name"],[CURRENCY_EUR,CURRENCY_USD])): ?>
            <span data-name="<?= $item["main_name"] ?>" data-val="<?= isset($item["value"]) ? $item["value"] : "" ?>" >
              <strong>
                  <?= isset($item["main_name"]) && $item["main_name"] ? $item["main_name"] : ""  ?>
              </strong>
              <?=isset($item["value"]) && $item["value"] ? number_format($item["value"],2) : "" ?>
            </span>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

    </ul>
  </nav>
<?php endif; ?>
