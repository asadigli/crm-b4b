<div class="modal fade" id="addConfig" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" data-role="add-modal-content">
      <div class="modal-header" >
        <h5 class="modal-title" id="ModalLabel"><?= lang("Add config") ?></h5>
      </div>
      <div class="modal-body" data-role="add-modal-form">
        <form>
          <div class="row">
            <div class="form-group col-6">
              <label><?= lang("Key") ?></label>
              <input autocomplete="off" type="text" name="key" class="form-control" placeholder="<?= lang("Key") ?>">
              <p class="mt-2 text-danger d-none" data-role="alert-message" ></p>
            </div>
            <div class="form-group col-6">
              <label><?= lang("Value") ?></label>
              <input autocomplete="off" type="text" name="value" class="form-control" placeholder="<?= lang("Value") ?>">
            </div>
            <div class="form-group col-6">
             <label><?= lang("Group") ?></label>
             <select name="group" class="custom-select" name="group">
               <?php if ($properties["groups"]): ?>
                 <?php foreach ($properties["groups"] as $key => $group): ?>
                   <option value="<?= $group  ?>"><?= lang(ucfirst($group)) ?></option>
                 <?php endforeach; ?>
               <?php endif; ?>
             </select>
           </div>
            <div class="form-group col-6">
             <label><?= lang("Type") ?></label>
             <select name="type" class="custom-select" name="type">
               <?php if ($properties["types"]): ?>
                 <?php foreach ($properties["types"] as $key => $type): ?>
                   <option value="<?= $type  ?>"><?= lang(ucfirst($type)) ?></option>
                 <?php endforeach; ?>
               <?php endif; ?>
             </select>
           </div>
            <div class="form-group col-6">
             <label><?= lang("Resource") ?></label>
             <select name="resource" class="custom-select" name="resource">
               <?php if ($properties["resources"]): ?>
                 <?php foreach ($properties["resources"] as $key => $resource): ?>
                   <option value="<?= $resource  ?>"><?= lang(ucfirst($resource)) ?></option>
                 <?php endforeach; ?>
               <?php endif; ?>
             </select>
           </div>
           <div class="form-group col-6">
               <label><?= lang("Status") ?></label>
             <input type="checkbox" name="active" data-text="is_active" data-role="is_active">
          </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?= lang("Close") ?></button>
        <button type="button" class="btn btn-info" data-role="config-save-btn"><?= lang("Save") ?></button>
      </div>
    </div>
  </div>
</div>
