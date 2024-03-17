<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Route::get("search","product/search/find");
Route::get("brand-list","product/brands/allList");
Route::get("certificates","home/certificateList");
Route::get("news-list","news/allList");
Route::get("promotions","promotions/allList");
Route::get("about","pages/about");

Route::get("contact","pages/contact");
Route::post("contact-us/send","contact/add/send_request");
Route::get("brand/{id}","product/brands/brandDetails/$1");
Route::get("news/{id}","news/details/$1");
Route::get("promotion/list-live","promotions/getAll");
Route::get("promotion/{id}","promotions/details/$1");
Route::get("news/list-live","news/getAll");
Route::post("send-order","contact/request/send_request");
Route::get("certificates/list-live","certificate/getAll");
Route::get("product/{id}","product/details/index/$1");


Route::get("privacy-policies","pages/privacy_policies");
Route::get("terms-and-conditions","pages/terms_and_conditions");
Route::get("faq","pages/faq");
// Route::get("page/{id}","pages/getStaticPageData/$1");


Route::prefix("admin",function(){
  Route::get("users-live","admin/main/usersLive");

  Route::get("product/get-1c-brands","admin/product/get1CBrands");

  Route::get("product/category","admin/product/categoryList");
  Route::post("product/category/add","admin/product/addCategory");
  Route::post("product/category/update","admin/product/updateCategory");
  Route::delete("product/category/{id}/delete","admin/product/deleteCategory/$1");

  Route::post("user/{id}/update","admin/main/updateInfo/$1");
  Route::post("user/{id}/status/update","admin/main/updateStatus/$1");
  Route::delete("user/{id}/delete","admin/main/deleteUser/$1");
  Route::post("user/add","admin/auth/addNewUser");
  // Route::get("up-ajax","user/auth/register");


  Route::post("product/update-orders","admin/main/updateProductOrders");
  Route::get("home-products","admin/main/homeProducts");
  Route::post("product/update-limit","admin/main/updateProductLimit");
  Route::get("product/check-code","admin/main/checkCode");
  Route::post("add-home-product","admin/main/addHP_product");
  Route::post("product/update","admin/product/updateProduct");
  Route::get("get-brands","product/search/getBrandsForAdmin");

  Route::post("product/home-list/remove","admin/product/removeFromHomeList");
  Route::post("product/home-list/update","admin/product/updateHomeList");
  Route::post("product/status/update","admin/product/updateStatus");
  Route::get("product/list-live","admin/product/getProducts");
  Route::post("product/add-action","admin/product/addNewProduct");
  Route::delete("product/{id}/delete","admin/product/delete/$1");



  // static pages control starts he`re
  Route::get("pages/list","admin/main/pageList");
  Route::post("pages/faq/add","admin/main/addFaq");
  Route::post("pages/faq/{id}/edit","admin/main/editFaq/$1");
  Route::delete("pages/faq/{id}/delete","admin/main/deleteFaq/$1");
  Route::post("pages/about/{id}/edit","admin/main/editAbout/$1");
  Route::post("pages/about/add","admin/main/addAbout");
  Route::post("news/{id}/edit-action","admin/news/update/$1");
  Route::post("news/{id}/change-status","admin/news/changeStatus/$1");

  Route::delete("promotion/{id}/delete","admin/promotion/delete/$1");
  Route::post("promotion/{id}/edit-action","admin/promotion/update/$1");
  Route::post("promotion/{id}/change-status","admin/promotion/changeStatus/$1");
  Route::post("promotion/add-action","admin/promotion/addNew");


  Route::get("user/list","admin/main/users");
  Route::get("home-page/products","admin/main/homePageProducts");

  Route::prefix("configurations",function(){
    Route::get("about_faq","admin/config/aboutFaqControl");
    // Route::get("list","admin/config/getList");
    // Route::get("update-footer","admin/config/updateFooterView");
    // Route::post("update-footer-action","admin/config/updateFooterAction");
  });

  Route::prefix("product",function(){
    Route::get("add","admin/product/productAddView");
    Route::get("{id}/edit","admin/product/editView/$1");
    Route::get("list","admin/product/productListView");
    Route::get("categories","admin/product/categoryListView");
  });


  Route::prefix("news",function(){
    Route::get("list","admin/news/listView");
    Route::get("{id}/edit","admin/news/editView/$1");
    Route::get("add","admin/news/addView");
  });

  Route::prefix("promotion",function() {
    Route::get("list","admin/promotion/listView");
    Route::get("{id}/edit","admin/promotion/editView/$1");
    Route::get("add","admin/promotion/addView");
  });

  Route::get("certificate_control","admin/main/certificateControl");

  # Admin brands
  Route::prefix("brand",function() {
    Route::get("list","admin/brand/brandList");
    Route::get("list-live","admin/brand/liveList");
    Route::post("add-new","admin/brand/addNew");
    Route::get("add","admin/brand/brandControl");
    Route::post("delete","admin/brand/delete");
    Route::get("{id}/edit","admin/brand/inDetail/$1");
    Route::post("{id}/update","admin/brand/update/$1");
    Route::post("update-order","admin/brand/updateOrder");
  });

  // Admin auth
  Route::get("login","admin/auth/adminAuth");
  Route::get("dashboard","admin/main/dashboard");

  Route::get("user/change","admin/main/userProfileEdit");

});


