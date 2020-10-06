@extends('chuckcms::backend.layouts.base')

@section('title')
	Orders
@endsection

@section('add_record')
	@can('create forms')
	<a href="{{ route('dashboard.module.ecommerce.products.create') }}" class="btn btn-link text-primary m-l-20 hidden-md-down">Nieuwe Bestelling</a>
	@endcan
@endsection




@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item active" aria-current="page">Bestellingen</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
    	<div class="col-sm-12 text-right">
    		<a href="{{ route('dashboard.module.ecommerce.products.create') }}" class="btn btn-sm btn-outline-success">Nieuwe Bestelling</a>
    	</div>
        <div class="col-sm-12 my-3">
        	<div class="table-responsive">
        		<table class="table" data-datatable style="width:100%">
        			<thead>
        				<tr>
        					<th scope="col">Order #</th>
							<th scope="col">Datum</th>
							<th scope="col">Naam & Adres</th>
							<th scope="col" class="pr-5">Totaal</th>
							<th scope="col">Status</th>
							<th scope="col" style="min-width:190px">Actions</th>
        				</tr>
        			</thead>
        			<tbody>
        				@foreach($orders as $order)
						<tr class="order_line" data-id="{{ $order->id }}">
					    	<td class="v-align-middle semi-bold">{{ $order->json['order_number'] }}</td>
					    	<td class="v-align-middle">{{ date('Y/m/d', strtotime($order->created_at)) }}</td>
					    	<td class="v-align-middle">{{ $order->surname .' '. $order->name }} <br> <a href="mailto:{{ $order->email }}">{{ $order->email }}</a> <br> @if(!is_null($order->tel)) <a href="tel:{{ $order->tel }}">{{ $order->tel }}</a> <br> @endif </td>
					    	<td class="v-align-middle semi-bold">{{ ChuckEcommerce::formatPrice($order->final) }}</td>
					    	<td class="v-align-middle">
					    		<span class="label {{ ChuckEcommerce::getSetting('order.statuses.'.$order->status.'.paid') ? 'label-inverse' : '' }}">{{ ChuckEcommerce::getSetting('order.statuses.'.$order->status.'.short.'.app()->getLocale()) }}</span>
					    	</td>
					    	<td class="v-align-middle semi-bold">
					    		<a href="{{ route('dashboard.module.ecommerce.orders.detail', ['order' => $order->id]) }}" class="btn btn-sm btn-outline-primary rounded d-inline-block">
					    			bekijken
					    		</a>
					    		<a href="{{ route('dashboard.module.ecommerce.orders.delivery', ['order' => $order->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
					    			pakbon
					    		</a>
					    		@if($order->hasInvoice())
					    		<a href="{{ route('dashboard.module.ecommerce.orders.invoice', ['order' => $order->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
					    			factuur
					    		</a>
					    		@endif
					    	</td>
					  	</tr>
					  	@endforeach


        			</tbody>
        		</table>
        	</div>
        </div>
    </div>
</div>
@endsection

@section('css')
	
@endsection

@section('scripts')
<script src="https://cdn.chuck.be/assets/plugins/sweetalert2.all.js"></script>
<script>
$( document ).ready(function (){
	$('.product_delete').each(function(){
		var form_id = $(this).attr("data-id");
		var token = '{{ Session::token() }}';
	  	$(this).click(function (event) {
	  		event.preventDefault();
	  		swal({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
			  	if (result.value) { 
			  		$.ajax({
                        method: 'POST',
                        url: "{{ route('dashboard.forms.delete') }}",
                        data: { 
                        	form_id: form_id, 
                        	_token: token
                        }
                    }).done(function (data) {
                    	if(data == 'success'){
                    		$(".product_line[data-id='"+product_id+"']").first().remove();
                    		swal('Deleted!','The product has been deleted.','success')
                    	} else {
                    		swal('Oops!','Something went wrong...','danger')
                    	}
                    });
			  	}
			})
	    });
	});
});
</script>
@endsection
