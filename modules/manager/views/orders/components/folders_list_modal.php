<div class="modal fade" id="folders-list" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="padding: 0.3rem 1rem;!important">
        <h5 class="modal-title"><?= lang("Folders") ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
        	<div class="col-lg-12 grid-margin stretch-card">
            <div class="p-0 m-0">

        			<?php if (false): ?>
                <div class="row justify-content-between">
                  <div class="col-6">
                    <input
                      class="form-control ui-autocomplete-input"
                      type="text"
                      data-role="search-keyword-in-folder"
                      placeholder="<?= lang("Search") ?>"
                      autocomplete="off"
                      >
                  </div>
                  <div class="col-6 d-flex justify-content-end align-items-center" >
                    <div class="me-2"><b data-role="content-result-count" > 0 </b> <?= strtolower(lang("Result")) ?></div>
                    <button
                      data-role="add-folder-modal"
                      data-bs-toggle="modal"
                      data-bs-target="#folders-add"
                      class="btn btn-info">
                      <i class="fa-regular fa-plus"></i>
                    </button>
                  </div>
                </div>

              <?php endif; ?>
              <div class="table-responsive mt-2">
                <table class="table table-bordered table-hover table-striped" style="width:100%;">
                  <thead>
                    <tr>
                      <th style="width:5%;"  >#</th>
                      <th style="width:20%;"  ><?= lang("Name") ?></th>
                      <th style="width:50%;"  ><?= lang("Description") ?></th>
                      <th style="width:8%;"  ></th>
                      <th style="width:10%;"  ><?= lang("Active") ?></th>
                      <th style="width:5%;"  ></th>
                    </tr>
                  </thead>
                  <tbody data-role="folders-table-list" >

                  </tbody>
                  <tfoot>
                    <tr>
                      <td style="padding:0;margin:0;" colspan="200">
                        <div class="d-flex justify-content-center" >
                          <div style="width:80%;text-align:center; min-height: 30px; color: #676689!important;background-color : transparent !important;border:none !important;"
                          class="c-pointer d-flex align-items-center justify-content-around"
                          data-role="add-folder-tr"
                          >
                              <div>
                                <strong><?= lang("Add") ?></strong>
                                <i class="fa-regular fa-plus"></i>
                              </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tfoot>
                  <div data-role="folder-messages" class="d-flex justify-content-center d-none" >
                    <div style="width:80%;text-align:center;color: #676689!important;background-color : transparent !important;border:none !important;"
                    class="c-pointer text-danger"
                    data-role="add-folder-tr"
                    >
                        <strong data-role="text" ></strong>
                        <i class="fa-regular fa-plus"></i>
                    </div>
                  </div>
                </table>

              </div>

            </div>
          </div>
        </div>
      </div>
      <?php if (false): ?>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang("Close") ?></button>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
