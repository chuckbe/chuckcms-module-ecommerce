<?php

Route::group(['middleware' => ['web']], function() {
	Route::group(['middleware' => 'auth'], function () {
		Route::get('/dashboard/ecommerce', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\EcommerceController@index')->name('dashboard.module.ecommerce.index');

		Route::get('/dashboard/ecommerce/orders', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\OrderController@index')->name('dashboard.module.ecommerce.orders.index');

		Route::get('/dashboard/ecommerce/products', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\ProductController@index')->name('dashboard.module.ecommerce.products.index');
		Route::get('/dashboard/ecommerce/products/create', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\ProductController@create')->name('dashboard.module.ecommerce.products.create');
		Route::get('/dashboard/ecommerce/products/save', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\ProductController@save')->name('dashboard.module.ecommerce.products.save');

		Route::get('/dashboard/ecommerce/customers', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CustomerController@index')->name('dashboard.module.ecommerce.customers.index');

		Route::get('/dashboard/ecommerce/discounts', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\DiscountController@index')->name('dashboard.module.ecommerce.discounts.index');
	});

});