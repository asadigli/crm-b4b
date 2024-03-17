<!DOCTYPE html>
<html lang="<?= $this->config->item("current_language") ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?= assets("image/favicon/logo-for-favicon.png", true); ?>">
  <meta name="robots" content="noindex, nofollow" />

  <title><?= $this->page_title ?: "Manager" ?> - <?= $this->config->item("company_name") ?></title>

  <?php $this->view("layouts/head/links"); ?>
  <style media="screen">
    .ci-elapsed_time {
      position: fixed;
      background: #ffffff;
      bottom: 0;
      right: 0;
      box-shadow: 0 3px 8px 2px #c3c3c3;
      padding: 3px 19px 3px 19px;
      z-index: 1;
    }
  </style>

      <?php if (ENVIRONMENT === "production"): ?>
        <!-- Google tag (gtag.js) -->
        <script async src=https://www.googletagmanager.com/gtag/js?id=></script>
        <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'G-QZ38271W86'); </script>
      <?php endif; ?>

</head>

<body class="layout-top-nav light-skin theme-primary fixed"  data-acceskey="<?= $this->config->item("ajax-header-key") ?>">

  <div class="ci-elapsed_time" title="<?= ENVIRONMENT ?>">
    {elapsed_time}
  </div>
  <div class="wrapper">
    <div id="loader"></div>

    <header class="main-header">
      <div class="inside-header d-flex justify-content-between row">
        <div class="d-flex align-items-center logo-box justify-content-start col-6">
          <!-- Logo -->
          <a href="<?= path_local("home") ?>" class="logo">
            <div class="logo-lg">
                <span class="light-logo"><?= readSVG("logo/customer") ?></span>
            </div>
          </a>
        </div>
        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top justify-content-end col-6">
          <div class="navbar-custom-menu r-side">
            <ul class="nav navbar-nav">
              <!-- User Account-->
              <div class="user-box">
                <div class="user-box-main-side top p-0">
                  <div class="user-box-content">
                    <span><?= Auth::fullname() ?></span>
                  </div>
                  <img src="<?= assets("image/avatar-user-png.png", true) ?>" alt="">
                </div>
                <div class="user-box-dropdown">
                  <div class="user-box-main-side bottom">
                    <div class="user-box-content">
                      <h6><?= Auth::name() ?></h6>
                      <span><?= Auth::email() ?></span>
                      <span><?=  lang(Auth::role()) ?></span>
                    </div>
                  </div>
                  <div class="user-box-list">
                    <a href="<?= path_local("profile") ?>">
                      <i class="fa-regular fa-user"></i>
                      <?= lang("My account") ?>
                    </a>
                    <a href="<?= path_local("auth/logout") ?>">
                      <i class="fa-solid fa-arrow-right-from-bracket"></i>
                      <?= lang("Logout") ?>
                    </a>
                  </div>
                </div>
              </div>
            </ul>
          </div>
        </nav>
      </div>
    </header>

    <!-- Modal loader -->
    <div class="modal-loader">
        <div class="inner">
            <img src="<?= assets("image/gif/loading-screen.gif", true) ?>" alt="<?= lang("Loading") ?>">
            <span><?= lang("Loading") ?></span>
        </div>
        <div class="modal-loader-overlay"></div>
    </div>
    <!-- Modal loader -->

    <button class="scroll-top btn btn-primary p-0"><i class="fas fa-arrow-up"></i></button>


    <?php $this->load->view("layouts/navbar"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <div class="container-full">
        <!-- Main content -->
        <section class="content">
