<?php
  $this->page_title = $page['title'];
  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
?>

<section class="wallp-second d-v" style="background-image: url('<?= assets("img/header cover.jpg"); ?>');">
</section>

<!-- PAGETITE START -->
<div class="page-title">
	<div class="container">
		<h1><?= $page['title']; ?></h1>
	</div>
</div>
<!-- PAGETITE END -->

<section class="position-cards">
	<div class="container">
		<div class="row justify-content-center">
			<?php foreach ($contacts as $key => $contact): ?>
			<div class="col-lg-4 col-md-12">
				<div class="card-def">
					<h4><?= lang($contact["position"]); ?></h4>
					<h5><?= lang($contact["person"]); ?></h5>
					<div class="line">
						<p>
							<i class="far fa-building"></i>
							<?= lang("Phone").": ".$contact["tel"]; ?>
						</p>
					</div>
					<div class="line">
						<p>
							<i class="fas fa-phone-alt"></i>
							<?= lang("Phone").": ".$contact["mob"]; ?>
						</p>
					</div>
					<div class="line">
						<p>
							<i class="far fa-envelope"></i>
							<?= lang("Email").": "; ?>
							<a href="mailto:<?= $contact["email"]; ?>?subject=<?= lang($contact["position"]); ?>"
								target="_blank" rel="noreferrer">
								<?= $contact["email"]; ?>
							</a>
						</p>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="map-contact mt-5">
	<div class="nav-contact-box m-v">
		<div class="container d-flex aic justify-content-between">
			<div class="line-center">
				<h4><?= lang("You have a question") ?></h4>

			</div>
			<div class="line-end">
				<div class="map-btn">
					<i class="fas fa-map-marker-alt"></i>
				</div>
			</div>
		</div>
	</div>
	<form action="contact-us/send" method="POST" class="contact-box">
		<h4 class="d-v"><?= lang("You have a question"); ?></h4>
		<div class="form-group">
			<input type="text" name="person_name"
				placeholder="<?= lang("Name") . " " . lang("Surname"); ?>" required>
		</div>
		<div class="form-group">
			<input type="text" name="person_email" placeholder="<?= lang("Email"); ?>" required>
		</div>
		<div class="form-group">
			<input type="text" name="person_number" placeholder="<?= lang("Phone"); ?>">
		</div>
		<div class="form-group">
			<input type="text" name="message_title" placeholder="<?= lang("Title"); ?>" required>
		</div>
		<div class="form-group">
			<textarea name="message_body" placeholder="<?= lang("Your message"); ?>"
				required></textarea>
		</div>
		<div class="form-group">
			<div class="g-recaptcha" data-sitekey="<?= $this->config->item("captcha_site_key") ?>"></div>
		</div>
		<div class="btn-contact d-flex justify-content-center w-100">
			<button class="btn" data-role="send-contact-btn"
				data-error-text="<?= lang("Cannot_be_empty"); ?>"><?= lang("Send") ?></button>
		</div>
		<iframe class="contact-box-ifr ifr"
			src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d2542.602542729277!2d49.87925087780807!3d40.41581798051883!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sSTN%20Plaza%20!5e0!3m2!1sen!2s!4v1624636823813!5m2!1sen!2s"
			allowfullscreen="" loading="lazy"></iframe>
	</form>
	<iframe class="ifr d-v"
			src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d2542.602542729277!2d49.87925087780807!3d40.41581798051883!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sSTN%20Plaza%20!5e0!3m2!1sen!2s!4v1624636823813!5m2!1sen!2s"
			allowfullscreen="" loading="lazy"></iframe>
</section>

<?php $this->load->view('layouts/foot') ?>
