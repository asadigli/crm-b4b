
  <link rel="stylesheet" href="<?= assets("css/libs/bootstrap.css", true, true) ?>">
  <link rel="stylesheet" href="<?= assets("css/libs/chartist.css", true, true) ?>">
  <link rel="stylesheet" href="<?= assets("css/libs/owl-carousel.css", true, true) ?>">
  <link rel="stylesheet" href="<?= assets("css/libs/owl-theme-default.min.css", true, true) ?>">
  <link rel="stylesheet" href="<?= assets("css/libs/select2.min.css", true, true) ?>">
  <link rel="stylesheet" href="<?= assets("css/libs/sweetalert2.min.css", true, true) ?>">

  <!-- Fontawesome -->
  <link rel="stylesheet" href="<?= assets("css/libs/fontawesome/all.min.css", true, true) ?>">
  <link rel="stylesheet" href="<?= assets("fonts/Roboto/stylesheet.css", true, true) ?>">

  <!-- Style-->
  <link rel="stylesheet" href="<?= assets("css/horizontal-menu.css", false, true) ?>">
  <link rel="stylesheet" href="<?= assets("css/style.css", false, true) ?>">
  <link rel="stylesheet" href="<?= assets("css/skin_color.css", false, true) ?>">
  <link rel="stylesheet" href="<?= assets("css/color_theme.css", true, true) ?>">


  <link rel="stylesheet" href="<?= assets("css/master.css", true) ?>">
  <link rel="stylesheet" href="<?= assets("css/master.css") ?>">


<?php if ($this->extraCSS) : ?>
  <?php foreach ($this->extraCSS as $key => $item) : ?>
    <link href="<?= assets($item) ?>" rel="stylesheet">
  <?php endforeach; ?>
<?php endif; ?>
