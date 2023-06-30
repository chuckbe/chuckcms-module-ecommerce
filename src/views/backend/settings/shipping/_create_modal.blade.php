<!-- Modal -->
<div class="modal fade stick-up disable-scroll" id="createCollectionModal" tabindex="-1" role="dialog" aria-hidden="false">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Voeg een nieuwe <span class="semi-bold">verzendmethode</span> toe</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="pb-sm">Vul de volgende velden aan om de verzendmethode toe te voegen.</p>
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
                              <input type="text" id="create_collection_name" name="name[{{ $langKey }}]" class="form-control" required>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required">
                              <label>Verzendtijd </label>
                              <input type="text" id="carrier_transit_time" name="transit_time[{{ $langKey }}]" class="form-control" required>
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
                    <input id="main_img_input" name="image" class="img_lfm_input form-control" accept="image/x-png" type="text">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group form-group-default required">
                    <label>Kostprijs (BTW inbegrepen)</label>
                    <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="cost" value="0.000000" placeholder="Kostprijs" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group form-group-default required">
                    <label>Max Prijs Winkelwagen (Vul 0.000000 in om te negeren)</label>
                    <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="max_cart" value="0.000000" placeholder="Maximum waarde winkelwagen" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group form-group-default required">
                    <label>Min Gewicht (Vul 0.000 om te negeren)</label>
                    <input type="text" data-a-dec="." data-a-sep="" data-m-dec="3" data-a-pad=true class="autonumeric form-control" name="min_weight" value="0.000" placeholder="Min Gewicht" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group form-group-default required">
                    <label>Max Gewicht (Vul 0.000 om te negeren)</label>
                    <input type="text" data-a-dec="." data-a-sep="" data-m-dec="3" data-a-pad=true class="autonumeric form-control" name="max_weight" value="0.000" placeholder="Max Gewicht" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group form-group-default required">
                    <label>Gratis vanaf (Vul 0.00 in om te negeren)</label>
                    <input type="text" data-a-dec="." data-a-sep="" data-m-dec="2" data-a-pad=true class="autonumeric form-control" name="free_from" value="0.00" placeholder="Gratis vanaf">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group form-group-default form-group-default-select2">
                    <label>Actief in volgende landen </label>
                    <select class="form-control" name="countries[]" multiple>
                      @foreach(ChuckEcommerce::getSupportedCountries() as $country)
                        <option value="{{ $country }}">{{ config('chuckcms-module-ecommerce.countries')[$country] }}</option>
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
                      <option selected disabled>-- Kies --</option>
                      <option value="true">Ja</option>
                      <option value="false">Nee</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          <div class="row">
            <div class="col-md-12 m-t-10 sm-m-t-10">
              <input type="hidden" name="create">
              <input type="hidden" name="_token" value="{{ Session::token() }}">
              <button type="button" class="btn btn-default m-t-5" data-dismiss="modal" aria-hidden="true">Annuleren</button>
              <button type="submit" class="btn btn-primary m-t-5 pull-right">Aanmaken</button>
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