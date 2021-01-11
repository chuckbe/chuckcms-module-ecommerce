@extends('chuckcms::backend.layouts.base')

@section('title')
	Producten
@endsection

@section('add_record')
	@can('create forms')
	<a href="{{ route('dashboard.module.ecommerce.products.create') }}" class="btn btn-link text-primary m-l-20 hidden-md-down">Voeg Nieuw Product Toe</a>
	@endcan
@endsection




@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item active" aria-current="page">Producten</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
    	<div class="col-sm-12 text-right">
    		<a href="{{ route('dashboard.module.ecommerce.products.create') }}" class="btn btn-sm btn-outline-success">Product Toevoegen</a>
    	</div>
        <div class="col-sm-12 my-3">
        	<div class="table-responsive">
        		<table class="table" data-datatable  style="width:100%">
        			<thead>
        				<tr>
        					<th scope="col">#</th>
        					<th scope="col">Titel</th>
        					<th scope="col">Collectie</th>
        					<th scope="col" class="pr-5">Prijs</th>
        					<th scope="col">Status</th>
        					<th scope="col">Hvl</th>
        					<th scope="col" style="min-width:170px">Acties</th>
        				</tr>
        			</thead>
        			<tbody>
        				@foreach($products as $product)
        				<tr class="product_line" data-id="{{ $product->id }}">
        					<th scope="row">{{ $product->id }}</th>
        					<td>{{ $product->json['title'][ChuckSite::getFeaturedLocale()] }}</td>
        					<td>{{ is_null(ChuckProduct::collection($product)) ? '' : ChuckProduct::collection($product)->json['name']}}</td>
        					<td>{{ChuckProduct::lowestPrice($product)}}</td>
        					<td class="text-center">
								<span class="badge badge-{{ ChuckProduct::isBuyable($product) ? 'success' : 'danger' }}">
									{!!ChuckProduct::isBuyable($product) ? '✓' : '✕'!!}
								</span>
							</td>
        					<td>{{ChuckProduct::quantity($product, ChuckProduct::defaultSku($product)) }}</td>
        					<td>
        						@can('edit forms')
					    		<a href="{{ route('dashboard.module.ecommerce.products.edit', ['product' => $product->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
					    			<i class="fa fa-pen"></i> edit 
					    		</a>
					    		@endcan
					    		@can('delete forms')
					    		<a href="#" class="btn btn-sm btn-outline-danger rounded d-inline-block form_delete" data-id="{{ $product->id }}">
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
