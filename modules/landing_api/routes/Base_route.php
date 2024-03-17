<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* Authorization && user details */
$base = 'user/';
$route[$base.'authorization/register'] = $project_folder.'user/user/register';
$route[$base.'authorization/login'] = $project_folder.'user/user/login';
$route[$base.'authorization/login-with-token'] = $project_folder.'user/user/login_with_token';
$route[$base.'authorization/reset-password-token'] = $project_folder.'user/user/resetPasswordToken';
$route[$base.'authorization/check-password-token'] = $project_folder.'user/user/checkResetPassword';
$route[$base.'authorization/change-password'] = $project_folder.'user/user/changePassword';
$route[$base.'authorization/verify-account'] = $project_folder.'user/user/verifyAccount';
$route[$base.'authorization/verify-account-token'] = $project_folder.'user/user/verifyAccountToken';
$route[$base.'check'] = $project_folder.'user/user/checkUser';
$route[$base.'avatar/change'] = $project_folder.'user/user/changeAvatar';

/* Product routes */
$base = 'product';
$folder = 'product/';
$file = 'product';
$route[$base.'/get-by-codes'] = $project_folder.$folder.$file.'/getByCodes';
$route[$base.'/group-list'] = $project_folder.$folder.$file.'/getProductList';
$route[$base.'/update-list'] = $project_folder.$folder.$file.'/updateProducts';
$route[$base.'/details'] = $project_folder.$folder.$file.'/productDetails';
$route[$base.'/details/update'] = $project_folder.$folder.$file.'/updateProductDetails';
$route[$base.'s/latest'] = $project_folder.$folder.$file.'/latestProducts';
$route[$base.'/similar/list'] = $project_folder.$folder.$file.'/similarProducts';
$route[$base.'s/home-page-list'] = $project_folder.$folder.$file.'/homePageList';
$route[$base.'/home-list/update'] = $project_folder.$folder.$file.'/addOrDeleteHomeProduct';
$route[$base.'/name/update'] = $project_folder.$folder.$file.'/updateProductName';
$route[$base.'/description/update'] = $project_folder.$folder.$file.'/updateDescription';
$route[$base.'/list-by-engine'] = $project_folder.$folder.$file.'/getByEngine';

$route[$base.'/car-brands/list'] = $project_folder.$folder.$file.'/getCarBrands';
$route[$base.'/brands/list'] = $project_folder.$folder.$file.'/getBrands';
$route[$base.'/group/all-list'] = $project_folder.$folder.$file.'/getAllGroups';
// $route[$base.'/add'] = $project_folder.$folder.$file.'/addNewProduct';


/* Brand routes */
$base = 'brand/';
$route[$base.'list'] = $project_folder.$folder.'brand/getAll';
$route[$base.'add'] = $project_folder.$folder.'brand/addNew';
$route[$base.'update'] = $project_folder.$folder.'brand/update';
$route[$base.'details'] = $project_folder.$folder.'brand/details';
$route[$base.'delete'] = $project_folder.$folder.'brand/delete';
$route[$base.'ordering'] = $project_folder.$folder.'brand/ordering';

/* News routes */
$base = 'news/';
$route[$base.'list'] = $project_folder.'news/getNewsList';
$route[$base.'add'] = $project_folder.'news/addNew';
$route[$base.'update'] = $project_folder.'news/update';
$route[$base.'delete'] = $project_folder.'news/delete';
$route[$base.'details'] = $project_folder.'news/details';

$route[$base.'(:any)/status/update'] = $project_folder.'news/changeStatus/$1';

/* Promotions routes */
$base = 'promotions/';
$route[$base.'list'] = $project_folder.'news/getPromotionsList';

/* Wishlist routes */
$base = 'wishlist/';
$route[$base.'add-new'] = $project_folder.$folder.'wishlist/addWishlist';
$route[$base.'delete'] = $project_folder.$folder.'wishlist/deleteWishlist';
$route[$base.'get'] = $project_folder.$folder.'wishlist/getWishlist';
$route[$base.'update-quantity'] = $project_folder.$folder.'wishlist/updateQuantity';

/* Cart routes */
$base = 'page/';
$route[$base.'list'] = $project_folder.'pages/getList';
$route[$base.'add'] = $project_folder.'pages/addNew';
$route[$base.'update'] = $project_folder.'pages/update';
$route[$base.'delete/(:any)'] = $project_folder.'pages/delete/$1';

/* Cart routes */
$base = 'cart/';
$route[$base.'get'] = $project_folder.$folder.'cart/getList';
$route[$base.'add-new'] = $project_folder.$folder.'cart/add';
$route[$base.'add-from-wishlist'] = $project_folder.$folder.'cart/addFromWishlist';
$route[$base.'delete'] = $project_folder.$folder.'cart/delete';
$route[$base.'update-quantity'] = $project_folder.$folder.'cart/updateQuantity';

