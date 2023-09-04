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
    	<div class="col-sm-6">
    		<button class="btn btn-sm btn-outline-danger mr-auto" id="labelPrinterStatus" disabled>
    			<i class="fa fa-circle"></i> <span>Geen printer gevonden</span>
    		</button>
    	</div>
    	<div class="col-sm-6 text-right">
    		<a href="{{ route('dashboard.module.ecommerce.products.create') }}" class="btn btn-sm btn-outline-success">Product Toevoegen</a>
            <button id="deleteAllProducts" class="btn btn-sm btn-outline-danger d-none">Verwijder geselecteerde producten</button>
    	</div>
        <div class="col-sm-12 my-3">
        	<div class="table-responsive">
        		<table class="table" data-datatable-selectable  style="width:100%">
        			<thead>
        				<tr>
                            <th scope="col"></th>
        					<th scope="col">#</th>
        					<th scope="col">Titel</th>
        					<th scope="col">Collectie</th>
        					<th scope="col" class="pr-5">Prijs</th>
        					<th scope="col">Status</th>
							<th scope="col">POS?</th>
        					<th scope="col">Hvl</th>
        					<th scope="col" style="min-width:200px">Acties</th>
        				</tr>
        			</thead>
        			<tbody>
        				@foreach($products as $product)
        				<tr class="product_line" data-id="{{ $product->id }}">
                            <td></td>
        					<td>{{ $product->id }}</td>
        					<td>{{ $product->json['title'][ChuckSite::getFeaturedLocale()] }}</td>
        					<td>{{ is_null(ChuckProduct::collection($product)) ? '' : ChuckProduct::collection($product)->json['name']}}</td>
        					<td>{{ ChuckProduct::lowestPrice($product) }}</td>
        					<td class="text-center">
								<span class="badge badge-{{ ChuckProduct::isBuyable($product) ? 'success' : 'danger' }}">
									{!! ChuckProduct::isBuyable($product) ? '✓' : '✕' !!}
								</span>
							</td>
							<td class="text-center">
								<span class="badge badge-{{ $product->json['is_pos_available'] == true ? 'success' : 'danger' }}">
									{!! $product->json['is_pos_available'] == true ? '✓' : '✕' !!}
								</span>
							</td>
        					<td>{{ChuckProduct::quantity($product, ChuckProduct::defaultSku($product)) }}</td>
        					<td>
        						@can('edit forms')
					    		<a href="{{ route('dashboard.module.ecommerce.products.edit', ['product' => $product->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
					    			<i class="fa fa-pen"></i> edit 
					    		</a>
								<button 
									class="btn btn-sm btn-outline-secondary rounded d-inline-block openLabelModalBtn"
									data-product-id="{{ $product->id }}">
					    			<i class="fa fa-tag"></i> 
					    		</button>
					    		@endcan
					    		@can('delete forms')
					    		<a href="#" class="btn btn-sm btn-outline-danger rounded d-inline-block product_delete" data-id="{{ $product->id }}">
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
<!-- Modal -->
@include('chuckcms-module-ecommerce::backend.products.modals.labels')
@endsection

@section('css')
	<style>
		.online, .offline {
			width: 5px;
			height: 5px;
			border-radius: 50%;
		}
	</style>
@endsection

@section('scripts')
<script src="https://cdn.chuck.be/assets/plugins/sweetalert2.all.js"></script>
@include('chuckcms-module-ecommerce::backend.products.labels')
<script>
$( document ).ready(function (){
    const _token = '{{ Session::token() }}';

    $('body').on('click', '.product_line', function (event) {
        toggleDeleteAllButton();
    });

    function toggleDeleteAllButton() {
        if ($('.product_line.selected').length > 0) {
            $('#deleteAllProducts').removeClass('d-none');
        } else {
            $('#deleteAllProducts').addClass('d-none');
        }
    }

    $('body').on('click', '#deleteAllProducts', function (event) {
        event.preventDefault();

        let product_ids = [];

        $('.product_line.selected').each(function (index) {
            product_ids.push($(this).data('id'));
        });

        let dataTableSelectable = $('table[data-datatable-selectable]').DataTable();

        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete them!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    method: 'POST',
                    url: "{{ route('dashboard.module.ecommerce.products.delete') }}",
                    data: {
                        product_id: product_ids,
                        _token: _token
                    }
                }).done(function (data) {
                    if(data.status == 'success'){
                        for (let i = 0; i < product_ids.length; i++) {
                            dataTableSelectable
                                .row($(".product_line[data-id='"+product_ids[i]+"']").first())
                                .remove()
                                .draw();
                        }
                        swal('Deleted!','The products has been deleted.','success')
                    } else {
                        swal('Oops!','Something went wrong...','danger')
                    }
                });
            }
        })
    });

    $('body').on('click', '.product_delete', function (event) {
        event.preventDefault();
        var product_id = $(this).attr("data-id");

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
                    url: "{{ route('dashboard.module.ecommerce.products.delete') }}",
                    data: { 
                        product_id: product_id, 
                        _token: _token
                    }
                }).done(function (data) {
                    if(data.status == 'success'){
                        dataTableSelectable.row($(".product_line[data-id='"+product_id+"']").first()).remove().draw();
                        swal('Deleted!','The product has been deleted.','success')
                    } else {
                        swal('Oops!','Something went wrong...','danger')
                    }
                });
            }
        })
    });
});
</script>
@endsection
