<div class="modal fade" id="addEntry" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" data-role="add-modal-content">
      <div class="modal-header" >
        <h5 class="modal-title" id="ModalLabel"><?= lang("Add entry") ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body" data-role="add-modal-form">
        <form>
          <div class="row">
            <div class="form-group col-6">
              <label><?= lang("Company name") ?></label>
              <input autocomplete="off" type="text" name="entry_name" class="form-control" placeholder="<?= lang("Entry name") ?>">
              <p class="mt-2 text-danger d-none" data-role="alert-message" ></p>
            </div>
            <div class="form-group col-6">
              <label><?= lang("Name") ?></label>
              <input autocomplete="off" type="text" name="name" class="form-control" placeholder="<?= lang("Name") ?>">
              <p class="mt-2 text-danger d-none" data-role="alert-message" ></p>
            </div>
            <div class="form-group col-6">
              <label><?= lang("Surname") ?></label>
              <input autocomplete="off" type="text" name="surname" class="form-control" placeholder="<?= lang("Surname") ?>">
            </div>
            <div class="form-group col-6">
              <label><?= lang("Phone") ?></label>
              <input autocomplete="off" type="text" name="phone" class="form-control" placeholder="<?= lang("Phone") ?>">
            </div>
            <div class="form-group col-6">
              <label><?= lang("Email") ?></label>
              <input autocomplete="off" type="email" name="email" class="form-control" placeholder="<?= lang("Email") ?>">
              <p class="mt-2 text-danger d-none" data-role="alert-message" ></p>
            </div>
            <div class="form-group col-6">
              <label><?= lang("Address") ?></label>
              <input autocomplete="off" type="text" name="address" class="form-control" placeholder="<?= lang("Address") ?>">
              <p class="mt-2 text-danger d-none" data-role="alert-message" ></p>
            </div>
            <div class="form-group col-6">
             <label><?= lang("Curator") ?></label>
             <select style="cursor:pointer;" class="custom-select"
                  data-text="<?= lang("Choose supervisor") ?>"  name="supervisor" disabled>
             </select>
           </div>
           <div class="form-group col-6">
            <label><?= lang("City") ?></label>
            <select style="cursor:pointer;" data-role="city-select" class="custom-select"
                  data-text="<?= lang("Choose city") ?>"  name="city" disabled>

            </select>
          </div>
           <div class="form-group col-6">
            <label><?= lang("Depo") ?></label>
            <select style="cursor:pointer;" data-role="depo-select" class="custom-select"
                    data-text="<?= lang("Choose warehouse") ?>" name="warehouse" disabled>

            </select>
          </div>
            <div class="form-group col-6">
             <label><?= lang("Ava customers") ?></label>
             <select style="cursor:pointer;" class="custom-select"
                      data-text="<?= lang("Choose AVA customer") ?>"  name="customer" multiple disabled>

             </select>
           </div>
           <div class="form-group col-6">
             <label><?= lang("Password") ?></label>
             <div class="input-group">
               <input autocomplete="off" type="text" name="password" class="form-control" placeholder="<?= lang("Password") ?>">
               <div class="input-group-prepend">
                 <span style="cursor:pointer;" class="input-group-text" data-role="generate-password"><i data-role="generate" class="fas fa-sync"></i></span>
               </div>
             </div>
           </div>
           <div class="form-group col-6">
             <label><?= lang("Entry limit") ?></label>
             <input autocomplete="off" type="text" name="limit" class="form-control" placeholder="<?= lang("Entry limit") ?>">
             <p class="mt-2 text-danger d-none" data-role="alert-message" ></p>
           </div>
           <div class="col-4">
             <div class="custom-control custom-checkbox">
                <input type="checkbox" data-role="is-active" name="is_active" id="is_active" checked>
                <label for="is_active"><?= lang("Active") ?></label>
              </div>
          </div>
           <div class="col-md-4">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" data-role="stock_show" name="stock_show" id="stock_show">
                <label for="stock_show"><?= lang("Stock show") ?></label>
              </div>
          </div>
          <div class="col-md-4">
             <div class="custom-control custom-checkbox">
               <input type="checkbox" data-role="is_store_active" name="is_store_active" id="is_store_active">
               <label for="is_store_active">60-90</label>
             </div>
         </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?= lang("Close") ?></button>
        <button type="button" class="btn btn-info" data-role="add-entry-button"><?= lang("Save") ?></button>
      </div>
    </div>
  </div>
</div>