/* Garage routes */
// $base = 'garage/';
// $route[$base.'add'] = $project_folder.'user/garage/create';
// $route[$base.'update/(:any)'] = $project_folder.'user/garage/update/$1';
// $route[$base.'delete/(:any)'] = $project_folder.'user/garage/delete/$1';
// $route[$base.'list'] = $project_folder.'user/garage/list';
// $route[$base.'update'] = $project_folder.'user/garage/updateCarInfo';

/* Review routes */
// $base = 'review/';
// $route[$base.'list'] = $project_folder.'product/reviews/getList';
// $route[$base.'add'] = $project_folder.'product/reviews/add';
// $route[$base.'delete/(:any)'] = $project_folder.'product/reviews/deleteReview/$1';

/* Search control */
$base = 'search';
$route[$base] = $project_folder.'search/findByAjax';
$route['panel-search/categories'] = $project_folder.'search/panelSearchCategories';
$route['panel-search/list/products'] = $project_folder.'search/listProducts';

$route['product/suggest-name'] = $project_folder.'search/searchSuggestions';

/* Category control */
// $base = 'category/';
// $route[$base.'(:any)'] = $project_folder.'category/main/$1';

/* Category control */
$base = 'certificate/';
$route[$base.'list'] = $project_folder.'certificate/getList';
$route[$base.'add'] = $project_folder.'certificate/addNew';
$route[$base.'delete'] = $project_folder.'certificate/delete';
$route[$base.'update'] = $project_folder.'certificate/update';

/* Category control */
$base = 'store/';
$route[$base.'list'] = $project_folder.'store/getList';
$route[$base.'create'] = $project_folder.'store/createStore';
$route[$base.'region/list'] = $project_folder.'store/regionList';
$route[$base.'description/update'] = $project_folder.'store/updateStoreDescription';

$route[$base.'details/name/change'] = $project_folder.'store/changeName';
$route[$base.'details/status/change'] = $project_folder.'store/updateStatus';
$route[$base.'details'] = $project_folder.'store/storeDetails/$1';
$route[$base.'details/add'] = $project_folder.'store/addDetail';
$route[$base.'details/update'] = $project_folder.'store/updateDetail';
$route[$base.'details/delete'] = $project_folder.'store/deleteDetail';
$route[$base.'details/avatar/update'] = $project_folder.'store/updateAvatar';
$route[$base.'details/business-hours/update'] = $project_folder.'store/updateBusinessHours';

$route[$base.'tag/list'] = $project_folder.'store/tagList';

/* Admin routes */

/* User control routes */
$base = 'admin/';
$route[$base.'users'] = $project_folder.'admin/user/getAll';
$route[$base.'user/(:any)/update'] = $project_folder.'admin/user/updateInfo/$1';
$route[$base.'user/(:any)/status/update'] = $project_folder.'admin/user/updateStatus/$1';
$route[$base.'check-user'] = $project_folder.'admin/user/checkUserExist';
$route[$base.'user/(:any)/delete'] = $project_folder.'admin/user/delete/$1';
$route[$base.'user/add'] = $project_folder.'admin/user/addNew';


$route[$base.'config/list'] = $project_folder.'admin/main/getConfigs';
$route[$base.'config/update'] = $project_folder.'admin/main/updateConfig';



$route[$base.'home-products'] = $project_folder.'admin/main/homeProducts';
$route[$base.'check-product-code'] = $project_folder.'admin/main/checkProdCode';
$route[$base.'add-home-product'] = $project_folder.'admin/main/addHP_product';
$route[$base.'product/update-orders'] = $project_folder.'admin/main/updateProductOrders';


$route[$base.'product/home-list/update'] = $project_folder.'admin/product/updateHomeList';
$route[$base.'product/status/update'] = $project_folder.'admin/product/changeStatus';
$route[$base.'product/(:any)/delete'] = $project_folder.'admin/product/delete/$1';

$route[$base.'product/add-category'] = $project_folder.'admin/product/addCategory';
$route[$base.'product/update'] = $project_folder.'admin/product/updateProduct';
$route[$base.'product/image/delete'] = $project_folder.'admin/product/deleteImage';
$route[$base.'product/category/list'] = $project_folder.'admin/product/getList';
$route[$base.'product/category/update'] = $project_folder.'admin/product/updateCategory';
$route[$base.'product/category/(:any)/delete'] = $project_folder.'admin/product/deleteCategory/$1';
$route[$base.'product/add-new'] = $project_folder.'admin/product/addProduct';
$route[$base.'product/get-list'] = $project_folder.'admin/product/getProductList';
$route[$base.'product/check-brand-code'] = $project_folder.'admin/product/checkBrandCode';
$route[$base.'product/get-1c-brands'] = $project_folder.'admin/product/get1CBrands';


$route[$base.'store/list'] = $project_folder.'store/getListFullData';








// --
