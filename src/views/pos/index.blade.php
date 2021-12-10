@extends('chuckcms-module-ecommerce::pos.layout')

@section('css')
    @include('chuckcms-module-ecommerce::pos.css')
@endsection

@section('content')
<section class="row wrapper gx-0" id="cof_orderFormGlobalSection" data-site-domain="{{ URL::to('/') }}">
    <div class="main col-lg-8">
        @include('chuckcms-module-ecommerce::pos.includes.header')
        @include('chuckcms-module-ecommerce::pos.includes.category_section')
        @include('chuckcms-module-ecommerce::pos.includes.product_section')
        @include('chuckcms-module-ecommerce::pos.includes.handler_section')
    </div>
    <div class="bestelling col-lg-4 position-relative">
        @include('chuckcms-module-ecommerce::pos.includes.cart_section')
    </div>
</section>
@include('chuckcms-module-ecommerce::pos.includes.options_modal')
@endsection

@section('scripts')
    @include('chuckcms-module-ecommerce::pos.scripts')
@endsection
