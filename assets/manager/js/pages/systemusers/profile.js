$(function(){
  $(document).on("click",`[data-role="save"]`,function(){
    let id = $(this).data("id");
        password = $(`[name="password"]`).val(),
        confirm_password = $(`[name="confirm_password"]`).val(),
        old_password = $(`[name="old_password"]`).val(),
        type = "edit-password";
    if (password.trim().length < 6) {
      Swal.fire("",lang("Password length must be at least 6 characters"),"warning");
      return;
    }
    if (password !== confirm_password) {
      Swal.fire("",lang("Passwords do not match"),"warning");
      return;
    }

    if (!old_password) {
      Swal.fire("",lang("Current password can not be empty"),"warning");
      return;
    }

    ModalLoader.start(lang("Loading") + "...")
    $.ajax({
      type: "put",
      url: `/system-users/${id}/edit-password`,
      headers,
      data: JSON.stringify({id,password,type,old_password,confirm_password}),
      success: function(d){
        if (d.code === 202) {
          $(`[name="password"],[name="old_password"],[name="confirm_password"]`).val("")
          Swal.fire({
            title: lang("Login information"),
            confirmButtonText: lang("Close"),
            html:`<div data-role="login-info"><p class="mb-2">${lang("Password")}: <b>${d.data.password}</b> </p></div>
                  <a href="javascript:void(0)" data-role="copy">${lang("Copy")} <i class="fa fa-copy"></i></a>`
          });
        } else {
          Swal.fire("",d.message,"warning");
        }
      },
      error: function(d){
        console.error(d);
      },
      complete: function(){
        ModalLoader.end();
      }
    })
  });


  $(document).on("click",`[data-role="view-password"]`,function(){
    let name = $(this).data("text"),
        password = $(`[name="${name}"]`).val();
    $(`[name="${name}"]`).removeAttr("type").attr("type","text");
    $(this).find(`i`).removeClass("fas fa-eye").addClass("fa-solid fa-eye-slash");
    $(this).removeAttr("data-role").attr("data-role","hide-password");
  })

  $(document).on("click",`[data-role="hide-password"]`,function(){
    let name = $(this).data("text"),
        password = $(`[name="${name}"]`).val();
    $(`[name="${name}"]`).removeAttr("type").attr("type","password");
    $(this).find(`i`).removeClass("fa-solid fa-eye-slash").addClass("fas fa-eye");
    $(this).removeAttr("data-role").attr("data-role","view-password");
  })

  const generatePassword = () => {
    let chars = "1234567890aAbBcCdDeEfFghHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ",
        string_length = 8,
        randomstring = '',
        charCount = 0,
        numCount = 0;

    for (var i=0; i<string_length; i++) {
        if((Math.floor(Math.random() * 2) == 0) && numCount < 3 || charCount >= 5) {
            var rnum = Math.floor(Math.random() * 10);
            randomstring += rnum;
            numCount += 1;
        } else {
            // If any of the above criteria fail, go ahead and generate an alpha character from the chars string
            var rnum = Math.floor(Math.random() * chars.length);
            randomstring += chars.substring(rnum,rnum+1);
            charCount += 1;
        }
    }
    return randomstring;
  };

  $(document).on("click",`[data-role="generate-password"]`,function(){
    let password = generatePassword();
    $(`[name="password"]`).val(password);
  })


    $(`[data-role="save"]`).tooltip();
    $(document).on("click",`[data-role="copy"]`,function(){
      let text = $(`[data-role="login-info"]`).text().trim();
      if(!text || text == ''){
        return;
      }
      // console.log(text);
      copyTextToClipboard(text);
    });

})
