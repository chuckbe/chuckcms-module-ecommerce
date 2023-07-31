@extends('chuckcms-module-ecommerce::pos.layout')

@section('content')
<div class="wrapper container-fluid p-0 d-flex" id="pos_mainWrapper" data-site-domain="{{ URL::to('/') }}">
    <div class="main col-7 position-relative d-flex flex-column">
        @include('chuckcms-module-ecommerce::pos.includes.header')

        @include('chuckcms-module-ecommerce::pos.includes.collections')

        @include('chuckcms-module-ecommerce::pos.includes.products')

        @include('chuckcms-module-ecommerce::pos.includes.handler_section', ['settings' => $settings])
    </div>

    @include('chuckcms-module-ecommerce::pos.cart.index')
</div>

@include('chuckcms-module-ecommerce::pos.includes.options_modal')


@include('chuckcms-module-ecommerce::pos.includes.modals.combinations')

@include('chuckcms-module-ecommerce::pos.includes.modals.orders')
@include('chuckcms-module-ecommerce::pos.includes.modals.payment')
@endsection

@section('scripts')
@include('chuckcms-module-ecommerce::pos.scripts')
@endsection

@section('css')
@include('chuckcms-module-ecommerce::pos.styles')
@endsection
