<div class="box">
    <div class="box-header with-border d-flex justify-content-between">
        <h4 class="box-title"><?= $promotion["promotion_title"] ?></h4>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-4">
                <img src="<?= path_local() . $promotion["promotion_photo_url"][0] ?>" class="img-fluid" alt="" />
            </div>
            <div class="col-md-8 d-flex flex-column justify-content-between">
                <div id="slimtest1">
                    <p><?= html_entity_decode($promotion["promotion_text"]) ?></p>
                </div>
                <div class="footer-card-promotion d-flex justify-content-end mt-2">
                    <span class="text-muted"><?= $promotion["promotion_ins_date"] ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
