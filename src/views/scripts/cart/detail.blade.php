<script>
	$('body').on('click', '.ce_removeProductFromCartBtn', function (event) {
		event.preventDefault();
		row_id = $(this).attr('data-row-id');
		product_sku = $(this).attr('data-product-sku');

		removeFromCart(row_id, product_sku).done(function(data) {
			if(data.status == 'success') {
				$('#ce_shoppingCartOverviewComponent').replaceWith(data.html_overview);
				$('#ce_shoppingCartDetailComponent').replaceWith(data.html_detail);
			

				popNotification(data.notification.type, data.notification.title, data.notification.message, data.notification.icon);
			} else {

				popNotification(data.notification.type, data.notification.title, data.notification.message, data.notification.icon);
			}

			$('.ce_addProductToCartBtn').prop('disabled', false);
		});

	});

	function removeFromCart(row_id, product_sku) {
		return $.ajax({
            method: 'POST',
            url: remove_from_cart_url,
            data: { 
            	product_sku: product_sku,
            	row_id: row_id, 
            	_token: a_token
            }
        });
	}
</script>