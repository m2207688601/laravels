<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});



//注册
Route::any('register',"IndexController@register");
Route::any('reg',"IndexController@reg");
Route::any('code',"IndexController@code");
//登录
Route::any('login',"IndexController@login");
Route::any('loginadd',"IndexController@loginadd");
//验证码
Route::any('create',"CaptchaController@create");
//项目首页
Route::any('/',"IndexController@index");
//商品列表
Route::any('allshops',"IndexController@allshops");
Route::any('test',"IndexController@test");
Route::any('get',"IndexController@get");
//商品详情
Route::any('shopcontent',"IndexController@shopcontent");
Route::any('cont',"IndexController@cont");
//购物车
Route::any('shopcart',"IndexController@shopcart")->middleware('login');
Route::any('alladd',"IndexController@alladd");
Route::any('pay',"IndexController@pay");
Route::any('deletes',"IndexController@deletes");
Route::any('upd',"IndexController@upd");
Route::any('del',"IndexController@del");
//我的潮购
Route::any('userpage',"IndexController@userpage")->middleware('login');
//晒单
Route::any('share',"IndexController@share");
//填写晒单内容
Route::any('willshare',"IndexController@willshare");

