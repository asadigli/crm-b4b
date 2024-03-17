<?php
  $this->page_title = $page['title'];
  $this->headCSS .= '<link href="'.assets("css/libs/jquery.fancybox.css").'" rel="stylesheet">';

  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
?>

<!-- BREADCRUMB START -->
<div class="container">
	<div class="breadcrumb">
		<a href="<?= path_local(); ?>"><?= lang('Home'); ?></a>
		<a href="<?= path_local("certificates"); ?>"
			class="active"><?= lang('Certificates'); ?></a>
	</div>
</div>
<!-- BREADCRUMB END -->

<!-- PAGETITE START -->
<div class="page-title">
	<div class="container">
		<h1><?= $page['title']; ?></h1>
	</div>
</div>
<!-- PAGETITE END -->

<section>
	<div class="container p-0">
		<div class="row m-0 load" id="certificate_list">
			<?php for ($i=0; $i < 4; $i++) { ?>
			<div class="col-md-3 col-6 d-flex">
				<div class="card-img-2">
					<img src="<?= assets("img/certif1.jpeg") ?>" alt="">
					<div class="overlay-hover">
						<a href="<?= assets("img/certif1.jpeg") ?>" class="svg-cont">
							<svg version="1.1" viewBox="0 0 513.28 513.28" style="enable-background:new 0 0 513.28 513.28;"
								xml:space="preserve">
								<g>
									<g>
										<path d="M495.04,404.48L410.56,320c15.36-30.72,25.6-66.56,25.6-102.4C436.16,97.28,338.88,0,218.56,0S0.96,97.28,0.96,217.6
                                            s97.28,217.6,217.6,217.6c35.84,0,71.68-10.24,102.4-25.6l84.48,84.48c25.6,25.6,64,25.6,89.6,0
                                            C518.08,468.48,518.08,430.08,495.04,404.48z M218.56,384c-92.16,0-166.4-74.24-166.4-166.4S126.4,51.2,218.56,51.2
                                            s166.4,74.24,166.4,166.4S310.72,384,218.56,384z"></path>
									</g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
								<g>
								</g>
							</svg>
						</a>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</section>

<?php
$this->extraJS .= '<script src="'.assets('js/libs/fancybox.js').'"></script>';
$this->load->view('layouts/foot')
?>
