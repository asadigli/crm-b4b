
<div class="row">
	<div class="col-lg-12">
		<div class="card filter">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col-md-2 col-6">
						<input type="date" name="" max="<?= date("Y-m-d") ?>" data-role="select-start-date" value="<?= $url_params["start_date"] ?: date("Y-m") . "-01"  ?>" class="form-control" />
					</div>
					<div class="col-md-2 col-6">
						<input type="date" name="" max="<?= date('Y-m-d') ?>" data-role="select-end-date" value="<?=$url_params["end_date"] ?: date("Y-m-d")  ?>" class="form-control" />
					</div>
					<div class="col-md-8 d-flex justify-content-end">
						<button type="button" data-role="search-filter" class="btn btn-primary" ><i class="fa-solid fa-magnifying-glass me-2"></i><?= lang("Search") ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header">
                  <div class="d-flex justify-content-between">
                    <div><b data-role="content-result-count"> 0 </b> <?= strtolower(lang("Result")) ?></div>
                    <div><b data-role="content-result-time"> 0 </b> <?= strtolower(lang("Sec")) ?></div>
                  </div>
                </div>
				<div class="table-responsive-md">
					<table class="table mb-0 table-bordered">
						<thead>
							<tr>
							<th scope="col" style="width:1%;">#</th>
							<th scope="col" style="width:10%;" ><?= lang("Code") ?></th>
							<th scope="col" style="width:10%;" ><?= lang("Date") ?></th>
							<th scope="col" style="width:10%;" ><?= lang("Warehouse") ?></th>
							<th scope="col" style="width:10%;" ><?= lang("Status") ?></th>
							<th scope="col" style="width:10%;" ><?= lang("Product count") ?></th>
							<th scope="col" style="width:10%;" ><?= lang("Amount") ?></th>
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
</section>

<div class="load-more d-none" id="load_more_div">
  <a data-role="load-more" href="javascript:void(0)"><?= lang("Load more") ?></a>
</div>
