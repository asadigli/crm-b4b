<div class="login-overlay"></div>
<div class="container h-p100">
  <div class="row justify-content-center align-items-center g-0 h-100vh">
    <div class="col-lg-4 col-md-5 col-12 login-box">
      <div class="bg-white rounded10 shadow-lg">
        <div class="content-top-agile p-20 pb-0">
          <a href="https://customer.com/" target="_blank" rel="nofollow noreferrer" class="login-logo">
            <?= readSVG("logo/customer") ?>
          </a>
          <?php if (false): ?>
            <p class="mb-0"><?= lang("Please sign in to continue") ?></p>
          <?php endif; ?>
          <?php
          $has_error = Flash::has("error");

          $contact_whatsapp = Services::configs(["group" => "contact_whatsapp"]);
          $link = "";
          if (isset($contact_whatsapp["request_whatsapp"][0])) {
            $whatsapp = $contact_whatsapp["request_whatsapp"][0];
            ?>
            <div class="alert alert-danger d-flex">
              <?= lang("Login info message") ?>
              <a class="me-1" href="" data-base="https://wa.me/<?= str_replace("+","",$whatsapp) ?>?text="
                    target="_blank" data-text="<?= lang("Have problem with login") ?>" rel="noreferrer nofollower" data-role="have-problem">
                <?= readSVG("icons/whatsapp") ?>
              </a>
            </div>
            <?php
          }
           ?>

        </div>
        <div class="p-40">
          <form action="<?= path_local("auth/action") ?>" method="post">
            <div class="form-group">
              <div class="input-group mb-3">
                <span class="input-group-text bg-transparent"><i class="fa-solid fa-user"></i></span>
                <input type="email" class="form-control ps-15 bg-transparent"
                    name="email" <?= Flash::has("last_email") ? ' value="'.Flash::get("last_email").'"' : "" ?>
                      placeholder="<?= lang("Company email") ?>" oninvalid="this.setCustomValidity('<?= lang("Please fill this input") ?>')" oninput="setCustomValidity('')" required>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group mb-3">
                <span class="input-group-text  bg-transparent"><i class="fa-solid fa-lock"></i></span>
                <input type="password" class="form-control ps-15 bg-transparent" name="password"
                      placeholder="<?= lang("Password") ?>" oninvalid="this.setCustomValidity('<?= lang("Please fill this input") ?>')" oninput="setCustomValidity('')" required>
              </div>

              <?php if ($has_error): ?>
                <?php $no_limit_text = Flash::get("error"); ?>
              <div class="alert alert-danger d-flex justify-content-between align-items-center">
                <p class="m-0"><?=  $no_limit_text ?></p>
                <div class="d-flex align-items-center">
                  <div data-bs-toggle="modal" data-bs-target="#questionLogin">
                    <button class="alert-icon-btn mt-1 me-2" data-toggle="tooltip" data-placement="left" title="<?= lang("Info") ?>" type="button"><i class="fa-solid fa-circle-question"></i></button>
                  </div>
                  <a class="me-1" href="" data-base="https://wa.me/<?= str_replace("+","",$whatsapp) ?>?text="
                        target="_blank" data-text="<?=  $no_limit_text ?>" rel="noreferrer nofollower" data-role="have-no-limit-problem">
                    <?= readSVG("icons/whatsapp") ?>
                  </a>
                </div>
              </div>
              <?php endif; ?>
              <?php if (Flash::has("error_email")): ?>
              <div class="alert alert-danger d-flex justify-content-between align-items-center">
                <p class="m-0"><?=  Flash::get("error_email") ?></p>
              </div>
              <?php endif; ?>
              <?php if (Flash::has("error_password")): ?>
              <div class="alert alert-danger d-flex justify-content-between align-items-center">
                <p class="m-0"><?= Flash::get("error_password")  ?></p>
              </div>
              <?php endif; ?>
            </div>
            <div class="row">
              <?php if(false): ?>
                <div class="col-12">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" id="remember_me">
                    <label for="remember_me"><?= lang("Remember me") ?></label>
                  </div>
                </div>
              <div class="col-6">
                <div class="fog-pwd text-end">
                  <a href="javascript:void(0)" class="hover-warning"><?= lang("Forgot password") ?></a><br>
                </div>
              </div>
              <?php endif; ?>
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary mt-4 wh-100"><?= lang("Sign in") ?></button>
              </div>
            </div>
            <div class="or">
              <span><?= lang("Follow us on social networks") ?></span>
            </div>
            <div class="social-icons justify-content-center">
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
            <hr>
            <div class="row align-items-center mt-4">
              <div class="col-12 d-flex flex-column align-items-center">
                <a href="https://test.loc./?redirect-from=<?= base64_encode("customer-b4b") ?>" class="login-avh-logo mb-2" target="_blank" rel="nofollow noreferrer">
                  <?= readSVG("logo/author") ?>
                </a>
                <p><?= lang("Created by") ?></p>
              </div>
              <?php if(false): ?>
              <div class="col-sm-6 d-none d-lg-flex justify-content-end">
                <a class="hover-warning"><?= lang("What is CRM?") ?></a>
              </div>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>


    <?php if (false): ?>
      <div class="bg-white rounded10 shadow-lg">
          <div class="content-top-agile p-20 pb-0">
              <p class="mb-0 mt-5">Please enter 6 digit otp</p>
          </div>
          <div class="p-40">
              <form action="https://crm-admin-dashboard-template.multipurposethemes.com/hrm/horizontal/main/index.html" method="post">
                  <div class="form-group">
                      <div class="input-group mb-3">
                          <input type="text" class="form-control bg-transparent numeric text-center" maxlength="1" autofocus>
                          <input type="text" class="form-control bg-transparent numeric text-center" maxlength="1" disabled>
                          <input type="text" class="form-control bg-transparent numeric text-center" maxlength="1" disabled>
                          <input type="text" class="form-control bg-transparent numeric text-center" maxlength="1" disabled>
                          <input type="text" class="form-control bg-transparent numeric text-center" maxlength="1" disabled>
                          <input type="text" class="form-control bg-transparent numeric text-center" maxlength="1" disabled>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-12 text-center">
                          <button type="submit" class="btn btn-primary mt-10">DONE!</button>
                      </div>
                  </div>
              </form>
          </div>
      </div>
    <?php endif; ?>
    </div>
  </div>

  <?php if ($has_error): ?>
    <div class="modal fade" id="questionLogin" aria-labelledby="questionLoginLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <img class="modal-body-img" src="<?= assets("image/login-question-modal.png", true) ?>" alt="">
            <p><?= lang("no_limit_text") ?></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang("Close") ?></button>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
