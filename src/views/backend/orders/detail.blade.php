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
					<div class="tools">
						<a class="collapse" href="javascript:;"></a>
						<a class="config" data-toggle="modal" href="#grid-config"></a>
						<a class="reload" href="javascript:;"></a>
						<a class="remove" href="javascript:;"></a>
					</div>
					<div class="pull-right">
				    	<div class="col-xs-12">
				    		<input type="text" id="search-table" class="form-control pull-right" placeholder="Search">
				    	</div>
				    </div>
				    <div class="clearfix"></div>
				</div>
				<div class="card-block">
					<div class="table-responsive">
						<table class="table table-hover table-condensed" id="condensedTable" data-table-count="30">
						<thead>
							<tr>
								<th style="width:32%">Product</th>
								<th style="width:18%">Hoeveelheid</th>
								<th style="width:34%">Prijs</th>
								<th style="width:16%">Totaal</th>
							</tr>
						</thead>
							<tbody>
								@foreach($order->json['products'] as $sku => $product)
								<tr class="order_line" data-id="{{ $sku }}">
							    	<td class="v-align-middle semi-bold">{{ $product['title'] }}</td>
							    	<td class="v-align-middle">{{ $product['quantity'] }}</td>
							    	<td class="v-align-middle">{{ ChuckEcommerce::formatPrice($product['price_tax'])  }} </td>
							    	<td class="v-align-middle semi-bold">{{ ChuckEcommerce::formatPrice($product['total'])  }}</td>
							  	</tr>
							  	@endforeach
							  	<tr class="shipping_line">
							    	<td class="v-align-middle semi-bold"></td>
							    	<td class="v-align-middle"></td>
							    	<td class="v-align-middle">Verzending </td>
							    	<td class="v-align-middle semi-bold">{{ $order->shipping > 0 ? ChuckEcommerce::formatPrice($order->shipping + $order->shipping_tax) : 'gratis' }}</td>
							  	</tr>
							  	<tr class="total_line">
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
@endsection