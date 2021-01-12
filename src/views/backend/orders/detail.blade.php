@extends('chuckcms::backend.layouts.base')

@section('title')
	Orders
@endsection

@section('add_record')
	
@endsection

@section('css')
	
@endsection

@section('scripts')
	
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
					<li class="breadcrumb-item"><a href="{{ route('dashboard.module.ecommerce.orders.index') }}">Bestellingen</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Bestelling #{{ $order->json['order_number'] }}</li>
                </ol>
            </nav>
        </div>
    </div>
	<div class="row">
		<div class="col-sm-12">
			<div class="jumbotron jumbotron-fluid">
				<div class="container pl-5">
					<p class="lead">Details</p>
					
					<div class="mb-3">
						<a href="{{ route('dashboard.module.ecommerce.orders.delivery', ['order' => $order->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
							pakbon
						</a>
						@if($order->hasInvoice())
						<a href="{{ route('dashboard.module.ecommerce.orders.invoice', ['order' => $order->id]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
							factuur
						</a>
						@endif
					</div>
					
					<b>Status:</b>	<a href="#" type="button" data-target="#updateStatusModal" data-toggle="modal" class="badge badge-{{ ChuckEcommerce::getSetting('order.statuses.'.$order->status.'.paid') ? 'success' : 'info' }}">{{ ChuckEcommerce::getSetting('order.statuses.'.$order->status.'.short.'.app()->getLocale()) }}</a> <br><br>
					
					<b>Verzending:</b> {{ $order->json['shipping']['name'] }} <br>
                    <b>Verzendtijd:</b> {{ $order->json['shipping']['transit_time'] }} 
                    <br><br>


                    @if($order->json['address']['shipping_equal_to_billing'])
                    <b>Facturatie & Verzendadres: </b>
                    @else 
                    <b>Facturatie adres: </b>
                    @endif  
                    <br>

                    <b>Naam</b>: {{ $order->surname . ' ' . $order->name }} <br>
                    <b>E-mail</b>: {{ $order->email }} 
                    @if(!is_null($order->tel)) 
                    <br>
                    <b>Tel</b>: {{ $order->tel }} 
                    @endif
                    <br>
                    @if(!is_null($order->customer->json['company']['name']))
                    <b>Bedrijfsnaam</b>: {{ $order->customer->json['company']['name'] }} <br>
                    <b>BTW-nummer</b>: {{ $order->customer->json['company']['vat'] }} <br>
                    @endif
                    <b>Adres</b>: <br> {{ $order->json['address']['billing']['street'] . ' ' . $order->json['address']['billing']['housenumber'] }}, <br> {{ $order->json['address']['billing']['postalcode'] . ' ' . $order->json['address']['billing']['city'] .', '. config('chuckcms-module-ecommerce.countries_data.'.$order->json['address']['billing']['country'].'.native') }} <br>
                    @if(!$order->json['address']['shipping_equal_to_billing'])
                    <br>
                    <b>Verzend adres: </b><br> 
                    {{ $order->json['address']['shipping']['street'] . ' ' . $order->json['address']['shipping']['housenumber'] }}, <br> {{ $order->json['address']['shipping']['postalcode'] . ' ' . $order->json['address']['shipping']['city'] .', '. config('chuckcms-module-ecommerce.countries_data.'.$order->json['address']['shipping']['country'].'.native') }} <br>
                    @endif
				</div>
			</div>
		</div>
	</div>
    <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
        <div class="col-sm-12 my-3">
        	<div class="table-responsive">
        		<table class="table" style="width:100%">
        			<thead>
        				<tr>
        					<th scope="col">#</th>
							<th scope="col">Product</th>
							<th scope="col">Hvl</th>
							<th scope="col" class="pr-5">Prijs</th>
							<th scope="col">Totaal</th>
        				</tr>
        			</thead>
        			<tbody>
						@foreach($order->json['products'] as $sku => $product)
						<tr class="order_line" data-id="{{ $sku }}">
							<td class="v-align-middle semi-bold">{{ $loop->iteration }}</td>
							<td class="v-align-middle semi-bold">{{ $product['title'] }}</td>
							<td class="v-align-middle">{{ $product['quantity'] }}</td>
							<td class="v-align-middle">{{ ChuckEcommerce::formatPrice($product['price_tax'])  }} </td>
							<td class="v-align-middle semi-bold">{{ ChuckEcommerce::formatPrice($product['total'])  }}</td>
						</tr>
						@endforeach
						<tr class="shipping_line">
							<td class="v-align-middle semi-bold">{{ count($order->json['products']) + 1 }}</td>
							<td class="v-align-middle semi-bold"></td>
							<td class="v-align-middle"></td>
							<td class="v-align-middle">Verzending </td>
							<td class="v-align-middle semi-bold">{{ $order->shipping > 0 ? ChuckEcommerce::formatPrice($order->shipping + $order->shipping_tax) : 'gratis' }}</td>
						</tr>
						<tr class="total_line">
							<td class="v-align-middle semi-bold">{{ count($order->json['products']) + 2 }}</td>
							<td class="v-align-middle semi-bold"></td>
							<td class="v-align-middle"></td>
							<td class="v-align-middle">Totaal </td>
							<td class="v-align-middle semi-bold">{{ ChuckEcommerce::formatPrice($order->final) }}</td>
						</tr>
        			</tbody>
        		</table>
        	</div>
        </div>
    </div>
</div>

@include('chuckcms-module-ecommerce::backend.orders._update_status_modal')
@endsection