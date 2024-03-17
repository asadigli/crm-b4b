<?php
  $this->metaDesc = '---';
  $this->metaKeys = '---';
  $this->page_title = lang("Edit");
  $this->load->view('layouts/head');
  $this->load->view('layouts/menu');
?>

<div class="page-container" data-role="product-edit-container" data-prod-id="<?php echo $data["id"]; ?>">
	<div class="container">
		<div class="mobile-margin-changer mb-4 mt-5">
			<div class="page-card-header tr-none position-relative">
				<div class="cs_whc_title px-3 py-3 mb-0 d-flex align-items-center justify-content-between">
					<h1><?php echo lang("Product_edit"); ?></h1>
					<a href="<?php echo path_local("product/".$data["prod_slug"]); ?>" class="tool-iteM" title="<?php echo lang("Preview"); ?>">
						<i class="fa fa-eye"></i>
					</a>
				</div>
			</div>
			<div class="page-card-body mobile-p-0 pt-0">
				<div class="container-shadow">
					<div class="tab-product tab-product-details">
						<!-- DESKTOP VERSION -->
						<nav>
							<div class="nav nav-tabs" id="nav-tab" role="tablist">
								<!-- 1 -->
								<a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab"
									aria-controls="nav-home" aria-selected="false"><?php echo lang("Product information"); ?></a>
								<!-- 2 -->
								<a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab"
									aria-controls="nav-profile" aria-selected="true"><?php echo lang("Gallery"); ?></a>
							</div>
						</nav>
						<div class="tab-content" id="nav-tabContent">
							<!-- 1 -->
							<div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
								<h5 class="mb-4"><strong>Məhsul məlumatını dəyiş</strong></h5>
                <hr>
                <label class="chck_container m-0">
                  <?php echo lang("Home product"); ?>
                  <input type="checkbox"<?php echo $data["home_product"] === "1" ? ' checked="checked"' : ""; ?> data-role="is_home_product">
                  <span class="checkmark"></span>
                </label>
                <hr>
									<div class="form-group tb_input_group">
										<i class="fas fa-user"></i>
										<input type="text" data-name="product-name" class="form-control" minlength="6" autocomplete="off"
                              data-error="<?php echo lang("Please enter valid product name"); ?>"
											         placeholder="<?php echo lang("Product_name"); ?>..." value="<?php echo $data["prod_name"]; ?>" required>
                    <small class="text-danger d-none"></small>
									</div>
									<div class="form-group tb_input_group">
										<textarea class="form-control" placeholder="Qısa təsvir..." maxlength="500"
                        data-name="short-description"><?php echo $data["short_description"]; ?></textarea>
									</div>
									<div class="form-group tb_input_group">
										<textarea class="form-control" placeholder="Ətraflı məlumat..."
                        data-name="description"><?php echo $data["description"]; ?></textarea>
									</div>
                  <div class="d-flex justify-content-end">
                    <button class="def-btn px-4" data-role="edit-product-details"
                              style="width:240px" data-text="<?php echo lang("Save"); ?>" disabled><?php echo lang("Save"); ?></button>
                  </div>
							</div>
							<!-- 2 -->
							<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
								<h5 class="mb-4"><strong>--- --- ---</strong></h5>
									<div id="storeImageSection">
										<input type="file" id="storeImageUpload" name="image" multiple="">
										<div id="prodImgPreview"></div>
										<div id="prodImgUploadBtn">
											<button class="btn btn-primary" type="button"><em class="fa fa-save"></em> <?php echo lang("Add"); ?> </button>
										</div>
									</div>

									<article class="gallery_container">
										<div class="title-section mb-4 border-bottom">
											<h5>Qalleriya</h5>
										</div>
										<div class="row row-1">


										</div>
									</article>
							</div>
						</div>

						<!-- MOBILE VERSION -->
						<div class="tb_accordion">
							<ul>
								<!-- first -->
								<li>
									<div class="line">
										<p>Açıqlama</p>
										<i class="fa fa-chevron-down" aria-hidden="true"></i>
									</div>
									<div class="tb_content_accordion">
										<h5><strong>Blah blah blah</strong></h5>
										<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Et consectetur
											molestias rerum
											sapiente
											quasi nostrum doloribus iste ea nam repellat exercitationem perspiciatis aliquid
											nisi
											pariatur
											sunt, eligendi, fugit quos corporis?</p>
										<div class="line-d">
											<p>Content</p>
											<span>323</span>
										</div>
										<div class="line-d">
											<p>Content</p>
											<span>323</span>
										</div>
										<div class="line-d">
											<p>Content</p>
											<span>323</span>
										</div>
									</div>
								</li>
								<!-- second -->
								<li>
									<div class="line">
										<p>Uyğun maşınlar</p>
										<i class="fa fa-chevron-down" aria-hidden="true"></i>
									</div>
									<div class="tb_content_accordion">
										<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi, inventore enim nobis
											natus
											consequatur aut illum rerum adipisci ex debitis sint aliquid vitae quidem molestiae
											id
											facere ea
											corporis laborum!
											<br>
											Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi, inventore enim nobis
											natus
											consequatur aut illum rerum adipisci ex debitis sint aliquid vitae quidem molestiae
											id
											facere ea
											corporis laborum!
											<br>
											Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi, inventore enim nobis
											natus
											consequatur aut illum rerum adipisci ex debitis sint aliquid vitae quidem molestiae
											id
											facere ea
											corporis laborum!
										</p>
									</div>
								</li>
								<!-- third -->
								<li>
									<div class="line">
										<p>OEM nömrələr</p>
										<i class="fa fa-chevron-down" aria-hidden="true"></i>
									</div>
									<div class="tb_content_accordion">
										<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi, inventore enim nobis
											natus
											consequatur aut illum rerum adipisci ex debitis sint aliquid vitae quidem molestiae
											id
											facere ea
											corporis laborum!
											<br>
											Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi, inventore enim nobis
											natus
											consequatur aut illum rerum adipisci ex debitis sint aliquid vitae quidem molestiae
											id
											facere ea
											corporis laborum!
											<br>
											Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi, inventore enim nobis
											natus
											consequatur aut illum rerum adipisci ex debitis sint aliquid vitae quidem molestiae
											id
											facere ea
											corporis laborum!
										</p>
									</div>
								</li>

              </ul>
						</div>

        	</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
$this->extraJSBefore .= '<script src="https://cdn.ckeditor.com/ckeditor5/27.0.0/classic/ckeditor.js"></script>';
$this->extraJS .= '<script type="module" data-role="page-js" src="'.assets("js/pvt/product.edit.waINE58nrJawNbX0owVdN17o0F5pd6.js",$this->config->item("is_production")).'"></script>';
$this->load->view('layouts/foot');
 ?>
