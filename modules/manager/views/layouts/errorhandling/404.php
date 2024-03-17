<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?= assets("image/favicon/logo-for-favicon.png", true); ?>">

    <title><?= $this->config->item("company_name") ?> - Error</title>

    <!-- Libs -->
    <link rel="stylesheet" href="<?= assets("css/libs/bootstrap.css", true) ?>">

    <!-- Font -->
    <link rel="stylesheet" href="<?= assets("fonts/Roboto/stylesheet.css", true) ?>">

    <!-- Style-->
    <link rel="stylesheet" href="<?= assets("css/style.css", true) ?>">
    <link rel="stylesheet" href="<?= assets("css/skin-color.css", true) ?>">
    <link rel="stylesheet" href="<?= assets("css/color_theme.css", true) ?>">
    <link rel="stylesheet" href="<?= assets("css/master.css", true) ?>">
  </head>
  <body class="hold-transition theme-primary bg-img" style="background-image: url(https://crm-admin-dashboard-template.multipurposethemes.com/images/auth-bg/bg-4.jpg)">

    <section class="error-page h-p100">
      <div class="container h-p100">
        <div class="row h-p100 align-items-center justify-content-center text-center">
          <div class="col-lg-7 col-md-10 col-12">
            <div class="rounded10 p-50">
              <img src="https://crm-admin-dashboard-template.multipurposethemes.com/images/auth-bg/404.jpg" class="max-w-200" alt="">
              <h1><?= lang("404_not_found") ?></h1>
              <?php if (false): ?>
                <h3><?= lang("404_not_found_text") ?></h3>
              <?php endif; ?>
              <div class="my-30"><a href="<?= path_local() ?>" class="btn btn-primary"><?= lang("Back to dashboard") ?></a></div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </body>
</html>
