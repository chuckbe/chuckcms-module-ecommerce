<!-- Modal -->
<div class="modal fade stick-up disable-scroll" id="createAttributeModal" tabindex="-1" role="dialog" aria-hidden="false">
<div class="modal-dialog ">
  <div class="modal-content-wrapper">
    <div class="modal-content">
      <div class="modal-header clearfix text-left">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
        </button>
        <h5>Maak een nieuw <span class="semi-bold">attribuut</span> aan</h5>
        <p class="p-b-10">Vul de volgende velden aan om het attribuut aan te maken.</p>
        @if($errors->any())
          @foreach ($errors->all() as $error)
            <p class="text-danger">{{ $error }}</p>
          @endforeach
        @endif
      </div>
      <div class="modal-body">
        <form role="form" method="POST" action="{{ route('dashboard.module.ecommerce.attributes.save') }}">
          <div class="form-group-attached">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Naam</label>
                  <input type="text" id="create_attribute_name" name="name" class="form-control" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default form-group-default-select2 required">
                  <label>Type </label>
                  <select class="full-width" name="type" data-init-plugin="select2" data-minimum-results-for-search="Infinity" data-placeholder="Selecteer een type" data-allow-clear="true" required>
                      <option value="select">Dropdown keuzelijst</option>
                      <option value="radio">Keuzerondjes</option>
                      <option value="color">Kleur</option>
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