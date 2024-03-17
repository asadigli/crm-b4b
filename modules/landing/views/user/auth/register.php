<?php
if (!isset($_SERVER['HTTP_BONLY']) || !$_SERVER['HTTP_BONLY']) {
  $this->page_title = $page['title'];
  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
}else{
  echo "<input type='hidden' value='".$page['title']." | ".$this->config->item('project_name')."' data-role='hidden-title'>";
}
?>

<div id="registerPg" data-role="live-container">
	<section class="page_wrapper py-5">
		<div class="container container-shadow py-5">
			<div class="row px-3">
				<div class="col-md-5">
					<h5 class="page_tit"><?= lang('Register'); ?></h5>
					<?php $red = $this->input->get('redirect') ? '?redirect='.$this->input->get('redirect') : '';
        		if ($this->input->get('redirect')){
              echo '<input type="hidden" id="redirect" value="'.$this->input->get('redirect').'">';
            } ?>
					<div class="form-group tb_input_group">
						<i class="fas fa-user"></i>
						<input type="text" name="password" id="reg_name" class="form-control" minlength="6"
                    data-error="<?= lang("Valid name"); ?>"
							       autocomplete="off" placeholder="<?= lang('Name'); ?>..." required="">
            <small class="text-danger d-none"></small>
					</div>
					<div class="form-group tb_input_group">
						<i class="fas fa-user"></i>
						<input type="text" name="password" id="reg_surname" class="form-control" minlength="6"
                    data-error="<?= lang("Valid surname"); ?>"
							       autocomplete="off" placeholder="<?= lang('Surname'); ?>..." required>
            <small class="text-danger d-none"></small>
					</div>
					<div class="form-group tb_input_group">
						<i class="far fa-envelope"></i>
						<input type="email" name="email" id="reg_email" class="form-control" minlength="6"
                    data-error="<?= lang("Enter valid email input"); ?>"
							       placeholder="<?= lang('Email'); ?>..." required>
            <small class="text-danger d-none"></small>
					</div>
					<!-- <div class="form-group">
					<input type="date" id="reg_birthdate" class="form-control cs_modal_input" minlength="6"
						placeholder="<?= lang('Birthdate'); ?>..." required />
				</div> -->
					<div class="form-group tb_input_group">
						<i class="fas fa-lock"></i>
						<input type="password" name="password" id="reg_password" class="form-control" minlength="6"
							       autocomplete="off" placeholder="<?= lang('Password'); ?>..."
                      data-error="<?= lang("Enter password"); ?>" required>
            <small class="text-danger d-none"></small>
					</div>

					<div class="form-group tb_input_group">
						<i class="fas fa-lock"></i>
						<input type="password" name="password" id="reg_cpassword" class="form-control" minlength="6"
                    data-error="<?= lang("Password not unmatched"); ?>"
							       autocomplete="off" placeholder="<?= lang('Confirm_password'); ?>..."
                      required>
            <small class="text-danger d-none"></small>
					</div>
					<div class="form-group">
						<button type="button" id="register_btn" disabled
							class="def-btn w-100"><?= lang('Register'); ?></button>
					</div>
          <a href="<?= path_local("sign-in"); ?>" data-role="live-routing"><?= lang("Go back to login"); ?></a>
					<!-- <div class="form-group text-center">
					<script src="//ulogin.ru/js/ulogin.js"></script>
					<div id="st_uLogin00e009fc"
						data-ulogin="display=panel;fields=first_name,last_name,email;optional=phone,city,country;theme=flat;providers=facebook,google;redirect_uri=">
					</div>
				</div> -->
				</div>
				<div class="col-md-7 d-md-flex align-items-center d-none">
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
