  </section>
  <!-- /.content -->
  </div>
  </div>
  <!-- /.content-wrapper -->
  <footer>
    <div class="container-fluid">
        <div class="row justify-content-between">
            <div class="col-lg-3 col-md-2 p-lg-0">
                <a class="footer-logo mb-2" href="<?= path_local() ?>"><?= readSVG("logo/customer") ?></a>
                <p><?= lang("Slogan") ?></p>

                <div class="social-icons">
                    <a href="https://www.facebook.com/" target="_blank" rel="noreferrer">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="https://www.instagram.com/" target="_blank" rel="noreferrer">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="https://www.linkedin.com/company/" target="_blank" rel="noreferrer">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>
                </div>
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

  <?php $this->load->view("layouts/foot/scripts"); ?>

</body>

</html>
