@extends('chuckcms-module-ecommerce::pos.layout')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" />
@include('chuckcms-module-ecommerce::pos.css')
<style>
    
</style>
@endsection

@section('content')
<section class="row wrapper gx-0">
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
@endsection

@section('scripts')
    @include('chuckcms-module-ecommerce::pos.scripts')
@endsection