<ul id="main-menu" class="sm sm-blue">
  <?php foreach ($this->config->item("pages") as $key => $page): ?>
    <?php if ($page["setup"]): ?>
      <li class="<?= uri_string() === $page["path"] ? "current" : "" ?>">
        <a class="nav-link" href="<?= $page["path"] !== "no_path" ? path_local($page["path"]) : "javascript:void(0)" ?>">
          <i class="<?= $page["icon"] ?>"></i>
          <?= lang($page["name"]) ?>
        </a>
      </li>
    <?php endif; ?>
  <?php endforeach; ?>

  <?php
    $currencies_data = Services::currencies();
    $currencies = isset($currencies_data["code"]) && $currencies_data["code"] === Status_codes::HTTP_OK ? $currencies_data["data"] : [];
   ?>

  <?php if (isset($currencies) && $currencies): ?>
    <div class="currency">
      <?php foreach ($currencies as $key => $item): ?>
        <?php if (in_array($item["main_name"],[CURRENCY_EUR,CURRENCY_USD])): ?>
          <span>
            <strong>
                <?= isset($item["main_name"]) && $item["main_name"] ? $item["main_name"] : ""  ?>
            </strong>
            <?=isset($item["value"]) && $item["value"] ? number_format($item["value"],2) : "" ?>
          </span>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>


    <?php if (false) : ?>
      <li>
        <a href="#">
          <i class="icon-Air-ballon"><span class="path1"></span><span class="path2"></span></i>
          Apps
        </a>
        <ul>
          <li><a href="mailbox.html"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Mailbox</a></li>
        </ul>
      </li>
    <?php endif; ?>
</ul>
