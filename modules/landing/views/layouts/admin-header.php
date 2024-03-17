<?php if ($this->admin): ?>
	<div class="showbox">
	    <div class="loader">
	        <svg class="circular" viewBox="25 25 50 50">
	            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="1.5" stroke-miterlimit="10" />
	        </svg>
	        <svg class="circular" viewBox="25 25 50 50">
	            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
	        </svg>
	    </div>
	</div>

<div class="top-line">
	<div class="container-fluid">
		<div class="nav-line">
			<div class="menu-button-top-line">
				<svg viewBox="0 0 900 900">
					<g id="dashes">
						<path class="menu_icon_dash" d="M145 609l609 0c74,0 74,111 0,111l-609 0c-74,0 -74,-111 0,-111z"
							id="dash_bottom">
						</path>
						<path class="menu_icon_dash"
							d="M146 394c203,0 406,0 609,0 74,0 74,111 0,111 -203,0 -406,0 -609,0 -74,0 -74,-111 0,-111z"
							id="dash_middle"></path>
						<path class="menu_icon_dash" d="M146 179l609 0c74,0 74,111 0,111l-609 0c-74,0 -74,-111 0,-111z"
							id="dash_top">
						</path>
					</g>
				</svg>
			</div>
			<ul class="main-ul" data-role="admin-header-list">
				<li class="list border-right">
					<a href="<?= path_local() ?>" class="list-a">
						<i class="fas fa-home"></i>
					</a>
				</li>

				<li class="list border-right">
					<a href="<?= path_local("admin/user/list"); ?>"
						class="list-a"><?= lang("Users"); ?></a>
				</li>
				<li class="list border-right">
					<a class="list-a" href="<?= path_local("admin/dashboard"); ?>"><?= lang("Admin Dashboard"); ?></a>
				</li>
				<li class="list border-right">
					<p class="list-p"><?= lang("Product"); ?></p>
					<em class="fas fa-chevron-down"></em>
					<ul class="list-child">
						<li>
							<a href="<?= path_local("admin/home-page/products"); ?>"><?= lang("Home_page_products"); ?>
							</a>
						</li>
						<li>
							<a href="<?= path_local("admin/product/list"); ?>"><?= lang("Product list"); ?>
							</a>
						</li>
						<li class="border-bottom">
							<a href="<?= path_local("admin/product/add"); ?>"><?= lang("Add new product"); ?>
							</a>
						</li>
						<li class="border-bottom">
							<a href="<?= path_local("admin/product/categories"); ?>"><?= lang("Category"); ?>
							</a>
						</li>
						<li class="list-inner-child">
							<p><?= lang("Brand"); ?></p>
							<em class="fas fa-chevron-down"></em>
							<ul class="list-i-child">
								<li><a href="<?= path_local("admin/brand/list"); ?>"><?= lang("Brand list"); ?></a>
								</li>
								<li><a href="<?= path_local("admin/brand/add"); ?>"><?= lang("Add new brand"); ?></a>
								</li>
							</ul>
						</li>
					</ul>
				</li>

				<li class="list border-right">
					<p class="list-p"><?= lang("Configurations"); ?></p>
					<em class="fas fa-chevron-down"></em>
					<ul class="list-child">
						<li>
							<a href="<?= path_local("admin/configurations/about_faq"); ?>"><?= lang("About & FAQ"); ?></a>
						</li>
						<!-- <li>
							<a href="<?= path_local("admin/configurations/update-footer"); ?>"><?= lang("Category control"); ?></a>
						</li>
						<li>
							<a href="<?= path_local("admin/configurations/update-footer"); ?>"><?= lang("General control"); ?></a>
						</li> -->
					</ul>
				</li>

				<li class="list border-right">
					<p class="list-p"><?= lang("News & Promotions"); ?></p>
					<em class="fas fa-chevron-down"></em>
					<ul class="list-child">
						<li>
							<a href="<?= path_local("admin/news/list"); ?>"><?= lang("News list"); ?></a>
						</li>
						<li class="border-bottom">
							<a href="<?= path_local("admin/news/add"); ?>"><?= lang("Create news"); ?></a>
						</li>
						<li>
							<a href="<?= path_local("admin/promotion/list"); ?>"><?= lang("Promotion list"); ?></a>
						</li>
						<li>
							<a href="<?= path_local("admin/promotion/add"); ?>"><?= lang("New promotion"); ?></a>
						</li>
					</ul>
				</li>

				<li class="list">
					<a href="<?= path_local("admin/certificate_control"); ?>"
						class="list-a"><?= lang("Certificate control"); ?></a>
				</li>
			</ul>
			<ul data-role="admin-header-list">
				<li class="list">
					<p class="list-p">
						<?= $this->session->userdata("name")." ".$this->session->userdata("surname"); ?>
					</p>
					<em class="fas fa-chevron-down m-0"></em>
					<ul class="list-child pos-r">
						<li>
							<a href="<?= base_url("sign-out"); ?>"><?= lang("Logout"); ?></a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>
<?php endif; ?>
