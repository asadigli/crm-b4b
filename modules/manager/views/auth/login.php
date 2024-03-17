<div class="login-overlay"></div>
<div class="container h-p100">
  <div class="row justify-content-center align-items-center g-0 h-100vh">
    <div class="col-lg-4 col-md-5 col-12 login-box">
      <div class="bg-white rounded10 shadow-lg">
        <div class="content-top-agile p-20 pb-0">
          <a href="https://customer.com/" target="_blank" rel="nofollow noreferrer" class="login-logo">
            <?= readSVG("logo/customer") ?>
          </a>
          <p class="mb-0"><?= lang("Please sign in to continue") ?></p>
        </div>
        <div class="p-40">
          <form action="<?= path_local("auth/login") ?>" method="POST">
            <div class="form-group">
              <div class="input-group mb-3">
                <span class="input-group-text bg-transparent"><i class="fa-solid fa-user"></i></span>
                <input type="email" class="form-control ps-15 bg-transparent" name="email" placeholder="<?= lang("User email") ?>">
              </div>
            </div>
            <div class="form-group">
              <div class="input-group mb-3">
                <span class="input-group-text  bg-transparent"><i class="fa-solid fa-lock"></i></span>
                <input type="password" class="form-control ps-15 bg-transparent" name="password" placeholder="<?= lang("Password") ?>">
              </div>
              <?php if (Flash::has("error")): ?>
                <p class="alert alert-danger"><?= Flash::get("error")  ?></p>
              <?php endif; ?>
              <?php if (Flash::has("error_email")): ?>
                <p class="alert alert-danger"><?=  Flash::get("error_email")  ?></p>
              <?php endif; ?>
              <?php if (Flash::has("error_password")): ?>
                <p class="alert alert-danger"><?=  Flash::get("error_password") ?></p>
              <?php endif; ?>
            </div>
            <div class="row">
              <?php if(false): ?>
                <div class="col-12 d-flex justify-content-end">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" id="basic_checkbox_1">
                    <label for="basic_checkbox_1"><?= lang("Remember me") ?></label>
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
              <a href="https://www.linkedin.com/company" target="_blank" rel="noreferrer">
                  <i class="fa-brands fa-linkedin-in"></i>
              </a>
            </div>
            <hr>
            <div class="row align-items-center mt-4">
              <div class="col-12 d-flex flex-column align-items-center">
                <a href="https://author.com/" class="login-avh-logo mb-2" target="_blank" rel="nofollow noreferrer">
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
</div>
