let mt = $("meta[name='meta_token']").attr("content");

$.fn.isInViewport = function() {
  if(!$(this).length) return;
    const elementTop = $(this).offset().top;
    const elementBottom = elementTop + $(this).outerHeight();
    const viewportTop = $(window).scrollTop();
    const viewportBottom = viewportTop + $(window).height();

    return elementBottom > viewportTop && elementTop < viewportBottom;
};

const export_table = (id,fl,sht) => {
  let table = document.getElementById(id),
      filename = fl || `export`,
      sheetname = sht || 'sheet 1';
  TableToExcel.convert(table, {
    name: filename+'.xlsx',
    sheet: {
      name: sheetname
    }
  });
  return true;
}

const notify = (icon,message,customClass = null) => {
  Swal.fire({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 5000,
    icon: icon,
    title: message,
    customClass: "swal-notify-mini",
    showCloseButton: true,
  });
};

const highlightLabel = (result, querystr) => {
  let reg = new RegExp(querystr, 'gi');
  let final_str = result.replace(reg, function(str) {return '<mark>'+str+'</mark>'});
  return final_str;
}

const validateEmail = (email) => {
  return email.match(
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  );
};

const disableAll = (bool) => {
  $("button").prop("disabled",bool);
  $("input").prop("disabled",bool);
  $("select").prop('disabled', bool);
};

const createElementFromHTML = (htmlString) => {
  var div = document.createElement('div');
  div.innerHTML = htmlString.trim();
  return div.firstChild;
}

 const customLoader = (remove = null) => {
    if (remove) {
      $("body").removeClass("modal-loading");
      return;
    }
    $("body").addClass("modal-loading");
  }

const AwesomeBtn = {
  spin: function(btn) {
    btn.find("i").addClass("fa-spinner").addClass("fa-spin");
  },
  success: function(btn) {
    btn.find("i").removeClass("fa-spinner").removeClass("fa-spin");
    btn.find("i").addClass("fa-check");
  },
  warning: function(btn) {
    btn.addClass("btn-warning");
    btn.find("i").removeClass("fa-spinner").removeClass("fa-spin");
    btn.find("i").addClass("fa-exclamation");
  },
  error: function(btn) {
    btn.addClass("btn-danger");
    btn.find("i").removeClass("fa-spinner").removeClass("fa-spin");
    btn.find("i").addClass("fa-triangle-exclamation");
  },
  end: function(btn,time) {
    const removeCheck = (btn) => {
      btn.removeClass("btn-danger");
      btn.removeClass("btn-warning");
      btn.find("i").removeClass("fa-check");
      btn.find("i").removeClass("fa-exclamation");
      btn.find("i").removeClass("fa-triangle-exclamation");
      btn.find("i").removeClass("fa-spinner").removeClass("fa-spin");
    }
    setTimeout(() => {removeCheck(btn)}, time * 1000);
  }
}

const ModalLoader = {
  start: function(text) {
    document.body.appendChild(createElementFromHTML(`<div class="modal-loader" data-role="modal-loader">
              <div class="inner">
                  <img src="/assets/globals/image/gif/loading-screen.gif" alt="">
                  <span>${text}</span>
              </div>
          		<div class="modal-loader-overlay"></div>
          	</div>`));
    document.body.className += " modal-loading";
  },

  end: function() {
    document.body.className = document.body.className.replace(" modal-loading","");
    document.querySelectorAll('[data-role="modal-loader"]').forEach(v => {
      v.remove();
    });
  }
}

var total_cached_seconds = 0;
const ModalCacheLoader = {
  start: function(text) {

    document.body.appendChild(createElementFromHTML(`<div class="modal-loader-box" data-role="modal-loader">
          		<div class="inner">
                <h5 class="mb-4">${text}</h5>
                <div class="bar-loader mt-2">
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
                <div class="d-flex mt-2" data-role="modal-cached-time" data-time="" ><label id="minutes">00</label>:<label id="seconds">00</label></div>
          		</div>
          		<div class="overlay"></div>
          	</div>`));
    document.body.className += " loading-modal";


    let minutesLabel = document.getElementById("minutes");
    let secondsLabel = document.getElementById("seconds");
    let totalSeconds = 0;
    total_cached_seconds = 0;
    setInterval(setTime, 1000);

    function setTime() {
      ++totalSeconds;
      total_cached_seconds = totalSeconds;
      secondsLabel.innerHTML = pad(totalSeconds % 60);
      minutesLabel.innerHTML = pad(parseInt(totalSeconds / 60));
    }

    function pad(val) {
      let valString = val + "";
      if (valString.length < 2) {
        return "0" + valString;
      } else {
        return valString;
      }
    }
  },

  end: function() {
    document.body.className = document.body.className.replace(" loading-modal","");
    document.querySelectorAll('[data-role="modal-loader"]').forEach(v => {
      v.remove();
    });
  },

}

