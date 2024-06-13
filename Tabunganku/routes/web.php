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

Route::get('/', 'Auth\LoginController@showLoginForm');

Auth::routes();

/**
 * account
 */
Route::prefix('account')->group(function () {

    //dashboard account
    Route::get('/dashboard', 'account\DashboardController@index')->name('account.dashboard.index');

    //categories debit
    Route::get('/categories_debit/search', 'account\CategoriesDebitController@search')->name('account.categories_debit.search');
    Route::Resource('/categories_debit', 'account\CategoriesDebitController',['as' => 'account']);
    //debit
    Route::get('/debit/search', 'account\DebitController@search')->name('account.debit.search');
    Route::Resource('/debit', 'account\DebitController',['as' => 'account']);
    //categories credit
    Route::get('/categories_credit/search', 'account\CategoriesCreditController@search')->name('account.categories_credit.search');
    Route::Resource('/categories_credit', 'account\CategoriesCreditController',['as' => 'account']);
    //credit
    Route::get('/credit/search', 'account\CreditController@search')->name('account.credit.search');
    Route::Resource('/credit', 'account\CreditController',['as' => 'account']);
    //categories hutang
    Route::get('/categories_hutang/search', 'account\CategoriesHutangController@search')->name('account.categories_hutang.search');
    Route::Resource('/categories_hutang', 'account\CategoriesHutangController',['as' => 'account']);
    //hutang
    Route::get('/hutang/search', 'account\HutangController@search')->name('account.hutang.search');
    Route::Resource('/hutang', 'account\HutangController',['as' => 'account']);
    //Catatan debit
    Route::get('/catatan_debit', 'account\CatatanDebitController@index')->name('account.catatan_debit.index');
    Route::get('/catatan_debit/check', 'account\CatatanDebitController@check')->name('account.catatan_debit.check');
    //Catatan credit
    Route::get('/catatan_credit', 'account\CatatanCreditController@index')->name('account.catatan_credit.index');
    Route::get('/catatan_credit/check', 'account\CatatanCreditController@check')->name('account.catatan_credit.check');
    //catatan hutang
     Route::get('/catatan_hutang', 'account\CatatanHutangController@index')->name('account.catatan_hutang.index');
     Route::get('/catatan_hutang/check', 'account\CatatanHutangController@check')->name('account.catatan_hutang.check');
     
});
