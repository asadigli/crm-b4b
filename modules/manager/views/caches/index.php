<?php if ($caches): ?>
	<div class="row">
		<div class="col-lg-12 grid-margin stretch-card">
	    <div class="card">
	      <div class="card-body">
	        <div class="table-responsive">
	          <table class="table table-bordered table-hover table-striped" style="width:100%;">
				<thead>
					<tr>
						<th style="width:3%;" >#</th>
						<th style="width:30%;" ><?= lang("Name") ?></th>
						<th style="width:30%;" ><?= lang("Request type") ?></th>
						<th style="width:20%;" ><?= lang("Last update date") ?></th>
						<th style="width:10%;" ><?= lang("Operations") ?></th>
					</tr>
				</thead>
				<tbody data-role="table-list">
					<?php foreach ($caches as $key => $item): ?>
						<tr>
							<td><?= ++$key ?></td>
							<td><?= lang($item["name"]) ?></td>
							<td><?= isset($history[$item["key"]]["request_type"]) ? lang($history[$item["key"]]["request_type"]) : "" ?></td>
							<td data-role="lastupdate-date" class="<?= isset($history[$item["key"]]["datetime"]) && date("Y-m-d", strtotime($history[$item["key"]]["datetime"])) === date("Y-m-d") ? "text-success" : "" ?>" ><?= isset($history[$item["key"]]["datetime"]) ? $history[$item["key"]]["datetime"] : "" ?></td>
							<td>
								<div class="d-flex">
									<button data-role="refresh-cache" type="button" data-type="<?= $item["key"] ?>" class="btn btn-primary btn-icon">
										<i class="fas fa-sync"></i>
									</button>
									<div data-role="cache-time" class="mt-2 ml-2">
										<?= isset($item["operation_date"]) && $item["operation_date"] ? $item["operation_date"] : "" ?>
									</div>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
	          	</table>
				<?= $this->load->view("layouts/components/loaders/table_loader") ?>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
	<?php $this->load->view("components/warehouses") ?>
<?php endif; ?>
