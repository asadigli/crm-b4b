<div class="modal fade" id="folders-add" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content" data-role="folders-add-modal-content" >
      <div class="modal-header">
        <h5 class="modal-title"><?= lang("Add folder") ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="">
          <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <label class="form-label"><?= lang("Name") ?></label>
                    <input type="text" autocomplete="off" class="form-control" data-name="name" name="name" placeholder="<?= lang("Name") ?>">
                    <div style="padding: 4px;margin-top: 7px;border-radius: 5px;" class="alert alert-danger d-none" data-role="alert-message" role="alert">
                    </div>
                  </div>
            </div>
            <div class="col-md-7">
                <div class="form-group">
                    <label class="form-label"><?= lang("Description") ?></label>
                    <textarea class="form-control" name="description" rows="4" cols="80"></textarea>
                </div>
            </div>
            <div class="col-md-7">
              <div class="form-check form-switch">
                <input name="is_active" class="form-check-input c-pointer" type="checkbox" role="switch" id="addFolder" checked>
                <div data-role="is-active-lang" class="badge badge-success"><?= lang("Active") ?></div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang("Close") ?></button>
        <button type="button" class="btn btn-primary" id="btn_loader_id" data-role="save-folder-add-modal" >
          <?= lang("Add") ?>
        </button>
      </div>
    </div>
  </div>
</div>
