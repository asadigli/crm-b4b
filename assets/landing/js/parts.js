export let mt = $("meta[name='meta_token']").attr("content");

export let pg_lang = $('html').attr("lang");

export const slugify = (str) => {
    str = str.replace(/^\s+|\s+$/g, '');

    // Make the string lowercase
    str = str.toLowerCase();

    // Remove accents, swap ñ for n, etc
    var from = "ÁÄÂÀÃÅČÇĆĎÉĚËÈÊẼĔȆÍÌÎÏŇÑÓÖÒÔÕØŘŔŠŤÚŮÜÙÛÝŸŽáäâàãåčçćďéěëèêẽĕȇíìîïňñóöòôõøðřŕšťúůüùûýÿžþÞĐđßÆa·/_,:;";
    var to   = "AAAAAACCCDEEEEEEEEIIIINNOOOOOORRSTUUUUUYYZaaaaaacccdeeeeeeeeiiiinnooooooorrstuuuuuyyzbBDdBAa------";
    for (var i=0, l=from.length ; i<l ; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    // Remove invalid chars
    str = str.replace(/[^a-z0-9 -]/g, '')
    // Collapse whitespace and replace by -
    .replace(/\s+/g, '-')
    // Collapse dashes
    .replace(/-+/g, '-');

    return str;
}

export let hdKey = {headerkey: document.body.getAttribute("data-ajaxKey"),language:pg_lang};


export const setCookie = (name,value,days) => {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
export const getCookie = (name) => {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
export const eraseCookie = (name) => {
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}


export let getBase64 = (file) => {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = error => reject(error);
  });
}

export const path_local = (url) => {
   let lang = $("html").attr("lang");
   let path = lang === "az" ? "/" : `/${lang}/`;
   return path + (url ? url : "");
}

export const stripHtml = (html) => {
   let tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
}

export const paginateController = (count, t, current_page, selector) => {
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
    `<a data-role="prev" class="desktop-btn-pgn">${l('Previous')}</a>
    <a data-role="prev" class="mobile-btn-pgn"><img src="/assets/landing/img/icons/left-arrow.svg" alt=""></a>
    ${next > (show_count - 3) && count > show_count ? `<a class="cdp_i" data-val="1">1</a>${disabled_link}` : ""}
    ${page_list}
    ${count > show_count && next < (count - (show_count - 5)) ? `${disabled_link}<a class="cdp_i" data-val="${count}">${count}</a>` : ""}
    <a data-role="next" class="desktop-btn-pgn">${l('Next')}</a>
    <a data-role="next" class="mobile-btn-pgn"><img src="/assets/landing/img/icons/right-arrow.svg" alt=""></a>`
  ).attr("class","content_detail__pagination cdp").attr("actpage","1");
   // + (!t ? " d-none" : "")
  return next;
}


export const latin_base64_encode = (str) => btoa(unescape(encodeURIComponent(str)));
export const latin_base64_decode = (str) => decodeURIComponent(escape(window.atob(str)));

