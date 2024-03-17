<div class="modal fade" id="addPriceOffer" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" data-role="add-modal-content">
      <div class="modal-header" >
        <h5 class="modal-title" id="ModalLabel"><?= lang("Add price offer") ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body" data-role="add-modal-form">
        <form>
          <div class="row">
            <div class="form-group col-6">
              <label><?= lang("Company name") ?></label>
              <input autocomplete="off" data-role="company-input" type="text" name="company-name" class="form-control" placeholder="<?= lang("Company name") ?>">
              <p class="mt-2 text-danger d-none" data-role="alert-message" ></p>
            </div>
            <div class="form-group col-6">
              <label><?= lang("Price") ?></label>
              <input autocomplete="off" data-role="price-input" type="number" name="price-offer" class="form-control" placeholder="<?= lang("Price") ?>">
              <p class="mt-2 text-danger d-none" data-role="alert-message" ></p>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="product_id" value="">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?= lang("Close") ?></button>
        <button type="button" class="btn btn-info" data-role="add-price-offer-button" disabled><?= lang("Save") ?></button>
      </div>
    </div>
  </div>
</div>
