@extends('chuckcms::backend.layouts.base')

@section('title')
	Merken
@endsection

@section('add_record')
	@can('create redirects')
	<a href="#" data-target="#createBrandModal" data-toggle="modal" class="btn btn-link text-primary m-l-20 hidden-md-down">Voeg Nieuw Merk Toe</a>
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
</script>
<script type="text/javascript">
$(document).ready(function() {

	$("#create_redirect_slug").keyup(function(){
		var text = $(this).val();
		slug_text = text.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-');
		$("#create_redirect_slug").val(slug_text);  
	});

});

function editModal(id, name, logo){
	$('#edit_brand_id').val(id);
	$('#edit_brand_name').val(name);
	$('#edit_brand_logo').val(logo);
	$('#editlogoholder').attr('src', logo);
	$('#editBrandModal').modal('show');
}

function deleteModal(id, name){
	$('#delete_brand_id').val(id);
	$('#delete_brand_name').text(name);
	$('#deleteBrandModal').modal('show');
}
</script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item active" aria-current="page">Merken</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
    	<div class="col-sm-12 text-right">
    		<a href="#" data-target="#createBrandModal" data-toggle="modal" class="btn btn-sm btn-outline-success">Nieuw Merk</a>
    	</div>
        <div class="col-sm-12 my-3">
        	<div class="table-responsive">
        		<table class="table" data-datatable style="width:100%">
        			<thead>
        				<tr>
        					<th scope="col">#</th>
							<th scope="col">Naam</th>
							<th scope="col" style="min-width:190px">Actions</th>
        				</tr>
        			</thead>
        			<tbody>
        				@foreach($brands as $brand)
						<tr>
							<td class="v-align-middle">{{ $brand->id }}</td>
							<td class="v-align-middle">{{$brand->json['name'] }}</td>
							<td class="v-align-middle semi-bold">
								@can('edit redirects')
								<a href="#" onclick="editModal({{ $brand->id }}, '{{ $brand->json['name'] }}', '{{ $brand->json['logo'] }}')" class="btn btn-default btn-sm btn-rounded m-r-20">
									<i data-feather="edit-2"></i> edit
								</a>
								@endcan

								@can('delete redirects')
								<a href="#" onclick="deleteModal({{ $brand->id }}, '{{ $brand->json['name'] }}')" class="btn btn-danger btn-sm btn-rounded m-r-20">
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
</div>
@include('chuckcms-module-ecommerce::backend.brands._create_modal')
@include('chuckcms-module-ecommerce::backend.brands._edit_modal')
@include('chuckcms-module-ecommerce::backend.brands._delete_modal')
@endsection