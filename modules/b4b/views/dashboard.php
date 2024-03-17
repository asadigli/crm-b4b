<?php if ($sliders && !$url_params["only_new_products"]): ?>
  <div id="dashboard-slide" class="dashboard-slide owl-carousel">
  <?php foreach ($sliders as $key => $slider): ?>
    <div class="crsl-item <?= $key === 0 ? "active" : "" ?>">
      <img src="<?= path_local($slider["image"] ?: "assets/globals/image/no-image.png") ?>" alt="<?= $slider["title"] ?>">
      <div class="carousel-overlay">
        <h1><?= trim($slider["title"]) ?></h1>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="row fx-element-overlay  <?= !$url_params["only_new_products"] ? "section-tp" : ""  ?>">
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><?= lang("New products from warehouses") ?></h4>
            <?php if(false): ?>
            <a href="javascript:void(0)"><?= lang("See all") ?></a>
            <?php endif ?>
        </div>
    </div>
</div>
  <div class="row" id="new_product" data-role="parent-new-products-from-warehouses">

  </div>
</div>

<script type="text/javascript">
  var words = {
    "Product" : "<?= lang("Product") ?>",
    "Car brand" : "<?= lang("Car brand") ?>",
    "Brand" : "<?= lang("Brand") ?>",
    "Brand code" : "<?= lang("Brand code") ?>",
    "Original code" : "<?= lang("Original code") ?>",
    "Price" : "<?= lang("Price") ?>",
  };
</script>
