@extends('chuckcms::backend.layouts.admin')

@section('title')
	Customers
@endsection

@section('breadcrumbs')
	<ol class="breadcrumb">
		<li class="breadcrumb-item active"><a href="{{ route('dashboard.module.ecommerce.index') }}">Customers</a></li>
	</ol>
@endsection

@section('content')
<div class=" container-fluid container-fixed-lg">
    <div class="row">
		<div class="col-lg-12">
		<!-- START card -->
			<div class="card card-transparent">
				<div class="card-header ">
					<div class="card-title">Customers</div>
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
								<th style="width:12%">Voornaam</th>
								<th style="width:13%">Naam</th>
								<th style="width:34%">Adres</th>
								<th style="width:12%">Land</th>
								<th style="width:13%">Groep</th>
								<th style="width:16%">Actions</th>
							</tr>
						</thead>
							<tbody>
								@foreach($customers as $customer)
								<tr class="customer_line" data-id="{{ $customer->id }}">
							    	<td class="v-align-middle semi-bold">{{ $customer->surname }}</td>
							    	<td class="v-align-middle">{{ $customer->name }}</td>
							    	<td class="v-align-middle">{{ array_key_exists('address', $customer->json) ? $customer->json['address']['billing']['street'] .' '. $customer->json['address']['billing']['street'] .', '. $customer->json['address']['billing']['postalcode'] .' '. $customer->json['address']['billing']['city'] : '' }} </td>
							    	<td class="v-align-middle semi-bold">{{ array_key_exists('address', $customer->json) ? $customer->json['address']['billing']['country'] : '' }}</td>
							    	<td class="v-align-middle">
							    		<span class="label label-inverse">{{ array_key_exists('group', $customer->json) ? ChuckEcommerce::getSetting('customer.groups.'.$customer->json['group'].'.name') : ChuckEcommerce::getSetting('customer.groups.'.ChuckEcommerce::getDefaultGroup().'.name') }}</span>
							    	</td>
							    	<td class="v-align-middle semi-bold">
							    		<a href="{{ route('dashboard.module.ecommerce.customers.detail', ['customer' => $customer->id]) }}" class="btn btn-default btn-sm btn-rounded m-r-20">
							    			<i data-feather="clipboard"></i> bekijken
							    		</a>
							    	</td>
							  	</tr>
							  	@endforeach
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

@section('scripts')

@endsection