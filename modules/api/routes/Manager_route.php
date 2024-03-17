<?php
defined('BASEPATH') OR exit('No direct script access allowed');


Route::prefix("manager",function(){

  Route::prefix("invoices",function(){
    Route::get("sales", "manager/invoices/sales");
    Route::get("sales/{code}/details", "manager/invoices/sales/details/$1");

    Route::get("daily-sales", "manager/invoices/sales/daily_list");

    Route::get("purchases", "manager/invoices/purchases");
    Route::get("purchases/{code}/details", "manager/invoices/purchases/details/$1");
  });

  Route::prefix("auth", function() {
    Route::post("login","manager/auth/login/action");
    Route::post("login/token", "manager/auth/login/loginWithToken");
  });

  Route::prefix("currencies", function() {
    Route::get("list", "manager/currencies/all/list");
  });


  Route::prefix("b4b",function(){
    Route::prefix("entry",function(){
      Route::post("add","manager/b4b/entries/add");
      Route::put("edit","manager/b4b/entries/edit");
      Route::get("list","manager/b4b/entries/all");
      Route::get("properties","manager/b4b/entries/all/properties");

      Route::prefix("{id}", function() {
        Route::put("edit-is-blocked","manager/b4b/entries/edit/editIsBlocked/$1");

        Route::delete("delete","manager/b4b/entries/edit/delete/$1");
        Route::put("stock-show","manager/b4b/entries/edit/stockShow/$1");
        Route::put("store-active","manager/b4b/entries/edit/storeActive/$1");
        Route::put("edit-properties","manager/b4b/entries/edit/editProperties/$1");
        Route::put("edit-detail","manager/b4b/entries/edit/detail/$1");
        Route::put("edit-password","manager/b4b/entries/edit/password/$1");
        Route::put("entry-limit","manager/b4b/entries/edit/entryLimit/$1");
        Route::put("add-customer","manager/b4b/entries/edit/addCustomer/$1");
      });
    });

    Route::get("brand-sliders", "manager/b4b/brand_sliders/all");
    Route::prefix("brand-sliders", function(){
      Route::post("add", "manager/b4b/brand_sliders/add");
      Route::put("edit", "manager/b4b/brand_sliders/edit");
      Route::delete("{id}/delete", "manager/b4b/brand_sliders/edit/delete/$1");
    });

    Route::get("sliders", "manager/b4b/sliders/all");
    Route::prefix("sliders", function(){
      Route::post("add", "manager/b4b/sliders/add");
      Route::put("edit", "manager/b4b/sliders/edit");
      Route::delete("{id}/delete", "manager/b4b/sliders/edit/delete/$1");
    });

    Route::get("news-popup", "manager/b4b/news/all");
    Route::prefix("news-popup", function(){
      Route::get("types", "manager/b4b/news/all/types");
      Route::post("add", "manager/b4b/news/add");
      Route::put("{id}/edit", "manager/b4b/news/edit/index/$1");
      Route::delete("{id}/delete", "manager/b4b/news/edit/delete/$1");
    });

    # Orders
    Route::get("orders", "manager/b4b/orders/all/index");
    Route::prefix("orders", function() {

      Route::get("statuses", "manager/b4b/orders/all/statuses");
      Route::prefix("{id}", function() {
        Route::get("details", "manager/b4b/orders/all/details/$1");

        Route::put("edit-status", "manager/b4b/orders/edit/editStatus/$1");

        Route::put("transfer-order", "manager/b4b/orders/edit/transferOrder/$1");


        // Route::put("status-confirm","manager/b4b/orders/edit/statusConfirm/$1");
        // Route::put("status-finish","manager/b4b/orders/edit/statusFinish/$1");
        // Route::put("status-cancel","manager/b4b/orders/edit/statusCancel/$1");
      });

      Route::get("returns", "manager/b4b/orders/returns/all/index");
      Route::prefix("returns", function() {
        Route::get("{id}/details", "manager/b4b/orders/returns/all/details/$1");
        Route::post("add", "manager/b4b/orders/returns/add/action");
      });

      #Folders
      Route::get("folders", "manager/b4b/orders/folders/all/index");
      Route::prefix("folders", function() {
        Route::get("list", "manager/b4b/orders/folders/all/list");


        Route::post("add", "manager/b4b/orders/folders/add/index");

        Route::prefix("{id}", function(){
          # Order operations
          Route::get("orders-list", "manager/b4b/orders/folders/all/ordersList/$1");
          Route::post("add-order", "manager/b4b/orders/folders/add/addOrder/$1");
          Route::delete("remove-order", "manager/b4b/orders/folders/edit/removeOrder/$1");

          #Folder operations
          Route::put("edit-name", "manager/b4b/orders/folders/edit/editName/$1");
          Route::put("edit-description", "manager/b4b/orders/folders/edit/editDescription/$1");
          Route::put("edit-is-active", "manager/b4b/orders/folders/edit/editIsActive/$1");
          Route::delete("delete", "manager/b4b/orders/folders/edit/delete/$1");
        });
      });

    });
  });


  Route::prefix("static",function(){
    Route::get("error-logs","manager/static/errors/applicationLogs");
    Route::get("error-logs/paths","manager/static/errors/applicationLogsPaths");
  });

  Route::prefix("users",function(){
    Route::get("list","manager/users/all");
    Route::get("groups","manager/users/all/groups");
    Route::get("roles","manager/users/all/roles");
    Route::get("order-groups","manager/users/all/orderGroups");
    Route::post("add","manager/users/add");
    Route::put("{id}/edit","manager/users/edit/index/$1");
    Route::put("{id}/edit-password","manager/users/edit/password/$1");
    Route::put("{id}/edit-group","manager/users/edit/editGroup/$1");
    Route::delete("{id}/delete","manager/users/edit/delete/$1");

  });

  Route::prefix("configs",function(){
    Route::get("list","manager/configs/all");
    Route::put("{id}/edit","manager/configs/edit/index/$1");
    Route::put("{id}/status","manager/configs/edit/status/$1");
    Route::post("add","manager/configs/add");
    Route::delete("{id}/delete","manager/configs/edit/delete/$1");
    Route::get("properties","manager/configs/all/properties");
  });


  Route::get("currencies", "manager/currencies/all/index");

  Route::prefix("caches",function(){
    Route::get("history","manager/caches/all/history");
  });


  Route::prefix("supervisors",function(){
    Route::get("all","manager/supervisors/all/index");
    Route::post("add","manager/supervisors/add/action");
    Route::put("edit","manager/supervisors/all/edit");
  });

  Route::get("customers","manager/customers/all");
  Route::prefix("customers",function(){

    Route::get("list","manager/customers/all/list");
    Route::get("city-list","manager/customers/all/cityList");
    Route::get("invoices-in-details","manager/customers/account/brandReportsInDetails");
    Route::get("invoices","manager/customers/account/brandReports");

    Route::prefix("{id}", function() {
      Route::put("edit-max-order-limit", "manager/customers/edit/editMaxOrderLimit/$1");
      Route::put("edit-is-blocked", "manager/customers/edit/editIsBlocked/$1");
      Route::put("edit-max-allowed-order-limit", "manager/customers/edit/editMaxAllowedOrderLimit/$1");
      Route::put("edit-has-order-limit", "manager/customers/edit/editHasOrderLimit/$1");

      Route::get("account", "manager/customers/account/index/$1");

      Route::get("account/{code}/details", "manager/customers/account/details/$1/$2");
    });
  });

  Route::prefix("dashboard",function(){
    Route::get("onlines","manager/dashboard/b4bOnlines");
  });

  # Products search
  Route::prefix("products", function() {
    Route::get("search", "manager/products/search/index");
    Route::get("product-comments", "manager/products/search/comments");
    Route::get("product-price-offers", "manager/products/search/priceOffers");
    Route::get("comments-list", "manager/products/comments/all");
    Route::get("price-offers-list", "manager/products/price_offers/all");
    Route::get("entries-list", "manager/products/comments/all/entriesList");

    Route::get("imports", "manager/products/imports/all/index");
    Route::prefix("imports", function() {
      Route::post("add", "manager/products/imports/add/index");

      Route::delete("{id}/delete", "manager/products/imports/all/delete/$1");
    });

    Route::prefix("properties", function() {
      Route::get("brands", "manager/products/properties/brands");
      Route::get("car-brands", "manager/products/properties/carBrands");
      Route::get("product-resources", "manager/products/properties/productResources");
    });

    Route::prefix("{id}", function() {
      Route::put("edit-price", "manager/products/edit/editPrice/$1");

      Route::put("discount-price", "manager/products/edit/discountPrice/$1");

      Route::put("hide-price", "manager/products/edit/hidePrice/$1");

      Route::put("edit-is-new-from-warehouse", "manager/products/edit/isNewFromWarehouse/$1");
    });

    Route::put("apply-discount", "manager/products/search/applyDiscount");

    Route::put("hide-prices", "manager/products/search/hidePrice");

    Route::get("discount-packages", "manager/products/discount_packages/all");

    Route::delete("discount-packages/{id}/delete", "manager/products/discount_packages/all/delete/$1");

  });


  Route::prefix("order-groups",function(){
    Route::get("list","manager/order_groups/all/list");
    Route::post("add","manager/order_groups/all/add");
    Route::delete("{id}/delete","manager/order_groups/all/delete/$1");
    Route::put("{id}/detail","manager/order_groups/all/detail/$1");
    Route::get("warehouses","manager/order_groups/all/warehouses");
  });


  Route::prefix("reports",function(){
    Route::get("dashboard","manager/reports/dashboard");

  });

  Route::prefix("banners",function(){
    Route::get("all","manager/banners/all/index");
    Route::post("add","manager/banners/add/action");
    Route::put("edit","manager/banners/all/edit");
    Route::delete("{code}/delete","manager/banners/all/delete/$1");
  });

  # Warehouse
  Route::get("warehouses", "manager/warehouses/all/index");


  Route::get("search-logs", "manager/searchlogs/index");
  Route::get("search-logs/only-customers", "manager/searchlogs/onlyCustomers");

});
