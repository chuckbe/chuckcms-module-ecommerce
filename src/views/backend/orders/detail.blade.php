@extends('chuckcms::backend.layouts.admin')

@section('title')
	Orders
@endsection

@section('add_record')
	
@endsection

@section('css')
	<link href="https://cdn.chuck.be/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.chuck.be/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.chuck.be/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('scripts')
	<script src="https://cdn.chuck.be/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.chuck.be/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="https://cdn.chuck.be/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="https://cdn.chuck.be/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://cdn.chuck.be/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="https://cdn.chuck.be/assets/plugins/datatables-responsive/js/lodash.min.js"></script>
    <script src="https://cdn.chuck.be/assets/js/tables.js" type="text/javascript"></script>
    <script src="https://cdn.chuck.be/assets/plugins/sweetalert2.all.js"></script>
    
@endsection

@section('content')
<div class=" container-fluid container-fixed-lg">
    <div class="row">
		<div class="col-lg-12">
		<!-- START card -->
			<div class="card card-transparent">
				<div class="card-header ">
					<div class="card-title">Orders</div>
				</div>

				<div class="card-block">
					<h4>Gegevens 
						<a href="#" type="button" data-target="#updateStatusModal" data-toggle="modal" class="label {{ ChuckEcommerce::getSetting('order.statuses.'.$order->status.'.paid') ? 'label-inverse' : '' }}">{{ ChuckEcommerce::getSetting('order.statuses.'.$order->status.'.short.'.app()->getLocale()) }}</a>
					</h4>
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

				<div class="card-block">
					<div class="table-responsive">
						<table class="table table-hover table-condensed" id="condensedTable" data-table-count="30">
						<thead>
							<tr>
								<th style="width:8%">#</th>
								<th style="width:28%">Product</th>
								<th style="width:14%">Hvl</th>
								<th style="width:34%">Prijs</th>
								<th style="width:16%">Totaal</th>
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
		<!-- END card -->
		</div>
    </div>
</div>

@include('chuckcms-module-ecommerce::backend.orders._update_status_modal')
@endsection