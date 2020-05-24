<script>
	function updateCartDetail() {
		return $.ajax({
            method: 'POST',
            url: update_cart_detail_url,
            data: { 
            	_token: a_token
            }
        });
	}
</script>