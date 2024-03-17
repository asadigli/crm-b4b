<div class="modal fade" id="editWarehouse" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" data-role="add-modal-content">
      <div class="modal-header" >
        <h5 class="modal-title" id="ModalLabel"><?= lang("Edit warehouse") ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body" data-role="add-modal-form">
        <form>
          <div class="row">

            <div class="form-group col-6">
             <label><?= lang("Warehouses") ?></label>
             <select style="cursor:pointer;" data-role="warehouse-select" class="custom-select"  name="warehouse" disabled>

             </select>
           </div>

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?= lang("Close") ?></button>
        <button type="button" class="btn btn-info" data-role="edit-warehouse-button"><?= lang("Save") ?></button>
      </div>
    </div>
  </div>
</div>
