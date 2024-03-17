/*
* Library => FLUP
* CDN => https://cdn.egim.io/assets/scripts/flup.js
*/
class Flup {
  constructor(params) {
    this.selector = params.selector;
    if(!document.querySelectorAll(this.selector).length) return;
    this.className = btoa(params.selector).replace(/[^a-zA-Z0-9]/g, '-');
    this.FILE_SIZE_LIMIT = params.size_limit ? params.size_limit : 5000000;
    this.FILE_COUNT_LIMIT = params.limit ? params.limit : 10;
    this.rotate = params.rotate === false ? false : true;
    this.title = params.title ? params.title : "Şəkli yükləmək üçün klikləyin";
    this.text = params.text ? params.text : "Maksimum " + this.FILE_COUNT_LIMIT + (this.FILE_COUNT_LIMIT > 1 ? " fayl " : " fayl") + " yüklənə bilər";
    this.uploadFiles = [];
    this.init();
  }

  rotateBase64Image90deg(base64Image, isClockwise) {
    return new Promise((resolve, reject) => {
      setInterval(function () {
        var offScreenCanvas = document.createElement('canvas');
        let offScreenCanvasCtx = offScreenCanvas.getContext('2d');
        var img = new Image();
        img.src = base64Image;
        offScreenCanvas.height = img.width;
        offScreenCanvas.width = img.height;
        if (isClockwise) {
          offScreenCanvasCtx.rotate(90 * Math.PI / 180);
          offScreenCanvasCtx.translate(0, -offScreenCanvas.width);
        } else {
          offScreenCanvasCtx.rotate(-90 * Math.PI / 180);
          offScreenCanvasCtx.translate(-offScreenCanvas.height, 0);
        }
        offScreenCanvasCtx.drawImage(img, 0, 0);
        resolve(offScreenCanvas.toDataURL("image/jpeg", 100))
      }, 500)
    })
  }

