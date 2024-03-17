const currency = $("#annualReports").data("currency");
let month_date = null,
    warehouse = $(`[name="warehouse"]`).val(),
    annualReports;

const getOnline = (data) => {
  let xValues = data.map(v => v.time),
      yValues = data.map(v => +v.count);

  let max = Math.max.apply(Math, yValues);
  new Chart("onlineChart", {
    type: "line",
    data: {
      labels: xValues,
      datasets: [{
        label: 'Max: ' + max,
        // fill: false,
        // lineTension: 1,
        backgroundColor: "#06317838",
        borderColor: "#073179",
        data: yValues,
        borderWidth: 2
      }]
    },
    options: {
      maintainAspectRatio: false,
      // legend: { display: false },
      elements: {
        point:{
          radius: 0
        }
      },
      scales:{
        xAxes: [{
          display: false //this will remove all the x-axis grid lines
        }]
      }
      // scales: {
      // yAxes: [{ticks: {min: 6, max:16}}],
      // }
    }
  });

  $("#onlineChart").css({"height": "290px"})
}

const monthNames = [
  "January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"
];

const getReports = (data) => {
  if (!data.sales) return;
  let xValues = data.sales.map(v => lang(monthNames[v.month - 1]));
  let types = Object.keys(data);

  let datasets = [];
  const colors = {
    "sales": "#1c976c",
    "payments": "#0060ff",
    "returns": "#dc0606",
    "purchases": "#e68b06",
  };

  const bgColors = {
    "sales": "#1c976c",
    "payments": "#06317838",
    "returns": "#db090861",
    "purchases": "#e78b035e",
  };

  types.map(v => {
    datasets.push({
      label: lang(v),
      data: data[v].map(v_sub => v_sub.amount.toFixed(2)),
      lineTension: 0,
      fill: false,
      backgroundColor: bgColors[v],
      borderColor: colors[v],
      borderWidth: 2
    });
  });

  annualReports ? annualReports.destroy() : "";
  annualReports = new Chart("annualReports", {
    type: "line",
    data: {
      labels: xValues,
      datasets
    },
    options: {
      legend: {
        display: true,
        position: "top",
        labels: {
          fontColor: 'black'
        }
      },
      maintainAspectRatio: false,
      tooltips: {
        mode: 'index',
        intersect: false,
        callbacks: {
          label: function (tooltipItem, data) {
            let label = data.datasets[tooltipItem.datasetIndex].label || '';
            if (label) {
              label += ': ';
            }
            label += tooltipItem.yLabel;
            return label;
          }
        }
      }
    }
  });
};


