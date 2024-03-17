<?php
  $this->page_title = $page['title'];
  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
?>


<section class="wallp-second about" style="background-image: url('<?php echo assets("img/header cover.jpg") ?>');">
	<div class="container">
		<h1><?php echo lang("Car dealer the best") ?></h1>
	</div>
</section>

<!-- BREADCRUMB START -->
<div class="container">
	<div class="breadcrumb">
		<a href="<?php echo path_local(); ?>"><?php echo lang('Home'); ?></a>
		<a href="<?php echo path_local(); ?>" class="active"><?php echo lang('About_us'); ?></a>
	</div>
</div>
<!-- BREADCRUMB END -->

<section class="mobile-pos">
	<div class="container mb-5">
		<div class="container-shadow">
			<div class="line-content">
				<div class="section-title">
					<h3><?php echo isset($data["data"]["title"]) ? $data["data"]["title"] : ""; ?></h3>
				</div>
				<p>
					<?php echo isset($data["data"]["details"]) ? $data["data"]["details"] : ""; ?>
				</p>
			</div>
		</div>
	</div>
</section>

<?php $this->load->view('layouts/foot') ?>
