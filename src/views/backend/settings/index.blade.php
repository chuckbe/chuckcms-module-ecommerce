@extends('chuckcms::backend.layouts.base')

@section('title')
Instellingen
@endsection

@section('content')
@php
$lang = \LaravelLocalization::getCurrentLocale();
@endphp
<div class="container min-height">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item active" aria-current="page">Instellingen</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        <div class="col-sm-12">
            <div class="my-3">
                <ul class="nav nav-tabs justify-content-start" id="pageTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link{{ $tab == 'general' ? ' active' : '' }}" id="s_general-tab" href="{{ route('dashboard.module.ecommerce.settings.index') }}">Algemeen</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link{{ $tab == 'layout' ? ' active' : '' }}" id="s_layout-tab" href="{{ route('dashboard.module.ecommerce.settings.index.layout') }}">Layout</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link{{ $tab == 'orders' ? ' active' : '' }}" id="s_orders-tab" href="{{ route('dashboard.module.ecommerce.settings.index.orders') }}">Bestellingen</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link{{ $tab == 'shipping' ? ' active' : '' }}" id="s_shipping-tab" href="{{ route('dashboard.module.ecommerce.settings.index.shipping') }}">Verzending</a>
                    </li>
                    <li class="nav-item d-none" role="presentation">
                        <a class="nav-link{{ $tab == 'products' ? ' active' : '' }}" id="s_products-tab" data-toggle="tab" href="#s_products" role="tab" aria-controls="s_products" aria-selected="false">Producten</a>
                    </li>
                    <li class="nav-item d-none" role="presentation">
                        <a class="nav-link{{ $tab == 'customers' ? ' active' : '' }}" id="s_customers-tab" href="#s_customers">Klanten</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link{{ $tab == 'integrations' ? ' active' : '' }}" id="s_integrations-tab" href="{{ route('dashboard.module.ecommerce.settings.index.integrations') }}">Integraties</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row tab-content bg-light shadow-sm rounded p-3 mb-5 mx-1" id="pageTabContent">
        <div class="col-sm-12 tab-pane fade show active" id="s_{{ $tab }}" role="tabpanel" aria-labelledby="s_{{ $tab }}-tab">
            @yield('tab')
        </div>

        {{-- <div class="col-sm-12 tab-pane fade" id="s_layout" role="tabpanel" aria-labelledby="s_layout-tab">
          @include('chuckcms-module-ecommerce::backend.settings.index._tab_layout')
        </div>

        <div class="col-sm-12 tab-pane fade" id="s_orders" role="tabpanel" aria-labelledby="s_orders-tab">
          @include('chuckcms-module-ecommerce::backend.settings.index._tab_orders')
        </div>

        <div class="col-sm-12 tab-pane fade" id="s_shipping" role="tabpanel" aria-labelledby="s_shipping-tab">
          @include('chuckcms-module-ecommerce::backend.settings.index._tab_shipping')
        </div> --}}

        {{-- <div class="col-sm-12 tab-pane fade" id="s_products" role="tabpanel" aria-labelledby="s_products-tab">
          @include('chuckcms-module-ecommerce::backend.settings.index._tab_products')
        </div> --}}

        {{-- <div class="col-sm-12 tab-pane fade" id="s_customers" role="tabpanel" aria-labelledby="s_customers-tab">
          @include('chuckcms-module-ecommerce::backend.settings.index._tab_customers')
        </div> --}}

        {{-- <div class="col-sm-12 tab-pane fade" id="s_integrations" role="tabpanel" aria-labelledby="s_integrations-tab">
          @include('chuckcms-module-ecommerce::backend.settings.index._tab_integrations')
        </div> --}}
    </div>
    <div class="row">
        <div class="col-sm-12 mb-3"></div>
    </div>
    {{-- <div class="row">
        <div class="col-sm-12 text-right">
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <button class="btn btn-outline-success" type="submit">Opslaan</button>
        </div>
    </div> --}}
</div>
@endsection

@section('css')
<style>
    .online, .offline {
        width: 5px;
        height: 5px;
        border-radius: 50%;
    }  
    .custom-file .custom-file-input:focus ~ .custom-file-label, .custom-file .custom-file-input:hover ~ .custom-file-label {
        border-color: inherit;
        box-shadow: none;
    }
    .custom-file .custom-file-input:focus ~ .custom-file-label.has-error, .custom-file .custom-file-input:hover ~ .custom-file-label.has-error , .custom-file-label.has-error{
        border-color: var(--danger);
    }
</style>
@endsection

@section('scripts')
{{-- <script src="{{ URL::to('vendor/laravel-filemanager/js/filemanager.js') }}"></script> --}}
<script src="{{ URL::to('/vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
<script src="/chuckbe/chuckcms-module-ecommerce/js/labelprinter/DYMO.Label.Framework.3.0.js"></script>
<script src="/chuckbe/chuckcms-module-ecommerce/js/labelprinter/Knockout-min.js"></script>
<script src="/chuckbe/chuckcms-module-ecommerce/js/labelprinter/label.js"></script>
<script>
const labelSrc = "{{ChuckEcommerce::getSetting('integrations.label.src') ?? '/chuckbe/chuckcms-module-ecommerce/test.label'}}";
$( document ).ready(function() { 
    init();

    function init () {
        var domain = "{{ URL::to('dashboard/media')}}";
        // $('#lfm').filemanager('image', {prefix: domain});
        $('#lfmlabel').filemanager('file', {prefix: domain});

        //$('.autonumeric').autoNumeric('init');
    }

    $('body').on('change', '.boolean_checkbox_input', function() {
        if($(this).is(':checked')) {
            $(this).val(1);
            $(this).parent('label').siblings('input').prop('disabled', true);
        } else {
            $(this).val(0);
            $(this).parent('label').siblings('input').prop('disabled', false);
        }
    });
});
</script>
<script type="text/javascript">
function editCarrierModal(id){
    $('#editCarrierModal_'+id).modal('show');
}

function deleteCarrierModal(id, name){
    $('#delete_carrier_key').val(id);
    $('#delete_carrier_name').text(name);
    $('#deleteCarrierModal').modal('show');
}
</script>


@if (session('notification'))
@include('chuckcms::backend.includes.notification')
@endif
@endsection