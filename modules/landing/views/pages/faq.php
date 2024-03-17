<?php
  $this->page_title = $page['title'];
  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
?>

<!-- BREADCRUMB START -->
<div class="container">
	<div class="breadcrumb">
		<a href="<?= path_local(); ?>"><?= lang("Home"); ?></a>
		<a href="" class="active"><?= $page['title']; ?></a>
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

<section class="about-content" id="faq">
	<div class="container">
		<div class="container-shadow content">
			<div class="row">
				<div class="col-lg-6">
					<div class="faq-inner">
						<?php if (isset($data["code"]) && $data["code"] === 200){
              		foreach ($data["data"] as $key => $item){
                 	if ($key%2 === 0){ ?>
						<div class="card">
							<div class="card-hedaer">
								<h2 class="mb-0">
									<?= $item["title"]; ?>
									<div class="plus-minus"></div>
								</h2>
							</div>
							<div class="card-body">
								<?= $item["details"]; ?>
							</div>
						</div>
						<?php }}} ?>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="faq-inner">
						<?php if (isset($data["code"]) && $data["code"] === 200){
              foreach ($data["data"] as $key => $item){
                 if ($key%2 === 1){ ?>
						<div class="card">
							<div class="card-hedaer">
								<h2 class="mb-0">
									<?= $item["title"]; ?>
									<div class="plus-minus"></div>
								</h2>
							</div>
							<div class="card-body">
								<?= $item["details"]; ?>
							</div>
						</div>
						<?php }}} ?>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>

<?php
$this->load->view('layouts/foot');
 ?>
