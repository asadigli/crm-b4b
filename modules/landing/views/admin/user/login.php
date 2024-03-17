<?php
  $this->page_title = $title;
  $this->load->view('layouts/admin/head');
?>

<div class="login-box">
	<div class="left-side side" style="background-image: url('<?= assets('img/gif/7443a31b6a19e5630b94a6538ff9bdd1.gif'); ?>"></div>
	<div class="right-side side">
		<div class="login-box-cont">
			<h1><?= lang("Login") ?></h1>
      <?php if ($this->session->flashdata("message")): ?>
        <div class="alert alert-danger" role="alert">
          <?= $this->session->flashdata("message"); ?>
        </div>
      <?php endif; ?>
			<form action="<?= base_url("sign-in-action"); ?>" method="POST">
        <input type="hidden" name="redirect" value="<?= $this->input->get("redirect"); ?>">
				<div class="form-group">
					<label for="email"><?= lang("Email"); ?></label>
					<input type="email" name="email" placeholder="<?= lang("Email"); ?>">
				</div>
				<div class="form-group">
					<label for="password"><?= lang("Password"); ?></label>
					<input type="password" name="password" placeholder="<?= lang("Password"); ?>">
				</div>
				<div class="d-flex justify-content-end">
					<button type="submit" class="btn"><?= lang("Login") ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php
$this->load->view('layouts/admin/foot')
?>
