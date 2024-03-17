<div class="modal fade" id="newsEdit" tabindex="-1" aria-labelledby="newsEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div data-role="edit-modal-content" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newsEditLabel"><?= lang("Edit news") ?></h5>
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
                      <textarea class="form-control" data-name="body" name="body" placeholder="<?= lang("Description") ?>" rows="3" cols="80"></textarea>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="form-label"><?= lang("Start date") ?></label>
                      <input type="datetime-local" autocomplete="off" class="form-control" data-name="start_date" name="start_date" placeholder="<?= lang("Start date") ?>">
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="form-label"><?= lang("End date") ?></label>
                      <input type="datetime-local" autocomplete="off" class="form-control" data-name="end_date" name="end_date" placeholder="<?= lang("End date") ?>">
                  </div>
              </div>
              <div class="col-md-12" >
                <div id="news_edit_image" data-role="image"></div>
              </div>
              <div class="col-md-12 d-flex" data-item="img-load-data" data-role="image-load-data">

              </div>
              <div class="col-6">
                <div class="">
                   <input type="checkbox" data-role="is-active" name="is_active" id="is_edit_active" checked>
                   <label for="is_active"><?= lang("Active") ?></label>
                 </div>
             </div>
             <div class="col-6">
               <div class="">
                 <input type="checkbox" data-role="is-popup" name="is_popup" id="is_edit_popup" checked>
                  <label for="is_popup"><?= lang("Popup") ?></label>
                </div>
            </div>
            <div class="col-6">
             <select style="cursor:pointer;" data-role="types-select" class="custom-select"  name="type">

             </select>
           </div>
              <input type="hidden" name="id" value="">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang("Close") ?></button>
        <button type="button" class="btn btn-primary" data-role="save"><?= lang("Save") ?></button>
      </div>
    </div>
  </div>
</div>
