<!-- Modal -->
<div class="modal fade stick-up disable-scroll" id="createCollectionModal" tabindex="-1" role="dialog" aria-hidden="false">
<div class="modal-dialog ">
  <div class="modal-content-wrapper">
    <div class="modal-content">
      <div class="modal-header clearfix text-left">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
        </button>
        <h5>Voeg een nieuwe <span class="semi-bold">verzendmethode</span> toe</h5>
        <p class="p-b-10">Vul de volgende velden aan om de verzendmethode toe te voegen.</p>
        @if($errors->any())
          @foreach ($errors->all() as $error)
            <p class="text-danger">{{ $error }}</p>
          @endforeach
        @endif
      </div>
      <div class="modal-body">
        <form role="form" method="POST" action="{{ route('dashboard.module.ecommerce.settings.shipping.carrier.save') }}">
          <div class="form-group-attached">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Naam</label>
                  <input type="text" id="create_collection_name" name="name" class="form-control" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Verzendtijd </label>
                  <input type="text" id="carrier_transit_time" name="transit_time" class="form-control" required>
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
                  <img id="mainimgholder" src="" style="margin-top:15px;max-height:100px;">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Kost </label>
                  <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="cost" value="0.000000" placeholder="Kostprijs">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default form-group-default-select2">
                  <label>Actief in volgende landen </label>
                  <select class="full-width" name="countries[]" data-init-plugin="select2" data-minimum-results-for-search="5" multiple data-placeholder="Selecteer landen" data-allow-clear="true">
                    <option></option>
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
                  <select class="full-width" name="default" data-init-plugin="select2" data-minimum-results-for-search="Infinity" data-placeholder="Kies" data-allow-clear="true">
                    <option></option>
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
  </div>
  <!-- /.modal-content -->
</div>
</div>
<style>
  .select2-dropdown {z-index:9999;}
</style>
<!-- /.modal-dialog -->