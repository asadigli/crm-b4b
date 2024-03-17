<div class="box">
    <div class="box-header with-border d-flex justify-content-between">
        <h4 class="box-title"><?= $news["news_title"] ?></h4>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-4">
                <img src="<?= path_local() . $news["news_photo_url"] ?>" class="img-fluid" alt="" />
            </div>
            <div class="col-md-8 d-flex flex-column justify-content-between">
                <div id="slimtest1">
                    <p><?= html_entity_decode($news["news_text"]) ?></p>
                </div>
                <div class="footer-card-promotion d-flex justify-content-end mt-2">
                    <span class="text-muted"><?= $news["news_date"] ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
