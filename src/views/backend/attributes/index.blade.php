@extends('chuckcms::backend.layouts.base')

@section('title')
	Attributen
@endsection

@section('add_record')
	@can('create redirects')
	<a href="#" data-target="#createAttributeModal" data-toggle="modal" class="btn btn-link text-primary m-l-20 hidden-md-down">Voeg Nieuw Attribuut Toe</a>
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

function deleteModal(id, name){
	$('#delete_attribute_id').val(id);
	$('#delete_attribute_name').text(name);
	$('#deleteAttributeModal').modal('show');
}
</script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item active" aria-current="page">Attributen</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
    	<div class="col-sm-12 text-right">
    		<a href="#" data-target="#createAttributeModal" data-toggle="modal" class="btn btn-sm btn-outline-success">Nieuw Attribuut</a>
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
        				@foreach($attributes as $attribute)
						<tr>
							<td class="v-align-middle">{{ $attribute->id }}</td>
							<td class="v-align-middle">{{$attribute->json['name'] }}</td>
							<td class="v-align-middle semi-bold">
								@can('edit redirects')
								<a href="{{ route('dashboard.module.ecommerce.attributes.edit', ['id' => $attribute->id]) }}" class="btn btn-default btn-sm btn-rounded m-r-20">
									<i data-feather="edit-2"></i> edit
								</a>
								@endcan

								@can('delete redirects')
								<a href="#" onclick="deleteModal({{ $attribute->id }}, '{{ $attribute->json['name'] }}')" class="btn btn-danger btn-sm btn-rounded m-r-20">
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
@include('chuckcms-module-ecommerce::backend.attributes._create_modal')
@include('chuckcms-module-ecommerce::backend.attributes._delete_modal')
@endsection