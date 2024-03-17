<!DOCTYPE html>
<html lang="<?= $this->local; ?>">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0,shrink-to-fit=no , maximum-scale=1">
	<meta name="description" content="<?= $this->metaDesc; ?>">
	<meta name="keywords" content="<?= $this->metaKeys; ?>">
	<meta name="author" content="<?= $this->config->item('project_name'); ?>">
	<meta property="”og:type”" content="<?= $this->pageType; ?>">
	<!-- <meta property="fb:page_id" content=""> -->
	<meta property="”og:site_name”" content="<?= $this->config->item('project_name'); ?>">
	<meta property="og:title" content="<?= $this->page_title; ?>">
	<meta property="og:description" content="<?= $this->metaDesc; ?>">
	<meta property="og:image:secure_url" itemprop="image" content="<?= assets('img/logo/customer-logo-mini.png'); ?>">
	<meta property="og:image" itemprop="image" content="<?= assets('img/logo/customer-logo-mini.png'); ?>">
	<meta property="og:image:type" content="image/png">
	<meta property="og:type" content="website">
	<meta property="og:image:width" content="300">
	<meta property="og:image:height" content="300">
	<meta property="og:image:alt" content="<?= $this->metaDesc; ?>">
	<meta property="og:url" content="<?= path_local(); ?>">

	<title><?= isset($this->page_title) ? $this->page_title.' | ' : ''; echo $this->config->item('project_name'); ?></title>
	<link rel="icon" href="<?= assets('img/favicon/logo-for-favicon-admin.png'); ?>">

	<meta name="theme-color" content="#ffffff">

	<link href="<?= assets('vendor/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
	<link href="<?= assets('css/master.css',$this->config->item("is_production")); ?>" rel="stylesheet">
	<link href="<?= assets('css/admin.css',$this->config->item("is_production")); ?>" rel="stylesheet">
	<link href="<?= assets('css/media.css',$this->config->item("is_production")); ?>" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
	<link href="<?= assets('css/fontawesome-all.css'); ?>"
		rel="stylesheet">
	<link href="<?= assets('css/libs/jquery.fancybox.css'); ?>" rel="stylesheet">
	<link href="<?= assets('css/libs/bootstrap-tagsinput.css'); ?>" rel="stylesheet">
	<!-- https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css -->
	<link href="<?= assets('css/libs/select2.min.css'); ?>" rel="stylesheet" />
	<style>
		.select2-results__group{
			color: #a0a0a0;
		}
	</style>

	<?= $this->headCSS; ?>
</head>

<body class="preload cs_gray_bg<?= $this->session->userdata("token") ? " logged_in" : "";  ?>"
	<?= in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1','::1']) ? " data-localhost='".path_local()."'" : ""; ?>
		data-ajaxKey="<?= $this->config->item("headerKey"); ?>">
