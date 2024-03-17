<div class="content-header">
  <div class="row align-items-center">
    <div class="col-6">
      <h3 class="page-title"><?= lang("Sliders") ?></h3>
    </div>
    <div class="col-6 d-flex justify-content-end">
      <button type="button" data-bs-toggle="modal" data-bs-target="#sliderAdd" class="btn btn-primary" name="button"><?= lang("New slider") ?></button>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
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
                <th style="width:10%;" ><?= lang("Is active") ?></th>
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
<?php $this->load->view("b4b/sliders/add_modal.php") ?>
<?php $this->load->view("b4b/sliders/edit_modal.php") ?>

<script type="text/javascript">
  var words = {
    "Active" : "<?= lang("Active") ?>",
    "Deactive" : "<?= lang("Deactive") ?>",
  };
</script>
