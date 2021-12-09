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
							<th scope="col">Type</th>
							<th scope="col">Volgorde</th>
							<th scope="col" style="min-width:190px">Actions</th>
        				</tr>
        			</thead>
                    <tbody>
                        @foreach($locations as $location)
                            <tr>
                                <td class="v-align-middle">{{ $location->id }}</td>
                                <td class="v-align-middle">{{$location->json['name'] }}</td>
                                <td class="v-align-middle">
                                    <span class="badge badge-{{ $location->type == 'takeout' ? 'primary' : 'secondary' }}">
                                        {{$location->type}}
                                    </span>
                                </td>
                                <td class="v-align-middle">{{$location->order }}</td>
                                <td class="v-align-middle semi-bold">
                                    @can('edit redirects')
                                    <a href="#" onclick="editModal({{ $location->id }}, '{{ $location->json['name'] }}', '{{ $location->type }}', '{{ $location->days_of_week_disabled }}', '{{ $location->on_the_spot ? '1' : '0' }}', '{{ $location->dates_disabled }}', '{{ $location->delivery_cost == 0 ? '0' : $location->delivery_cost }}', '{{ $location->delivery_limited_to }}', '{{ $location->delivery_radius == null ? 'null' : $location->delivery_radius }}', '{{ $location->delivery_radius_from }}', '{{ implode(',', array_filter($location->json['delivery_in_postalcodes'])) }}', '{{ $location->time_required ? '1' : '0' }}', '{{ $location->time_min == 0 ? '0' : $location->time_min }}', '{{ $location->time_max }}', '{{ $location->pos_users }}', '{{ $location->pos_name }}', '{{ $location->pos_address1 }}', '{{ $location->pos_address2 }}', '{{ $location->pos_vat }}', '{{ $location->pos_receipt_title }}', '{{ $location->pos_receipt_footer_line1 }}', '{{ $location->pos_receipt_footer_line2 }}', '{{ $location->pos_receipt_footer_line3 }}', '{{ $location->order }}')" class="btn btn-default btn-sm btn-rounded m-r-20">
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
{{--  @include('chuckcms-module-ecommerce::backend.locations._edit_modal')
@include('chuckcms-module-ecommerce::backend.locations._delete_modal')  --}}
@endsection