<div class="modal fade" id="addGroup" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" data-role="add-modal-content">
      <div class="modal-header" >
        <h5 class="modal-title" id="ModalLabel"><?= lang("Add order group") ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body" data-role="add-modal-form">
        <form>
          <div class="row">
            <div class="form-group col-6">
              <label><?= lang("Name") ?></label>
              <input autocomplete="off" type="text" name="name" class="form-control" placeholder="<?= lang("Name") ?>">
              <p class="mt-2 text-danger d-none" data-role="alert-message" ></p>
            </div>
            <div class="form-group col-6">
              <label><?= lang("Description") ?></label>
              <textarea name="description" class="form-control" rows="1" cols="80" placeholder="<?= lang("Description") ?>"></textarea>
              <p class="mt-2 text-danger d-none" data-role="alert-message" ></p>
            </div>
            <div class="form-group col-6">
              <label><?= lang("Details") ?></label>
              <textarea name="details" class="form-control" rows="2" cols="80" placeholder="<?= lang("Details") ?>"></textarea>
              <p class="mt-2 text-danger d-none" data-role="alert-message" ></p>
            </div>
            <div class="form-group col-6">
             <label><?= lang("Warehouse") ?></label>
             <select style="cursor:pointer;" data-role="select-warehouses" class="custom-select" name="warehouse" disabled>

             </select>
           </div>
           <div class="form-group col-6">
             <label><?= lang("Default filter") ?></label>
             <input autocomplete="off" type="date" name="default_start_date" class="form-control">
             <p class="mt-2 text-danger d-none" data-role="alert-message" ></p>
           </div>
           <div class="form-group col-6">
            <label><?= lang("Status") ?></label>
            <div class="custom-control custom-switch">
               <input autocomplete="off" data-role="is_active" name="is_active" type="checkbox" class="custom-control-input" checked="">
               <label style="cursor:pointer;" class="custom-control-label" for="customSwitchesisActive"></label>
             </div>
          </div>
           <div class="form-group col-6">
            <label><?= lang("Is remote") ?></label>
            <div class="custom-control custom-switch">
               <input autocomplete="off" data-role="is_remote" name="is_remote" type="checkbox" class="custom-control-input" >
               <label style="cursor:pointer;" class="custom-control-label" for="customSwitchesisActive"></label>
             </div>
          </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?= lang("Close") ?></button>
        <button type="button" id="btn_loader_id" class="btn btn-info" data-role="add-group-button"><?= lang("Save") ?></button>
      </div>
    </div>
  </div>
</div>
