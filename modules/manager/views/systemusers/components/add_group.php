<div class="modal fade" id="addGroup" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
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
             <label><?= lang("Groups") ?></label>
             <select style="cursor:pointer;" data-role="groups-select" class="custom-select"  name="order-group" multiple disabled>

             </select>
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
