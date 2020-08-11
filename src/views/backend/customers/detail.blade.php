@extends('chuckcms::backend.layouts.admin')

@section('title')
	Klant: {{ $customer->surname . ' ' . $customer->name }}
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
    
  	@if (session('notification'))
  	@include('chuckcms::backend.includes.notification')
  	@endif
@endsection

@section('content')
<!-- START CONTAINER FLUID -->
<div class=" container-fluid   container-fixed-lg">

	<!-- START card -->
	<div class="card card-transparent">
	  	<div class="card-header ">
	    	<div class="card-title">Klant: {{ $customer->surname . ' ' . $customer->name }}</div>
	  	</div>
	  	<div class="card-block">
	   		<div class="row">
	      		<div class="col-md-12">
		        @if ($errors->any())
		          <div class="alert alert-danger">
		              <ul>
		                  @foreach ($errors->all() as $error)
		                      <li>{{ $error }}</li>
		                  @endforeach
		              </ul>
		          </div>
		        @endif
		        <div class="card card-transparent">
		          	<!-- Nav tabs -->
		          	<ul class="nav nav-tabs nav-tabs-linetriangle" data-init-reponsive-tabs="dropdownfx">
		            	<li class="nav-item">
		              		<a href="#" class="active" data-toggle="tab" data-target="#general_setup"><span>Algemeen</span></a>
		            	</li>
		            	<li class="nav-item">
		              		<a href="#" data-toggle="tab" data-target="#order_setup"><span>Bestellingen</span></a>
		            	</li>
		          	</ul>
		          	<!-- Tab panes -->
		          	<div class="tab-content">
		            	<div class="tab-pane fade show active" id="general_setup">
		              		<div class="row column-seperation">
				              	<div class="col-sm-12">
				              		<div class="table-responsive">
										<table class="table table-hover table-condensed" id="condensedTable" data-table-count="30">
										<thead>
											<tr>
												<th style="width:7%">#</th>
												<th style="width:40%">Gegevens</th>
												<th style="width:40%">Waarde</th>
												<th style="width:13%">Actie</th>
											</tr>
										</thead>
											<tbody>
												<tr class="order_line" data-id="{{ $customer->id }}">
													<td class="v-align-middle">1</td>
											    	<td class="v-align-middle semi-bold">Naam: </td>
											    	<td class="v-align-middle">{{ $customer->surname.' '.$customer->name }}</td>
											    	<td class="v-align-middle"><button class="btn btn-xs float-right btn-primary editDateModal"><i data-feather="edit"></i></button> </td>
											  	</tr>
											  	<tr class="billing_line" data-id="{{ $customer->id }}">
											  		<td class="v-align-middle">2</td>
											    	<td class="v-align-middle semi-bold">Facturatie adres: </td>
											    	<td class="v-align-middle">{{ ChuckCustomer::forCustomer($customer)->address()['street'] .' '. ChuckCustomer::forCustomer($customer)->address()['housenumber'] .', '. ChuckCustomer::forCustomer($customer)->address()['postalcode'] .' '. ChuckCustomer::forCustomer($customer)->address()['city'] }}</td>
											    	<td class="v-align-middle"><button class="btn btn-xs float-right btn-primary editDateModal"><i data-feather="edit"></i></button> </td>
											  	</tr>
											  	<tr class="shipping_line" data-id="{{ $customer->id }}">
											  		<td class="v-align-middle">3</td>
											    	<td class="v-align-middle semi-bold">Verzendingsadres: </td>
											    	<td class="v-align-middle">{{ ChuckCustomer::forCustomer($customer)->address(false)['street'] .' '. ChuckCustomer::forCustomer($customer)->address(false)['housenumber'] .', '. ChuckCustomer::forCustomer($customer)->address(false)['postalcode'] .' '. ChuckCustomer::forCustomer($customer)->address(false)['city'] }}</td>
											    	<td class="v-align-middle"><button class="btn btn-xs float-right btn-primary editDateModal"><i data-feather="edit"></i></button> </td>
											  	</tr>
											  	<tr class="group_line" data-id="{{ $customer->id }}">
											  		<td class="v-align-middle">4</td>
											    	<td class="v-align-middle semi-bold">Klantengroep: </td>
											    	<td class="v-align-middle"><span class="label label-inverse">{{ array_key_exists('group', $customer->json) ? ChuckEcommerce::getSetting('customer.groups.'.$customer->json['group'].'.name') : ChuckEcommerce::getSetting('customer.groups.'.ChuckEcommerce::getDefaultGroup().'.name') }}</span></td>
											    	<td class="v-align-middle"><button class="btn btn-xs float-right btn-primary editDateModal"><i data-feather="edit"></i></button> </td>
											  	</tr>
											</tbody>
										</table>
									</div>
				              	</div>
		              		</div>
		            	</div>



			            <div class="tab-pane fade" id="order_setup">
			            	<div class="row column-seperation">
			            		<div class="table-responsive">
									<table class="table table-hover table-condensed" id="condensedTable" data-table-count="30">
									<thead>
										<tr>
											<th style="width:12%">Order #</th>
											<th style="width:13%">Datum</th>
											<th style="width:23%">Naam & Adres</th>
											<th style="width:12%">Totaal</th>
											<th style="width:13%">Status</th>
											<th style="width:27%">Actions</th>
										</tr>
									</thead>
										<tbody>
											@foreach($customer->orders as $order)
											<tr class="order_line" data-id="{{ $order->id }}">
										    	<td class="v-align-middle semi-bold">{{ $order->json['order_number'] }}</td>
										    	<td class="v-align-middle">{{ date('Y/m/d', strtotime($order->created_at)) }}</td>
										    	<td class="v-align-middle">{{ $order->surname .' '. $order->name }} <br> <a href="mailto:{{ $order->email }}">{{ $order->email }}</a> <br> @if(!is_null($order->tel)) <a href="tel:{{ $order->tel }}">{{ $order->tel }}</a> <br> @endif </td>
										    	<td class="v-align-middle semi-bold">{{ ChuckEcommerce::formatPrice($order->final) }}</td>
										    	<td class="v-align-middle">
										    		<span class="label {{ ChuckEcommerce::getSetting('order.statuses.'.$order->status.'.paid') ? 'label-inverse' : '' }}">{{ ChuckEcommerce::getSetting('order.statuses.'.$order->status.'.short.'.app()->getLocale()) }}</span>
										    	</td>
										    	<td class="v-align-middle semi-bold">
										    		<a href="{{ route('dashboard.module.ecommerce.orders.detail', ['order' => $order->id]) }}" class="btn btn-default btn-sm btn-rounded m-r-20">
										    			<i data-feather="clipboard"></i> bekijken
										    		</a>
										    		@if($order->hasInvoice())
										    		<a href="{{ route('dashboard.module.ecommerce.orders.invoice', ['order' => $order->id]) }}" class="btn btn-secondary btn-sm btn-rounded m-r-20">
										    			<i data-feather="list"></i> 
										    		</a>
										    		@endif
										    	</td>
										  	</tr>
										  	@endforeach
										</tbody>
									</table>
								</div>
			            	</div> 
			            </div>



		            


		          </div>
		        </div>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- END card -->
</div>
<!-- END CONTAINER FLUID -->

@endsection
