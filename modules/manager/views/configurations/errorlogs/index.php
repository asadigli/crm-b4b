	<div class="row">
		<div class="col-lg-12">
		  <div class="card">
		    <div class="card-body">
		      <div class="row">
				<div class="col-md-4 col-6">
					<div class="form-group m-0">
						<input type="date" class="form-control mb-0" name="date" value="<?= $url_params["date"] ?: date("Y-m-d") ?>" >
					</div>
				</div>
				<div class="col-md-2 col-6">
					<div class="form-group m-0">
						<select data-role="log-path" name="log_path" class="custom-select">
							<option value=""><?= lang("Log paths") ?></option>
						</select>
					</div>
				</div>
				<div class="col-md-6 d-flex justify-content-end mt-3 mt-md-0">
					<button type="button" data-role="search-filter" class="btn btn-primary">
						<i class="fa-solid fa-magnifying-glass"></i>
						<?= lang("Search") ?>
					</button>
				</div>
		      </div>
		    </div>
		  </div>
		</div>
	</div>

	<div class="row mt-4">
		<div class="col-lg-12 grid-margin stretch-card">
	    <div class="card">
				<div class="card-header">
					<div class="d-flex justify-content-between">
						<div><b data-role="content-result-count" > 0 </b> <?= strtolower(lang("Result")) ?></div>
						<?php if (false): ?>
							<div><b data-role="content-result-time" > 0 </b> <?= strtolower(lang("Sec")) ?></div>
						<?php endif; ?>
					</div>
				</div>
	      <div class="card-body">
	        <div class="table-responsive">
	          <table class="table table-bordered table-hover table-striped" style="width:100%;">
	            <thead>
	              <tr>
	                <th style="width:1%;" >#</th>
									<th style="width:5%;" ><?= lang("Type") ?></th>
									<th style="width:7%;" ><?= lang("Date") ?></th>
									<th style="width:20%;" ><?= lang("Title") ?></th>
									<th style="width:66%;" ><?= lang("Body") ?></th>
	              </tr>
	            </thead>
	            <tbody data-role="table-list">

	            </tbody>
	          </table>
						<?= $this->load->view("layouts/components/loaders/table_loader_d") ?>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
