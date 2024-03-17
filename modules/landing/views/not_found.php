<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?= assets("css/error_handling.css",$this->config->item("is_production")); ?>" />
	<link rel="stylesheet" href="<?= assets("css/master.css",$this->config->item("is_production")); ?>" />
	<link href="<?= assets('css/fontawesome-all.css'); ?>" rel="stylesheet">


	<!-- FONT -->
		<link rel="apple-touch-icon" sizes="57x57"
			href="<?= assets('img/favicon/apple-icon-57x57.png'); ?>">
		<link rel="apple-touch-icon" sizes="60x60"
			href="<?= assets('img/favicon/apple-icon-60x60.png'); ?>">
		<link rel="apple-touch-icon" sizes="72x72"
			href="<?= assets('img/favicon/apple-icon-72x72.png'); ?>">
		<link rel="apple-touch-icon" sizes="76x76"
			href="<?= assets('img/favicon/apple-icon-76x76.png'); ?>">
		<link rel="apple-touch-icon" sizes="114x114"
			href="<?= assets('img/favicon/apple-icon-114x114.png'); ?>">
		<link rel="apple-touch-icon" sizes="120x120"
			href="<?= assets('img/favicon/apple-icon-120x120.png'); ?>">
		<link rel="apple-touch-icon" sizes="144x144"
			href="<?= assets('img/favicon/apple-icon-144x144.png'); ?>">
		<link rel="apple-touch-icon" sizes="152x152"
			href="<?= assets('img/favicon/apple-icon-152x152.png'); ?>">
		<link rel="apple-touch-icon" sizes="180x180"
			href="<?= assets('img/favicon/apple-icon-180x180.png'); ?>">
		<link rel="icon" type="image/png" sizes="192x192"
			href="<?= assets('img/favicon/android-icon-192x192.png'); ?>">
		<link rel="icon" type="image/png" sizes="32x32"
			href="<?= assets('img/favicon/favicon-32x32.png'); ?>">
		<link rel="icon" type="image/png" sizes="96x96"
			href="<?= assets('img/favicon/favicon-96x96.png'); ?>">
		<link rel="icon" type="image/png" sizes="16x16"
			href="<?= assets('img/favicon/favicon-16x16.png'); ?>">
		<link rel="manifest" href="<?= assets('img/favicon/manifest.json'); ?>">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage"
			content="<?= assets('img/favicon/ms-icon-144x144.png'); ?>">
		<meta name="theme-color" content="#ffffff">
	<title><?= $this->session->flashdata("error_mess"); ?></title>
</head>

<body class="bg-nf">
	<div id="error-section">
		<div class="container">
			<div class="inner">
				<!-- LEFT -->
				<div class="inner-left">
					<?php if ($this->session->flashdata("error_code") >= 500): ?>
						<h1 class="five">
							<?= $this->session->flashdata("error_code"); ?>
						</h1>
					<?php else: ?>
						<h1 class="four">
							<?= $this->session->flashdata("error_code") ?: "400"; ?>
						</h1>
					<?php endif; ?>
					<p class="text-capitalize">
						<?= $this->session->flashdata("error_mess") ?: "Page is not found"; ?>
					</p>
					<a href="<?= $this->session->flashdata("error_prev"); ?>" class="def-btn">
						<i class="fas fa-arrow-left"></i>
						<?= lang('Go_to_back'); ?>
					</a>
				</div>
				<!-- RIGHT -->
				<div class="inner-right">
					<img src="<?= assets('img/error.svg'); ?>" alt="ERROR">
				</div>
			</div>
		</div>
	</div>
	<?php
	$this->session->set_flashdata("error_mess",null);
	$this->session->set_flashdata("error_code",null);
	$this->session->set_flashdata("error_prev",null);
	 ?>
</body>

</html>