let slugify = (str) => {
  str = str.toLowerCase();

  // remove accents, swap ñ for n, etc
  var from = "ÁÄÂÀÃÅČÇĆĎÉĚËÈÊẼĔȆĞÍÌÎÏİŇÑÓÖÒÔÕØŘŔŠŞŤÚŮÜÙÛÝŸŽáäâàãåčçćďéěëèêẽĕȇğíìîïıňñóöòôõøðřŕšşťúůüùûýÿžþÞĐđßÆa·/_,:;";
  var to   = "AAAAAACCCDEEEEEEEEGIIIIINNOOOOOORRSSTUUUUUYYZaaaaaacccdeeeeeeeegiiiiinnooooooorrsstuuuuuyyzbBDdBAa------";
  for (var i = 0, l = from.length; i < l; i++) {
    str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
  }

  str =  str.replace(/ /g, '-')
            .replace(/[^\w-]+/g, '');

  return str;
};

const setCookie = (name,value,days) => {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
const getCookie = (name) => {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

const eraseCookie = (name) => {
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

const convertToSlug = (Text) => {
  return Text.toLowerCase()
             .replace(/[^\w ]+/g, '')
             .replace(/ +/g, '-');
}

// const eraseCookie = (name) => {
//     document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
// }


let getBase64 = (file) => {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = error => reject(error);
  });
}

const path_local = (url) => {
   let lang = $("html").attr("lang");
   let path = lang === "az" ? "/" : `/${lang}/`;
   return path + (url ? url : "");
}

const stripHtml = (html) => {
   let tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
}

const warningComponent = (message) => {
  return `<tr>
            <td style="padding:0;margin:0;" colspan="200">
              <div class="d-flex justify-content-center" >
                <div style="margin:0.3rem 0.8rem; width:80%;text-align:center;color: #676689!important;background-color : transparent !important;border:none !important;" class="alert alert-warning text-warning fade show" role="alert">
                    <strong>${message ?? ""}</strong>
                </div>
              </div>
            </td>
          </tr>`;
};

 let allLangs = [];
 const lang = (key,param = null) => {
  if (!allLangs.length) {
      allLangs = JSallLangs;
      return typeof allLangs[key] !== "undefined" ? allLangs[key] : `no_locale.${key}`;
    }
    return typeof allLangs[key] !== "undefined" ? allLangs[key] : `no_locale.${key}`;
  }
  if (document.querySelectorAll(`[data-role="js-all-langs"]`)[0]) {
    document.querySelectorAll(`[data-role="js-all-langs"]`)[0].remove();
  }

const paginateController = (count, t, current_page, selector) => {
  if(t && t.hasClass("active")) return false;
  let show_count = 7,
      page_list = "",
      disabled_link = `<a class="cdp_i" disabled>...</a>`,
      next = current_page ? parseInt(current_page) : (t && t.data("val") ? parseInt(t.data("val")) : null),
      $sel = selector ? selector : $(`[data-role="pagination"]`);
  if (!$sel.length) {console.error("Selector not found");return;}
  if (t && !next) {
    let selected_page = parseInt(t.siblings("a.active").data("val")) || 1;
    if (t.data("role") === "next" && selected_page < count) {
      selected_page++;
    }else if(t.data("role") === "prev" && selected_page > 1){
      selected_page--;
    }
    next = selected_page;
    t = t.siblings(`a[data-val="${next}"]`);
  }

  for (let i = 0; i < count; i++) {
    let cls = "";
    if (count > show_count) {
      if (!t && !current_page) {
        cls = (i > (show_count - 3))  ? " d-none " : "";
      }else{
        if (next > 4 && next < (count - 1)) {
          cls =  i < (next - 3) || i >= (next + 2) ? " d-none " : "";
        }else if(next > (count - 1)){
          cls =  i < (next - 5)  ? " d-none " : "";
        }else if(next > (count - 2)){
          cls =  i < (next - 4)  ? " d-none " : "";
        }else{
          cls = i > 4 ?  " d-none " : "";
        }
      }
    }
    page_list += `<a class="cdp_i${cls}${next === (i + 1) ? " active" : (!next && !i ? " active" : "")}" data-val="${i + 1}">${i+1}</a>`;
  }
  $sel.html(
    `
    <a data-role="prev" class="mobile-btn-pgn"><img src="/assets/cms/img/icons/left-arrow.svg" alt=""></a>
    ${next > (show_count - 3) && count > show_count ? `<a class="cdp_i" data-val="1">1</a>${disabled_link}` : ""}
    ${page_list}
    ${count > show_count && next < (count - (show_count - 5)) ? `${disabled_link}<a class="cdp_i" data-val="${count}">${count}</a>` : ""}

    <a data-role="next" class="mobile-btn-pgn"><img src="/assets/cms/img/icons/right-arrow.svg" alt=""></a>`
  ).attr("class","content_detail__pagination cdp").attr("actpage","1");
   // + (!t ? " d-none" : "")
  return next;
}
//<a data-role="prev" class="desktop-btn-pgn">${l('Previous')}</a>
//<a data-role="next" class="desktop-btn-pgn">${l('Next')}</a>

const latin_base64_encode = (str) => btoa(unescape(encodeURIComponent(str)));
const latin_base64_decode = (str) => decodeURIComponent(escape(window.atob(str)));

const get_date = (manual_date) => {
  var dt = manual_date ? new Date(manual_date) : new Date();
  var dd = String(dt.getDate()).padStart(2, '0');
  var mm = String(dt.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy = dt.getFullYear();
  dt = dd + '/' + mm + '/' + yyyy;
  return dt;
}

let incrementValue = (e) => {
  e.preventDefault();
  var fieldName = $(e.target).data('field');
  var parent = $(e.target).closest('div');
  var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

  if (!isNaN(currentVal)) {
    parent.find('input[name=' + fieldName + ']').val(currentVal + 1);
  } else {
    parent.find('input[name=' + fieldName + ']').val(0);
  }
  return currentVal + 1;
}

let decrementValue = (e) => {
  e.preventDefault();
  var fieldName = $(e.target).data('field');
  var parent = $(e.target).closest('div');
  var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

  if (!isNaN(currentVal) && currentVal > 0) {
    parent.find('input[name=' + fieldName + ']').val(currentVal - 1);
  } else {
    parent.find('input[name=' + fieldName + ']').val(0);
  }
  return currentVal - 1;
}

let getUrlParameter = function getUrlParameter(sParam) {
  var sPageURL = window.location.search.substring(1),
      sURLVariables = sPageURL.split('&'),
      sParameterName,
      i;

  for (i = 0; i < sURLVariables.length; i++) {
      sParameterName = sURLVariables[i].split('=');

      if (sParameterName[0] === sParam) {
          return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
      }
  }
};

let delay = (callback, ms) => {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}

let number_format = (number, decimals, dec_point, thousands_point) => {
      if (number == null || !isFinite(number)) {throw new TypeError("number is not valid");}
      if (!decimals) {
          var len = number.toString().split('.').length;
          decimals = len > 1 ? len : 0;
      }
      if (!dec_point) {
          dec_point = '.';
      }
      if (!thousands_point) {
          thousands_point = ',';
      }
      number = parseFloat(number).toFixed(decimals);
      number = number.replace(".", dec_point);
      var splitNum = number.split(dec_point);
      splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
      number = splitNum.join(dec_point);
      return number;
  }

let $ld = $(".loading");

let pg_lang = $('html').attr("lang");

let isset = obj => {return typeof obj !== 'undefined' ? true : false;}

// let variable = 123;

// let proj_name = "Avto Hesab";

const environment = document.body.hasAttribute("data-localhost") ? document.body.getAttribute("data-localhost") : '/';

let wplink = `https://api.whatsapp.com/send?phone=`;
let rand = () => Math.random(0).toString(36).substr(2);
let token = (length) => (rand()+rand()+rand()+rand()).substr(0,length);

let redMess = (message) => `<small class="text-danger">${message}</small>`;

let isInValid = (selector) => {
  let s = $(selector);
  let val = s.val();
  if (!val || val.length == 0) {
    s.addClass('is-invalid');
    return false;
  }else{
    s.hasClass('is-invalid') ? s.removeClass('is-invalid') : '';
  }
  return val;
}

let emojis = ['#9989;','#9997;','#10060;','#10068;','#10069;','#127383;','#128077;','#129488;','#128545;','#128512;','#128077;','#128078;','#129309;','#x1F600', '#x1F604', '#x23F0','#x1F4DA'];


let checkUrl = (url, cb) => {
  $.ajax({
    url:url,dataType: 'text',type:'GET',
    complete:function(xhr){
      if(typeof cb === 'function'){cb.apply(this, [xhr.status]);}
    }
  });
}


let changeurl = (page, url) => {
  if (typeof (history.pushState) != "undefined") {
    let obj = {Page: page, Url: url};
    history.pushState(obj, obj.Page, (obj.Url ? obj.Url : obj.Page));
  } else {
    window.location.href = "/";
  }
}

let remove_hash = (c_url) => {
  return c_url.substring(c_url, c_url.indexOf('#'));
}

let filter_url = (arr) => {
  let link = "",index = 0;
  for (let i = 0; i < arr.length; i++) {
    let val = Object.values(arr[i])[0] ? Object.values(arr[i])[0] : null,
        key = Object.keys(arr[i])[0];
    if (val && val.length > 0) {
      link += !index ? `?${key}=${val}` : `&${key}=${val}`;
      index++;
    }
  }
  let lh = location.href;
  let base_url = lh.substring(0 , lh.indexOf('?'));
  changeurl(lh,link ? `${base_url}${link}` : base_url);
}

let str_limit = (text, limit) => {
  return text.length > limit ?
           text.substring(0, limit) + '...' :
           text;
}
// let first_order_added = [];
let baseName = (str) => {
   let base = new String(str).substring(str.lastIndexOf('/') + 1);
   base = base.lastIndexOf(".") != -1 ? base.substring(0, base.lastIndexOf(".")) : base;
   base = base.indexOf('?') > 0 ? base.substring(0, base.indexOf('?')) : base;
   return base.indexOf('#') > 0 ? base.substring(0, base.indexOf('#')) : base
}
let $_get = (index,url) => {
  let path = url ? url : location.href;
  let vars = path.substring(path.indexOf("?") + 1).split("&");
  let qs = {};
  for (var i = 0; i < vars.length; i++) {
    let pair = vars[i].split("=");
    let key = decodeURIComponent(pair[0]),
        value = remove_hash(decodeURIComponent(pair[1]));
    if (typeof qs[key] === "undefined") {
      qs[key] = decodeURIComponent(value);
    } else if (typeof qs[key] === "string") {
      var arr = [qs[key], decodeURIComponent(value)];
      qs[key] = arr;
    } else {qs[key].push(decodeURIComponent(value));}
  }
  return qs[index] || "";
}

let go_tp = document.getElementById("go_to_top");
let scrollFunction = () => {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    go_tp.style.display = "block";
  } else {
    go_tp.style.display = "none";
  }
}
let btn_spinner = `<i class="fas fa-spinner fa-spin"></i>`;

let l = (k) => {
  return k;
  // if (lang.length == 0) {
  //   let res = "";
  //   $.ajax({
  //     async: false,type: 'GET',cache:true,
  //     headers: hdKey,
  //     url: '/get-lang-list',data: {token: mt},
  //     success:function(d){
  //       d.forEach((v, i) => {
  //         let key = Object.keys(v)[0],val = Object.values(v)[0] || '';
  //         lang.push([key,val]);
  //       });
  //       res = Object.fromEntries(lang)[k] ? Object.fromEntries(lang)[k]  : `app.${k}`;
  //     }
  //   });
  //   return res;
  //   // return isset(res) ? res : l(k);
  // }else{
  //   return Object.fromEntries(lang)[k] ? Object.fromEntries(lang)[k]  : `app.${k}`;
  // }
}


// const Toast = Swal.mixin({
//   toast: true,
//   position: 'top-end',
//   showConfirmButton: false,
//   timer: 3000
// });
let notify_once = (mess,type,time) => {
  // Expected "success", "error", "warning", "info" or "question", got "danger"
  Toast.fire({
    type: type,
    title: mess,
    timer: (time ? time : 3000)
  });
}


let success_popup = (title,message,icon) => {
  Swal.fire(
    title,message,icon
  );
}
let confirm = (title,text,confirm,succ_title,succ_text,func,arr) => {
  Swal.fire({
    title: title,
    text: text,
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: confirm
  }).then((result) => {
    if (result.value) {
      success_popup(succ_title,succ_text,'success');
      func(arr);
    }
  })
}
let timeSince = (date) => {
  let seconds = Math.floor((new Date() - date) / 1000);
  let interval = Math.floor(seconds / 31536000);
  if (interval > 1) {
    return interval + " " + l('years');
  }
  interval = Math.floor(seconds / 2592000);
  if (interval > 1) {
    return interval + " " + l('months');
  }
  interval = Math.floor(seconds / 86400);
  if (interval > 1) {
    return interval + " " + l('days');
  }
  interval = Math.floor(seconds / 3600);
  if (interval > 1) {
    return interval + " " + l('hours');
  }
  interval = Math.floor(seconds / 60);
  if (interval > 1) {
    return interval + " " + l('minutes');
  }
  return Math.floor(seconds) + " " + l('seconds');
}


let $db = $(document.body);
let em = '--';


/*  removeStorage: removes a key from localStorage and its sibling expiracy key
   params:
       key <string>     : localStorage key to remove
   returns:
       <boolean> : telling if operation succeeded
*/
const removeStorage = (name) => {
   try {
       localStorage.removeItem(name);
       localStorage.removeItem(name + '_expiresIn');
   } catch(e) {
       console.log('removeStorage: Error removing key ['+ key + '] from localStorage: ' + JSON.stringify(e) );
       return false;
   }
   return true;
}
/*  getStorage: retrieves a key from localStorage previously set with setStorage().
   params:
       key <string> : localStorage key
   returns:
       <string> : value of localStorage key
       null : in case of expired key or failure
*/
const getStorage = (key) => {

   let now = Date.now();  //epoch time, lets deal only with integer
   // set expiration for storage
   let expiresIn = localStorage.getItem(key+'_expiresIn');
   if (expiresIn===undefined || expiresIn===null) { expiresIn = 0; }

   if (expiresIn < now) {// Expired
       removeStorage(key);
       return null;
   } else {
       try {
           let value = localStorage.getItem(key);
           return value;
       } catch(e) {
           console.log('getStorage: Error reading key ['+ key + '] from localStorage: ' + JSON.stringify(e) );
           return null;
       }
   }
}
/*  setStorage: writes a key into localStorage setting a expire time
   params:
       key <string>     : localStorage key
       value <string>   : localStorage value
       expires <number> : number of seconds from now to expire the key
   returns:
       <boolean> : telling if operation succeeded
*/
const setStorage = (key, value, expires) => {

   if (expires === undefined || expires===null) {
       expires = (24*60*60);  // default: seconds for 1 day
   } else {
       expires = Math.abs(expires); //make sure it's positive
   }

   let now = Date.now();  //millisecs since epoch time, lets deal only with integer
   let schedule = now + expires*1000;
   try {
       localStorage.setItem(key, value);
       localStorage.setItem(key + '_expiresIn', schedule);
   } catch(e) {
       console.log('setStorage: Error setting key ['+ key + '] in localStorage: ' + JSON.stringify(e) );
       return false;
   }
   return true;
}


const date_format = (dateString,format = null) => {
  if (!dateString) { return ""; }
  let new_date = new Date(dateString);
  let day = +new_date.getDate() > 9 ? new_date.getDate() : "0"+new_date.getDate();
  let month = new_date.getMonth()+1;
  month = +month > 9 ? month : "0"+month;
  let hour = +new_date.getHours() > 9 ? new_date.getHours() : "0"+new_date.getHours();
  let min = +new_date.getMinutes() > 9 ? new_date.getMinutes() : "0"+new_date.getMinutes();
  let sec = +new_date.getSeconds() > 9 ? new_date.getSeconds() : "0"+new_date.getSeconds();
  if (format) {
    if (format === "d-m-y") {
      return `${day}-${month}-${new_date.getFullYear()}`;
    }
  } else {
    return `${day}-${month}-${new_date.getFullYear()} ${hour}:${min}:${sec}`;
  }
}
