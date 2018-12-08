@extends('chuckcms::backend.layouts.admin')

@section('title')
	Collecties
@endsection

@section('add_record')
	@can('create redirects')
	<a href="#" data-target="#createCollectionModal" data-toggle="modal" class="btn btn-link text-primary m-l-20 hidden-md-down">Voeg Nieuwe Collectie Toe</a>
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
    <script src="{{ URL::to('vendor/laravel-filemanager/js/lfm.js') }}"></script>
	<script>
	$( document ).ready(function() { 

		init();

		function init () {
			//init media manager inputs 
			var domain = "{{ URL::to('dashboard/media')}}";
			$('.img_lfm_link').filemanager('image', {prefix: domain});
		}

	});
	</script>
    <script type="text/javascript">
    function editModal(id, name, parent, image){
    	$('#edit_collection_id').val(id);
    	$('#edit_collection_name').val(name);
    	$('#edit_collection_parent').val(parent).trigger('change');
    	$('#edit_collection_image').val(image);
    	$('#editcollectionimageholder').attr('src', image);
    	$('#editCollectionModal').modal('show');
    }

    function deleteModal(id, name){
    	$('#delete_collection_id').val(id);
    	$('#delete_collection_name').text(name);
    	$('#deleteCollectionModal').modal('show');
    }
    </script>
@endsection

@section('content')
<div class=" container-fluid container-fixed-lg">
    <div class="row">
		<div class="col-lg-12">
		<!-- START card -->
			<div class="card card-transparent">
				<div class="card-header ">
					<div class="card-title">Collecties</div>
					<div class="tools">
						<a class="collapse" href="javascript:;"></a>
						<a class="config" data-toggle="modal" href="#grid-config"></a>
						<a class="reload" href="javascript:;"></a>
						<a class="remove" href="javascript:;"></a>
					</div>
				</div>
				<div class="card-block">
					<div class="table-responsive">
						<table class="table table-hover table-condensed" id="condensedTable" data-table-count="6">
						<thead>
							<tr>
								<th style="width:5%">ID</th>
								<th style="width:35%">Naam</th>
								<th style="width:35%">Hoofdcollectie</th>
								<th style="width:25%">Actions</th>
							</tr>
						</thead>
							<tbody>
								@foreach($collections as $collection)
								<tr>
									<td class="v-align-middle">{{ $collection->id }}</td>
							    	<td class="v-align-middle">{{$collection->json['name'] }}</td>
							    	<td class="v-align-middle">{{$collections->where('id', $collection->json['parent'])->first() ? $collections->where('id', $collection->json['parent'])->first()->json['name'] : '' }}</td>
							    	<td class="v-align-middle semi-bold">
							    		@can('edit redirects')
							    		<a href="#" onclick="editModal({{ $collection->id }}, '{{ $collection->json['name'] }}', '{{ $collection->json['parent'] }}', '{{ $collection->json['image'] }}')" class="btn btn-default btn-sm btn-rounded m-r-20">
							    			<i data-feather="edit-2"></i> edit
							    		</a>
							    		@endcan

							    		@can('delete redirects')
							    		<a href="#" onclick="deleteModal({{ $collection->id }}, '{{ $collection->json['name'] }}')" class="btn btn-danger btn-sm btn-rounded m-r-20">
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

@include('chuckcms-module-ecommerce::backend.collections._create_modal')

@include('chuckcms-module-ecommerce::backend.collections._edit_modal')

@include('chuckcms-module-ecommerce::backend.collections._delete_modal')

@endsection