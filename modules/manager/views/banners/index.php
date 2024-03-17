	<div class="row">
		<div class="col-lg-12 grid-margin stretch-card">
			<div class="d-flex justify-content-end mb-2">
				<button type="button" data-bs-toggle="modal" data-bs-target="#bannerAdd" class="btn btn-primary" name="button"><?= lang("New banner") ?></button>
			</div>
	    <div class="card">
				<div class="box-header">
          <div class="d-flex justify-content-between">
            <div><b data-role="content-result-count">0</b> <?= lang("result") ?></div>
            <div><b data-role="content-result-time">0</b> <?= lang("sec.") ?></div>
          </div>
        </div>
	      <div class="card-body">
	        <div class="table-responsive">
	          <table class="table table-bordered table-hover table-striped" style="width:100%;">
							<thead>
								<tr>
									<th style="width:3%;" >#</th>
									<th style="width:5%;" ><?= lang("Image") ?></th>
									<th style="width:30%;" ><?= lang("Title") ?></th>
									<th style="width:30%;" ><?= lang("Description") ?></th>
									<th style="width:10%;" ><?= lang("Start date") ?></th>
									<th style="width:10%;" ><?= lang("End date") ?></th>
									<th style="width:10%;" ><?= lang("Url") ?></th>
									<td style="width:7%;"></td>
								</tr>
							</thead>
							<tbody data-role="table-list">

							</tbody>
		      	</table>
						<?= $this->load->view("layouts/components/loaders/table_loader") ?>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
	<?php $this->load->view("banners/add_modal.php") ?>
	<?php $this->load->view("banners/edit_modal.php") ?>
