<?php
$seo["name"] = $product["prod_name"];
$seo["brand"] = $product["brand"] ? ", " . $product["brand"] : "";
$seo["brand_code"] = $product["brand_code"] ? ", " . $product["brand_code"] : "";
$seo["OEM"] = $product["OEM"] ? ", " . $product["OEM"] : "";
$this->metaKeys = lang("Seo_product_keywords") . ", " . $seo["name"] . $seo["brand"] . $seo["brand_code"] . $seo["OEM"];
$this->metaDesc = lang("Seo_product_description") . ": ". $seo["name"];
$this->page_title = $product["prod_name"];
$this->pageType = "product";
$this->load->view('layouts/head');
$this->load->view('layouts/menu');
?>
<!-- BREADCRUMB START -->
<div class="container">
	<div class="breadcrumb">
		<a href="<?= path_local(); ?>"><?= lang("Home"); ?></a>
		<a href="<?= path_local(); ?>"><?= lang("Products"); ?></a>
		<a href="" class="active"><?= $product["prod_name"]; ?></a>
	</div>
</div>
<!-- BREADCRUMB END -->

<!-- PAGETITE START -->
<div class="page-title">
	<div class="container">
		<h1><?= lang("Product"); ?></h1>
	</div>
</div>
<!-- PAGETITE END -->

