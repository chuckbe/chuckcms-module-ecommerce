@extends('chuckcms::backend.layouts.base')

@section('title')
	Collecties
@endsection

@section('add_record')
@can('create redirects')
<a href="#" data-target="#createCollectionModal" data-toggle="modal" class="btn btn-link text-primary m-l-20 hidden-md-down">Voeg Nieuwe Collectie Toe</a>
@endcan
@endsection

@section('css')
@endsection

@section('scripts')
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
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item active" aria-current="page">Collecties</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
    	<div class="col-sm-12 text-right">
    		<a href="#" data-target="#createCollectionModal" data-toggle="modal" class="btn btn-sm btn-outline-success">Nieuwe Collectie</a>
    	</div>
        <div class="col-sm-12 my-3">
        	<div class="table-responsive">
        		<table class="table" data-datatable style="width:100%">
        			<thead>
        				<tr>
        					<th scope="col">#</th>
							<th scope="col">Naam</th>
							<th scope="col">Producten</th>
							<th scope="col">Hoofdcollectie</th>
							<th scope="col" style="min-width:190px">Actions</th>
        				</tr>
        			</thead>
        			<tbody>
        				@foreach($collections as $collection)
						<tr>
							<td class="v-align-middle">{{ $collection->id }}</td>
							<td class="v-align-middle">{{$collection->json['name'] }}</td>
							<td class="v-align-middle">{{ ChuckProduct::forCollection($collection->json['name'], $collection->json['parent'], true) }}</td>
							<td class="v-align-middle">{{$collections->where('id', $collection->json['parent'])->first() ? $collections->where('id', $collection->json['parent'])->first()->json['name'] : '' }}</td>
							<td class="v-align-middle semi-bold">
								<a href="{{ route('dashboard.module.ecommerce.collections.sorting', ['collection' => $collection->id]) }}" class="btn btn-primary btn-sm btn-rounded m-r-20">
									<i class="fa fa-eye"></i>
								</a>

								@can('edit redirects')
								<a href="#" onclick="editModal({{ $collection->id }}, '{{ $collection->json['name'] }}', '{{ $collection->json['parent'] }}', '{{ $collection->json['image'] }}')" class="btn btn-secondary btn-sm btn-rounded m-r-20">
									<i class="fa fa-edit"></i>
								</a>
								@endcan

								@can('delete redirects')
								<a href="#" onclick="deleteModal({{ $collection->id }}, '{{ $collection->json['name'] }}')" class="btn btn-danger btn-sm btn-rounded m-r-20">
									<i class="fa fa-trash"></i>
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
@include('chuckcms-module-ecommerce::backend.collections._create_modal')
@include('chuckcms-module-ecommerce::backend.collections._edit_modal')
@include('chuckcms-module-ecommerce::backend.collections._delete_modal')
@endsection