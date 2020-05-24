@extends('chuckcms::backend.layouts.admin')

@section('title')
	Instellingen
@endsection

@section('content')
<!-- START CONTAINER FLUID -->
<div class=" container-fluid   container-fixed-lg">

<!-- START card -->
<form action="{{ route('dashboard.settings.save') }}" method="POST">
<div class="card card-transparent">
  <div class="card-header ">
    <div class="card-title">Pas instellingen aan
    </div>
  </div>
  <div class="card-block">
    <div class="row">
      <div class="col-md-12">
		{{-- <h5>Fade effect</h5> Add the class
        <code>fade</code> to the tab panes
        <br>
        <br> --}}
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
              <a href="#" data-toggle="tab" data-target="#layout_setup"><span>Layout</span></a>
            </li>
            <li class="nav-item">
              <a href="#" data-toggle="tab" data-target="#order_setup"><span>Bestellingen</span></a>
            </li>
            <li class="nav-item">
              <a href="#" data-toggle="tab" data-target="#shipping_setup"><span>Verzending</span></a>
            </li>
            <li class="nav-item">
              <a href="#" data-toggle="tab" data-target="#product_setup"><span>Producten</span></a>
            </li>
            <li class="nav-item">
              <a href="#" data-toggle="tab" data-target="#client_setup"><span>Klanten</span></a>
            </li>
            <li class="nav-item">
              <a href="#" data-toggle="tab" data-target="#integrations_setup"><span>Integraties</span></a>
            </li>
          </ul>
          <!-- Tab panes -->
          <div class="tab-content">
            <div class="tab-pane fade show active" id="general_setup">
              
              <div class="row column-seperation">
                <div class="col-lg-12">
                      <div class="form-group form-group-default form-group-default-select2">
                        <label>Actieve Valuta</label>
                        <select class="full-width" data-init-plugin="select2" multiple name="general[currency_main]">
                          @foreach(config('chuckcms-module-ecommerce.currencies') as $currency => $currencyName)
                            <option value="{{$currency}}" @if( in_array($currency, $module->json['settings']['general']['supported_currencies']) ) selected @endif>{{ $currencyName }}</option>
                          @endforeach
                        </select>
                      </div>
                </div>
              </div>

              <div class="row column-seperation">
                <div class="col-lg-12">
                      <div class="form-group form-group-default form-group-default-select2">
                        <label>Hoofdvaluta</label>
                        <select class="full-width" data-init-plugin="select2" name="general[currencies]">
                          @foreach($module->json['settings']['general']['supported_currencies'] as $currency)
                            <option value="{{$currency}}" @if( $currency == $module->json['settings']['general']['featured_currency'] ) selected @endif>{{ config('chuckcms-module-ecommerce.currencies')[$currency] }}</option>
                          @endforeach
                        </select>
                      </div>
                </div>
              </div>

              <div class="row column-seperation">
                <div class="col-md-4">
                      <div class="form-group form-group-default required ">
                        <label>Aantal decimalen *</label>
                        <input type="number" min="0" max="6" step="1" class="form-control" placeholder="Aantal decimalen" name="general[decimals]" value="{{ array_key_exists('general', $module->json['settings']) ? $module->json['settings']['general']['decimals'] : 2 }}" onkeyup="this.value=this.value.replace(/[^\d]/,'')" required>
                      </div>
                </div>
                <div class="col-md-4">
                      <div class="form-group form-group-default required ">
                        <label>Scheidingsteken decimalen *</label>
                        <input type="text" class="form-control" placeholder="Scheidingsteken" name="general[decimals_separator]" value="{{ array_key_exists('general', $module->json['settings']) ? $module->json['settings']['general']['decimals_separator'] : ',' }}" required>
                      </div>
                </div>
                <div class="col-md-4">
                      <div class="form-group form-group-default required ">
                        <label>Scheidingsteken 1000 *</label>
                        <input type="text" class="form-control" placeholder="Scheidingsteken" name="general[thousands_separator]" value="{{ array_key_exists('general', $module->json['settings']) ? $module->json['settings']['general']['thousands_separator'] : '.' }}" required>
                      </div>
                </div>
              </div>
              
            </div>

            <div class="tab-pane fade" id="layout_setup">
              <div class="row column-seperation">
                <div class="col-lg-12">
                    <div class="form-group form-group-default required ">
                      <label>Template</label>
                      <select class="full-width" data-init-plugin="select2" name="layout[template]" data-minimum-results-for-search="-1" required>
                        @foreach($templates as $tmpl)
                          <option value="{{ $tmpl->id }}" {{ array_key_exists('layout', $module->json['settings']) ? $module->json['settings']['layout']['template'] == $tmpl->slug ? 'selected' : '' : '' }}>{{ $tmpl->name }} (v{{ $tmpl->version }})</option>
                        @endforeach
                      </select>
                    </div>
                </div>
              </div>

            </div>



            <div class="tab-pane fade" id="order_setup">
              <div class="row column-seperation">
                <div class="col-lg-12">
                      <div class="form-group form-group-default required ">
                        <label>Minimum bestelbedrag</label>
                        <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="order[minimum]" value="{{ array_key_exists('order', $module->json['settings']) ? $module->json['settings']['order']['minimum'] : '0' }}" placeholder="Minimum bestelbedrag" required>
                      </div>
                      <div class="form-group form-group-default required ">
                        <label>Actieve landen</label>
                        <select class="full-width" data-init-plugin="select2" name="order[countries]" data-minimum-results-for-search="5" multiple required>
                          @foreach(config('chuckcms-module-ecommerce.countries') as $countryKey => $country)
                            <option value="{{ $countryKey }}" {{ array_key_exists('order', $module->json['settings']) ? in_array($countryKey, $module->json['settings']['order']['countries']) ? 'selected' : '' : '' }}>{{ $country }}</option>
                          @endforeach
                        </select>
                      </div>
                </div>
              </div>
            </div>



            <div class="tab-pane fade" id="shipping_setup">
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
            </div>


            

            <div class="tab-pane fade" id="integrations_setup">
              <div class="row">
                <div class="col-sm-12">
                  <h6><b>mollie</b></h6>
                </div>
                <div class="col-lg-12">
                  <div class="form-group form-group-default ">
                    <label>mollie API key</label>
                    <input type="text" class="form-control" placeholder="eg test_XXXXXXXXXXXXXXXXXXXX" name="integrations[mollie][key]" value="{{ array_key_exists('integrations', $module->json['settings']) ? $module->json['settings']['integrations']['mollie']['key'] : '' }}">
                  </div>
                  <div class="form-group form-group-default required ">
                    <label>Actieve betaalmethodes</label>
                    <select class="full-width" data-init-plugin="select2" name="integrations[mollie][methods]" data-minimum-results-for-search="5" multiple required>
                      @foreach(config('chuckcms-module-ecommerce.integrations.mollie.methods') as $methodKey => $method)
                        <option value="{{ $methodKey }}" {{ array_key_exists('integrations', $module->json['settings']) ? in_array($methodKey, $module->json['settings']['integrations']['mollie']['methods']) ? 'selected' : '' : '' }}>{{ $method['display_name'] }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>


          </div>
          <br>
          <p class="pull-right">
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <button type="submit"class="btn btn-success btn-cons pull-right">Opslaan</button>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END card -->
</form>
</div>
<!-- END CONTAINER FLUID -->

@include('chuckcms-module-ecommerce::backend.settings._create_shipping_method_modal')

@endsection

@section('css')
	
@endsection

@section('scripts')
  <script src="{{ URL::to('vendor/laravel-filemanager/js/lfm.js') }}"></script>
	<script>
		$( document ).ready(function() { 
			
      init();

      function init () {
        //init media manager inputs 
        var domain = "{{ URL::to('dashboard/media')}}";
        $('#lfm').filemanager('image', {prefix: domain});

        $('.autonumeric').autoNumeric('init');
      }

		});
	</script>
  @if (session('notification'))
      @include('chuckcms::backend.includes.notification')
  @endif
@endsection