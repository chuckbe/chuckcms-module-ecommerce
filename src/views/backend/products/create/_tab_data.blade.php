<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Bijlage</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="file_input" class="btn btn-primary file_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload bijlage
          </a>
        </span>
        <input id="file_input" name="files[]" class="file_lfm_input form-control" type="text" value="">
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Bijlage</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="file1_input" class="btn btn-primary file_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload bijlage
          </a>
        </span>
        <input id="file1_input" name="files[]" class="file_lfm_input form-control" type="text" value="">
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Bijlage</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="file2_input" class="btn btn-primary file_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload bijlage
          </a>
        </span>
        <input id="file2_input" name="files[]" class="file_lfm_input form-control" type="text" value="">
      </div>
    </div>
  </div>
</div>

<div class="my-3">
    <ul class="nav nav-tabs justify-content-start" role="tablist">
        @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
        <li class="nav-item" role="presentation">
            <a href="#" class="nav-link{{ $loop->iteration == 1 ? ' active' : '' }}" data-toggle="tab" data-target="#tab_product_data_{{ $langKey }}"><span>{{ $langValue['name'] }} ({{ $langValue['native'] }})</span></a>
        </li>
        @endforeach
    </ul>
</div>
<!-- Tab panes -->
<div class="tab-content">
    @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
    <div class="tab-pane fade show @if($loop->iteration == 1) active @endif" id="tab_product_data_{{ $langKey }}">
        <div class="row">
            <div class="col-12">
                <div class="resource_field_wrapper" data-lang="{{ $langKey }}">
                    <div class="row resource_field_row" data-order="1">
                        <div class="col-lg-4">
                            <div class="form-group form-group-default required ">
                                <label>Veld Key</label>
                                <input type="text" class="form-control resource_key" placeholder="key" id="resource_key" name="resource_key[{{ $langKey }}][]" data-order="1">
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group form-group-default required ">
                                <label>Veld Waarde</label>
                                <input type="text" class="form-control resource_value" placeholder="waarde" id="resource_value" name="resource_value[{{ $langKey }}][]" data-order="1"> 
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-primary add_resource_field_btn" id="add_resource_field_btn">+ Toevoegen</button>
                    </div>
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-warning remove_resource_field_btn" id="remove_resource_field_btn" style="display:none;">- Verwijderen</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
