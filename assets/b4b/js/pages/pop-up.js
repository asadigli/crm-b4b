$(function(){

  const popup_token = () => {
    let token = btoa(new Date().getTime());
    if(getCookie("popup_token")){
      let op_date = atob(getCookie("popup_token"));
      let now = new Date().getTime();
      if (now - (5 * 3600 * 1000) >= op_date) {
        setCookie("popup_token",token,1);
        return true;
      }
      return false;
    }
    setCookie("popup_token",token,1);
    return true;
  }

  const component = (d,i) => {
     d.images.map((v,i) => {
      //  console.log(v);
     })
    return  `<div class="notification-popup" data-name="popup-card" data-key="${i}">
              <div class="inner">
                <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                  <ol class="carousel-indicators">
                      ${ d.images.length ? d.images.map((v,i) => {
                       return `<li data-target="#carouselExampleCaptions" data-slide-to="${i}"
                       ${i === "0" ? "class='active'" : ''}></li>`
                      }).join("") : (
                       `<li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>`
                      )}
                  </ol>
                  <div class="carousel-inner" role="listbox">
                    ${ d.images.length ? d.images.map((v,i) => {
                      return `<a href="${window.location.origin + "/" + v ?? "javasript:void(0)"}" target="_blank" class="carousel-item ${i === 0 ? "active" : ""}">
                            <img class="d-block img-fluid" src="${window.location.origin + "/" + v ?? ''}" alt="${d.title ?? " "}" />
                              </a>`
                    }).join("") :
                    (`<div class="carousel-item active">
                        <img class="d-block img-fluid" src="" alt="${ d.title ?? " " }" />

                      </div>`)}
                  </div>
                  <a class="carousel-control-prev" role="button" data-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="sr-only">Previous</span>
                  </a>
                  <a class="carousel-control-next"  role="button" data-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="sr-only">Next</span>
                  </a>
                </div>
                <h4 class="">${ d.title ?? " " }</h4>
                <p class="">${ d.body ?? " " }</p>
              </div>
            </div>`;
  }


  const getPopup = () => {
    $.get({
      url: `/news-popup/list`,
      headers,
      success: function(d){
        // console.log(d);
        let h = "";
        if (d.code === 200) {
          overlay = `<div class="notf-popup-overlay"></div>`;
          h = d.data.map((v,i) => {
            return component(v,i);
          }).reverse().join("");
          $("body").addClass("overflow-hidden");
          $("body").append(h+overlay);
        }
      },
      error: function(e){
        console.error(e)
      },
      complete: function(){
      }
    })
  }
  if (popup_token()) {
    getPopup();
  }

  $(document).on("click",`.notification-popup`,function(e){
    $(this).remove();
    if(!$(`.notification-popup`).length){
      $("body").removeClass("overflow-hidden");
      $(".notf-popup-overlay").remove();
    }
  });
});
