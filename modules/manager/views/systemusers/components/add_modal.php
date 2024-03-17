<div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" data-role="add-modal-content">
      <div class="modal-header" >
        <h5 class="modal-title" id="ModalLabel"><?= lang("Add system user") ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
              <label><?= lang("Surname") ?></label>
              <input autocomplete="off" type="text" name="surname" class="form-control" placeholder="<?= lang("Surname") ?>">
            </div>
            <div class="form-group col-6">
              <label><?= lang("Email") ?></label>
              <input autocomplete="off" type="email" name="email" class="form-control" placeholder="<?= lang("Email") ?>">
              <p class="mt-2 text-danger d-none" data-role="alert-message" ></p>
            </div>
            <div class="form-group col-6">
              <label><?= lang("Phone") ?></label>
              <input autocomplete="off" type="text" name="phone" class="form-control" placeholder="<?= lang("Phone") ?>">
            </div>
          <?php if (false): ?>
            <div class="form-group col-6">
             <label><?= lang("Group") ?></label>
             <select style="cursor:pointer;" data-role="select-groups" class="custom-select" name="group" disabled>

             </select>
           </div>
          <?php endif; ?>
           <div class="form-group col-6">
            <label><?= lang("Role") ?></label>
            <select name="role" class="custom-select" name="role">
              <?php if ($roles): ?>
                <?php foreach ($roles as $key => $role): ?>
                  <?php if ($role === "developer"): ?>
                    <?php if (Auth::isDeveloper()): ?>
                      <option value="<?= $role  ?>"><?= lang(ucfirst($role)) ?></option>
                    <?php endif; ?>
                  <?php else: ?>
                      <option value="<?= $role  ?>"><?= lang(ucfirst($role)) ?></option>
                  <?php endif; ?>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
           <div class="form-group col-6">
             <label><?= lang("Password") ?></label>
             <div class="input-group">
               <input autocomplete="off" type="text" name="password" class="form-control" placeholder="<?= lang("Password") ?>">
               <div class="input-group-prepend">
                 <span style="cursor:pointer;" class="input-group-text rounded-0 rounded-end" data-role="generate-password"><i data-role="generate" class="fas fa-sync"></i></span>
               </div>
             </div>
           </div>
           <div class="form-group col-6">
            <label><?= lang("Dashboard") ?></label>
            <div class="custom-control custom-switch">
               <input autocomplete="off" data-role="dashboard" name="dashboard" type="checkbox" class="custom-control-input" id="customSwitchesisActive" checked="">
               <label style="cursor:pointer;" class="custom-control-label" for="customSwitchesisActive"></label>
             </div>
          </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal"><?= lang("Close") ?></button>
        <button type="button" class="btn btn-info" data-role="add-user-button"><?= lang("Save") ?></button>
      </div>
    </div>
  </div>
</div>
