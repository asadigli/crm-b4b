<div class="modal fade" id="warehouse-modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" data-role="warehouse-modal-content">
      <div class="modal-header" >
        <h5 class="modal-title" id="ModalLabel"><?= lang("Warehouses") ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0" data-role="warehouse-modal-form">
        <div class="row ">
          <div class="col-lg-12 grid-margin stretch-card">

                <div class="table-responsive">
                  <table class="table table-bordered table-hover table-striped" style="width:100%;">
                    <thead>
                      <tr>
                        <th style="width:1%;" >#</th>
                        <th style="width:74%;" ><?= lang("Name") ?></th>
                        <th style="width:25%;" ><?= lang("Operations") ?></th>
                      </tr>
                    </thead>
                    <tbody data-role="table-warehouses-list">

                    </tbody>
                  </table>
                  <?= $this->load->view("layouts/components/loaders/table_loader_d") ?>
                </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
