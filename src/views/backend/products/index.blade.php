@extends('chuckcms::backend.layouts.admin')

@section('title')
	Producten
@endsection

@section('add_record')
	@can('create forms')
	<a href="{{ route('dashboard.module.ecommerce.products.create') }}" class="btn btn-link text-primary m-l-20 hidden-md-down">Voeg Nieuw Product Toe</a>
	@endcan
@endsection

@section('css')
	<link href="https://cdn.chuck.be/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.chuck.be/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.chuck.be/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('scripts')
	<script src="https://cdn.chuck.be/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.chuck.be/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="https://cdn.chuck.be/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="https://cdn.chuck.be/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://cdn.chuck.be/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="https://cdn.chuck.be/assets/plugins/datatables-responsive/js/lodash.min.js"></script>
    <script src="https://cdn.chuck.be/assets/js/tables.js" type="text/javascript"></script>
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
	                    })
	                    .done(function (data) {
	                    	if(data == 'success'){
	                    		$(".product_line[data-id='"+product_id+"']").first().remove();
	                    		swal(
						      		'Deleted!',
						      		'The product has been deleted.',
						      		'success'
						    	)
	                    	} else {
	                    		swal(
						      		'Oops!',
						      		'Something went wrong...',
						      		'danger'
						    	)
	                    	}

	                        
	                    });
				    	
				  	}
				})
		    });
		});
    });

    </script>
@endsection

@section('content')
<div class=" container-fluid   container-fixed-lg">
    <div class="row">
		<div class="col-lg-12">
		<!-- START card -->
			<div class="card card-transparent">
				<div class="card-header ">
					<div class="card-title">Producten</div>
					<div class="tools">
						<a class="collapse" href="javascript:;"></a>
						<a class="config" data-toggle="modal" href="#grid-config"></a>
						<a class="reload" href="javascript:;"></a>
						<a class="remove" href="javascript:;"></a>
					</div>
				</div>
				<div class="card-block">
					<div class="table-responsive">
						<table class="table table-hover table-condensed" id="condensedTable">
						<thead>
							<tr>
								<th style="width:5%">ID</th>
								<th style="width:25%">Titel</th>
								<th style="width:20%">Slug</th>
								<th style="width:35%">Actions</th>
							</tr>
						</thead>
							<tbody>
								@foreach($products as $product)
								<tr class="product_line" data-id="{{ $product->id }}">
									<td class="v-align-middle">{{ $product->id }}</td>
							    	<td class="v-align-middle semi-bold">{{ $product->json['title'][ChuckSite::getFeaturedLocale()] }}</td>
							    	<td class="v-align-middle">{{$product->slug}}</td>
							    	<td class="v-align-middle semi-bold">
							    		@can('edit forms')
							    		<a href="{{ route('dashboard.module.ecommerce.products.edit', ['product' => $product->id]) }}" class="btn btn-primary btn-sm btn-rounded m-r-20">
							    			<i data-feather="edit-2"></i> edit
							    		</a>
							    		@endcan
							    		@can('delete forms')
							    		<a href="#" class="btn btn-danger btn-sm btn-rounded m-r-20 form_delete" data-id="{{ $product->id }}">
							    			<i data-feather="trash"></i> delete
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
		<!-- END card -->
		</div>
    </div>
</div>
@endsection