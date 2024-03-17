<div class="modal fade" id="supervisorAdd" tabindex="-1" aria-labelledby="supervisorAddLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="supervisorAddLabel"><?= lang("Add supervisor") ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="validation-form" novalidate="novalidate">
          <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="form-label"><?= lang("Name") ?></label>
                      <input type="text" autocomplete="off" class="form-control" data-name="name" name="name" placeholder="<?= lang("Name") ?>">
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="form-label"><?= lang("Surname") ?></label>
                      <input type="text" autocomplete="off" class="form-control" data-name="surname" name="surname" placeholder="<?= lang("Surname") ?>">
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="form-label"><?= lang("Phone") ?></label>
                      <input type="text" autocomplete="off" class="form-control" data-name="phone" name="phone" placeholder="<?= lang("Phone") ?>">
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="form-label"><?= lang("Email") ?></label>
                      <input type="text" autocomplete="off" class="form-control" data-name="email" name="email" placeholder="<?= lang("Email") ?>">
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="form-label"><?= lang("Whatsapp") ?></label>
                      <input type="text" autocomplete="off" class="form-control" data-name="whatsapp" name="whatsapp" placeholder="<?= lang("Whatsapp") ?>">
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="form-label"><?= lang("Name from AVA base") ?></label>
                      <input type="text" autocomplete="off" class="form-control" data-name="ava_name" name="ava_name" placeholder="<?= lang("Name from AVA base") ?>">
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="form-label"><?= lang("Image") ?></label>
                      <input type="file" name="image" class="form-control" data-value="">
                  </div>
              </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang("Close") ?></button>
        <button type="button" class="btn btn-primary" data-role="add-supervisor"><?= lang("Add") ?></button>
      </div>
    </div>
  </div>
</div>
