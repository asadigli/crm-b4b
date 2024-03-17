<?php
  $this->metaDesc = "";
  $this->metaKeys = "";
  $this->page_title = lang("Engines");
  $this->load->view("layouts/head");
  $this->load->view("layouts/menu");
?>

<div class="page-container">
	<div class="container">
		<div class="mobile-margin-changer mb-4 mt-5">
			<div class="page-card-header tr-none position-relative">
				<div class="cs_whc_title px-3 py-3 mb-0 d-flex align-items-center justify-content-between">
					<h1><?php echo lang("Engines"); ?></h1>
				</div>
			</div>
      <div class="breadcrumb-nav px-4 mt-0 mb-3">
        <li>
          <a href="/">first</a>
        </li>
        <li>
          <a href="">second</a>
        </li>
      </div>
			<div class="page-card-body mobile-p-0 pt-0">
				<div class="container-shadow">
					<div class="search-group-def">
						<input type="text" class="form-control" placeholder="Axtar...">
						<button class="def-btn">
							<i class="fas fa-search text-grey" aria-hidden="true"></i>
						</button>
					</div>
				</div>
				<div class="container-shadow p-2 load" data-role="engine-pg-list" data-brand-id="<?php echo $brand; ?>"  data-model-id="<?php echo $model; ?>">
					<div class="cg-list">
            <!-- first -->
						<div class="list-line">
							<ul>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
								<li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
							</ul>
						</div>
						<!-- second -->
						<div class="list-line">
              <ul>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
								<li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
							</ul>
						</div>
						<!-- third -->
						<div class="list-line">
              <ul>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
                <li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
								<li><a href="#"><em class="fas fa-sort-up"></em><p>--------</p></a></li>
							</ul>
						</div>
        	</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
$this->extraJS .= '<script type="module" src="'.assets("js/brand.js",$this->config->item("is_production")).'"></script>';
$this->load->view("layouts/foot");
?>