Route::get("sign-up-ajax","user/auth/register");
// Route::get("sign-in-ajax","user/auth/login");
Route::post("sign-in-action","user/auth/login");
Route::get("sign-out","user/auth/logout");
Route::get("sign-in","user/auth/login_page");
Route::get("sign-up","user/auth/register_page");
// Route::get("send-otp","user/auth/send_otp");
Route::get("password-reset","user/auth/password_reset_view");
Route::post("change-password","user/auth/change_password");
// Route::get("email-confirmation","user/auth/email_confirmation");





// Route::post("brand/{id}/edit-action","product/brands/edit/$1");
// Route::get("get-currencies","home/get_currencies");
Route::get("menu/live-list","home/get_menu");



// Route::get("verify-email","user/auth/verifyEmail");
Route::get("product/list-ajax","product/details/getProductsAjax");
Route::get("product/latest-ajax","product/details/getLatestProducts");
Route::get("product/cross-references","product/details/getCrossReference");
Route::get("product/similar-oems","product/details/similarOEMs");
Route::get("product/compatible-cars","product/details/compatibleCars");
Route::get("product/details","product/details/getPartDetails");
Route::post("product/details/update","product/details/updateDetails");


Route::get("search/live","product/search/searchAjax");
Route::get("search/auto-complete","product/search/getSuggestions");
Route::get("sub-category-live","product/category/subcatLive");
Route::get("category-live","product/category/categoryLive");
Route::get("product/category","product/details/categories");
Route::get("product/category-by-group","product/details/categoryByGroup");
Route::get("group-products/live","product/details/groupProducts");
Route::get("product/regions","product/details/regions");

Route::get("get-car-brands","home/getCarBrands");
Route::get("get-car-years","home/getBrandYears");
Route::get("get-car-models","home/getBrandModels");
Route::get("get-car-engine","home/getBrandEngine");


Route::get("auto-update-catalog","home/auto_update_catalog");

// Route::get("change-user-avatar","user/user/changeAvatar");
// Route::get("product/review/add","product/review/addReview");
// Route::get("product/review/list","product/review/getList");
// Route::get("panel-search/get-products","product/search/listProducts");
Route::get("product/home-products","product/details/homeProducts");




// static pages control ends here
Route::get("brand/list-live","product/brands/liveList");
// Route::post("brand/add-new","product/brands/addNew");

Route::post("news/add-action","admin/news/addNew");
Route::post("news/delete","admin/news/delete");



Route::post("certificates/add","certificate/addNew");
Route::post("certificates/delete","certificate/delete");



Route::get("get-lang-list","config/lang/languages");
Route::get("news/add","news/addNew");
Route::get("promotion/add","promotions/addNew");


  /* user routes */
Route::get("profile/{id}","user/user/profile/$1");





Route::get("product/list","product/details/getProducts");
Route::post("product/home-list/update","product/details/updateHomeProduct");
Route::post("product/name/update","product/details/changeName");
// Route::post("product/description/update","product/details/changeDescription");
Route::get("product/similar/list","product/details/getSimilars");
Route::get("product/edit/{id}","product/details/productEdit/$1");
Route::get("product/add","product/details/addNew");
Route::get("product/edit-manual/{id}","product/details/editManualProduct/$1");
Route::get("product/all-categories","product/details/allCategories");




Route::get("search/get-brands","product/search/getBrands");
// Route::get("panel-search","product/search/panelSearch");

Route::get("brand/{id}/models","product/carbrand/models/$1");
Route::get("brand/{id}/models/{id}/engine","product/carbrand/engines/$1/$2");
Route::get("brand/{id}/models/{id}/engine/{id}/cataloge","product/carbrand/cataloge/$1/$2/$3");
Route::get("cataloge/products/{id}/{id}","product/carbrand/products/$1/$2");






// Route::get("product/list-by-engine","product/details/getByEngine");
