$(document).ready(function () {

	$('body').on('click', '.add-to-wishlist-link', function (event) {
		event.preventDefault();

		$.ajax({
            method: 'POST',
            url: add_to_wishlist_url,
            data: { 
            	product_id: $(this).data("product-id"),
            	base_spec_code: $(this).data("model-base-spec-code"),  
            	_token: sesh_fd_token
            }
        })
        .done(function(data) {

        	console.log('check it out');
        	new Noty({
				theme: 'metroui',
			    type: data.status,
			    layout: 'bottomRight',
			    text: data.notification,
			    timeout: 3000
			}).show();

			if(data.status == "success") {
				updateWishlistCount(data.wishlist_count);
				updateWishlistLinkBlock(data.product_id);
			}
        
        });
	});

	$('body').on('click', '.remove-from-wishlist-link', function (event) {
		event.preventDefault();

		//@TODO:: Overlay over complete table so it's not edittable / clickable while call is running

		$.ajax({
            method: 'POST',
            url: remove_from_wishlist_url,
            data: { 
            	row_id: $(this).data("row-id"),  
            	_token: sesh_fd_token
            }
        })
        .done(function(data) {
			if(data.status == "success") {
				updateWishlistTable();
				updateWishlistLinkBlock(data.product_id);
				wishlistInit();
			}
        });
	});

	function updateWishlistCount(new_count){
		$('.wishlist-counter-no').text(new_count);
	}

	function updateWishlistLinkBlock(product_id_ip){
		$.ajax({
            method: 'POST',
            url: update_wishlist_link_url,
            data: {  
            	product_id: product_id_ip,
            	_token: sesh_fd_token
            }
        })
        .done(function(data) {

			if(data.status == "success") {
				$('#wishlistLinkBlock_'+product_id_ip).replaceWith(data.html_link);
			}
        
        });
	}

	function updateWishlistTable(){
		$.ajax({
            method: 'POST',
            url: update_wishlist_table_url,
            data: {  
            	_token: sesh_fd_token
            }
        })
        .done(function(data) {

			if(data.status == "success") {
				$('#cartOverviewTable').replaceWith(data.html_table);
				updateWishlistCount(data.wishlist_count);
				//wishlistInit();
			}
        
        });
	}
});