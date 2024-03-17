<div class="modal fade" id="sliderAdd" tabindex="-1" aria-labelledby="sliderAddLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sliderAddLabel"><?= lang("Add slider") ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      <label class="form-label"><?= lang("Title") ?></label>
                      <input type="text" autocomplete="off" class="form-control" data-name="title" name="title" placeholder="<?= lang("Title") ?>">
                  </div>
              </div>
              <div class="col-md-12">
                  <div class="form-group">
                      <label class="form-label"><?= lang("Description") ?></label>
                      <textarea class="form-control" data-name="description" name="description" placeholder="<?= lang("Description") ?>" rows="3" cols="80"></textarea>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="form-label"><?= lang("Start date") ?></label>
                      <input type="datetime-local" autocomplete="off" value="<?= strftime('%Y-%m-%dT%H:%M:%S', strtotime(date("Y-m-d H:i:s"))) ?>" data-start-date="<?= strftime('%Y-%m-%dT%H:%M:%S', strtotime(date("Y-m-d H:i:s"))) ?>" class="form-control" data-name="start_date" name="start_date" placeholder="<?= lang("Start date") ?>">
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="form-label"><?= lang("End date") ?></label>
                      <input type="datetime-local" autocomplete="off" value="<?=strftime('%Y-%m-%dT%H:%M:%S', strtotime(date("Y-m-d H:i:s", strtotime ( '+1 week' , strtotime ( date("Y-m-d") ) ))))?>" data-end-date="<?=strftime('%Y-%m-%dT%H:%M:%S', strtotime(date("Y-m-d H:i:s", strtotime ( '+1 week' , strtotime ( date("Y-m-d") ) ))))?>" class="form-control" data-name="end_date" name="end_date" placeholder="<?= lang("End date") ?>">
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="form-label"><?= lang("Url") ?></label>
                      <input type="text" autocomplete="off" class="form-control" data-name="url" name="url" placeholder="<?= lang("Url") ?>">
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="form-label"><?= lang("Image") ?></label>
                      <input type="file" name="image" class="form-control" data-value="">
                  </div>
              </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang("Close") ?></button>
        <button type="button" class="btn btn-primary" data-role="add-slider"><?= lang("Add") ?></button>
      </div>
    </div>
  </div>
</div>
