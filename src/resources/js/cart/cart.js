$(document).ready(function () {
	$('body').on('click', '.add-to-cart-link', function (event) {
		event.preventDefault();

		$.ajax({
            method: 'POST',
            url: add_to_cart_url,
            data: { 
            	product_id: $(this).data("product-id"),
            	base_spec_code: $(this).data("model-base-spec-code"),  
            	_token: sesh_fd_token
            }
        })
        .done(function(data) {

        	new Noty({
				theme: 'metroui',
			    type: data.status,
			    layout: 'bottomRight',
			    text: data.notification,
			    timeout: 3000
			}).show();

			if(data.status == "success") {
				updateCartCount(data.cart_count);
			}
        
        });
	});

	$('body').on('click', '.remove-from-cart-link', function (event) {
		event.preventDefault();

		//@TODO:: Overlay over complete table so it's not edittable / clickable while call is running

		$.ajax({
            method: 'POST',
            url: remove_from_cart_url,
            data: { 
            	row_id: $(this).data("row-id"),  
            	_token: sesh_fd_token
            }
        })
        .done(function(data) {
			if(data.status == "success") {
				updateCartTable();
			}
        });
	});

	$('body').on('click', '.addition-product-link', function (event) {
		event.preventDefault();

		//@TODO:: Overlay over complete table so it's not edittable / clickable while call is running

		$.ajax({
            method: 'POST',
            url: update_cart_item_url,
            data: { 
            	row_id: $(this).data("row-id"), 
            	add: 'addition',
            	_token: sesh_fd_token
            }
        })
        .done(function(data) {
			if(data.status == "success") {
				updateCartTable();
				new Noty({
					theme: 'metroui',
				    type: data.status,
				    layout: 'bottomRight',
				    text: data.notification,
				    timeout: 3000
				}).show();
			}
        });
	});

	$('body').on('click', '.subtract-product-link', function (event) {
		event.preventDefault();

		//@TODO:: Overlay over complete table so it's not edittable / clickable while call is running

		$.ajax({
            method: 'POST',
            url: update_cart_item_url,
            data: { 
            	row_id: $(this).data("row-id"), 
            	add: 'subtract',
            	_token: sesh_fd_token
            }
        })
        .done(function(data) {
			if(data.status == "success") {
				updateCartTable();
				new Noty({
					theme: 'metroui',
				    type: data.status,
				    layout: 'bottomRight',
				    text: data.notification,
				    timeout: 3000
				}).show();
			}
        });
	});

	function updateCartTable(){
		$.ajax({
            method: 'POST',
            url: update_cart_table_url,
            data: {  
            	_token: sesh_fd_token
            }
        })
        .done(function(data) {

			if(data.status == "success") {
				$('#cartOverviewTable').replaceWith(data.html_table);
				updateCartCount(data.cart_count);
			}
        
        });
	}

	function updateCartCount(new_count){
		$('.cart-counter-no').text(new_count);
	}
});