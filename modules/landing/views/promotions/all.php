<?php
  $this->metaDesc = '';
  $this->metaKeys = '';
  $this->page_title = lang("Promotions");
  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
?>

<!-- BREADCRUMB START -->
<div class="container">
	<div class="breadcrumb">
		<a href="<?= path_local(); ?>"><?= lang('Home'); ?></a>
		<a href="<?= path_local(); ?>" class="active"><?= lang('Promotions'); ?></a>
	</div>
</div>
<!-- BREADCRUMB END -->

<!-- PAGETITE START -->
<div class="page-title">
	<div class="container">
		<h1><?= lang('Promotions'); ?></h1>
	</div>
</div>
<!-- PAGETITE END -->

<section class="main-section">
	<div class="container">
		<div class="inner-cont load" id="promotions_list">
			<?php for ($i=0; $i < 5; $i++) { ?>
			<div class="card-line">
				<div class="left-side">
					<img src="">
				</div>
				<div class="right-side">
					<div class="content">
						<h4>
							------------------------------
							------------------------------
							------------------------------------
						</h4>
						<p>------------------------------</p>
					</div>
					<div class="d-flex justify-content-between">
						<div class="date-card">
							<img src="">
							<span>------------------------</span>
						</div>
						<a href="">
							<p>-------------</p>
							<em>-------</em>
						</a>
					</div>
				</div>
			</div>
			<?php }
     ?>
		</div>
    <!-- PAGEINATION -->
    <div data-role="pagination"></div>
    <!-- PAGEINATION -->
	</div>
</section>


<?php
$this->load->view('layouts/foot')
?>
