<?php
  $this->metaDesc = '';
  $this->metaKeys = implode(",",$data["tags"]);
  $this->page_title = $data['title'];
  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
?>

<!-- BREADCRUMB START -->
<div class="container">
	<div class="breadcrumb">
    <a href="<?= path_local(); ?>"><?= lang('Home'); ?></a>
		<a href="<?= path_local("promotions"); ?>"><?= lang('Promotions'); ?></a>
		<a href="" class="active"><?= $data['title']; ?></a>
	</div>
</div>
<!-- BREADCRUMB END -->

<!-- PAGETITE START -->
<div class="page-title">
	<div class="container">
		<h1><?= $data['title']; ?></h1>
	</div>
</div>
<!-- PAGETITE END -->

<section class="mb-5">
	<div class="container">
		<div class="container-shadow p-0">
      <?php if ($data["images"]): ?>
        <div class="carousel-img-card">
  				<div class="owl-carousel">
  					<?php foreach ($data["images"] as $key => $image): ?>
              <img src="<?= $image["large"]; ?>" alt="">
            <?php endforeach; ?>
  				</div>
  			</div>
      <?php endif; ?>
			<div class="content p-4">
				<h4 class="title-main">
					<?= $data["title"]; ?>
				</h4>
        <?= $data["details"]; ?>
			</div>
      <?php if ($data["tags"]): ?>
        <div class="tag-items">
  				<h4><?= lang("Tags"); ?></h4>
          <div class="line">
            <?php foreach ($data["tags"] as $key => $tag): ?>
              <span><?= $tag; ?></span>
            <?php endforeach; ?>
  				</div>
  			</div>
      <?php endif; ?>
		</div>
	</div>
</section>

<?php
$this->load->view("layouts/foot")
?>
