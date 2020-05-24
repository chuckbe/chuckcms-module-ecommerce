<script type="text/javascript">
var followup_url = "{{ route('module.ecommerce.checkout.status') }}";
var a_token = "{{ Session::token() }}";

$(document).ready(function() {
	statusOrder();
});

function statusOrder () {

	$.ajax({
        method: 'POST',
        url: followup_url,
        data: { 
        	order_number: $('input[name=order_number]').val(), 
        	_token: a_token
        }
    })
    .done(function(data) {
        if (data.status.paid == "1"){
        	$('.order-success').removeClass('d-none');
        } else { 
        	$('.order-canceled').removeClass('d-none');
        } 
    });
}

</script>