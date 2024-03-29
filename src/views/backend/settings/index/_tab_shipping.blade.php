@extends('chuckcms-module-ecommerce::backend.settings.index')

@section('tab')
@foreach($module->json['settings']['shipping']['carriers'] as $carrierKey => $carrier)
<div class="row column-seperation">
  <div class="col-lg-12">
      <h6>
        @php
        $langKey = ChuckSite::getFeaturedLocale();
        @endphp
        <b>{{ $carrier['name'][$langKey] }}</b> <small>{{ $carrier['transit_time'][$langKey] }}</small> <span class="badge badge-success badge-pill{{ $carrier['default'] ? '' : ' d-none' }}">Standaard verzending</span>
        <a href="#" onclick="deleteCarrierModal('{{ $carrierKey }}', '{{ $carrier['name'][$langKey] }}')" class="btn btn-danger btn-sm btn-rounded float-right">delete</a>
        <a href="#" onclick="editCarrierModal('{{ $carrierKey }}')" class="btn btn-secondary btn-sm btn-rounded mr-2 float-right">edit</a>
      </h6>
      <p class="mb-1">Kostprijs: {{ (float)$carrier['cost'] > 0 ? ChuckEcommerce::formatPrice($carrier['cost']) : 'Free shipping' }}</p>
      <p class="mb-1">Gratis vanaf: {{ !array_key_exists('free_from', $carrier) ? '/' : ((float)$carrier['free_from'] == 0 || is_null($carrier['free_from']) ? '/' : ChuckEcommerce::formatPrice($carrier['free_from'])) }}</p>

      <p>Min. Gewicht: {{ !array_key_exists('min_weight', $carrier) ? '∞' : ((float)$carrier['min_weight'] == 0 ? '0 kg' : $carrier['min_weight'].' kg') }}</p>
      <p>Max. Gewicht: {{ !array_key_exists('max_weight', $carrier) ? '∞' : ((float)$carrier['max_weight'] == 0 ? '∞' : $carrier['max_weight'].' kg') }}</p>

      @if(array_key_exists('min_cart', $carrier))
      <p>Min. Waarde in Winkelwagen: {{ !array_key_exists('min_cart', $carrier) ? '/' : ((float)$carrier['min_cart'] == 0 ? '/' : ChuckEcommerce::formatPrice($carrier['min_cart'])) }}
      <br>Max. Waarde in Winkelwagen: {{ !array_key_exists('max_cart', $carrier) ? '∞' : ((float)$carrier['max_cart'] == 0 ? '∞' : ChuckEcommerce::formatPrice($carrier['max_cart'])) }}</p>
      @endif

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