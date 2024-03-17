$(function(){

  const getBrands = () => {
    $.get({
      url: `/products/brands`,
      headers,
      success: function(d){
        // console.log(d);
        if (d.code === 200) {
          let h = `<option value="">${lang("Choose brand")}</option>`;
          d.data.map(v => {
            h += `<option value="${v.id}">${v.name}</option>`;
          });
          $(`[name="brand"]`).html(h);
        }
      },
      error: function(d){
        console.error(d);
      },
      complete: function(){

      }
    });
  }
  getBrands();


  const getCarBrands = () => {
    $.get({
      url: `/products/car-brands`,
      headers,
      success: function(d){
        // console.log(d);
        if (d.code === 200) {
          let h = `<option value="">${lang("Choose car brand")}</option>`;
          d.data.map(v => {
            h += `<option value="${v.id}">${v.name}</option>`;
          });
          $(`[name="car_brand"]`).html(h);
        }
      },
      error: function(d){
        console.error(d);
      },
      complete: function(){

      }
    });
  }
  getCarBrands();

});
