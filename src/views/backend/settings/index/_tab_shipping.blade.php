@extends('chuckcms-module-ecommerce::backend.settings.index')

@section('tab')
@foreach($module->json['settings']['shipping']['carriers'] as $carrierKey => $carrier)
<div class="row column-seperation">
  <div class="col-lg-12">
      <h6>
        <b>{{ $carrier['name'] }}</b> <small>{{ $carrier['transit_time'] }}</small> <span class="badge badge-success badge-pill{{ $carrier['default'] ? '' : ' d-none' }}">Standaard verzending</span>
        <a href="#" onclick="deleteCarrierModal('{{ $carrierKey }}', '{{ $carrier['name'] }}')" class="btn btn-danger btn-sm btn-rounded float-right">delete</a>
        <a href="#" onclick="editCarrierModal('{{ $carrierKey }}')" class="btn btn-secondary btn-sm btn-rounded mr-2 float-right">edit</a>
      </h6>
      <p>Kostprijs: {{ (float)$carrier['cost'] > 0 ? ChuckEcommerce::formatPrice($carrier['cost']) : 'Free shipping' }}</p>
      <p>Beschikbaar in:</p>
      <ul>
        @foreach($carrier['countries'] as $country)
        <li>{{ config('chuckcms-module-ecommerce.countries')[$country] }}</li>
        @endforeach
      </ul>
  </div>
</div>
@include('chuckcms-module-ecommerce::backend.settings.shipping._edit_modal', ['carrierKey' => $carrierKey, 'carrier' => $carrier])
<hr>
@endforeach
<div class="row column-seperation">
  <div class="col-lg-12">
        <button class="btn btn-primary" type="button" data-target="#createCollectionModal" data-toggle="modal">Verzendmethode Toevoegen</button>
  </div>
</div>
@include('chuckcms-module-ecommerce::backend.settings.shipping._create_modal')
@include('chuckcms-module-ecommerce::backend.settings.shipping._delete_modal')
@endsection