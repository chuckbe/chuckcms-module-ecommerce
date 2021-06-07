<script>
	$('body').on('click', '.ce_additionCartProductBtn', function (event) {
		event.preventDefault();
		row_id = $(this).attr('data-row-id');
		newValue = parseInt($('.ce_productCartQuantityInput[data-row-id='+row_id+']').val()) + 1;
		if(newValue <= parseInt($('.ce_productCartQuantityInput[data-row-id='+row_id+']').attr('max'))){
			$('.ce_productCartQuantityInput[data-row-id='+row_id+']').val(newValue);

			$('.ce_additionCartProductBtn').prop('disabled', true);
			$('.ce_subtractionCartProductBtn').prop('disabled', true);

			updateCartItem(row_id, newValue).done(function(item_data){
				if(item_data.status == 'success') {
					$('#ce_shoppingCartOverviewComponent').replaceWith(item_data.html_overview);
					$('#ce_shoppingCartDetailComponent').replaceWith(item_data.html_detail);
				}

				popNotification(item_data.notification.type, item_data.notification.title, item_data.notification.message, item_data.notification.icon);
			});
		}
	});

	$('body').on('click', '.ce_subtractionCartProductBtn', function (event) {
		event.preventDefault();
		row_id = $(this).attr('data-row-id');
		newValue = parseInt($('.ce_productCartQuantityInput[data-row-id='+row_id+']').val()) - 1;
		if(newValue >= 1) {
			$('.ce_productCartQuantityInput[data-row-id='+row_id+']').val(newValue);

			$('.ce_additionCartProductBtn').prop('disabled', true);
			$('.ce_subtractionCartProductBtn').prop('disabled', true);

			updateCartItem(row_id, newValue).done(function(item_data){
				if(item_data.status == 'success') {
					$('#ce_shoppingCartOverviewComponent').replaceWith(item_data.html_overview);
					$('#ce_shoppingCartDetailComponent').replaceWith(item_data.html_detail);
				}

				popNotification(item_data.notification.type, item_data.notification.title, item_data.notification.message, item_data.notification.icon);
			});
		}
	});

	$('body').on('click', '.ce_addCouponButton', function (event) {
		event.preventDefault();
		
		$('ce_couponInputWarning').addClass('d-none');

		if($('.ce_couponInputField').val().length < 1) {
			return;
		}

		$('.ce_addCouponButton').prop('disabled', true);

		coupon_input = $('.ce_couponInputField').val();
		addCouponToCart(coupon_input).done(function(coupon_data){
			$('.ce_addCouponButton').prop('disabled', false);

			if(coupon_data.status == 'undefined' || coupon_data.status == 'invalid') {
				$('.ce_couponInputWarning').text(coupon_data.status_text);
				$('.ce_couponInputWarning').removeClass('d-none');
				setTimeout(function() { 
			        $('.ce_couponInputWarning').addClass('d-none');
			        $('.ce_couponInputField').val('');
			    }, 5000);
			}

			if(coupon_data.status == 'success') {
				$('#ce_shoppingCartOverviewComponent').replaceWith(coupon_data.html_overview);
				$('#ce_shoppingCartDetailComponent').replaceWith(coupon_data.html_detail);
			}

			//popNotification(item_data.notification.type, item_data.notification.title, item_data.notification.message, item_data.notification.icon);
		});
	});

	$('body').on('click', '.ce_removeCouponButton', function (event) {
		event.preventDefault();

		coupon = $(this).attr('data-discount');
		removeCouponFromCart(coupon).done(function(coupon_data){


			if(coupon_data.status == 'undefined' || coupon_data.status == 'invalid') {
				$('.ce_couponInputWarning').text(coupon_data.status_text);
				$('.ce_couponInputWarning').removeClass('d-none');
				setTimeout(function() { 
			        $('.ce_couponInputWarning').addClass('d-none');
			        $('.ce_couponInputField').val('');
			    }, 5000);
			}

			if(coupon_data.status == 'success') {
				$('#ce_shoppingCartOverviewComponent').replaceWith(coupon_data.html_overview);
				$('#ce_shoppingCartDetailComponent').replaceWith(coupon_data.html_detail);
			}

			//popNotification(item_data.notification.type, item_data.notification.title, item_data.notification.message, item_data.notification.icon);
		});
	});

	function addCouponToCart(coupon_input) {
		return $.ajax({
            method: 'POST',
            url: add_coupon_to_cart_url,
            data: { 
            	coupon: coupon_input, 
            	_token: a_token
            }
        });
	}

	function removeCouponFromCart(coupon_input) {
		return $.ajax({
            method: 'POST',
            url: remove_coupon_from_cart_url,
            data: { 
            	coupon: coupon_input, 
            	_token: a_token
            }
        });
	}

	function updateCartItem(row_id, quantity) {
		return $.ajax({
            method: 'POST',
            url: update_item_url,
            data: { 
            	row_id: row_id, 
            	quantity: quantity,
            	_token: a_token
            }
        });
	}
</script>