<?php
  $this->metaDesc = '';
  $this->metaKeys = '';
  $this->page_title = lang("Brands");
  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
?>

<!-- BREADCRUMB START -->
<div class="container">
	<div class="breadcrumb">
		<a href="<?= path_local(); ?>"><?= lang('Home'); ?></a>
		<a href="<?= path_local(); ?>" class="active"><?= lang('Brands'); ?></a>
	</div>
</div>
<!-- BREADCRUMB END -->

<!-- PAGETITE START -->
<div class="page-title">
	<div class="container">
		<h1><?= lang("Brands") ?></h1>
	</div>
</div>
<!-- PAGETITE END -->

<section class="">
	<div class="container p-0">
		<div class="row m-0 load" id="brand_container">
      	<?php for ($i=0; $i < 10; $i++) { ?>
        	<div class="col-lg-2 col-md-3 col-6">
  				<a href="" class="card-img">
					<div class="overlay">
						<p>-----</p>
					</div>
  					<img src="" >
  				</a>
  			</div>
      	<?php } ?>
		</div>
	</div>
</section>

<?php
$this->load->view('layouts/foot');
?>
