<div class="modal fade" id="add-modal" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" data-role="add-modal-content">
      <div class="modal-header" >
        <h5 class="modal-title" id="ModalLabel"><?= lang("Add 60-90 products") ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" data-role="add-excel-modal-form">
        <div class="row align-items-center">
          <div class="col-4">
            <div class="form-group">
              <label><?= lang("Choose brand") ?></label>
                <select data-role="select-brands" name="brand_id" class="form-control custom-select">
                  <option value=""><?= lang("All brands") ?></option>
                </select>
              <p class="mt-2 text-danger d-none" data-role="alert-message" data-name="type_id" ></p>
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
              <label><?= lang("Choose currency") ?></label>
                <select data-role="select-currencies" name="currency_id" class="form-control custom-select">
                  <option value=""><?= lang("Currencies") ?></option>
                </select>
              <p class="mt-2 text-danger d-none" data-role="alert-message" data-name="type_id" ></p>
            </div>
          </div>
          <div class="col-4">
            <label><?= lang("Price rate") ?></label>
            <div class="input-group mb-3">
              <input type="number" name="brand_price_rate" class="form-control" placeholder="0" aria-describedby="basic-addon1">
              <span class="input-group-text rounded-0 rounded-end" id="basic-addon1">%</span>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label><?= lang("Description") ?></label>
              <input autocomplete="off" type="text" class="form-control" name="description">
              <p class="mt-2 text-danger d-none" data-role="alert-message" data-name="description" ></p>
            </div>
          </div>
          <div class="col-12 ">
            <div class="mb-3">
              <label for="formFile" class="form-label"><?= lang("Choose Excel") ?></label>
              <input name="excel_file" class="form-control" type="file" id="formFile">
            </div>
          </div>
          <?php if (false): ?>
            <div class="col-6 mt-4">
              <div class="custom-control custom-switch">
                <input autocomplete="off" data-role="check-is-b2b-active" name="b2b_active" type="checkbox" class="custom-control-input" id="status-add-checkbox" checked="">
                <label class="custom-control-label c-pointer" for="status-add-checkbox"><?= lang("B2B active") ?></label>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang("Close") ?></button>
        <button type="button" id="btn_loader_add_id" class="btn btn-info"  data-role="save-add-modal" ><i></i> <?= lang("Download") ?></button>
      </div>
    </div>
  </div>
</div>