<section class="product-detail mb-5" id="productDetail" data-brand-code="<?= $product["brand_code"]; ?>" data-no-info-text="<?= lang("No information"); ?>"
	data-product-slug="<?= $slug; ?>">
	<div class="container">
		<div class="line">
			<div class="left-side">
				<div class="side-inner">
					<div class="top">
						<div class="image-sec" style="background-image: url('<?php
                $img = isset($product["images"][0]["large"]) ? $product["images"][0]["large"] : assets("img/no_photo.png");
                echo $img
                ?>');" data-role="main-img-product"></div>
						<div class="content-sec d-v">
							<h3><?= $product["prod_name"]; ?></h3>
							<?php if ((float)$product["quantity"] < 1){ ?>
							<button class="not-in-stock"><?= lang("NOT IN STOCK"); ?></button>
							<?php }else{ ?>
							<button class="in-stock"><?= lang("IN STOCK"); ?></button>
							<?php } ?>
							<p> <?= $product["short_description"]; ?> </p>
						</div>
					</div>
					<?php
          if (isset($product["images"][0]["small"])){ ?>
					<div class="bottom">
						<div class="carousel-product-detail">
							<div class="owl-carousel">
								<?php foreach ($product["images"] as $key => $item){ ?>
								<img class="prod-thumbnail" src="<?= $item["small"]; ?>"
									data-src="<?= $item["large"]; ?>" data-role="prod-img-thumbnail"
									alt="<?php $product["prod_name"]. " - image ". ($key + 1) ?>">
								<?php } ?>
							</div>
						</div>
					</div>
					<?php } ?>

					<div class="content-sec m-v">
						<h3><?= $product["prod_name"]; ?></h3>
						<p><?= $product["short_description"]; ?></p>
					</div>
				</div>
			</div>
			<div class="right-side">
				<div class="side-inner">
					<?php if ($product["parent"]){ ?>
					<div class="line">
						<p><?= lang("Car_brand"); ?></p>
						<span class="text-main"><?= $product["parent"]; ?></span>
					</div>
					<?php }
            if ($product["brand"]){ ?>
					<div class="line">
						<p><?= lang("Brand"); ?></p>
						<span><?= $product["brand"]; ?></span>
					</div>
					<?php }
           if ($product["brand_code"]){ ?>
					<div class="line">
						<p><?= lang("Brand_code"); ?></p>
						<span><?= $product["brand_code"]; ?></span>
					</div>
					<?php }
          if ($product["OEM"]){ ?>
					<div class="line">
						<p><?= lang("OEM_code"); ?></p>
						<span><?= $product["OEM"]; ?></span>
					</div>
					<?php } ?>
				</div>
				<div class="side-inner">
					<div class="line-sec">
						<svg fill="#f6c065" version="1.1" x="0px" y="0px" viewBox="0 0 512 512"
							style="enable-background:new 0 0 512 512;" xml:space="preserve">
							<g>
								<g>
									<path
										d="M119.467,337.067c-28.237,0-51.2,22.963-51.2,51.2c0,28.237,22.963,51.2,51.2,51.2s51.2-22.963,51.2-51.2
									C170.667,360.03,147.703,337.067,119.467,337.067z M119.467,422.4c-18.825,0-34.133-15.309-34.133-34.133
									c0-18.825,15.309-34.133,34.133-34.133s34.133,15.309,34.133,34.133C153.6,407.091,138.291,422.4,119.467,422.4z" />
								</g>
							</g>
							<g>
								<g>
									<path
										d="M409.6,337.067c-28.237,0-51.2,22.963-51.2,51.2c0,28.237,22.963,51.2,51.2,51.2c28.237,0,51.2-22.963,51.2-51.2
										C460.8,360.03,437.837,337.067,409.6,337.067z M409.6,422.4c-18.825,0-34.133-15.309-34.133-34.133
										c0-18.825,15.309-34.133,34.133-34.133c18.825,0,34.133,15.309,34.133,34.133C443.733,407.091,428.425,422.4,409.6,422.4z" />
								</g>
							</g>
							<g>
								<g>
									<path d="M510.643,289.784l-76.8-119.467c-1.57-2.441-4.275-3.917-7.177-3.917H332.8c-4.719,0-8.533,3.823-8.533,8.533v213.333
									c0,4.719,3.814,8.533,8.533,8.533h34.133v-17.067h-25.6V183.467h80.674l72.926,113.442v82.825h-42.667V396.8h51.2
									c4.719,0,8.533-3.814,8.533-8.533V294.4C512,292.77,511.531,291.157,510.643,289.784z" />
								</g>
							</g>
							<g>
								<g>
									<path d="M375.467,277.333V217.6h68.267v-17.067h-76.8c-4.719,0-8.533,3.823-8.533,8.533v76.8c0,4.719,3.814,8.533,8.533,8.533h128
									v-17.067H375.467z" />
								</g>
							</g>
							<g>
								<g>
									<path
										d="M332.8,106.667H8.533C3.823,106.667,0,110.49,0,115.2v273.067c0,4.719,3.823,8.533,8.533,8.533H76.8v-17.067H17.067v-256
										h307.2v256H162.133V396.8H332.8c4.719,0,8.533-3.814,8.533-8.533V115.2C341.333,110.49,337.519,106.667,332.8,106.667z" />
								</g>
							</g>
							<g>
								<g>
									<rect x="8.533" y="345.6" width="51.2" height="17.067" />
								</g>
							</g>
							<g>
								<g>
									<rect x="179.2" y="345.6" width="145.067" height="17.067" />
								</g>
							</g>
							<g>
								<g>
									<rect x="469.333" y="345.6" width="34.133" height="17.067" />
								</g>
							</g>
							<g>
								<g>
									<rect x="34.133" y="140.8" width="298.667" height="17.067" />
								</g>
							</g>
							<g>
								<g>
									<rect x="110.933" y="379.733" width="17.067" height="17.067" />
								</g>
							</g>
							<g>
								<g>
									<rect x="401.067" y="379.733" width="17.067" height="17.067" />
								</g>
							</g>
							<g>
								<g>
									<rect x="34.133" y="72.533" width="119.467" height="17.067" />
								</g>
							</g>
							<g>
								<g>
									<rect y="72.533" width="17.067" height="17.067" />
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
						<p><?= lang("Easy delivery")?></p>
					</div>
					<div class="line-sec">
						<svg fill="#f6c065" viewBox="0 0 74 74">
							<path
								d="m55.919 54.06a1 1 0 0 1 -.814-1.581c.343-.48.677-.96 1-1.45.214-.321.417-.63.61-.947a1 1 0 1 1 1.711 1.037c-.208.342-.425.673-.654 1.015-.334.507-.681 1.007-1.038 1.507a1 1 0 0 1 -.815.419z" />
							<path
								d="m37 72.01a1.006 1.006 0 0 1 -.591-.193c-19.468-14.245-27.409-27.099-27.409-44.367v-18.44a1 1 0 0 1 .6-.91c18.306-8.087 36.749-8.085 54.808 0a1 1 0 0 1 .592.91v18.44a48.959 48.959 0 0 1 -.542 7.371 1 1 0 0 1 -1.977-.3 46.934 46.934 0 0 0 .519-7.071v-17.789c-17.139-7.494-34.625-7.493-52 0v17.789c0 19.1 10.368 30.783 26 42.319a98.036 98.036 0 0 0 13.549-11.761 1 1 0 1 1 1.463 1.364 101.587 101.587 0 0 1 -14.421 12.445 1.006 1.006 0 0 1 -.591.193z" />
							<path
								d="m60.79 45.371a.985.985 0 0 1 -.385-.078 1 1 0 0 1 -.538-1.308c.4-.952.729-1.837 1.013-2.706a1 1 0 1 1 1.9.621c-.3.921-.649 1.854-1.067 2.855a1 1 0 0 1 -.923.616z" />
							<path
								d="m37 64.488a.993.993 0 0 1 -.616-.213c-15.184-11.889-21.384-22.558-21.384-36.826v-14.417a1 1 0 0 1 .646-.936 60.611 60.611 0 0 1 21.448-4.088 59.475 59.475 0 0 1 21.262 4.071 1 1 0 0 1 .644.934v14.436c0 14.268-6.195 24.937-21.384 36.826a.993.993 0 0 1 -.616.213zm-20-50.761v13.722c0 13.384 5.806 23.5 20 34.765 14.194-11.265 20-21.381 20-34.765v-13.744a57.283 57.283 0 0 0 -19.906-3.7 58.371 58.371 0 0 0 -20.094 3.722z" />
							<path
								d="m32.75 41.167a1 1 0 0 1 -.707-.293l-6.5-6.5a1 1 0 1 1 1.414-1.415l5.793 5.793 14.293-14.293a1 1 0 1 1 1.414 1.415l-15 15a1 1 0 0 1 -.707.293z" />
						</svg>

						<p><?= lang("Guaranteed product")?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="tab mb-3">
	<div class="container">
		<div class="container-shadow p-2 d-v">
			<nav>
				<div class="nav nav-tabs" id="nav-tab" role="tablist">
					<!-- 1 -->
					<button class="nav-link active" id="product-features-tab" data-bs-toggle="tab"
						data-bs-target="#nav-product-features" type="button" role="tab"
						aria-controls="nav-product-features"
						aria-selected="true"><?= lang("Product features") ?></button>
					<!-- 2 -->
					<button class="nav-link" id="compatible-cars-tab" data-bs-toggle="tab"
						data-bs-target="#nav-compatible-cars" type="button" role="tab"
						aria-controls="nav-compatible-cars"
						aria-selected="false"><?= lang("Compatible cars") ?></button>
					<!-- 3 -->
					<button class="nav-link" id="oem-codes-tab" data-bs-toggle="tab" data-bs-target="#nav-oem-codes"
						type="button" role="tab" aria-controls="nav-oem-codes"
						aria-selected="false"><?= lang("OEM codes") ?></button>
					<!-- 4 -->
					<button class="nav-link" id="cross-reference-tab" data-bs-toggle="tab"
						data-bs-target="#nav-cross-reference" type="button" role="tab" aria-controls="nav-contact"
						aria-selected="false"><?= lang("Cross reference") ?></button>
				</div>
			</nav>
			<div class="tab-content" id="nav-tabContent">
				<!-- 1 -->
				<div class="tab-pane fade show active" id="nav-product-features" role="tabpanel"
					aria-labelledby="product-features-tab">
					<div class="inner-tab load" id="productDescription">
						<div class="mb-3 ml-2">
              <?= $product["description"]; ?>
            </div>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th><?= lang("Brand"); ?></th>
									<th></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td rowspan="3">- -</td>
									<td>- -</td>
									<td>- -</td>
								</tr>
								<tr>
									<td>- -</td>
									<td>- -</td>
								</tr>
								<tr>
									<td>- -</td>
									<td>- -</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<!-- 2 -->
				<div class="tab-pane fade" id="nav-compatible-cars" role="tabpanel" aria-labelledby="compatible-cars-tab">
					<div class="inner-tab load" id="compatible_cars_list">
						<table class="table">
							<thead>
								<tr>
                  <!-- CAR_BRAND  -->
                  <th scope="col">BRAND</th>
                  <!-- CAR_MODEL  -->
                  <th scope="col">MODEL</th>
                  <!-- CAR_TYP  -->
                  <th scope="col">TYP</th>
                  <!-- CAR_BODY  -->
                  <th scope="col">BODY</th>
                  <!-- CAR_OF_YEAR  -->
                  <th scope="col">OF YEAR</th>
                  <!-- CAR_TO_YEAR  -->
                  <th scope="col">TO YEAR</th>
                  <!-- CAR_KW  -->
                  <th scope="col">KW (mühərrikin həcmi)</th>
                  <!-- CAR_PM  -->
                  <th scope="col">PM</th>
                  <!-- CAR_CC  -->
                  <th scope="col">CC</th>
								</tr>
							</thead>
							<tbody>
								<?php for ($i=0; $i < 5; $i++) { ?>
                  <tr>
                    <td>---</td>
  									<td>---</td>
                    <td>---</td>
  									<td>---</td>
  									<td>---</td>
                    <td>---</td>
  									<td>---</td>
  									<td>---</td>
  									<td>---</td>
  								</tr>
                <?php } ?>
							</tbody>
						</table>
					</div>
				</div>
				<!-- 3 -->
				<div class="tab-pane fade" id="nav-oem-codes" role="tabpanel" aria-labelledby="oem-codes-tab">
					<div class="inner-tab load" id="oems_list">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<td>------</td>
								</tr>
								<tr>
									<td>-----</td>
								</tr>
								<tr>
									<td>----------</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<!-- 4 -->
				<div class="tab-pane fade" id="nav-cross-reference" role="tabpanel"
					aria-labelledby="cross-reference-tab">
					<div class="inner-tab load" id="crossreference_list">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th></th>
									<th></th>
									<th><?= lang("Changeable number") ?></th>
									<th><?= lang("Changeable brand") ?></th>
									<th></th>
									<th><?= lang("Brand code") ?></th>
									<th><?= lang("Group") ?></th>
									<th><?= lang("Part") ?></th>
								</tr>
							</thead>
							<tbody>
								<?php for ($i=0; $i < 10; $i++) { ?>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- Mobile -->
		<div class="container-shadow m-v p-0">
			<div class="accordion" id="piMobileTabs">
				<!-- 1 -->
				<div class="accordion-item">
					<h2 class="accordion-header" id="prodDetails">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
							data-bs-target="#prodDetailsTab" aria-expanded="true" aria-controls="prodDetailsTab">
							<?= lang("Product features") ?>
							<i class="fas fa-chevron-down"></i>
						</button>
					</h2>
					<div id="prodDetailsTab" class="accordion-collapse collapse load" aria-labelledby="prodDetails"
						data-bs-parent="#piMobileTabs">
						<div class="accordion-body">
							<div class="table-wrapper">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th><?= lang("Brand"); ?></th>
											<th></th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td rowspan="3">- -</td>
											<td>- -</td>
											<td>- -</td>
										</tr>
										<tr>
											<td>- -</td>
											<td>- -</td>
										</tr>
										<tr>
											<td>- -</td>
											<td>- -</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- 2 -->
				<div class="accordion-item">
					<h2 class="accordion-header" id="compatibleCarsAccordion">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
							data-bs-target="#compatibleCars" aria-expanded="false" aria-controls="compatibleCars">
							<?= lang("Compatible cars") ?>
							<i class="fas fa-chevron-down"></i>
						</button>
					</h2>
					<div id="compatibleCars" class="accordion-collapse collapse load"
						aria-labelledby="compatibleCarsAccordion" data-bs-parent="#piMobileTabs">
						<div class="accordion-body">
							<div class="table-wrapper">
								<table class="table">
									<thead>
										<tr>
                      <!-- CAR_BRAND  -->
                      <th scope="col">BRAND</th>
                      <!-- CAR_MODEL  -->
                      <th scope="col">MODEL</th>
                      <!-- CAR_TYP  -->
                      <th scope="col">TYP</th>
                      <!-- CAR_BODY  -->
                      <th scope="col">BODY</th>
                      <!-- CAR_OF_YEAR  -->
                      <th scope="col">OF YEAR</th>
                      <!-- CAR_TO_YEAR  -->
                      <th scope="col">TO YEAR</th>
                      <!-- CAR_KW  -->
                      <th scope="col">KW (mühərrikin həcmi)</th>
                      <!-- CAR_PM  -->
                      <th scope="col">PM</th>
                      <!-- CAR_CC  -->
                      <th scope="col">CC</th>
										</tr>
									</thead>
									<tbody>
                    <?php for ($i=0; $i < 5; $i++) { ?>
                      <tr>
                        <td>---</td>
      									<td>---</td>
                        <td>---</td>
      									<td>---</td>
      									<td>---</td>
                        <td>---</td>
      									<td>---</td>
      									<td>---</td>
      									<td>---</td>
      								</tr>
                    <?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- 3 -->
				<div class="accordion-item">
					<h2 class="accordion-header" id="OEMCodesAccordion">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
							data-bs-target="#oemListMobile" aria-expanded="false" aria-controls="oemListMobile">
							<?= lang("OEM codes") ?>
							<i class="fas fa-chevron-down"></i>
						</button>
					</h2>
					<div id="oemListMobile" class="accordion-collapse collapse load" aria-labelledby="OEMCodesAccordion"
						data-bs-parent="#piMobileTabs">
						<div class="accordion-body">
							<div class="table-wrapper">
								<table class="table">
									<tbody>
										<tr>
											<td>--</td>
										</tr>
										<tr>
											<td>----</td>
										</tr>
										<tr>
											<td>----------</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- 4 -->
				<div class="accordion-item">
					<h2 class="accordion-header" id="CrossReferenceMobile">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
							data-bs-target="#CrossReferenceMobileTab" aria-expanded="false"
							aria-controls="CrossReferenceMobileTab">
							<?= lang("Cross reference") ?>
							<i class="fas fa-chevron-down"></i>
						</button>
					</h2>
					<div id="CrossReferenceMobileTab" class="accordion-collapse collapse load"
						aria-labelledby="CrossReferenceMobile" data-bs-parent="#piMobileTabs">
						<div class="accordion-body">
							<div class="table-wrapper">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th></th>
											<th></th>
											<th><?= lang("Changeable number") ?></th>
											<th><?= lang("Changeable brand") ?></th>
											<th></th>
											<th><?= lang("Brand code") ?></th>
											<th><?= lang("Group") ?></th>
											<th><?= lang("Part") ?></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th scope="row">1</th>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<th scope="row"></th>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<th scope="row"></th>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- CARD CAROUSEL START -->
<section class="card-carousel load" id="similarProducts">
	<div class="container d-flex justify-content-center">
		<div class="fake-card-line">
			<?php for ($i=0; $i < 4; $i++) { ?>
			<div class="product-card">
				<div class="product-card-top">
					<img src="<?= assets("img/mehsil 1.svg"); ?>" alt="">
				</div>
				<div class="product-card-body">
					<a href="#">
						<h4>-------</h4>
					</a>
					<p>------------------------------</p>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</section>
<!-- CARD CAROUSEL START -->

<?php
// $this->extraJS .= '<script src="'.assets('js/imageZoom.js').'?v='.md5(microtime()).'"></script>';
$this->load->view('layouts/foot')
?>