  getBase64(file) {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => resolve({
        ext: file.name.split('.').pop(),
        file: reader.result
      });
      reader.onerror = error => reject(error);
    });
  }

  init() {
    let css = `.${this.className}{
      background: #F1F1F1;
      border: 1px dashed #CACACA;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      height: 155px;
      position: relative;
      cursor: pointer;
    }
    .${this.className} input[type=file]{
      position: absolute;
      z-index: 0;
      left: 0;
      top: 0;
      opacity: 0;
      cursor: pointer;
    }
    .${this.className} > .imgs-line{
      width: 101%;
      min-height: 100%;
      display: flex;
      justify-content: flex-start;
      align-items: center;
      position: absolute;
      flex-wrap: wrap;
      height: fit-content;
      background: #f1f1f1;
      border: 1px dashed #CACACA;
      top: 0;
      z-index: 1;
    }
    .${this.className} > .imgs-line.d-none{
      display:none;
    }
    .${this.className} > .imgs-line div{
      height: 85px;
      width: 85px;
      margin: 0 5px 5px 5px;
      position: relative;
      display: flex;
      min-width: 0;
      word-wrap: break-word;
      background-color: #fff;
      background-clip: border-box;
      border: 1px solid rgba(0,0,0,.125);
      border-radius: .25rem;
    }
    .${this.className} > .imgs-line div:hover .overlay{
      opacity: 1;
      visibility: visible;
    }
    .${this.className} > .imgs-line div > img{
      height: 100%;
      width: 100%;
      object-fit: contain;
    }
    .${this.className} > .imgs-line div .overlay{
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,.349);
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      visibility: hidden;
      transition: .4s!important;
      margin:0;
    }
    .${this.className} > .imgs-line div .overlay svg{
      height: 17px;
      width: 17px;
      margin:0 10px;
    }
    .${this.className} > .imgs-line div .overlay a:nth-child(2) svg{
      height: 14px;
      width: 14px;
    }
    .${this.className} span{
      font-style: normal;
      font-weight: bold;
      font-size: 14px;
      line-height: 21px;
      color: #50A329;
    }
    .${this.className}{
      font-style: normal;
      font-weight: normal;
      font-size: 18px;
      line-height: 27px;
      color: #4F4F4F;
    }
    .${this.className} > svg{
      width: 45px;
      height: 39.85px;
    }
    .${this.className} a{
      text-decoration: none;
      cursor: pointer;
    }`,
      head = document.head || document.getElementsByTagName('head')[0],
      style = document.createElement('style');
    head.appendChild(style);
    style.type = 'text/css';
    if (style.styleSheet) {
      style.styleSheet.cssText = css;
    } else {
      style.appendChild(document.createTextNode(css));
    }
    this.appendBody();
    let th = this;


    document.onpaste = function (event) {
      let items = (event.clipboardData  || event.originalEvent.clipboardData).items;
      let blob = null;
      for (var i = 0; i < items.length; i++) {
        if (items[i].type.indexOf("image") === 0) {
          blob = items[i].getAsFile();
        }
      }
			if (blob !== null) {
				let reader = new FileReader();
				reader.onload = function(event) {
    				th.uploadFiles.length < th.FILE_COUNT_LIMIT && !th.uploadFiles.includes(event.target.result) ? th.uploadFiles.push(event.target.result) : "";
			    	th.previewFunction(th.uploadFiles, th);
				};
				reader.readAsDataURL(blob);
			}
		}

    document.querySelectorAll(this.selector)[0].onclick = function (e) {
      let parent_a = e.target.closest("a[data-role]");
      if (parent_a) {
        if (parent_a.getAttribute("data-role") === "remove-image") {
          let item = parent_a.closest("div[data-role]");
          let file = item.querySelector("img").getAttribute("src");
          th.uploadFiles = th.uploadFiles.filter(element => element !== file);
          th.previewFunction(th.uploadFiles, th);
        } else if (parent_a.getAttribute("data-role") === "rotate-image") {
          let item = parent_a.closest("div[data-role]");
          let file = item.querySelector("img").getAttribute("src");
          th.rotateBase64Image90deg(file, true).then(data => {
            th.uploadFiles = th.uploadFiles.map(element => element !== file ? element : data);
            th.previewFunction(th.uploadFiles, th);
          }).catch(err => console.error(err))
        }
      }
      if (!e.target.classList.contains("overlay") && !["svg", "path", "img", "a", "A"].includes(e.target.tagName)) {
        document.querySelectorAll(th.selector)[0].querySelectorAll("input[type=file]")[0].click();
      }
    }
  }

  reset() {
    this.uploadFiles.length = 0;
    this.previewFunction(this.uploadFiles, this);
  }

  imageComponent(base64,th) {
    return `<div data-role="uploaded-item">
              <img src="${base64}" alt="">
              <div class="overlay">
                ${th.rotate ? `<a data-role="rotate-image" href="javascript:void(0)" xmlns="http://www.w3.org/2000/svg">
                  <svg enable-background="new 0 0 85.168 85.168" fill="#ffff" version="1.1" viewBox="0 0 85.168 85.168" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <path d="m61.696 14.999-4.126 4.457c8.806 5.774 13.923 16.353 12.184 27.41-1.146 7.288-5.063 13.694-11.027 18.037-5.206 3.791-11.43 5.615-17.777 5.252l1.09-1.144c-0.021-1e-3 -0.044 2e-3 -0.065 1e-3l4.129-4.332-3.813-3.639-8.188 8.596-2e-3 -3e-3 -3.533 3.71 3.811 3.636 2e-3 -1e-3 8.593 8.189 3.536-3.71-5.565-5.302c7.616 0.36 15.066-1.853 21.315-6.403 7.261-5.286 12.028-13.084 13.424-21.956 2.057-13.103-3.787-25.657-13.988-32.798z"/>
                  <path d="m15.415 38.302c1.146-7.288 5.063-13.694 11.027-18.037 5.206-3.791 11.43-5.615 17.777-5.252l-1.09 1.144c0.021 1e-3 0.044-2e-3 0.065-1e-3l-4.129 4.332 3.813 3.639 8.188-8.596 2e-3 3e-3 3.533-3.71-3.811-3.636-2e-3 1e-3 -8.593-8.189-3.536 3.71 5.565 5.302c-7.616-0.36-15.066 1.853-21.315 6.403-7.261 5.286-12.028 13.084-13.424 21.956-2.06 13.104 3.785 25.658 13.985 32.799l4.126-4.457c-8.803-5.776-13.92-16.354-12.181-27.411z"/>
                  </svg></a>` : ""}
                <a href="javascript:void(0)" data-role="remove-image">
                  <svg fill="#ffff" viewBox="0 0 329.26933 329" xmlns="http://www.w3.org/2000/svg"><path d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0"/></svg>
                </a>
              </div>
          </div>`;
  }

  previewFunction(files, th) {
    let view = "",
      files_as_value = "";
    files.map((v, i) => {
      view += th.imageComponent(v,th);
      files_as_value += `${v}${i < (files.length - 1) ? "|FLUP_DIVIDER|" : ""}`
    })
    let parent = document.getElementsByClassName(th.className)[0];
    let element = parent.getElementsByClassName("imgs-line")[0];
    parent.querySelector(`input[name='${th.selector.replace("#","")}']`).value = files_as_value;
    view ? element.classList.remove("d-none") : element.classList.add("d-none");
    element.innerHTML = view;
  }

  appendBody() {
    let body = `<svg width="45" height="41" viewBox="0 0 45 41" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M16.961 16.5925C18.6684 16.5925 20.0526 15.2084 20.0526 13.5009C20.0526 11.7935 18.6684 10.4093 16.961 10.4093C15.2535 10.4093 13.8694 11.7935 13.8694 13.5009C13.8694 15.2084 15.2535 16.5925 16.961 16.5925Z" fill="#CACACA"/>
    <path d="M42.3378 24.8797C40.8779 23.5057 39.0315 22.604 37.0134 22.3463V6.63068C37.0134 4.95606 36.3263 3.4532 35.2529 2.33679C34.1364 1.22038 32.6336 0.576294 30.959 0.576294H6.05439C4.37977 0.576294 2.87691 1.26332 1.7605 2.33679C0.644084 3.4532 0 4.95606 0 6.63068V26.4255V28.2719V32.2652C0 33.9398 0.687023 35.4427 1.7605 36.5591C2.87691 37.6755 4.37977 38.3196 6.05439 38.3196H30.1431C31.7319 39.6078 33.7071 40.4236 35.8969 40.4236C38.4303 40.4236 40.7061 39.3931 42.3378 37.7614C43.9695 36.1297 45 33.854 45 31.3206C45 28.7872 43.9695 26.5114 42.3378 24.8797ZM2.27576 6.63068C2.27576 5.60015 2.70515 4.65549 3.39218 4.01141C4.0792 3.32439 5.02385 2.895 6.05439 2.895H30.959C31.9895 2.895 32.9342 3.32439 33.6212 4.01141C34.3082 4.69843 34.7376 5.64309 34.7376 6.67362V20.0706L28.3826 13.7156C27.9532 13.2862 27.2233 13.2433 26.751 13.7156L17.1756 23.3339L10.6918 16.8072C10.2624 16.3778 9.53244 16.3349 9.06011 16.8072L2.27576 23.6774V6.63068ZM6.01145 36.1297V36.0438C4.98092 36.0438 4.03626 35.6145 3.34924 34.9274C2.70515 34.2404 2.27576 33.2958 2.27576 32.2652V28.2719V26.9408L9.87595 19.2977L16.3597 25.7814C16.7891 26.2108 17.5191 26.2108 17.9914 25.7814L27.5668 16.1631L33.8359 22.4751C33.7071 22.5181 33.5782 22.561 33.4494 22.604C33.2777 22.6469 33.1059 22.6898 32.8912 22.7757C32.7195 22.8187 32.5477 22.9045 32.376 22.9475C32.2471 22.9904 32.1613 23.0334 32.0324 23.1192C31.8607 23.2051 31.7319 23.248 31.6031 23.3339C31.3884 23.4627 31.1737 23.5916 30.959 23.7204C30.8302 23.8063 30.7443 23.8492 30.6155 23.9351C30.5296 23.978 30.4866 24.021 30.4008 24.0639C30.0143 24.3215 29.6708 24.6221 29.3702 24.9656C27.7386 26.5973 26.708 28.8731 26.708 31.4064C26.708 32.0505 26.7939 32.6517 26.9227 33.2958C26.9656 33.4675 27.0086 33.5963 27.0515 33.7681C27.1803 34.1975 27.3092 34.6269 27.4809 35.0563V35.0992C27.6527 35.4427 27.8244 35.8292 28.0391 36.1297H6.01145ZM40.6632 36.1297C39.4179 37.375 37.7433 38.1049 35.854 38.1049C34.0506 38.1049 32.376 37.375 31.1737 36.2156C31.0019 36.0438 30.8302 35.8292 30.6584 35.6574C30.5296 35.5286 30.4008 35.3568 30.2719 35.228C30.1002 35.0133 29.9714 34.7557 29.8426 34.498C29.7567 34.3263 29.6708 34.1975 29.5849 34.0257C29.499 33.811 29.4132 33.5534 29.3702 33.2958C29.3273 33.124 29.2414 32.9093 29.1985 32.7376C29.1126 32.3082 29.0697 31.8358 29.0697 31.3635C29.0697 29.4742 29.8426 27.7996 31.0448 26.5543C32.2471 25.3091 33.9647 24.5792 35.854 24.5792C37.7433 24.5792 39.4179 25.3521 40.6632 26.5543C41.9084 27.7996 42.6384 29.4742 42.6384 31.3635C42.6384 33.2099 41.8655 34.8845 40.6632 36.1297Z" fill="#CACACA"/>
    <path d="M36.6699 26.7261C36.584 26.6402 36.4552 26.5543 36.2834 26.4684C36.1546 26.4255 36.0258 26.3826 35.897 26.3826C35.854 26.3826 35.854 26.3826 35.854 26.3826C35.8111 26.3826 35.8111 26.3826 35.8111 26.3826C35.6823 26.3826 35.5535 26.4255 35.4247 26.4684C35.2958 26.5114 35.167 26.5973 35.0382 26.7261L32.376 29.3883C31.9466 29.8177 31.9466 30.5476 32.376 31.02C32.8054 31.4494 33.5353 31.4494 34.0077 31.02L34.6947 30.333V35.0562C34.6947 35.7003 35.21 36.2156 35.854 36.2156C36.4981 36.2156 37.0134 35.7003 37.0134 35.0562V30.333L37.7004 31.02C38.1298 31.4494 38.8598 31.4494 39.3321 31.02C39.7615 30.5906 39.7615 29.8606 39.3321 29.3883L36.6699 26.7261Z" fill="#CACACA"/>
    </svg>
    ${this.title ? `<p>${this.title}</p>` : ""}
    ${this.text ? `<span>${this.text}</span>` : ""}
    <input type="file" multiple>
    <input type="hidden" name="${this.selector.replace("#","")}">
    <div class="imgs-line d-none"></div>`;
    document.querySelectorAll(this.selector)[0].innerHTML = body;
    document.querySelectorAll(this.selector)[0].classList.add(this.className);
    let th = this;
    document.querySelectorAll(this.selector + " input[type='file']")[0].onchange = function (e) {
      let input = e.target;
      if (input.files && input.files.length) {
        for (let i = 0; i < input.files.length; i++) {
          if (input.files[i].size < th.FILE_SIZE_LIMIT) {
            if (i < th.FILE_COUNT_LIMIT) {
              th.getBase64(input.files[i]).then(
                data => {
                  th.uploadFiles.length < th.FILE_COUNT_LIMIT ? th.uploadFiles.push(data.file) : "";
                  th.previewFunction(th.uploadFiles, th);
                }
              ).finally(v => {
                });
            } else {
              alert("File limit cannot overload " + th.FILE_COUNT_LIMIT)
            }
          } else {
            alert("File size cannot overload " + th.FILE_SIZE_LIMIT / 1000 / 1000 + "MB")
          }
        }
      }
    }
  }

  values() {
    let parent = document.getElementsByClassName(this.className)[0];
    let values = parent.querySelector(`input[name='${this.selector.replace("#","")}']`).value.split("|FLUP_DIVIDER|"),
        realnames = [];
    if (this.realnames) {
      realnames = parent.querySelector(`input[name='${this.selector.replace("#","")}_realnames']`).value.split("|FLUP_DIVIDER|");
    }
    return {
      realnames,
      values
    };
  }

}
