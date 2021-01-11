@extends('chuckcms::backend.layouts.base')

@section('title')
	E-commerce
@endsection

@section('content')
<div class="container p-3 min-height">
  <div class="row pb-3">
    <div class="col-sm-6">
      <div class="jumbotron jumbotron-fluid">
        <div class="container pl-5">
          <p class="lead">Revenue Past 7 Days</p>
          <h1 class="display-4">{{ ChuckEcommerce::totalSalesLast7Days() }}</h1>
          <p>{{ ChuckEcommerce::totalSalesLast7DaysQty() }} orders</p>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="jumbotron jumbotron-fluid">
        <div class="container pl-5">
          <p class="lead">Total Revenue</p>
          <h1 class="display-4">{{ ChuckEcommerce::totalSales() }}</h1>
          <p>{{ $orders_count }} orders</p>
        </div>
      </div>
    </div>
  </div>
  <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
    <div class="col-12">
      <div class="table-responsive">
        <table class="table table-condensed table-hover">
          <tbody>
            @foreach($orders->sortByDesc('created_at') as $order)
            <tr>
              <td class="font-montserrat all-caps fs-12 w-25">{{ date('Y-m-d', strtotime($order->created_at)) }}</td>
              <td class="font-montserrat all-caps fs-12 w-50">#{{ $order->json['order_number'] }}</td>
              <td class="w-25 b-l b-dashed b-grey">
                <span class="font-montserrat fs-18">{{ ChuckEcommerce::formatPrice($order->final) }}</span>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-12">
      <a href="{{ route('dashboard.module.ecommerce.orders.index') }}" class="btn btn-block btn-primary">View all orders</a>
    </div>
  </div>
</div>
@endsection

@section('scripts')

@endsection