<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('App\Http\Controllers')->group(function (){

    /**
     * 授权登录
     */
    Route::post('/oauth/token', [
        'uses' => 'AccessTokenController@issueToken',
        'middleware' => 'throttle',
    ]);

    /**
     * 注册
     */
    Route::post('/register', "UserController@register");

    /**
     * 注册验证码
     */
    Route::post('/captcha/registerSms', "UserController@registerSms");

    /**
     * 修改密码验证码
     */
    Route::post('/captcha/modifyPasswordSms', "UserController@modifyPasswordSms");

    /**
     * 修改密码，忘记密码
     */
    Route::post('/modifyPassword', "UserController@modifyPassword");

    /**
     * 检测是否登录
     */
    Route::post("/checkAuth", 'UserController@checkAuth');

    /**
     * 需要登录访问的接口列表
     */
    Route::middleware('auth:api')->group(function(){
    });

});

