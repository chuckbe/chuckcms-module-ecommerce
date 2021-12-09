<?php

Route::group(['middleware' => ['web']], function() {
	Route::group(['middleware' => 'auth'], function () {
		Route::group(['middleware' => ['role:super-admin|administrator|moderator']], function () {
			Route::get('/dashboard/ecommerce', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\EcommerceController@dashboard')->name('dashboard.module.ecommerce.index');
			
			//START OF: ORDERS ROUTES
			Route::get('/dashboard/ecommerce/orders', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\OrderController@index')->name('dashboard.module.ecommerce.orders.index');
			Route::get('/dashboard/ecommerce/orders/create', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\OrderController@create')->name('dashboard.module.ecommerce.orders.create');
			Route::get('/dashboard/ecommerce/orders/{order}/detail', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\OrderController@detail')->name('dashboard.module.ecommerce.orders.detail');
			Route::post('/dashboard/ecommerce/orders/status/update', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\OrderController@status')->name('dashboard.module.ecommerce.orders.status.update');
			Route::get('/dashboard/ecommerce/orders/{order}/invoice', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\OrderController@invoice')->name('dashboard.module.ecommerce.orders.invoice');
			Route::get('/dashboard/ecommerce/orders/{order}/delivery', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\OrderController@delivery')->name('dashboard.module.ecommerce.orders.delivery');
			//END OF: ORDERS ROUTES
			
			//START OF: PRODUCTS ROUTES
			Route::get('/dashboard/ecommerce/products', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\ProductController@index')->name('dashboard.module.ecommerce.products.index');
			Route::get('/dashboard/ecommerce/products/create', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\ProductController@create')->name('dashboard.module.ecommerce.products.create');
			Route::get('/dashboard/ecommerce/products/{product}/edit', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\ProductController@edit')->name('dashboard.module.ecommerce.products.edit');
			Route::post('/dashboard/ecommerce/products/save', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\ProductController@save')->name('dashboard.module.ecommerce.products.save');
			Route::post('/dashboard/ecommerce/products/update', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\ProductController@update')->name('dashboard.module.ecommerce.products.update');
			Route::post('/dashboard/ecommerce/products/delete', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\ProductController@delete')->name('dashboard.module.ecommerce.products.delete');
			//END OF: PRODUCTS ROUTES
			
			//START OF: COLLECTIONS ROUTES
			Route::get('/dashboard/ecommerce/collections', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CollectionController@index')->name('dashboard.module.ecommerce.collections.index');
			Route::get('/dashboard/ecommerce/collections/{collection}/sorting', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CollectionController@sorting')->name('dashboard.module.ecommerce.collections.sorting');
			Route::post('/dashboard/ecommerce/collections/{collection}/sorting/update', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CollectionController@updateSort')->name('dashboard.module.ecommerce.collections.update_sort');
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
			Route::get('/dashboard/ecommerce/customers/{customer}/detail', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CustomerController@detail')->name('dashboard.module.ecommerce.customers.detail');
			//END OF: CUSTOMERS ROUTES
			
			//START OF: DISCOUNTS ROUTES
			Route::get('/dashboard/ecommerce/discounts', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\DiscountController@index')->name('dashboard.module.ecommerce.discounts.index');
			Route::get('/dashboard/ecommerce/discounts/create', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\DiscountController@create')->name('dashboard.module.ecommerce.discounts.create');
			Route::get('/dashboard/ecommerce/discounts/{discount}/edit', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\DiscountController@edit')->name('dashboard.module.ecommerce.discounts.edit');
			Route::post('/dashboard/ecommerce/discounts/save', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\DiscountController@save')->name('dashboard.module.ecommerce.discounts.save');
			Route::post('/dashboard/ecommerce/discounts/delete', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\DiscountController@delete')->name('dashboard.module.ecommerce.discounts.delete');
			//END OF: DISCOUNTS ROUTES
			
			//START OF: SETTINGS ROUTES
			Route::get('/dashboard/ecommerce/settings', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\SettingController@index')->name('dashboard.module.ecommerce.settings.index');
			Route::get('/dashboard/ecommerce/settings/update', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\Settings\GeneralController@update')->name('dashboard.module.ecommerce.settings.index.general.update');
			
			Route::get('/dashboard/ecommerce/settings/layout', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\SettingController@layout')->name('dashboard.module.ecommerce.settings.index.layout');
			Route::post('/dashboard/ecommerce/settings/layout/update', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\Settings\LayoutController@update')->name('dashboard.module.ecommerce.settings.index.layout.update');
			
			Route::get('/dashboard/ecommerce/settings/orders', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\SettingController@orders')->name('dashboard.module.ecommerce.settings.index.orders');
			Route::get('/dashboard/ecommerce/settings/orders/statuses/edit/{status}', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\Settings\StatusController@edit')->name('dashboard.module.ecommerce.settings.index.orders.statuses.edit');
			Route::get('/dashboard/ecommerce/settings/orders/statuses/{status}/emails/new', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\Settings\StatusController@email')->name('dashboard.module.ecommerce.settings.index.orders.statuses.email.new');
			Route::post('/dashboard/ecommerce/settings/orders/statuses/update', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\Settings\StatusController@update')->name('dashboard.module.ecommerce.settings.index.orders.statuses.update');
			Route::post('/dashboard/ecommerce/settings/orders/statuses/emails/save', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\Settings\StatusController@emailSave')->name('dashboard.module.ecommerce.settings.index.orders.statuses.email.save');
			Route::post('/dashboard/ecommerce/settings/orders/statuses/emails/delete', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\Settings\StatusController@emailDelete')->name('dashboard.module.ecommerce.settings.index.orders.statuses.email.delete');
			
			Route::get('/dashboard/ecommerce/settings/shipping', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\SettingController@shipping')->name('dashboard.module.ecommerce.settings.index.shipping');
			Route::post('/dashboard/ecommerce/settings/shipping/carrier/save', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\Settings\CarrierController@save')->name('dashboard.module.ecommerce.settings.shipping.carrier.save');
			Route::post('/dashboard/ecommerce/settings/shipping/carrier/delete', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\Settings\CarrierController@delete')->name('dashboard.module.ecommerce.settings.shipping.carrier.delete');

			Route::get('/dashboard/ecommerce/settings/integrations', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\SettingController@integrations')->name('dashboard.module.ecommerce.settings.index.integrations');
			Route::post('/dashboard/ecommerce/settings/integrations/update', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\Settings\IntegrationsController@update')->name('dashboard.module.ecommerce.settings.index.integrations.update');
			//END OF: SETTINGS ROUTES

			// STARTOF: POS ROUTES
			Route::get('/dashboard/ecommerce/pos', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\POSController@index')->name('dashboard.module.ecommerce.pos.index');
		});
		
		//START OF: FRONT_END ACCOUNT ROUTES
		Route::group(['middleware' => ['role:customer']], function () {
		    Route::get('/account/profile', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\AccountController@index')->name('module.ecommerce.account.index');
		    Route::post('/account/profile/update', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\AccountController@update')->name('module.ecommerce.account.update');
		    
		    Route::get('/account/orders', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\AccountOrderController@index')->name('module.ecommerce.account.order.index');
		    Route::get('/account/orders/{order_number}', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\AccountOrderController@detail')->name('module.ecommerce.account.order.detail');
		    
		    Route::get('/account/addresses', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\AccountAddressController@index')->name('module.ecommerce.account.address.index');
		    Route::post('/account/addresses/update', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\AccountAddressController@update')->name('module.ecommerce.account.address.update');
		    
		    Route::get('/account/wishlist', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\AccountWishlistController@index')->name('module.ecommerce.account.wishlist.index');
		    
		    Route::get('/account/tickets', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\AccountTicketController@index')->name('module.ecommerce.account.ticket.index');
		});
		//END OF: FROND_END ACCOUNT ROUTES
	});

	Route::post('/fetch-combination', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\ProductController@getCombination')->name('module.ecommerce.product.get_combination');

	Route::group([
	    'prefix' => LaravelLocalization::setLocale(),
	        'middleware' => [ 
	            'Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter', 
	            'Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath'
	            ]
	    ], function() {
	    Route::get('/shopping-cart', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CartController@overview')->name('module.ecommerce.cart.overview');
		Route::get('/checkout', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CheckoutController@index')->name('module.ecommerce.checkout.index');
	});

	Route::post('/place-order', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CheckoutController@placeOrder')->name('module.ecommerce.checkout.finalize');
	Route::post('/order/get-status', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CheckoutController@orderStatus')->name('module.ecommerce.checkout.status');

	Route::get('{order_number}/bedankt-voor-uw-bestelling-chuck', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CheckoutController@orderFollowup')->name('module.ecommerce.checkout.followup');
	Route::get('{order_number}/online-betalen-chuck', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CheckoutController@orderPay')->name('module.ecommerce.checkout.pay');


	Route::post('/update-cart', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CartController@updateCart')->name('module.ecommerce.cart.update');
	Route::post('/update-cart-item', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CartController@updateCartItem')->name('module.ecommerce.cart.update.item');

	Route::post('/add-to-cart', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CartController@addToCart')->name('module.ecommerce.cart.add.product');
	Route::post('/remove-from-cart', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CartController@removeFromCart')->name('module.ecommerce.cart.remove.product');

	Route::post('/cart-detail-html', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CartController@detailHtml')->name('module.ecommerce.cart.html.detail');
	Route::post('/cart-overview-html', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CartController@overviewHtml')->name('module.ecommerce.cart.html.overview');

	Route::post('/cart-add-coupon', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CartController@addCoupon')->name('module.ecommerce.cart.coupon.add');
	Route::post('/cart-remove-coupon', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\CartController@removeCoupon')->name('module.ecommerce.cart.coupon.remove');

	Route::get('/verlanglijstje', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\WishlistController@overview')->name('module.ecommerce.wishlist.overview');
	Route::post('/update-wishlist', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\WishlistController@updateWishlist')->name('module.ecommerce.wishlist.update');
	Route::post('/update-wishlist-link', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\WishlistController@updateWishlistLink')->name('module.ecommerce.wishlist.update.link');

	Route::post('/toevoegen-aan-verlanglijstje', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\WishlistController@addToWishlist')->name('module.ecommerce.wishlist.add.product');
	Route::post('/verwijder-uit-verlanglijstje', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\WishlistController@removeFromWishlist')->name('module.ecommerce.wishlist.remove.product');

	Route::post('/webhook/chuck-ecommerce-module-mollie', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\OrderController@webhookMollie')->name('module.ecommerce.mollie_webhook');

	Route::get('/convert', 'Chuckbe\ChuckcmsModuleEcommerce\Controllers\POSController@convert')->name('module.ecommerce.pos.convert');

});