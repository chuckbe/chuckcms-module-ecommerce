<script>
	var get_combination_url = "{{ route('module.ecommerce.product.get_combination') }}";
	var add_to_cart_url = "{{ route('module.ecommerce.cart.add.product') }}";
	var update_item_url = "{{ route('module.ecommerce.cart.update.item') }}";
	var remove_from_cart_url = "{{ route('module.ecommerce.cart.remove.product') }}";
	var update_cart_detail_url = "{{ route('module.ecommerce.cart.html.detail') }}";
	var update_cart_overview_url = "{{ route('module.ecommerce.cart.html.overview') }}";
	var a_token = "{{ Session::token() }}";
</script>