const getDashboardReports = (month_date, warehouse) => {
  let hide_daily_sales = !!month_date;
  if (month_date) {
    hide_daily_sales = !(new Date(month_date).getMonth() === new Date().getMonth() && new Date(month_date).getFullYear() === new Date().getFullYear());
    if (!hide_daily_sales) {
      month_date = null;
    }
  }
  ModalLoader.start(lang("Loading"));
  $.get({
    url: `/dashboard-reports`,
    data: {month_date: month_date, warehouse},
    headers,
    success: function (d) {
      if (d.code === 200) {
        let data = d.data;

        // data.onlines ? getOnline(data.onlines.list) : "";
        data.reports.list ? getReports(data.reports.list) : "";
        $(`[data-role="current_online"]`).html(d.data.onlines.current);

        if (d.data.amount) {
          $(`[data-role="daily_sales"]`).html(d.data.amount.daily_eur_sales ? number_format(hide_daily_sales ? 0 : d.data.amount.daily_eur_sales,2,",",".",0) : 0)
          // $(`[data-role="daily_sales_azn"]`).html(d.data.amount.daily_azn_sales ? number_format(d.data.amount.daily_azn_sales,2,",",".",0) : 0)
          $(`[data-role="monthly_sales"]`).html(d.data.amount.monthly_eur_sales ? number_format(d.data.amount.monthly_eur_sales,2,",",".",0) : 0)
          // $(`[data-role="monthly_sales_azn"]`).html(d.data.amount.monthly_azn_sales ? number_format(d.data.amount.monthly_azn_sales,2,",",".",0) : 0)
          $(`[data-role="monthly_purchase"]`).html(d.data.amount.monthly_eur_purchase ? number_format(d.data.amount.monthly_eur_purchase,2,",",".",0) : 0)
          // $(`[data-role="monthly_purchase_azn"]`).html(d.data.amount.monthly_azn_purchase ? number_format(d.data.amount.monthly_azn_purchase,2,",",".",0) : 0)
          $(`[data-role="customer_debt"]`).html(d.data.amount.customer_eur_debt ? number_format(d.data.amount.customer_eur_debt,2,",",".",0) : 0)
          // $(`[data-role="customer_debt_azn"]`).html(d.data.amount.customer_azn_debt ? number_format(d.data.amount.customer_azn_debt,2,",",".",0) : 0)
        }


        if (d.data.b4b.order_count) {
          $(`[data-role="b4b-reports"] [data-role="daily-orders"]`).html(d.data.b4b.order_count.daily);
          $(`[data-role="b4b-reports"] [data-role="monthly-orders"]`).html(d.data.b4b.order_count.monthly);
          $(`[data-role="b4b-reports"] [data-role="annual-orders"]`).html(d.data.b4b.order_count.annual);
        }

        if (d.data.reports && Object.values(d.data.reports).length) {
          if (typeof d.data.reports.list.sales !== "undefined") {
            $(`[data-role="total-sales"]`).html(number_format(d.data.reports.list.sales.map(v => v.amount).reduce((a, b) => a + b, 0),2,",",".") + " " + currency);
          }
          if (typeof d.data.reports.list.purchases !== "undefined") {
            $(`[data-role="total-purchases"]`).html(number_format(d.data.reports.list.purchases.map(v => v.amount).reduce((a, b) => a + b, 0),2,",",".") + " " + currency);
          }
          if (typeof d.data.reports.list.payments !== "undefined") {
            $(`[data-role="total-payments"]`).html(number_format(d.data.reports.list.payments.map(v => v.amount).reduce((a, b) => a + b, 0),2,",",".") + " " + currency);
          }
          if (typeof d.data.reports.list.returns !== "undefined") {
            $(`[data-role="total-sale-returns"]`).html(number_format(d.data.reports.list.returns.map(v => v.amount).reduce((a, b) => a + b, 0),2,",",".") + " " + currency);
          }

          // console.log(new Date().getFullYear(),0,1);
          $(`[data-role="average-sales"]`).html(number_format(d.data.reports.average_sales,2,",",".") + " " + currency);
          $(`[data-role="average-purchases"]`).html(number_format(d.data.reports.average_purchases,2,",",".") + " " + currency);
          $(`[data-role="average-payments"]`).html(number_format(d.data.reports.average_payments,2,",",".") + " " + currency);
          // $(`[data-role="average-sale-returns"]`).html(number_format(d.data.reports.returns.map(v => v.amount).reduce((a, b) => a + b, 0)/12,2,",",".") + " " + currency);
        }

      }
    },
    complete: function() {
      ModalLoader.end();
      $(`[name="month_date"]`).prop("disabled",false);
      $(`[name="warehouse"]`).prop("disabled",false);
    }
  });
}

$(function () {

  getDashboardReports(month_date,warehouse);
  setInterval(function () {
    getDashboardReports(month_date,warehouse);
  }, 1000 * 60 * 5);

  $(document).on("change",`[name="warehouse"]`,function(){
    $(`[name="month_date"],[name="warehouse"]`).prop("disabled",true);
    month_date          = $(`[name="month_date"]`).val();
    warehouse           = $(`[name="warehouse"]`).val();
    filter_url([
      {warehouse: (warehouse || "")},
    ]);
    getDashboardReports(month_date,warehouse);
  });

  $(document).on("keypress",`[name="month_date"]`,function(e){
    if (e.which === 13) {
      $(`[name="month_date"],[name="warehouse"]`).prop("disabled",true);
      month_date          = $(`[name="month_date"]`).val();
      warehouse           = $(`[name="warehouse"]`).val();
      filter_url([
        {warehouse: (warehouse || "")},
      ]);
      getDashboardReports(month_date,warehouse);
    }
  });

  $(document).on("click",`[data-role="filter-action-btn"]`,function(){
    $(`[name="month_date"],[name="warehouse"]`).prop("disabled",true);
    month_date          = $(`[name="month_date"]`).val();
    warehouse           = $(`[name="warehouse"]`).val();
    filter_url([
      {warehouse: (warehouse || "")},
    ]);
    getDashboardReports(month_date,warehouse);
  });

});
