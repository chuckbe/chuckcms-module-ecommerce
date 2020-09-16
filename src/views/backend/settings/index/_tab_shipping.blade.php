@extends('chuckcms-module-ecommerce::backend.settings.index')

@section('tab')
@foreach($module->json['settings']['shipping']['carriers'] as $carrierKey => $carrier)
<div class="row column-seperation">
  <div class="col-lg-12">
      <h6>
        <b>{{ $carrier['name'] }}</b> <small>{{ $carrier['transit_time'] }}</small> <span class="badge badge-success badge-pill{{ $carrier['default'] ? '' : ' d-none' }}">Standaard verzending</span>
        <a href="#" onclick="deleteModal(23, '')" class="btn btn-danger btn-sm btn-rounded m-r-20 float-right">
          <i data-feather="trash"></i> delete
        </a>
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
<hr>
@endforeach
<div class="row column-seperation">
  <div class="col-lg-12">
        <button class="btn btn-primary" type="button" data-target="#createCollectionModal" data-toggle="modal">Verzendmethode Toevoegen</button>
  </div>
</div>
@endsection