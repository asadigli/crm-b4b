let SPINNER = `<svg height="100%" width="100%" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: none; display: block; shape-rendering: auto;" width="204px" height="204px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
<circle cx="50" cy="50" fill="none" stroke="#ffffff" stroke-width="14" r="38" stroke-dasharray="179.0707812546182 61.690260418206066">
  <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="0.6097560975609756s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
</circle></svg>`;

let SUCCESS_ICON = `<svg fill="#fff" enable-background="new 0 0 24 24" height="24px" id="Layer_1" version="1.1" viewBox="0 0 24 24" width="24px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path clip-rule="evenodd" d="M21.652,3.211c-0.293-0.295-0.77-0.295-1.061,0L9.41,14.34  c-0.293,0.297-0.771,0.297-1.062,0L3.449,9.351C3.304,9.203,3.114,9.13,2.923,9.129C2.73,9.128,2.534,9.201,2.387,9.351  l-2.165,1.946C0.078,11.445,0,11.63,0,11.823c0,0.194,0.078,0.397,0.223,0.544l4.94,5.184c0.292,0.296,0.771,0.776,1.062,1.07  l2.124,2.141c0.292,0.293,0.769,0.293,1.062,0l14.366-14.34c0.293-0.294,0.293-0.777,0-1.071L21.652,3.211z" fill-rule="evenodd"/></svg>`;

let ERROR_ICON = `<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="100%" height="100%" viewBox="0 0 458.096 458.096" style="enable-background:new 0 0 458.096 458.096;"
	 xml:space="preserve" fill="#fff">
<g>
	<path d="M454.106,396.635L247.33,38.496c-3.783-6.555-10.775-10.592-18.344-10.592c-7.566,0-14.561,4.037-18.344,10.592
		L2.837,398.414c-3.783,6.555-3.783,14.629,0,21.184c3.783,6.556,10.778,10.593,18.344,10.593h415.613c0.041,0,0.088,0.006,0.118,0
		c11.709,0,21.184-9.481,21.184-21.185C458.096,404.384,456.612,400.116,454.106,396.635z M57.872,387.822L228.986,91.456
		L400.1,387.828H57.872V387.822z M218.054,163.009h21.982c1.803,0,3.534,0.727,4.8,2.021c1.259,1.3,1.938,3.044,1.892,4.855
		l-4.416,138.673c-0.095,3.641-3.073,6.537-6.703,6.537h-13.125c-3.635,0-6.614-2.902-6.7-6.537l-4.418-138.673
		c-0.047-1.812,0.636-3.555,1.895-4.855C214.52,163.736,216.251,163.009,218.054,163.009z M246.449,333.502v25.104
		c0,3.699-2.997,6.696-6.703,6.696h-21.394c-3.706,0-6.7-2.997-6.7-6.696v-25.104c0-3.7,2.994-6.703,6.7-6.703h21.394
		C243.452,326.793,246.449,329.802,246.449,333.502z"/>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>`;

let config = {
  start_cmd: "start",
  end_cmd: "end",
  btn_text: "loader-main-text",
  btn_style: "loader-main-style"
}

let Loader = {
  btn: function(params){
    let {
      element,
      action,
      time,
      successColor,
      successBgColor,
      successText,
      errorColor,
      errorBgColor,
      errorText,
      warningColor,
      warningBgColor,
      warningText
    } = params;
    let timeForTimeout = typeof time === "number" ? time : 2000;
    if(!element) return false;
    let SIZES = element.getBoundingClientRect(),
        HTML = element.innerHTML,
        HIDDEN_VAL = document.getElementById(config.btn_text),
        HIDDEN_STYLE = document.getElementById(config.btn_style);
    if (action !== config.start_cmd && !HIDDEN_VAL) {
        console.error("Loader need to start first");
        return false;
    }
    if (!action || action === config.start_cmd) {
      let VAL_INPUT = document.createElement("input");
      VAL_INPUT.type = "hidden";
      VAL_INPUT.id = config.btn_text;
      VAL_INPUT.value = HTML;
      element.after(VAL_INPUT);

      let STYLE_INPUT = document.createElement("input");
      STYLE_INPUT.type = "hidden";
      STYLE_INPUT.id = config.btn_style;
      STYLE_INPUT.value = element.getAttribute("style");
      STYLE_INPUT.value ? element.after(STYLE_INPUT) : "";

      element.disabled = true;
      element.style.width = SIZES.width + "px";
      element.style.height = SIZES.height + "px";
      element.style.cursor = "wait";
      element.innerHTML = SPINNER;
    }else if(action === "success"){
      element.innerHTML = successText ? successText : SUCCESS_ICON;
      element.style.setProperty("background-color", successBgColor ? successBgColor : "#88cc67", "important")
    }else if(action === "error"){
      element.innerHTML = errorText ? errorText : ERROR_ICON;
      element.style.setProperty("background-color", errorBgColor ? errorBgColor : "#f78c8c", "important");
    }else if(action === "warning"){
      element.innerHTML = errorText ? errorText : ERROR_ICON;
      element.style.setProperty("background-color", warningBgColor ? warningBgColor : "#ffc107", "important");
    }else if(action === config.end_cmd){
      setTimeout(function(){
        HIDDEN_VAL ? HIDDEN_VAL.remove() : "";
        HIDDEN_STYLE ? HIDDEN_STYLE.remove() : "";
        setTimeout(function() {
          element.removeAttribute("style");
          element.innerHTML = HIDDEN_VAL ? HIDDEN_VAL.value : "";
          element.disabled = false;
          HIDDEN_STYLE && HIDDEN_STYLE.value ? element.setAttribute("style", HIDDEN_STYLE.value) : "";
        },timeForTimeout)
      },timeForTimeout)
    }


    if (["success","error","warning"].includes(action)) {
      let color = "#fff";
      if (action === "success") {
        color = successColor ? successColor : color;
      }else if(action === "error"){
        color = errorColor ? errorColor : color;
      }else if(action === "warning"){
        color = warningColor ? warningColor : color;
      }
      element.style.border = "none";
      element.style.color = color;
      element.style.borderRadius = ".25rem";
    }

  }
}
