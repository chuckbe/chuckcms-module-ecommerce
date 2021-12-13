<script>
$(document).ready(function() {
    $('body').on('click', '.cof_pos_product_card', function (event) {
            event.preventDefault();
            let productId = $(this).attr('data-product-id');
            getCombinationsModalBodyFromProductId(productId);
    });

    $('body').on('click', '#openCouponsModal', function (event) {
            event.preventDefault();
            $('#couponsModal').modal('show');
    });
    $('body').on('click', '.btn-group-toggle>.btn input[type="radio"]', function (event) {
            event.preventDefault();
            if($(this).prop('checked')){
                $(this).parent().addClass('active');
            }
    });
});
function getCombinationsModalBodyFromProductId(productId){
    let a_token = "{{ Session::token() }}";
    $.ajax({
        method: 'POST',
        url: '{{route("ecommerce_pos_url", ["variable" => "get_product_combinations"])}}',
        data: { 
            product_id: productId,
            _token: a_token,
        }
    }).done(function(data) {
        if (data.status == "success"){
            console.log(data);
            $('#cof_orderFormGlobalSection').after(data.html);
            $('#optionsModal').modal('show');
        }
    });
}

</script>