<div class="breadcrumb-header justify-content-between mb-2">
	<div class="my-auto">
		<div class="d-flex justify-content-between align-items-center">
			<h4 class="content-title mb-0 my-auto"><?= lang("System setups") ?></h4>
		</div>
	</div>
</div>

	<div class="row mt-4">
		<div class="col-lg-12 grid-margin stretch-card">
	    <div class="card">
	      <div class="card-body">
	        <div class="table-responsive">
	          <table class="table table-bordered table-hover table-striped" style="width:100%;">
	            <thead>
	              <tr>
	                <th style="width:1%;" >#</th>
	                <th style="width:72%;" ><?= lang("Name") ?></th>
									<th style="width:5%;" ><?= lang("Operations") ?></th>
									<?php if (false): ?>
																<th style="width:17%;" ><?= lang("Latest update time") ?></th>
									<?php endif; ?>
	              </tr>
	            </thead>
	            <tbody data-role="table-list">
								<tr>
									<td></td>
									<td><?= lang("Refresh local caches") ?></td>
									<td>
										<div class="d-flex">
											<button data-role="refresh-local-cache" type="button" class="btn btn-info btn-icon">
													<i class="fas fa-sync"></i>
												</button>
										</div>
									</td>

								</tr>

								<tr>
									<td></td>
									<td><?= lang("Clear local sessions") ?></td>
									<td>
										<div class="d-flex">
											<button data-role="clear-local-sessions" type="button" class="btn btn-info btn-icon">
													<i class="fas fa-sync"></i>
												</button>
										</div>
									</td>

								</tr>

								<tr>
									<td></td>
									<td><?= lang("Server Environment") ?></td>
									<td>
										<code><?= ENVIRONMENT ?></code>
									</td>
								</tr>
	            </tbody>
	          </table>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>

<script type="text/javascript">
  var words = {
		"u_sure_refresh_cache": "<?= lang("u_sure_refresh_cache") ?>",
		"cache_refreshing": "<?= lang("cache_refreshing") ?>",
		"second": "<?= lang("second") ?>",
		"minute": "<?= lang("minute") ?>",
		"Loading": "<?= lang("Loading") ?>",
  }
</script>
