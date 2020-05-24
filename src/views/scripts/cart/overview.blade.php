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