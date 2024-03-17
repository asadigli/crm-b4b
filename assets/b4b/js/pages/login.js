$(function(){
  $('[data-toggle="tooltip"]').tooltip();

  $('[data-toggle="tooltip"]').click(function () {
    $('[data-toggle="tooltip"]').tooltip("hide");
    console.log("here");
  });

  $("button").on("blur", function() {
    $('[data-toggle="tooltip"]').tooltip("hide");
  });

  const noLimitWpIcon = (email) => {
    const $hp = $(`[data-role="have-no-limit-problem"]`);
    $hp.attr("href",`${$hp.data("base")}${encodeURIComponent(`${$hp.data("text")}${email ? `: \nEmail: ${email}` : ""}`)}`);
  }

  const getEmail = (email) => {
    const $hp = $(`[data-role="have-problem"]`);
    $hp.attr("href",`${$hp.data("base")}${encodeURIComponent(`${$hp.data("text")}${email ? `: \nEmail: ${email}` : ""}`)}`);
  }

  $(document).on("change",`[name="email"]`,function(){
    getEmail($(this).val());
    noLimitWpIcon($(this).val());
  });
  setTimeout(function(){
    getEmail($(`[name="email"]`).val())
    noLimitWpIcon($(`[name="email"]`).val())
  },1000);
});
