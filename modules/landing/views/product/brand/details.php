<?php
  $this->metaDesc = '';
  $this->metaKeys = '';
  $this->page_title = lang("Brands");
  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
  $edit = $this->admin && $this->input->get("action") === "edit";
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
		<h1><?= lang('Brands'); ?></h1>
	</div>
</div>
<!-- PAGETITE END -->

<section class="brand-inner-page mb-4">
	<div class="container">
		<div class="container-shadow d-flex">
			<div class="brand-inner-img mr-5">
				<img src="<?= $data["image"]["large"] ?: assets("img/brands/customer.png"); ?>" alt="<?= $data["name"]; ?>" style="height:200px">
			</div>
			<div class="content-brand-inner">
				<h4><?= $data["name"]; ?></h4>
				<?= $data["description"]; ?>
			</div>
		</div>
	</div>
</section>

<?php
$this->load->view('layouts/foot');
?>
