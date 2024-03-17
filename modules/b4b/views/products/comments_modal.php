<div class="modal fade" id="addComment" tabindex="-1" aria-labelledby="addCommentLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCommentLabel"><?= lang("Add comment") ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <!-- <div class="col-xxxl-8 col-lg-7 col-12"> -->
            <div class="box">
							  <div class="box-body">
								  <div class="slimScrollDiv">
                    <div class="chat-box-one2" data-role="comment-list">

                    </div>
                  </div>
							  </div>
                <div class="box-footer no-border">
                 <div class="d-md-flex d-block justify-content-between align-items-center bg-white p-5 rounded10 b-1 overflow-hidden">
                   <input class="form-control b-0 py-10" type="text" placeholder="<?= lang("Say something") ?>" data-role="comment-input" data-name="comment" name="comment" autocomplete="off">
                    <div class="d-flex justify-content-between align-items-center mt-md-0 mt-30">
                      <button type="button" class="btn btn-primary" data-role="add-comment" disabled>
                            <?= lang("Send it") ?>
                      </button>
                      <input type="hidden" name="product_id" value="">
                    </div>
                  </div>
                </div>
							</div>
          <!-- </div> -->
        </form>
      </div>
    </div>
  </div>
</div>
