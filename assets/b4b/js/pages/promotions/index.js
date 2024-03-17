$(function(){

  const promotionsComponent = (v,i) => {
    return `<div class="col-md-3">
              <div class="box promotion-card">
                <div class="box-header with-border">
                  <h5 class="box-title">${v.promotion_title || ""}</h5>
                </div>
                <div class="box-body">
                  <img src="${v.promotion_photo_url}" class="img-fluid" alt="" />
                  <p>${stripHtml(v.promotion_text)}</p>
                  <div class="d-flex justify-content-between mt-2">
                    <span class="text-muted">${v.promotion_ins_date ? date_format(v.promotion_ins_date,"d-m-y") : ""}</span>
                    <a href="/promotions/${v.promotion_id}/details">${lang("See details")}</a>
                  </div>
                </div>
            </div>
          </div>`
  }

  let counter = null;

  const listAll = (data) => {
    let counter_start = new Date().getTime();
    counter = setInterval(() => {
      $(`[data-role="content-result-time"]`).html(new Date().getTime() - counter_start);
    },100)
    customLoader();
    $.get({
      url: `/promotions/list`,
      headers,
      data,
      success: function(d){
        let h = "";
        if (d.code === 200) {
          d.data.map((v,i) => {
            h += promotionsComponent(v,i);
          })
        }else{
          h = warningComponent(d.message)
        }
        $("#promotions_list").html(h);
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
