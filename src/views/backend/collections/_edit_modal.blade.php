<!-- Modal -->
<div class="modal fade stick-up disable-scroll" id="editCollectionModal" tabindex="-1" role="dialog" aria-hidden="false">
<div class="modal-dialog ">
  <div class="modal-content-wrapper">
    <div class="modal-content">
      <div class="modal-header clearfix text-left">
        <h5 class="modal-title">Bewerk de volgende <span class="semi-bold">collectie</span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
          <p class="p-b-10">Bewerk de volgende velden om de collectie te wijzigen.</p>
        </div>
        <form role="form" method="POST" action="{{ route('dashboard.module.ecommerce.collections.save') }}">
          <div class="form-group-attached">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Naam</label>
                  <input type="text" id="edit_collection_name" name="name" class="form-control" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default form-group-default-select2">
                  <label class="">Hoofdcollectie</label>
                  <select class="custom-select" id="edit_collection_parent" name="parent" data-placeholder="Selecteer een collectie" data-minimum-results-for-search="-1" data-init-plugin="select2">
                      <option></option>
                      @foreach($collections as $collection)
                        <option value="{{ $collection->id }}">{{ $collection->json['name'] }}</option>
                      @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default">
                  <label>Afbeelding</label>
                  <div class="input-group">
                    <span class="input-group-btn">
                      <a id="lfm" data-input="edit_collection_image" data-preview="editcollectionimageholder" class="btn btn-primary img_lfm_link" style="color:#FFF">
                        <i class="fa fa-picture-o"></i> Afbeelding
                      </a>
                    </span>
                    <input id="edit_collection_image" name="image" class="img_lfm_input form-control" accept="image/x-png" type="text">
                  </div>
                  <img id="editcollectionimageholder" src="" style="margin-top:15px;max-height:100px;">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default">
                  <input type="hidden" class="edit_collection_checkbox_input_hidden" value="0" name="is_pos_available">
                  <label for="is_pos_available">
                      <input type="checkbox" class="edit_collection_checkbox_input" id="is_pos_available" value="0" name="is_pos_available"/> Is beschikbaar in POS?
                  </label>
                </div>
              </div>
            </div>
          </div>
        <div class="row">
          <div class="col-md-12 m-t-10 sm-m-t-10 pull-right">
            <input type="hidden" id="edit_collection_id" name="id" value="">
            <input type="hidden" name="update">
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <button type="button" class="btn btn-default m-t-5" data-dismiss="modal" aria-hidden="true">Annuleren</button>
            <button type="submit" class="btn btn-primary float-right">Bewerken</button>
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