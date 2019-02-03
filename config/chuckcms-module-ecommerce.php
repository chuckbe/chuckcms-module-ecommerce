<?php

return [

	'pages' => [
		'product_detail' => 'chuckcms-module-ecommerce::frontend.products.detail'

	],

	'vat' => [
		'be21' 	=> ['type' => 'BTW BE 21%', 'amount' => 21, 'default' => true],
		'be12' 	=> ['type' => 'BTW BE 12%', 'amount' => 12, 'default' => false],
		'be6' 	=> ['type' => 'BTW BE 6%', 'amount' => 6, 'default' => false],
		'be0' 	=> ['type' => 'BTW BE 0%', 'amount' => 0, 'default' => false]
	],

	'attributes' => [
		'slug' 	=> 'attributes',
		'url' => 'attribute/',
		'page' => 'chuckcms-template-antwerp::templates.chuckcms-template-antwerp.default'
	],

	'brands' => [
		'slug' 	=> 'brands',
		'url' => 'brands/',
		'page' => 'chuckcms-template-antwerp::templates.chuckcms-template-antwerp.default'
	],

	'collections' => [
		'slug' 	=> 'collections',
		'url' => 'collections/',
		'page' => 'chuckcms-template-antwerp::templates.chuckcms-template-antwerp.default'
	],

	'products' => [
		'slug' 	=> 'products',
		'url' => 'products/',
		'page' => 'chuckcms-module-ecommerce::frontend.products.detail'
	],

	'auth' => [
		'redirect' => [
			'user' => '/dashboard',
			'moderator' => '/dashboard',
			'administrator' => '/dashboard',
			'super-admin' => '/dashboard',
			'customer' => '/account/profile',
		],

		'template' => [
			'hintpath' => 'chuckcms-template-london'
		],
	],
];