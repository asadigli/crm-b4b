<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-2 col-6 mb-3 mb-md-0">
                <label><?= lang("Date from") ?></label>
                <input class="form-control ui-autocomplete-input" type="date"
                       value="<?= $this->input->get("start_date") ?: date("Y-m-d") ?>" name="start_date"
                       autocomplete="off">
            </div>
            <div class="col-md-2 col-6 mb-3 mb-md-0">
                <label><?= lang("Date to") ?></label>
                <input class="form-control ui-autocomplete-input" type="date"
                       value="<?= $this->input->get("end_date") ?: date("Y-m-d") ?>" name="end_date" autocomplete="off">
            </div>
            <div class="col-md-2 mb-3 mb-md-0">
                <label><?= lang("Customer") ?></label>
                <select class="form-select custom-select" name="customer"
                        data-value="<?= $this->input->get("customer") ?>">
                </select>
            </div>
            <div class="col-md-2 d-flex justify-content-end align-items-end">
                <button class="btn btn-primary border-0" data-role="search"><?= lang("Search") ?></button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered mb-0" data-role="logs-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th><?= lang("Keyword") ?> </th>
                    <th><?= lang("Brand") ?> </th>
                    <th><?= lang("Marka") ?> </th>

                    <th><?= lang("Search count") ?> </th>
                    <th><?= lang("Result count") ?> </th>
                    <th> <?= lang("Searchers") ?> </th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>



<div class="modal fade" id="searchLogsCustomersModal" tabindex="-1" aria-labelledby="searchLogsCustomersModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sliderEditLabel"><?= lang("Searchers") ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table table-bordered ">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?= lang("Name") ?> </th>
                            <th><?= lang("Count") ?> </th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang("Close") ?></button>
<!--                <button type="button" class="btn btn-primary" data-role="save">--><?php //= lang("Save") ?><!--</button>-->
            </div>
        </div>
    </div>
</div>

<div class="load-more " id="load_more_div">
    <a data-role="load-more" href="javascript:void(0)"><?= lang("Load more") ?></a>
</div>


<script>
    var words = {
        "No result" : "<?=lang("No result") ?>",
    }
</script>
