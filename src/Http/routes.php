<?php

use ManoCode\Erp\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('erp', [Controllers\ErpController::class, 'index']);

// 商品分类
Route::resource('goods_class', \ManoCode\Erp\Http\Controllers\GoodsClasController::class);
// 计量单位
Route::resource('goods_unit', \ManoCode\Erp\Http\Controllers\GoodsUnitController::class);
// 品牌分类
Route::resource('brand_class', \ManoCode\Erp\Http\Controllers\BrandClasController::class);
// 商品品牌
Route::resource('goods_brand', \ManoCode\Erp\Http\Controllers\GoodsBrandController::class);
// 商品管理
Route::resource('goods', \ManoCode\Erp\Http\Controllers\GoodController::class);
// 企业分类
Route::resource('company_class', \ManoCode\Erp\Http\Controllers\CompanyClasController::class);
// 企业管理
Route::resource('company', \ManoCode\Erp\Http\Controllers\CompanyController::class);
// 仓库管理
Route::resource('warehouse', \ManoCode\Erp\Http\Controllers\WarehouseController::class);
// 仓库管理
Route::resource('warehouse_class', \ManoCode\Erp\Http\Controllers\WarehouseClasController::class);
// 采购管理
Route::resource('purchase', \ManoCode\Erp\Http\Controllers\PurchaseController::class);
// 入库记录
Route::resource('put_warehouse', \ManoCode\Erp\Http\Controllers\PutWarehouseController::class);

/**
 * 提审采购单
 */
Route::post('/erp/push-purchase-process',[Controllers\PurchaseController::class,'pushProcessApi']);
/**
 * 入库提交
 */
Route::post('/erp/put-purchase-warehouse',[Controllers\PurchaseController::class,'putPurchaseWarehouse']);
/**
 * 获取商品列表
 */
Route::get('/erp/get-goods-lists',[Controllers\GoodController::class,'getGoodsLists']);
/**
 * 获取指定商品的 SKU
 */
Route::get('/erp/get-goods-sku',[Controllers\GoodController::class,'getGoodsSku']);
/**
 * 获取商品单位
 */
Route::get('/erp/get-goods-unit',[Controllers\GoodsUnitController::class,'getGoodsUnit']);
/**
 * 品牌类型
 */
Route::get('/erp/get-brand-class',[Controllers\BrandClasController::class,'getBrandClass']);
/**
 * 获取企业列表
 */
Route::get('/erp/get-company-lists',[Controllers\CompanyController::class,'getCompanyLists']);
/**
 * 获取企业分类
 */
Route::get('/erp/get-company-class',[Controllers\CompanyClasController::class,'getCompanyClass']);
/**
 * 获取品牌列表
 */
Route::get('/erp/get-brand',[Controllers\GoodsBrandController::class,'getBrand']);
/**
 * 商品分类的tree
 */
Route::get('/erp/get-goods-class-tree',[Controllers\GoodsClasController::class,'getGoodsClassTree']);
/**
 * 获取仓库分类树形列表
 */
Route::get('/erp/get-store-class-tree',[Controllers\WarehouseClasController::class,'getWarehouseClassTree']);
/**
 * 获取仓库列表
 */
Route::get('/erp/get-store-lists',[Controllers\WarehouseController::class,'getStoreLists']);
/**
 * 获取仓位
 */
Route::get('/erp/get-store-position',[Controllers\WarehouseController::class,'getStorePosition']);
/**
 * 获取对照表
 */
Route::get('/erp/get-goods-class-lists',[Controllers\GoodsClasController::class,'getGoodsClassLists']);
/**
 * 获取商品 code 对照表
 */
Route::get('/erp/get-goods-lists-as-code',[Controllers\GoodsClasController::class,'getGoodsClassLists']);
