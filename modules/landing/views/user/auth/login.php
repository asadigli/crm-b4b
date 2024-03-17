<?php
if (!isset($_SERVER['HTTP_BONLY']) || !$_SERVER['HTTP_BONLY']) {
  $this->page_title = $page['title'];
  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
}else{
  echo "<input type='hidden' value='".$page['title']." | ".$this->config->item('project_name')."' data-role='hidden-title'>";
}
?>

<div id="loginPg" data-role="live-container">
	<section class="page_wrapper py-5">
		<div class="container container-shadow py-5">
			<div class="row">
				<div class="col-md-6 col-12">
					<h5 class="page_tit"><?= lang('Login'); ?></h5>
					<div class="alert d-none" role="alert"></div>
					<?php if ($this->input->get('redirect')){ echo '<input type="hidden" id="redirect" value="'.$this->input->get('redirect').'">'; } ?>
					<div class="form-group tb_input_group">
						<i class="far fa-envelope"></i>
						<input type="email" name="email" id="st_log_email" class="form-control" minlength="6"
                    data-error="<?= lang("Enter valid email input"); ?>"
							       placeholder="<?= lang('Email'); ?>..." required />
            <small class="text-danger d-none"></small>
					</div>
					<div class="form-group tb_input_group">
						<i class="fas fa-lock"></i>
						<input type="password" name="password" id="st_log_password" class="form-control" minlength="6"
                    data-error="<?= lang("Enter password"); ?>"
                      autocomplete="off" placeholder="<?= lang('Password'); ?>..." required />
            <small class="text-danger d-none"></small>
					</div>
					<div class="cont-links d-flex align-items-center mb-3">
						<label class="chck_container m-0"><?= lang('Remember_me'); ?>
							<input type="checkbox" checked="checked" value="1" id="st_log_remember_me">
							<span class="checkmark"></span>
						</label>
						<a href="<?= path_local("authorization/password-reset"); ?>" class="reset-link">
							<p><?= lang('Reset_password'); ?></p>
							<img src="<?= assets("img/icons/right-arrow.svg"); ?>">
						</a>
					</div>
					<div class="row d-flex align-items-center">
						<div class="col-6 pr-0">
							<div class="form-group pr-1">
								<button type="button" id="st_login_btn" disabled class="btn btn-default btn-block def-btn"><?= lang('Login'); ?></button>
							</div>
						</div>
						<div class="col-6 pl-0">
							<div class="form-group pl-1">
								<a href="<?php
              		$red = $this->input->get('redirect') ? '?redirect='.$this->input->get('redirect') : '';
              		echo path_local('sign-up').$red; ?>" class="btn btn-block def-btn" data-role="live-routing">
									<?= lang('Register'); ?>
								</a>
							</div>
						</div>
					</div>
					<div class="form-group text-center">
						<!-- <script src="//ulogin.ru/js/ulogin.js"></script>
					<div id="st_uLogin00e009fc"
						data-ulogin="display=panel;fields=first_name,last_name,email;optional=phone,city,country;theme=flat;providers=facebook,google;redirect_uri=">
					</div> -->
						<div class="social-icons-login">
							<a href="">
								<img src="<?= assets("img/icons/facebook.svg"); ?>">
							</a>
							<a href="">
								<img src="<?= assets("img/icons/google.svg"); ?>">
							</a>
						</div>
					</div>
					<!-- <div class="form-group text-center mt-2">
					<p>Şifrənizi unutmusunuz? <a href="" class="cs_link">Bərpa et</a>
				</p>
				</div> -->
				</div>
				<div class="col-md-6 d-md-flex d-none flex-column justify-content-center">
					<img class="login_illustration" src="<?= assets("img/login-car.jpg"); ?>">
				</div>
			</div>
		</div>
	</section>
</div>

<?php
if (!isset($_SERVER['HTTP_BONLY']) || !$_SERVER['HTTP_BONLY']) {
  $this->extraJS .= '<script type="module" src="'.assets("js/auth.js",$this->config->item("is_production")).'"></script>';
  $this->load->view('layouts/foot');
}
?>
