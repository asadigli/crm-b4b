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
					<?= lang('Reset_your_password'); ?>
				</h5>
        <?php if ($this->session->flashdata("message")) { echo '<div class="alert alert-'.$this->session->flashdata("type").'">'.$this->session->flashdata("message").'</div>'; } ?>
				<?php if ($this->input->get('token') && !$this->session->flashdata("message")): ?>
          <form action="<?= base_url("change-password"); ?>" method="POST">
            <input type="hidden" name="otp_key" value="<?= $this->input->get('token'); ?>">
            <div class="form-group">
              <input type="password" class="form-control cs_modal_input" minlength="6" name="password"
                placeholder="<?= lang('Password'); ?>..." required />
            </div>
            <div class="form-group">
              <input type="password" class="form-control cs_modal_input" minlength="6" name="confirm_password"
                placeholder="<?= lang('Confirm_password'); ?>..." required />
            </div>
            <div class="row d-flex align-items-center">
              <div class="col-6">
                <div class="form-group">
                  <button type="submit"
                    class="btn btn-default btn-block tb_dark_btn"><?= lang('Next'); ?></button>
                </div>
              </div>
            </div>
          </form>
        <?php elseif(!$this->input->get('token') && $this->session->flashdata("type") !== 'success'): ?>
          <form action="<?= base_url("send-otp"); ?>" method="POST">
            <div class="form-group tb_input_group mb-4">
              <i class="far fa-envelope"></i>
    					<input type="email" class="form-control cs_modal_input" minlength="6" name="email"
    						placeholder="<?= lang('Email'); ?>..." required />
    				</div>
    				<div class="row d-flex align-items-center justify-content-end">
    					<div class="col-6">
    						<div class="form-group">
    							<button type="submit"
                    class="btn btn-default btn-block tb_dark_btn">
                    <?= lang('Next'); ?>
                  </button>
    						</div>
    					</div>
    				</div>
          </form>
        <?php endif; ?>
			</div>
			<div class="col-7 d-flex flex-column justify-content-center">
				<img class="login_illustration" src="<?= assets("img/reset-password.jpg"); ?>">
			</div>
		</div>
	</div>
</section>

<?php $this->load->view('layouts/foot') ?>
