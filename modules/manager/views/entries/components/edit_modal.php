<div class="modal fade" id="editProperties" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" data-role="edit-modal-content">
      <div class="modal-header" >
        <h5 class="modal-title" id="ModalLabel"><?= lang("Edit entry") ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body" data-role="edit-modal-form">
        <form>
          <div class="row">
            <div class="form-group col-6">
              <label><?= lang("Responsible person name") ?></label>
              <input autocomplete="off" type="text" name="person_name" class="form-control" placeholder="<?= lang("Name") ?>">
              <p class="mt-2 text-danger d-none" data-role="alert-message" ></p>
            </div>
            <div class="form-group col-6">
              <label><?= lang("Responsible person surname") ?></label>
              <input autocomplete="off" type="text" name="person_surname" class="form-control" placeholder="<?= lang("Surname") ?>">
            </div>
            <div class="form-group col-6">
              <label><?= lang("Address") ?></label>
              <input autocomplete="off" type="text" name="address" class="form-control" placeholder="<?= lang("Address") ?>">
            </div>
            <div class="form-group col-6">
             <label><?= lang("Curator") ?></label>
             <select style="cursor:pointer;" data-role="supervisor-select" class="custom-select"  name="supervisor" disabled>

             </select>
           </div>
            <div class="form-group col-6">
             <label><?= lang("City") ?></label>
             <select style="cursor:pointer;" data-role="city-select" class="custom-select"  name="city" disabled>

             </select>
           </div>
            <div class="form-group col-6">
             <label><?= lang("Depo") ?></label>
             <select style="cursor:pointer;" data-role="depo-select" class="custom-select"  name="warehouse" disabled>

             </select>
           </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?= lang("Close") ?></button>
        <button type="button"  class="btn btn-info" data-role="edit-properties-button"><?= lang("Save") ?></button>
      </div>
    </div>
  </div>
</div>
