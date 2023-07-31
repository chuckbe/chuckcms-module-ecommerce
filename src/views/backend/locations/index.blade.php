@extends('chuckcms::backend.layouts.base')

@section('title')
Locaties
@endsection

@section('add_record')
@can('create redirects')
<a href="#" data-target="#createLocationModal" data-toggle="modal" class="btn btn-link text-primary m-l-20 hidden-md-down">Voeg Nieuwe Locatie Toe</a>
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
    function editModal(id, name, pos_users, pos_name, pos_address1, pos_address2, pos_vat, pos_receipt_title, pos_receipt_footer_line1, pos_receipt_footer_line2, pos_receipt_footer_line3, mollie_terminal_id, order){
        $('#edit_location_id').val(id);
        $('#edit_location_name').val(name);
        $('#edit_location_pos_users').val(pos_users);
        $('#edit_location_pos_name').val(pos_name);
        $('#edit_location_pos_address1').val(pos_address1);
        $('#edit_location_pos_address2').val(pos_address2);
        $('#edit_location_pos_vat').val(pos_vat);
        $('#edit_location_pos_receipt_title').val(pos_receipt_title);
        $('#edit_location_pos_receipt_footer_line1').val(pos_receipt_footer_line1);
        $('#edit_location_pos_receipt_footer_line2').val(pos_receipt_footer_line2);
        $('#edit_location_pos_receipt_footer_line3').val(pos_receipt_footer_line3);
        $('#edit_location_mollie_terminal_id').val(mollie_terminal_id);
        $('#edit_location_order').val(order);
        $('#editLocationModal').modal('show');
    }
    function deleteModal(id, name){
        $('#delete_location_id').val(id);
        $('#delete_location_name').text(name);
        $('#deleteLocationModal').modal('show');
    }
	
</script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item active" aria-current="page">Locaties</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
    	<div class="col-sm-12 text-right">
    		<a href="#" data-target="#createLocationModal" data-toggle="modal" class="btn btn-sm btn-outline-success">Nieuwe Locatie</a>
    	</div>
        <div class="col-sm-12 my-3">
            <div class="table-responsive">
                <table class="table" data-datatable style="width:100%">
                    <thead>
        				<tr>
        					<th scope="col">#</th>
							<th scope="col">Naam</th>
							<th scope="col">Volgorde</th>
							<th scope="col" style="min-width:190px">Actions</th>
        				</tr>
        			</thead>
                    <tbody>
                        @foreach($locations as $location)
                            <tr>
                                <td class="v-align-middle">{{ $location->id }}</td>
                                <td class="v-align-middle">{{$location->json['name'] }}</td>
                                <td class="v-align-middle">{{$location->order }}</td>
                                <td class="v-align-middle semi-bold">
                                    @can('edit redirects')
                                    <a href="#" onclick="editModal({{ $location->id }}, '{{ $location->json['name'] }}', '{{ $location->pos_users }}', '{{ $location->pos_name }}', '{{ $location->pos_address1 }}', '{{ $location->pos_address2 }}', '{{ $location->pos_vat }}', '{{ $location->pos_receipt_title }}', '{{ $location->pos_receipt_footer_line1 }}', '{{ $location->pos_receipt_footer_line2 }}', '{{ $location->pos_receipt_footer_line3 }}', '{{ $location->mollie_terminal_id }}', '{{ $location->order }}')" class="btn btn-default btn-sm btn-rounded m-r-20">
                                        <i data-feather="edit-2"></i> edit
                                    </a>
                                    @endcan

                                    @can('delete redirects')
                                    <a href="#" onclick="deleteModal({{ $location->id }}, '{{ $location->json['name'] }}')" class="btn btn-danger btn-sm btn-rounded m-r-20">
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
@include('chuckcms-module-ecommerce::backend.locations._create_modal')
@include('chuckcms-module-ecommerce::backend.locations._edit_modal')
@include('chuckcms-module-ecommerce::backend.locations._delete_modal')
@endsection
