$(function(){

  const newsComponent = (v,i) => {
    return `<div class="col-lg-3 col-md-4 col-sm-6">
              <div class="box promotion-card">
                <div class="box-header with-border">
                  <h5 class="box-title">${v.news_title || ""}</h5>
                </div>
                <div class="box-body">
                  <img src="${v.news_photo_url[0]}" class="img-fluid" alt="" />
                  <div class="d-flex justify-content-between mt-2">
                    <span class="text-muted">${v.date ? date_format(v.date,"d-m-y") : ""}</span>
                    <a href="/news/${v.news_id}/details">${lang("See details")}</a>
                  </div>
                </div>
            </div>
          </div>`;
  }

  let counter = null;

  const listAll = (data) => {

    let counter_start = new Date().getTime();
    counter = setInterval(() => {
      $(`[data-role="content-result-time"]`).html(new Date().getTime() - counter_start);
    },100)
    customLoader();
    $.get({
      url: `/news/list`,
      headers,
      success: function(d){
        let h = "";
        if (d.code === 200) {
          d.data.map((v,i) => {
            h += newsComponent(v,i);
          })
        }else{
          h = warningComponent(d.message)
        }
        $(`[data-role="content-result-count"]`).html(d.data.length);
        $("#news_list").html(h);
      },
      error: function(e){
        console.error(e)
      },
      complete: function(){
        ModalLoader.end();
        clearInterval(counter);
        customLoader(true);
      }
    })
  }

  listAll();


});
