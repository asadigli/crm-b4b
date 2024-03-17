<!DOCTYPE html>
<html lang="<?= $this->config->item("current_language") ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow" />
    <meta name="author" content="Author">
    <link rel="icon" href="<?= assets("image/favicon/logo-for-favicon.png", true); ?>">

    <title><?= $this->page_title ?: "B4B" ?> - <?= $this->config->item("company_name") ?></title>

    <!-- Libs -->
    <link rel="stylesheet" href="<?= assets("css/libs/bootstrap.css", true, true) ?>">
    <link rel="stylesheet" href="<?= assets("css/libs/owl-carousel.css", true, true) ?>">
    <link rel="stylesheet" href="<?= assets("css/libs/owl-theme-default.min.css", true, true) ?>">
    <link rel="stylesheet" href="<?= assets("css/libs/select2.min.css", true, true) ?>">
    <link rel="stylesheet" href="<?= assets("css/libs/sweetalert2.min.css", true, true) ?>">

    <!-- Font -->
    <link rel="stylesheet" href="<?= assets("css/libs/fontawesome/all.min.css", true, true) ?>">
    <link rel="stylesheet" href="<?= assets("fonts/Roboto/stylesheet.css", true, true) ?>">

    <!-- Style-->
    <link rel="stylesheet" href="<?= assets("css/horizontal-menu.css", false, true) ?>">
    <link rel="stylesheet" href="<?= assets("css/style.css", true) ?>">
    <link rel="stylesheet" href="<?= assets("css/skin-color.css", true) ?>">
    <link rel="stylesheet" href="<?= assets("css/color_theme.css", true, true) ?>">
    <link rel="stylesheet" href="<?= assets("css/master.css", true) ?>">
    <link rel="stylesheet" href="<?= assets("css/master.css") ?>">

    <?php if ($this->extraCSS) : ?>
    <?php foreach ($this->extraCSS as $key => $item) : ?>
        <link href="<?= assets($item) ?>" rel="stylesheet">
    <?php endforeach; ?>
    <?php endif; ?>

    <?php if (ENVIRONMENT === "production"): ?>
<!-- Google tag (gtag.js) --> <script async src=https://www.googletagmanager.com/gtag/js?id=></script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'G-ZYH5M0VM9T'); </script>
    <?php endif; ?>
</head>

<body class="layout-top-nav light-skin theme-primary fixed" data-token="<?= md5(Auth::user()) ?>"
      data-acceskey="<?= $this->config->item("ajax_header_key_value") ?>">
    <div class="wrapper">
        <div class="d-none" id="loader"></div>
