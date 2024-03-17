<?php
  $this->metaDesc = "";
  $this->metaKeys = "";
  $this->page_title = lang("Auto_cataloge");
  $this->load->view("layouts/head");
  $this->load->view("layouts/menu");
?>

<div class="page-container">
	<div class="container">
		<div class="mobile-margin-changer mb-4 mt-5">
			<div class="page-card-header tr-none position-relative">
				<div class="cs_whc_title px-3 py-3 mb-0 d-flex align-items-center justify-content-between">
					<h1><?php echo lang("Auto_cataloge"); ?></h1>
				</div>
			</div>
      <div class="breadcrumb-nav px-4 mt-0 mb-3">
        <li>
          <a href="/">first</a>
        </li>
        <li>
          <a href="">second</a>
        </li>
      </div>
			<div class="page-card-body mobile-p-0 pt-0 d-flex justify-content-center">
				<div class="MenuContent menu-content-page" data-role="main-cataloge">
					<div class="menu-content-inner w-100 d-flex justify-content-between load" data-role="pg-cataloge" data-engine-id="<?php echo $engine; ?>">
						<div class="MenuContentleftbar">
							<ul class="first">
								<li class="menu-content-li" data-id="0">
									<a class="menu-content-a" href="#">
										<p class="d-flex align-items-center m-0 p-0">
											<i class="fas fa-th mr-3"></i> - - - - - - - - - - - - - - - </p>
										<i class="fas fa-chevron-right arrowdown"></i>
									</a>
									<div class="drop-down-leftbar">
										<div class="line">
											<p>
												<strong>- - - - - - - - - - - - - - - -</strong>
												<i class="fas fa-chevron-right"></i>
											</p>
											<div class="drop-down-leftbar-inner">
												<ul>
													<li><a href="#"> - - - - - - - - - -</a> </li>
													<li><a href="#"> - - - - - - - - - -</a> </li>
													<li><a href="#"> - - - - - - - - - -</a> </li>
													<li><a href="#"> - - - - - - - - - -</a> </li>
													<li><a href="#"> - - - - - - - - - -</a> </li>
												</ul>
											</div>
										</div>

									</div>

								</li>
								<li class="menu-content-li mt-2"><a class="menu-content-a" href="#">
										<p> - - - - - - </p>
									</a></li>
								<li class="menu-content-li mt-2"><a class="menu-content-a" href="#">
										<p> - - - - - - </p>
									</a></li>
								<li class="menu-content-li mt-2"><a class="menu-content-a" href="#">
										<p> - - - - - - </p>
									</a></li>
								<li class="menu-content-li mt-2"><a class="menu-content-a" href="#">
										<p> - - - - - - </p>
									</a></li>
								<li class="menu-content-li mt-2"><a class="menu-content-a" href="#">
										<p> - - - - - - </p>
									</a></li>
								<li class="menu-content-li mt-2"><a class="menu-content-a" href="#">
										<p> - - - - - - </p>
									</a></li>
							</ul>
						</div>
						<div class="MenuContentRight">
							<div class="SubContent active" data-menu="part0">
								<div class="SubBox">
									<strong><a href="#">- - - - - - - - - -</a></strong>
									<ul>
										<li> <a href="#">- - - - - - - - - </a> </li>
										<li> <a href="#">- - - - - - - - - </a> </li>
										<li> <a href="#">- - - - - - - - - </a> </li>
										<li> <a href="#">- - - - - - - - - </a> </li>
										<li> <a href="#">- - - - - - - - - </a> </li>
										<li> <a href="#">- - - - - - - - - </a> </li>
										<li> <a href="#">- - - - - - - - - </a> </li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
$this->extraJS .= '<script type="module" src="'.assets("js/brand.js",$this->config->item("is_production")).'"></script>';
$this->load->view("layouts/foot");
?>
