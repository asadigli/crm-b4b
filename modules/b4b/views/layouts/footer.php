    </div>
  </div>

  <footer>
    <div class="container-fluid">
      <div class="row justify-content-between">
        <div class="col-lg-3 col-md-2 p-lg-0">
            <a class="footer-logo mb-2" href="<?= path_local() ?>"><?= readSVG("logo/customer") ?></a>
            <p><?= lang("Slogan") ?></p>

            <?php if ($this->config->item("social networks")): ?>
              <div class="social-icons">
                <?php foreach ($this->config->item("social networks") as $key => $item): ?>
                  <a href="<?= $item["url"] ?>" target="_blank" rel="noreferrer">
                      <i class="fa-brands fa-<?= $item["key"] ?>"></i>
                  </a>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

        </div>
        <div class="col-lg-9 col-md-10 d-flex justify-content-md-end">
          <div class="row justify-content-end wh-100">
            <?php foreach ($this->config->item("footer_links") as $key => $item): ?>
            <div class="col-lg-3 col-md-4 d-none d-md-flex flex-column">
              <h4 class="footer-mini-title"><?= lang($item["key"]) ?></h4>
              <div class="footer-menu-item">
                <?php foreach ($item["children"] as $sub_key => $sub_item): ?>
                  <a href="<?= $sub_item["link"] ?>" target="<?= $sub_item["target"] ?>"><?= lang($sub_item["key"]) ?></a>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endforeach; ?>
            <div class="col-lg-3 col-md-4 d-flex flex-column">
              <h4 class="footer-mini-title d-none d-md-block"><?= lang("Contact us") ?></h4>
              <div class="footer-menu-item">
                  <a><i class="fas fa-envelope me-2"></i><?= $this->config->item("company email") ?></a>
                  <a><i class="fas fa-phone-alt me-2"></i><?= $this->config->item("company phone") ?></a>
                  <a><i class="fas fa-map-marker-alt me-2"></i><?= $this->config->item("company address") ?></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="bottom-line-footer row">
        <?php $copyright = $this->config->item("copyright");  ?>
        <div class="col-md-6">
            <div class="copyright-text d-flex align-items-center">
              <span><?= $copyright["start_year"] ?> - <?= $copyright["current_year"] ?> © <b><?= $copyright["project_name"] ?></b></span>
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-md-end">
            <div class="footer-copyright">
                <b><?= lang($copyright["text"],["company" => $copyright["company_name"],"link" => $copyright["link"]]) ?></b>
            </div>
        </div>
      </div>
    </div>
  </footer>

</div>

<script src="<?= assets("js/libs/jquery.min.js", true, true) ?>"></script>
<script src="<?= assets("js/libs/vendors.js", true, true) ?>"></script>
<script src="<?= assets("js/libs/owl-carousel.min.js", true, true) ?>"></script>
<script src="<?= assets("js/custom.js", true) ?>"></script>
<script src="<?= assets("js/libs/sweetalert2.min.js", true, true) ?>"></script>
<script src="<?= assets("js/libs/tableToExcel.js", true, true) ?>"></script>
<script src="<?= assets("js/libs/select2.min.js", true, true) ?>"></script>
<script src="<?= assets("js/loader.js", true, true) ?>"></script>
<script src="<?= assets("js/master.js") ?>"></script>
<script src="<?= assets("js/helpers.js") ?>"></script>
<script src="<?= assets("js/pages/pop-up.js") ?>"></script>

<?php if ($this->extraJS): ?>
  <?php foreach ($this->extraJS as $key => $item): ?>
    <script src="<?= assets($item) ?>"></script>
  <?php endforeach; ?>
<?php endif; ?>

<?=  $this->lang_dom; ?>
</body>

</html>
