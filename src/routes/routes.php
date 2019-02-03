<?php

Route::group(['middleware' => ['web']], function() {
	Route::group(['middleware' => 'auth'], function () {
		
		Route::get('/dashboard/ecommerce', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\EcommerceController@index')->name('dashboard.module.ecommerce.index');
		
		//START OF: ORDERS ROUTES
		Route::get('/dashboard/ecommerce/orders', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\OrderController@index')->name('dashboard.module.ecommerce.orders.index');
		//END OF: ORDERS ROUTES
		
		//START OF: PRODUCTS ROUTES
		Route::get('/dashboard/ecommerce/products', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\ProductController@index')->name('dashboard.module.ecommerce.products.index');
		Route::get('/dashboard/ecommerce/products/create', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\ProductController@create')->name('dashboard.module.ecommerce.products.create');
		Route::get('/dashboard/ecommerce/products/{product}/edit', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\ProductController@edit')->name('dashboard.module.ecommerce.products.edit');
		Route::post('/dashboard/ecommerce/products/save', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\ProductController@save')->name('dashboard.module.ecommerce.products.save');
		Route::post('/dashboard/ecommerce/products/update', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\ProductController@update')->name('dashboard.module.ecommerce.products.update');
		//END OF: PRODUCTS ROUTES
		
		//START OF: COLLECTIONS ROUTES
		Route::get('/dashboard/ecommerce/collections', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CollectionController@index')->name('dashboard.module.ecommerce.collections.index');
		Route::post('/dashboard/ecommerce/collections/save', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CollectionController@save')->name('dashboard.module.ecommerce.collections.save');
		Route::post('/dashboard/ecommerce/collections/delete', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CollectionController@delete')->name('dashboard.module.ecommerce.collections.delete');
		//END OF: COLLECTIONS ROUTES
		
		//START OF: BRANDS ROUTES
		Route::get('/dashboard/ecommerce/brands', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\BrandController@index')->name('dashboard.module.ecommerce.brands.index');
		Route::post('/dashboard/ecommerce/brands/save', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\BrandController@save')->name('dashboard.module.ecommerce.brands.save');
		Route::post('/dashboard/ecommerce/brands/delete', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\BrandController@delete')->name('dashboard.module.ecommerce.brands.delete');
		//END OF: BRANDS ROUTES
		
		////START OF: ATTRIBUTES ROUTES
		Route::get('/dashboard/ecommerce/attributes', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\AttributeController@index')->name('dashboard.module.ecommerce.attributes.index');
		Route::get('/dashboard/ecommerce/attributes/edit/{id}', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\AttributeController@edit')->name('dashboard.module.ecommerce.attributes.edit');
		Route::post('/dashboard/ecommerce/attributes/save', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\AttributeController@save')->name('dashboard.module.ecommerce.attributes.save');
		Route::post('/dashboard/ecommerce/attributes/delete', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\AttributeController@delete')->name('dashboard.module.ecommerce.attributes.delete');
		//END OF: ATTRIBUTES ROUTES
		
		//START OF: CUSTOMERS ROUTES
		Route::get('/dashboard/ecommerce/customers', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CustomerController@index')->name('dashboard.module.ecommerce.customers.index');
		//END OF: CUSTOMERS ROUTES
		
		//START OF: DISCOUNTS ROUTES
		Route::get('/dashboard/ecommerce/discounts', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\DiscountController@index')->name('dashboard.module.ecommerce.discounts.index');
		//END OF: DISCOUNTS ROUTES
	});

	Route::group(['middleware' => ['role:customer']], function () {
	    Route::get('/account/profile', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\AccountController@index')->name('module.ecommerce.account.index');
	    Route::get('/account/orders', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\AccountOrderController@index')->name('module.ecommerce.account.order.index');
	    Route::get('/account/addresses', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\AccountAddressController@index')->name('module.ecommerce.account.address.index');
	    Route::get('/account/wishlist', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\AccountWishlistController@index')->name('module.ecommerce.account.wishlist.index');
	    Route::get('/account/tickets', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\AccountTicketController@index')->name('module.ecommerce.account.ticket.index');
	});

});