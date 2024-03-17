<div class="page-container">
	<!-- HEADER START -->
	<header>
		<?php $this->load->view('layouts/admin-header'); ?>
		<div class="top-side-header">
			<div class="container-sm">
				<div class="row w-100 m-0 justify-content-center align-items-center">
					<div class="col-md-9 col-12 d-flex align-items-center justify-content-center">
						<p class=" d-v"><?= lang("For more information"); ?></p>
						<span class="mini-divider d-v"></span>
						<p><?= $this->config->item("company phone number"); ?></p>
						<a class="wp-head-link"
							href="https://wa.me/<?= $this->config->item("only company phone number"); ?>"
							target="_blank" rel="noreferrer"><?= lang("Whatsapp contact"); ?></a>
					</div>
					<div class="col-md-3 col-12">
						<div class="social-icons">
							<a class="a-fb" href="https://www.facebook.com/" target="_blank" rel="noreferrer">
								<?= readSVG("icons/social/facebook") ?>
							</a>
							<a class="a-insta" href="https://www.instagram.com/" target="_blank" rel="noreferrer">
								<?= readSVG("icons/social/instagram") ?>
							</a>
							<a class="a-insta" href="https://www.linkedin.com/company" target="_blank" rel="noreferrer">
								<?= readSVG("icons/social/linkedin") ?>
							</a>

						</div>
					</div>
				</div>
			</div>
			<div class="lang-c">
				<!-- Language Select -->
				<?php $this->load->view("layouts/langs") ?>
			</div>
		</div>
		<div class="center-side-header" style="background-image: url('<?= assets("img/header cover.jpg"); ?>');">
			<div class="container d-flex align-items-center justify-content-between">
				<a href="<?= path_local(); ?>" class="logo-customer">
					<?= readSVG("logo/customer") ?>
					<span><?= lang("Customer text 42") ?></span>
				</a>
				<a href="<?= path_local(); ?>" class="logo-customer-m">
					<?= readSVG("logo/mobile-logo") ?>
				</a>
				<div class="menu-button m-v">
					<?= readSVG("icons/menu-button") ?>
				</div>
				<button class="def-btn popBtn" data-id="be-partner"><?= lang("Want to be a partner"); ?></button>
				<div class="header-second-logo">
					<img src="<?= assets("img/logo/customer & outo.png"); ?>" alt="">
				</div>
			</div>
		</div>
		<div class="bottom-side-header">
			<div class="container">
				<div class="row justify-content-between align-items-center">
					<div class="col-md-9 col-8">
						<ul class="nav--bar d-flex">
							<?php $menu_list = ["search" => "Products","brand-list" => "Brands","certificates" => "Certificates","news-list" => "News","promotions" => "Promotions","about" => "About_us","contact" => "Contact"];
							foreach (array_keys($menu_list) as $key => $item){
								 echo '<li'.(base_url(uri_string()) === path_local($item) ? ' class="active"' : "").'><a href="'.path_local($item).'">'.lang($menu_list[$item]).'</a></li>';
							 } ?>
						</ul>
					</div>
					<div class="col-md-3 col-4 pr-0 d-flex justify-content-end">
						<a href="https://test.loc" target="_blank" rel="noreferrer" class="def-btn">
							B4B <?= lang("Entry") ?>
						</a>
					</div>
				</div>
			</div>
		</div>
		<!-- MENU START -->
		<div class="menu">
			<div class="menu-inner">
				<a href="https://b4b.avtohisse.com/" target="_blank" rel="noreferrer" class="menu-inner-b4b">B4B
					<?= lang("Entry") ?></a>
				<ul>
					<li><a href="<?= path_local("news-list"); ?>"><?= lang("News") ?></a></li>
					<li><a
							href="<?= path_local("promotions"); ?>"><?= lang("Promotions") ?></a>
					</li>
					<li><a href="<?= path_local("about"); ?>"><?= lang("About_us") ?></a></li>
					<li><a href="<?= path_local("brand-list"); ?>"><?= lang("Brands") ?></a>
					</li>
					<li><a href="<?= path_local("search"); ?>"><?= lang("Products") ?></a></li>
					<li><a href="<?= path_local("contact"); ?>"><?= lang("Contact") ?></a></li>
					<li class="active"><a
							href="<?= path_local("certificates"); ?>"><?= lang("Certificates") ?></a>
					</li>
				</ul>
				<?php if(false): ?>
				<div class="menu-footer">
					<div class="line">
						<span><?= lang("Phone") ?></span>
						<p><?= $this->config->item("company phone number"); ?></p>
					</div>
					<div class="line">
						<span><?= lang("Email") ?></span>
						<p><?= $this->config->item("company email"); ?></p>
					</div>
				</div>
				<?php endif; ?>
				<div class="menu-lang">
					<a href="<?= langSwitcher(uri_string(),'az'); ?>"<?= $this->local === 'az' ? ' class="active"' : ''; ?>>Az</a>
					<a href="<?= langSwitcher(uri_string(),'en'); ?>"<?= $this->local === 'en' ? ' class="active"' : ''; ?>>EN</a>
					<a href="<?= langSwitcher(uri_string(),'ru'); ?>"<?= $this->local === 'ru' ? ' class="active"' : ''; ?>>Ru</a>
					<a href="<?= langSwitcher(uri_string(),'tr'); ?>"<?= $this->local === 'tr' ? ' class="active"' : ''; ?>>Tr</a>
				</div>
			</div>
		</div>
		<!-- MENU END -->
	</header>
	<!-- HEADER END -->
