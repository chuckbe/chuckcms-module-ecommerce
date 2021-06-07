@extends('chuckcms::backend.layouts.base')

@section('title')
	Customers
@endsection

@section('add_record')
	@can('create forms')
	<a href="{{ route('dashboard.module.ecommerce.products.create') }}" class="btn btn-link text-primary m-l-20 hidden-md-down">Nieuwe Klant</a>
	@endcan
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item active" aria-current="page">Klanten</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded py-3 mb-3 mx-1">
    	<div class="col-sm-12 text-right d-none">
    		<a href="{{ route('dashboard.module.ecommerce.products.create') }}" class="btn btn-sm btn-outline-success">Nieuwe Klant</a>
    	</div>
        <div class="col-sm-12 my-3">
        	<div class="table-responsive">
        		<table class="table" data-datatable style="width:100%">
        			<thead>
        				<tr>
							<th scope="col">ID</th>
        					<th scope="col">Naam</th>
							<th scope="col">Adres</th>
							<th scope="col">Land</th>
							<th scope="col" class="pr-5">Groep</th>
							<th scope="col" style="min-width:190px">Actions</th>
        				</tr>
        			</thead>
        			<tbody>
        				@foreach($customers as $customer)
						<tr class="customer_line" data-id="{{ $customer->id }}">
							<td class="v-align-middle semi-bold">{{ $customer->id }}</td>
					    	<td class="v-align-middle semi-bold">{{ $customer->surname.' '.$customer->name }}  <br> <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a> <br> @if(!is_null($customer->tel)) <a href="tel:{{ $customer->tel }}">{{ $customer->tel }}</a> <br> @endif</td>
					    	<td class="v-align-middle">{{ array_key_exists('address', $customer->json) ? $customer->json['address']['billing']['street'] .' '. $customer->json['address']['billing']['housenumber'] .', '. $customer->json['address']['billing']['postalcode'] .' '. $customer->json['address']['billing']['city'] : '' }} </td>
					    	<td class="v-align-middle">{{ array_key_exists('address', $customer->json) ? $customer->json['address']['billing']['country'] : '' }}</td>
					    	<td class="v-align-middle semi-bold">
								<span class="badge badge-info">{{ array_key_exists('group', $customer->json) ? ChuckEcommerce::getSetting('customer.groups.'.$customer->json['group'].'.name') : ChuckEcommerce::getSetting('customer.groups.'.ChuckEcommerce::getDefaultGroup().'.name') }}</span>
							</td>
					    	<td class="v-align-middle semi-bold">
					    		<a href="{{ route('dashboard.module.ecommerce.customers.detail', ['customer' => $customer->id]) }}" class="btn btn-sm btn-outline-primary rounded d-inline-block">
					    			bekijken
					    		</a>
					    	</td>
					  	</tr>
					  	@endforeach


        			</tbody>
        		</table>
        	</div>
        </div>
    </div>
</div>
@endsection

@section('css')
	
@endsection

@section('scripts')

@endsection