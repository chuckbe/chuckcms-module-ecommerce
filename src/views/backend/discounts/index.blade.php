@extends('chuckcms::backend.layouts.base')

@section('title')
	Kortingen & Coupons
@endsection

@section('add_record')
	@can('create forms')
	<a href="{{ route('dashboard.module.ecommerce.discounts.create') }}" class="btn btn-link text-primary m-l-20 hidden-md-down">Voeg Nieuw Korting Toe</a>
	@endcan
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item active" aria-current="page">Kortingen & Coupons</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
    	<div class="col-sm-12 text-right">
    		<a href="{{ route('dashboard.module.ecommerce.discounts.create') }}" class="btn btn-sm btn-outline-success">Korting Toevoegen</a>
    	</div>
        <div class="col-sm-12 my-3">
        	<div class="table-responsive">
        		<table class="table" data-datatable  style="width:100%">
        			<thead>
        				<tr>
        					<th scope="col">#</th>
        					<th scope="col">Naam</th>
        					<th scope="col">Code</th>
        					<th scope="col">Type</th>
        					<th scope="col" style="min-width:170px">Acties</th>
        				</tr>
        			</thead>
        			<tbody>
        				
        				@foreach($discounts as $discount)
        				<tr class="discount_line" data-id="{{ $discount->id }}">
        					<th scope="row">{{ $discount->id }}</th>
                            <td>{{ $discount->json['name'] }}</td>
                            <td>{{ $discount->json['code'] }}</td>
        					<td class="text-center">{!!'-'.$discount->value.($discount->type == 'percentage' ? '%' : '€')!!}</td>
        					<td>
        						@can('edit forms')
					    		<a href="{{ route('dashboard.module.ecommerce.discounts.edit', ['discount' => $discount->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
					    			<i class="fa fa-pen"></i> edit 
					    		</a>
					    		@endcan
					    		@can('delete forms')
					    		<a href="#" class="btn btn-sm btn-outline-danger rounded d-inline-block discount_delete" data-id="{{ $discount->id }}">
					    			<i class="fa fa-trash"></i> delete 
					    		</a>
					    		@endcan
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
	$('.discount_delete').each(function(){
		var discount_id = $(this).attr("data-id");
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
                        url: "{{ route('dashboard.module.ecommerce.discounts.delete') }}",
                        data: { 
                        	discount_id: discount_id, 
                        	_token: token
                        }
                    }).done(function (data) {
                    	if(data.status == 'success'){
                    		$(".discount_line[data-id='"+discount_id+"']").first().remove();
                    		swal('Deleted!','The discount has been deleted.','success')
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
