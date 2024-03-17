	<div class="row">
		<div class="col-lg-12 grid-margin stretch-card">
			<div class="d-flex justify-content-end mb-2">
				<button type="button" data-bs-toggle="modal" data-bs-target="#supervisorAdd" class="btn btn-primary" name="button"><?= lang("New supervisor") ?></button>
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
									<th style="width:20%;" ><?= lang("Name") ?></th>
									<th style="width:20%;" ><?= lang("Surname") ?></th>
									<th style="width:20%;" ><?= lang("Phone") ?></th>
									<th style="width:10%;" ><?= lang("Email") ?></th>
									<th style="width:10%;" ><?= lang("Whatsapp") ?></th>
									<td style="width:3%;"></td>
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
	<?php $this->load->view("supervisors/add_modal.php") ?>
<?php $this->load->view("supervisors/edit_modal.php") ?>
