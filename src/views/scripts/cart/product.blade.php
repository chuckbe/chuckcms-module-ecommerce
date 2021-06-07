<script>
	$('body').on('click', '.ce_additionProductBtn', function (event) {
		event.preventDefault();
		product_id = $(this).attr('data-product-id');
		newValue = parseInt($('.ce_productQuantityInput[data-product-id='+product_id+']').val()) + 1;
		if(newValue <= parseInt($('.ce_productQuantityInput[data-product-id='+product_id+']').attr('max'))){
			$('.ce_productQuantityInput[data-product-id='+product_id+']').val(newValue);
		}
	});

	$('body').on('click', '.ce_subtractionProductBtn', function (event) {
		event.preventDefault();
		product_id = $(this).attr('data-product-id');
		newValue = parseInt($('.ce_productQuantityInput[data-product-id='+product_id+']').val()) - 1;
		if(newValue >= 1) {
			$('.ce_productQuantityInput[data-product-id='+product_id+']').val(newValue);
		}
	});

	$('body').on('change', '.ce_extraSelectInput', function (event) {
		updatePrices();
	});

	$('body').on('change', '.ce_attributeSelectInput', function (event) {
		//event.preventDefault();
		
		product_id = $(this).attr('data-product-id');
		$('.ce_attributeSelectInput').prop('disabled', true);

		getCombination(product_id).done(function(data) {

			$('.ce_combinationInput[data-product-id='+product_id+']').val(data.combination.code.sku);
			$('.ce_product_default_sku[data-product-id='+product_id+']').text(data.combination.code.sku);
			$('.ce_productQuantityInput[data-product-id='+product_id+']').attr('max', data.combination.quantity);

			if(data.combination.quantity == 0) {
				$('.ce_in_stock_label').addClass('d-none');
				$('.ce_no_stock_label').removeClass('d-none');
				$('.ce_productQuantityInput[data-product-id='+product_id+']').val(0);
				$('.ce_addProductToCartBtn[data-product-id='+product_id+']').prop('disabled', true);
			} else {
				$('.ce_in_stock_label').removeClass('d-none');
				$('.ce_no_stock_label').addClass('d-none');
				$('.ce_productQuantityInput[data-product-id='+product_id+']').val(1);
				$('.ce_addProductToCartBtn[data-product-id='+product_id+']').prop('disabled', false);
			}

			if(parseFloat(data.combination.price.discount) > 0) {
				updateDiscountPrice(data.combination.price.final, data.combination.price.discount);
			} else {
				updateFinalPrice(data.combination.price.discount, data.combination.price.final);
			}

			$('.ce_attributeSelectInput').prop('disabled', false);
		});
	});

	$('body').on('click', '.ce_addProductToCartBtn', function (event) {
		event.preventDefault();
		$(this).prop('disabled', true);

		product_id = $(this).attr('data-product-id');
		sku = $('.ce_combinationInput[data-product-id='+product_id+']').val();
		options = [];
		$('.ce_optionSelectInput').each(function() {
			options.push($(this).children("option:selected").attr('data-option-key')+'%|%'+$(this).children("option:selected").val());
		});

		extras = [];
		$('.ce_extraSelectInput').each(function() {
			extras.push($(this).children("option:selected").attr('data-extra-key')+'%|%'+$(this).children("option:selected").val());
		});

		quantity = $('.ce_productQuantityInput[data-product-id='+product_id+']').val();

		addToCart(product_id, sku, options, extras, quantity).done(function(data) {
			if(data.status == 'success') {
				console.log('graett jobb', data);

				updateCartDetail().done(function(cart_data) {
					$('#ce_shoppingCartDetailComponent').replaceWith(cart_data.html);
				});

				popNotification(data.notification.type, data.notification.title, data.notification.message, data.notification.icon);
			} else {
				console.log('baaddd jobb', data);

				popNotification(data.notification.type, data.notification.title, data.notification.message, data.notification.icon);
			}

			$('.ce_addProductToCartBtn').prop('disabled', false);
		});
	});

	function getCombination(product_id) {
		selectedAttributes = [];
		$('.ce_attributeSelectInput').each(function() {
			selectedAttributes.push($(this).children("option:selected").attr('data-attribute-key'));
		});

		return $.ajax({
            method: 'POST',
            url: get_combination_url,
            data: { 
            	product_id: product_id, 
            	attribute_keys: selectedAttributes,
            	_token: a_token
            }
        });
	}

	function addToCart(product_id, sku, options, extras, quantity) {
		return $.ajax({
            method: 'POST',
            url: add_to_cart_url,
            data: { 
            	product_id: product_id, 
            	sku: sku,
            	options: options,
            	extras: extras,
            	quantity: quantity,
            	_token: a_token
            }
        });
	}

	function updateDiscountPrice(discount, final) {
		extras = getExtrasPrice();
		
		$('.ce_product_price_discount').removeClass('d-none').text(formatPrice(parseFloat(discount) + extras));
		$('.ce_product_price_discount').attr('data-price', discount);
		$('.ce_product_price_final').text(formatPrice(parseFloat(final) + extras));
		$('.ce_product_price_final').attr('data-price', final);
	}

	function updateFinalPrice(discount, final) {
		extras = getExtrasPrice();

		$('.ce_product_price_discount').addClass('d-none').text(formatPrice(parseFloat(discount) + extras));
		$('.ce_product_price_discount').attr('data-price', discount);
		$('.ce_product_price_final').text(formatPrice(parseFloat(final) + extras));
		$('.ce_product_price_final').attr('data-price', final);
	}

	function getExtrasPrice() {
		extra_price = 0;
		$('.ce_extraSelectInput').each(function() {
			single_extra_price = parseFloat($(this).attr('data-extra-final'));
			qty = parseInt($(this).find('option:selected').first().val());
			extra_price = extra_price + (qty * single_extra_price);
		});

		return extra_price;
	}

	function updatePrices() {
		extras = getExtrasPrice();
		discount = parseFloat($('.ce_product_price_discount').attr('data-price'));
		finalprice = parseFloat($('.ce_product_price_final').attr('data-price'));
		
		$('.ce_product_price_discount').addClass('d-none').text(formatPrice(discount + extras));
		$('.ce_product_price_final').text(formatPrice(finalprice + extras));
	}

	function formatPrice(price) {
		formatted = parseFloat(price).toFixed(2).replace('.', ',');
		return '€ ' + formatted;
	}
</script>