export const get_date = (manual_date) => {
  var dt = manual_date ? new Date(manual_date) : new Date();
  var dd = String(dt.getDate()).padStart(2, '0');
  var mm = String(dt.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy = dt.getFullYear();
  dt = dd + '/' + mm + '/' + yyyy;
  return dt;
}

export let incrementValue = (e) => {
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

export let decrementValue = (e) => {
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

export let getUrlParameter = function getUrlParameter(sParam) {
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

export let delay = (callback, ms) => {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}

export let number_format = (number, decimals, dec_point, thousands_point) => {
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

export let $ld = $(".loading");


export let isset = obj => {return typeof obj !== 'undefined' ? true : false;}

// export let variable = 123;

// export let base_url = 'https://b2c.avtohisse.com/';

// export let proj_name = "Avto Hesab";

export const environment = document.body.hasAttribute("data-localhost") ? document.body.getAttribute("data-localhost") : '/';

export let wplink = `https://api.whatsapp.com/send?phone=`;
export let rand = () => Math.random(0).toString(36).substr(2);
export let token = (length) => (rand()+rand()+rand()+rand()).substr(0,length);

export let redMess = (message) => `<small class="text-danger">${message}</small>`;

export let isInValid = (selector) => {
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

export let emojis = ['#9989;','#9997;','#10060;','#10068;','#10069;','#127383;','#128077;','#129488;','#128545;','#128512;','#128077;','#128078;','#129309;','#x1F600', '#x1F604', '#x23F0','#x1F4DA'];


export let checkUrl = (url, cb) => {
  $.ajax({
    url:url,dataType: 'text',type:'GET',
    complete:function(xhr){
      if(typeof cb === 'function'){cb.apply(this, [xhr.status]);}
    }
  });
}


export let changeurl = (page, url) => {
  if (typeof (history.pushState) != "undefined") {
    let obj = {Page: page, Url: url};
    history.pushState(obj, obj.Page, (obj.Url ? obj.Url : obj.Page));
  } else {
    window.location.href = "/";
  }
}

export let remove_hash = (c_url) => {
  return c_url.substring(c_url, c_url.indexOf('#'));
}

export let filter_url = (arr) => {
  let link = "",index = 0;
  for (let i = 0; i < arr.length; i++) {
    let val = Object.values(arr[i])[0] ? Object.values(arr[i])[0] : null,
        key = Object.keys(arr[i])[0];
    if (val && val.length > 0) {
      link += !index ? `?${key}=${val}` : `&${key}=${val}`;
      index++;
    }
  }
  let lh = remove_hash(location.href);
  // console.log(`${lh.substring(0 , lh.indexOf('?'))}${link}`)
  changeurl(lh,link ? `${lh.substring(0 , lh.indexOf('?'))}${link}` : lh);
}

export let str_limit = (text, limit) => {
  return text.length > limit ?
           text.substring(0, limit) + '...' :
           text;
}
// export let first_order_added = [];
export let baseName = (str) => {
   let base = new String(str).substring(str.lastIndexOf('/') + 1);
   base = base.lastIndexOf(".") != -1 ? base.substring(0, base.lastIndexOf(".")) : base;
   base = base.indexOf('?') > 0 ? base.substring(0, base.indexOf('?')) : base;
   return base.indexOf('#') > 0 ? base.substring(0, base.indexOf('#')) : base
}
export let $_get = (index,url) => {
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

export let go_tp = document.getElementById("go_to_top");
export let scrollFunction = () => {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    go_tp.style.display = "block";
  } else {
    go_tp.style.display = "none";
  }
}
export let btn_spinner = `<i class="fas fa-spinner fa-spin"></i>`;
export let lang = [];
export let l = (k) => {
  if (lang.length == 0) {
    let res = "";
    $.get({
      async: false,cache:true,
      headers: hdKey,
      url: path_local("get-lang-list"),data: {token: mt},
      success:function(d){
        d.map((v, i) => {
          let key = Object.keys(v)[0],val = Object.values(v)[0] || '';
          lang.push([key,val]);
        });
        res = Object.fromEntries(lang)[k] ? Object.fromEntries(lang)[k]  : `app.${k}`;
      }
    });
    return res;
    // return isset(res) ? res : l(k);
  }else{
    return Object.fromEntries(lang)[k] ? Object.fromEntries(lang)[k]  : `app.${k}`;
  }
}


const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});
export let notify_once = (mess,type,time) => {
  // Expected "success", "error", "warning", "info" or "question", got "danger"
  Toast.fire({
    type: type,
    title: mess,
    timer: (time ? time : 3000)
  });
}


export let success_popup = (title,message,icon) => {
  Swal.fire(
    title,message,icon
  );
}
export let confirm = (title,text,confirm,succ_title,succ_text,func,arr) => {
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
export let timeSince = (date) => {
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


export let $db = $(document.body);
export let em = '--';
