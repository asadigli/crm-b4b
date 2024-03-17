<?php
  $this->metaDesc = $data["title"];
  $this->metaKeys = implode(",",$data["tags"]);
  $this->page_title = $data["title"];
  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
?>

<!-- BREADCRUMB START -->
<div class="container">
	<div class="breadcrumb">
		<a href="<?php echo path_local(); ?>"><?php echo lang("Home"); ?></a>
		<a href="<?php echo path_local("news-list"); ?>"><?php echo lang("News list"); ?></a>
		<a href="" class="active"><?php echo str_limit($data["title"],50); ?></a>
	</div>
</div>
<!-- BREADCRUMB END -->

<!-- PAGETITE START -->
<div class="page-title">
	<div class="container">
		<h1><?php echo lang("News"); ?></h1>
	</div>
</div>
<!-- PAGETITE END -->

<section class="mb-5">
	<div class="container">
		<div class="container-shadow p-0">
			<?php if ($data["images"]): ?>
			<div class="carousel-img-card">
				<<?= isset($data["image_link"]) && $data["image_link"] ? "a href='{$data["image_link"]}' target='_blank' rel='noreferer' " : "div"?> class="owl-carousel">
					<?php foreach ($data["images"] as $key => $image): ?>
					<img src="<?php echo $image["large"]; ?>" alt="">
					<?php endforeach; ?>
				</<?= isset($data["image_link"]) && $data["image_link"] ? "a" : "div"?>>
			</div>
			<?php endif; ?>
			<div class="content p-4">
				<div class="date-card">
					<img src="<?php echo assets("img/icons/png calendar.png") ?>" alt="">
					<span><?php
          $date = strtotime($data["date"]);
          echo date('h:i, d-M-Y', $date); ?></span>
				</div>
				<h4 class="title-main">
					<?php echo $data["title"]; ?>
				</h4>
				<?php echo $data["details"]; ?>
			</div>
			<?php if ($data["tags"]): ?>
			<div class="tag-items">
				<h4><?php echo lang("Tags"); ?></h4>
				<div class="line">
					<?php foreach ($data["tags"] as $key => $tag): ?>
					<span><?php echo $tag; ?></span>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
$this->load->view('layouts/foot')
?>
