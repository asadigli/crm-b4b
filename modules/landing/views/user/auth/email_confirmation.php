<?php
$this->page_title = $page['title'];
$this->load->view('layouts/head');
$this->load->view('layouts/menu');
 ?>

<section class="page_wrapper py-5">
	<div class="container container-shadow py-5">
		<div class="row px-3">
			<div class="col-5">
				<h5 class="page_tit">
					<?php echo lang('Login'); ?>
				</h5>
				<div class="form-group tb_input_group">
					<i class="far fa-envelope"></i>
					<input type="email" id="st_log_email" class="form-control cs_modal_input" minlength="6"
						placeholder="<?php echo lang('Email'); ?>..." required />
				</div>
				<div class="form-group tb_input_group">
					<i class="far fa-envelope"></i>
					<input type="password" id="st_log_password" class="form-control cs_modal_input" minlength="6"
						autocomplete="off" placeholder="<?php echo lang('Password'); ?>..." required />
					<i></i>
				</div>
				<label class="chck_container my-4"><?php echo lang('Remember_me'); ?>
					<input type="checkbox" checked="checked" value="1" id="log_remember_me">
					<span class="checkmark"></span>
				</label>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<button type="button" id="st_login_btn"
								class="btn btn-default btn-block cs_modal_button"><?php echo lang('Login'); ?></button>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<a data-toggle="modal" data-target="#registrationModal"
								class="btn btn-default btn-block cs_modal_button tb_dark_btn" href="#">Qeydiyyatdan
								keç</a>
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
							<img src="<?php echo assets("img/icons/facebook.svg"); ?>">
						</a>
						<a href="">
							<img src="<?php echo assets("img/icons/google.svg"); ?>">
						</a>
					</div>
				</div>
				<div class="form-group text-center mt-2">
					<p>Şifrənizi unutmusunuz? <a href="#" class="cs_link">Bərpa et</a></p>
				</div>
			</div>
			<div class="col-7">
				<img class="login_illustration" src="<?php echo assets("img/login-car.jpg"); ?>">
			</div>
		</div>
	</div>
</section>

<?php
$this->load->view('layouts/foot');
?>
