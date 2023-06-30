@extends('chuckcms-module-ecommerce::pos.layout')

@section('css')
<link
  rel="stylesheet"
  href="https://unpkg.com/swiper@7/swiper-bundle.min.css"
/>
    @include('chuckcms-module-ecommerce::pos.css')
    <style>
        .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;
        display: -webkit-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        -webkit-align-items: center;
        align-items: center;
      }
    </style>
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
    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: "auto",
            spaceBetween: 10
        });
    </script>
    @include('chuckcms-module-ecommerce::pos.scripts')
@endsection
