import { notify_once,l,path_local,
					filter_url,number_format,$db,hdKey } from './../../parts.min.js?v=1ab';
import {
	Loader
} from './../../loader.min.js?v=2';


$(function(){
    function renumber() {
      var count = 1;
      $('tbody tr').each(function () {
        $(this).find('.order-number').text(count).data("order",(count + 100));
        count++;
      });
    }

    function init() {
      $(".droppable-area1, .droppable-area2" ).sortable({
          connectWith: ".connected-sortable",
          stack: '.connected-sortable'
        }).disableSelection();
    }
    $(init);

    $('input.form-control').change(function () {
      var startNo = $(this).val();
      $('.js-start-number').text(startNo);
    });

    $(".connected-sortable" ).on("sortupdate", function(event, ui) {
      renumber();
      let trs = $("#homeProducts").find("tr"),list = [];
      trs.each(v => {
        list.push({product:trs.eq(v).data("id"),order:trs.eq(v).find("td:first").data("order")})
      })
      if (list.length) {
        $.ajax({
          url: `/admin/product/update-orders`,
          headers:hdKey,type: 'POST',cache:true,
          data:{list},
          success:function(d){
            notify_once(d.message,d.code === 200 ? "success" : "warning")
          },error: function(d){
            console.error(d)
          }
        });
      }
    });
    let getProds = () => {
      $("body").addClass("loader");
      $.ajax({
        url: `/product/home-products`,
        headers:hdKey,type: 'GET',cache:true,
        success:function(d){
          let h = '',nm = 0,nm_max = 0,nm_min = 0,slc = '';
          d.forEach((v, i) => {
            nm = v.product_limit;
            nm_max = v.product_limit_max;
            nm_min = v.product_limit_min;
            h += `<tr data-id="${v.id}">
                    <td class="order-number" data-order="${i + 101}">${i + 1}</td>
                    <td><a href="${path_local(`product/` + v.slug)}" target="_blank">${v.prod_name}</a></td>
                    <td>${v.OEM ? v.OEM : '---'} <br> ${v.brand_code ? v.brand_code : '---'} </td>
                    <td>${v.price  ? number_format(v.price,2,'.','') : '---'}</td>
                    <td>
                      <a href="javascript:void(0)" class="btn btn-danger" data-role="removeFromHome"><em class="fa fa-times"></em></a>
                    </td>
                  </tr>`
          });
          for (let i = nm_min; i <= nm_max; i++){
            slc += `<option value="${i}" ${nm == i ? 'selected' : ''}>${i}</option>`;
          }
          $("#prodLimitNumb").html(`Əsas səhifə göstərilən məhsulların say limiti: <select class="updateProdLimit" style="width: 50px;">${slc}</select>`);
          $("#homeProducts").html(h);
        },complete:function(){
          $("body").removeClass("loader");
        }
      })
    }
    $db.on("change",".updateProdLimit",function(){
      $.ajax({
        url: `/admin/product/update-limit`,
        headers:hdKey,type: 'POST',cache:true,
        data:{limit:$(this).val()},
        success:function(d){
          console.log(d);
          if (d.code === 200) {
            add
          }
        }
      });
    })
    getProds();

    $(document).on("click",`[data-role="removeFromHome"]`,function(){
      let t = $(this);
  		let data = {
  			product: t.parents(`tr`).data("id")
  		}
  		Swal.fire({
  		  text: "Məhsulu vitrindən qaldırmaqdan əminsinizmi?",
  			showCancelButton: true,
  			confirmButtonText: l("Yes"),
  			confirmButtonColor: '#d63030',
  			cancelButtonText: l("Cancel")
  		}).then((res) => {
  		  if (res.value) {
  				$.post({
  					url: `/admin/product/home-list/remove`,
  					data,headers: hdKey,
  					success: function (d){
  						// console.log(d)
  						if(d.code === 200){
  							Swal.fire(d.message, '', 'success');
  							t.parents(`tr`).remove();
  						}
  					},error: function(d){
  						console.error(d)
  					}
  				})
  		  } else {
  		    // Swal.fire('Changes are not saved', '', 'info')
  		  }
  		})
    })

})
