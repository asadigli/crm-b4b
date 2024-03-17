<!DOCTYPE html>
<html lang="<?= $this->local; ?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0,shrink-to-fit=no , maximum-scale=1">
	<meta name="description" content="<?= $this->metaDesc ?: lang("Seo_home_description"); ?>">
	<meta name="keywords" content="<?= $this->metaKeys ?: lang("Seo_home_keywords"); ?>">
	<meta name="author" content="<?= $this->config->item('project_name'); ?>">
	<meta property="og:type" content="<?= $this->pageType ?: ""; ?>">
	<meta name="robots" content="INDEX,FOLLOW">
	<?php if (false): ?>
		<!-- <meta property="fb:page_id" content=""> -->
	<?php endif; ?>
	<meta property="og:site_name" content="<?= $this->config->item('project_name'); ?>">
	<meta property="og:title" content="<?= $this->page_title ?: $this->config->item('project_name'); ?>">
	<meta property="og:description" content="<?= $this->metaDesc ?: lang("Seo_home_description"); ?>">
	<meta property="og:image:secure_url" itemprop="image"
		content="<?= assets('img/logo/customer-og-image.png'); ?>">
	<meta property="og:image" itemprop="image" content="<?= $this->OG_image_alt ?: assets('img/logo/customer-og-image.png'); ?>">
	<meta property="og:image:type" content="image/png">
	<meta property="og:type" content="website">
	<meta property="og:image:width" content="300">
	<meta property="og:image:height" content="300">
	<meta property="og:image:alt" content="<?= $this->OG_image_alt ?: assets('img/logo/customer-og-image.png'); ?>">
	<meta property="og:url" content="<?= path_local(); ?>">

	<title>
		<?= ($this->page_title ? $this->page_title.' | ' : '') . $this->config->item('project_name'); ?>
	</title>
	<link rel="icon" href="<?= assets('img/favicon/logo-for-favicon.png'); ?>">

	<meta name="theme-color" content="#ffffff">

	<link href="<?= assets('vendor/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
	<link href="<?= assets('css/libs/owl.theme.default.css'); ?>" rel="stylesheet">
	<link href="<?= assets('css/fontawesome-all.css'); ?>" rel="stylesheet">
	<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
	<link href="<?= assets('css/master.css',$this->config->item("is_production")); ?>" rel="stylesheet">
	<link href="<?= assets('css/media.css',$this->config->item("is_production")); ?>" rel="stylesheet">
	<?php if ($this->admin): ?>
		<link href="<?= assets('css/admin.css',$this->config->item("is_production")); ?>" rel="stylesheet">
	<?php endif; ?>
	<!-- FONT -->
	<link href="<?= assets('fonts/Montserrat/stylesheet.css'); ?>" rel="stylesheet">
	<link href="<?= assets('fonts/Helvetica/stylesheet.css'); ?>" rel="stylesheet">

	<?php if ($this->config->item("is_production")): ?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id="></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'G-X651B85DD2');
		</script>
	<?php endif; ?>

	<?= $this->headCSS; ?>
</head>

<body class="preload<?= $this->admin ? " admin-line-visible" : "" ?> cs_gray_bg<?= $this->session->userdata("token") ? " logged_in" : "";  ?>"
	<?= in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1','::1']) ? " data-localhost='".path_local()."'" : ""; ?>
	data-ajaxKey="<?= $this->config->item("headerKey"); ?>"
	data-v="<?= $this->config->item("system_version_int"); ?>">

	<!-- Pop up start -->
	<?php $this->load->view('layouts/form'); ?>
	<!-- Pop up end -->
