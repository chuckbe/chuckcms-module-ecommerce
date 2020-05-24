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
    
@endsection

@section('content')
<div class=" container-fluid container-fixed-lg">
    <div class="row">
		<div class="col-lg-12">
		<!-- START card -->
			<div class="card card-transparent">
				<div class="card-header ">
					<div class="card-title">Klant: {{ $customer->surname . ' ' . $customer->name }}</div>
				</div>
				<div class="card-block">
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
							    	<td class="v-align-middle">{{ array_key_exists('address', $customer->json) ? $customer->json['address']['billing']['street'] .' '. $customer->json['address']['billing']['street'] .', '. $customer->json['address']['billing']['postalcode'] .' '. $customer->json['address']['billing']['city'] : '' }}</td>
							    	<td class="v-align-middle"><button class="btn btn-xs float-right btn-primary editDateModal"><i data-feather="edit"></i></button> </td>
							  	</tr>
							  	<tr class="shipping_line" data-id="{{ $customer->id }}">
							  		<td class="v-align-middle">3</td>
							    	<td class="v-align-middle semi-bold">Verzendingsadres: </td>
							    	<td class="v-align-middle">{{ array_key_exists('address', $customer->json) ? $customer->json['address']['shipping']['street'] .' '. $customer->json['address']['shipping']['street'] .', '. $customer->json['address']['shipping']['postalcode'] .' '. $customer->json['address']['shipping']['city'] : '' }}</td>
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
		<!-- END card -->
		</div>
    </div>
</div>
@endsection