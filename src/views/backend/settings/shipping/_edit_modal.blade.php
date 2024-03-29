<!-- Modal -->
<div class="modal fade stick-up disable-scroll" id="editCarrierModal_{{ $carrierKey }}" tabindex="-1" role="dialog" aria-hidden="false">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Wijzig de <span class="semi-bold">verzendmethode</span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @if($errors->any())
        @foreach ($errors->all() as $error)
          <p class="text-danger">{{ $error }}</p>
        @endforeach
        @endif
        <form role="form" method="POST" action="{{ route('dashboard.module.ecommerce.settings.shipping.carrier.save') }}">
          <div class="form-group-attached">
            <div class="row">
              <div class="col-sm-12">
                <ul class="nav nav-tabs justify-content-start mb-1"  role="tablist">
                  @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
                    <li class="nav-item" role="presentation">
                      <a href="#" class="nav-link{{ $loop->iteration == 1 ? ' active' : '' }}" @if($loop->iteration == 1) class="active" @endif data-toggle="tab" data-target="#tab_product_{{ $langKey }}"><span>{{ $langValue['name'] }} ({{ $langValue['native'] }})</span></a>
                    </li>
                  @endforeach
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">

                  @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
                  <div class="tab-pane fade show @if($loop->iteration == 1) active @endif" id="tab_product_{{ $langKey }}">
                        <div class="row">
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required">
                              <label>Naam</label>
                              <input type="text" id="create_collection_name" name="name[{{ $langKey }}]" value="{{ $carrier['name'][$langKey] }}" class="form-control" required>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required">
                              <label>Verzendtijd </label>
                              <input type="text" id="carrier_transit_time" name="transit_time[{{ $langKey }}]" value="{{ $carrier['transit_time'][$langKey] }}" class="form-control" required>
                            </div>
                          </div>
                        </div>
                  </div>
                  @endforeach

                </div>
              </div>
            </div>
            

            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default">
                  <label>Logo</label>
                  <div class="input-group">
                    <span class="input-group-btn">
                      <a id="lfm" data-input="main_img_input" data-preview="mainimgholder" class="btn btn-primary img_lfm_link" style="color:#FFF">
                        <i class="fa fa-picture-o"></i> Afbeelding
                      </a>
                    </span>
                    <input id="main_img_input" name="image" value="{{ $carrier['image'] }}" class="img_lfm_input form-control" accept="image/x-png" type="text">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group form-group-default required">
                    <label>Kost </label>
                    <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="cost" value="{{ $carrier['cost'] }}" placeholder="Kostprijs">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group form-group-default required">
                    <label>Max Prijs Winkelwagen (Vul 0 in om te negeren)</label>
                    <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="max_cart" value="{{ $carrier['max_cart'] ?? '0.000000' }}" placeholder="Minimum waarde in winkelwagen" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group form-group-default required">
                    <label>Min Gewicht </label>
                    <input type="text" data-a-dec="." data-a-sep="" data-m-dec="3" data-a-pad=true class="autonumeric form-control" name="min_weight" value="{{ !array_key_exists('min_weight', $carrier) ? '0.000' : $carrier['min_weight'] }}" placeholder="Min Gewicht" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group form-group-default required">
                    <label>Max Gewicht </label>
                    <input type="text" data-a-dec="." data-a-sep="" data-m-dec="3" data-a-pad=true class="autonumeric form-control" name="max_weight" value="{{ !array_key_exists('max_weight', $carrier) ? '0.000' : $carrier['max_weight'] }}" placeholder="Max Gewicht" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group form-group-default required">
                    <label>Gratis vanaf (Vul 0 in om te negeren)</label>
                    <input type="text" data-a-dec="." data-a-sep="" data-m-dec="2" data-a-pad=true class="autonumeric form-control" name="free_from" value="{{ !array_key_exists('free_from', $carrier) ? '0.00' : (is_null($carrier['free_from']) ? '0.00' : $carrier['free_from']) }}" placeholder="Gratis vanaf">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group form-group-default form-group-default-select2">
                    <label>Actief in volgende landen </label>
                    <select class="form-control" name="countries[]" multiple>
                      @foreach(ChuckEcommerce::getSupportedCountries() as $country)
                        @if(in_array($country, $carrier['countries']))
                        <option value="{{ $country }}" selected>{{ config('chuckcms-module-ecommerce.countries')[$country] }}</option>
                        @else
                        <option value="{{ $country }}">{{ config('chuckcms-module-ecommerce.countries')[$country] }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group form-group-default form-group-default-select2">
                    <label>Standaard verzendmethode </label>
                    <select class="form-control" name="default" required>
                      <option disabled>-- Kies --</option>
                      @if($carrier['default'])
                      <option value="true" selected>Ja</option>
                      <option value="false">Nee</option>
                      @else
                      <option value="true">Ja</option>
                      <option value="false" selected>Nee</option>
                      @endif
                    </select>
                  </div>
                </div>
              </div>
            </div>
          <div class="row">
            <div class="col-md-12 m-t-10 sm-m-t-10">
              <input type="hidden" name="update">
              <input type="hidden" name="slug" value="{{ $carrierKey }}">
              <input type="hidden" name="_token" value="{{ Session::token() }}">
              <button type="button" class="btn btn-default m-t-5" data-dismiss="modal" aria-hidden="true">Annuleren</button>
              <button type="submit" class="btn btn-primary m-t-5 pull-right">Wijzigen</button>
            </div>
          </div>
          </form>
        </div>
      </div>
    <!-- /.modal-content -->
  </div>
  </div>
  <style>
    .select2-dropdown {z-index:9999;}
  </style>
  <!-- /.modal-dialog -->