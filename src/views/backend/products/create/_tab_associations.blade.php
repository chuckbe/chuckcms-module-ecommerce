<div class="row">
  <div class="col-sm-6">
    <div class="form-group form-group-default form-group-default-select2 required">
      <label>Collectie *</label>
      <select class="custom-select" name="collection[]" data-init-plugin="select2" data-minimum-results-for-search="5" data-placeholder="Selecteer een collectie" multiple="multiple" required>
        <option disabled>Selecteer een collectie</option>
        @foreach($collections as $collection)
          <option value="{{ $collection->id }}" @if( (is_array(old('collection')) && in_array($collection->id, old('collection'))) || old('collection') == $collection->id ) selected @endif>{{ $collection->json['name'] }} {{ $collections->where('id', $collection->json['parent'])->first() ? '('.$collections->where('id', $collection->json['parent'])->first()->json['name'].')' : '' }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="form-group form-group-default form-group-default-select2">
      <label>Merk/Fabrikant *</label>
      <select class="custom-select" name="brand" data-init-plugin="select2" data-minimum-results-for-search="Infinity" data-placeholder="Selecteer een merk" data-allow-clear="true">
        <option disabled>Selecteer een merk</option>
        @foreach($brands as $brand)
          <option value="{{ $brand->id }}" @if(old('brand') == $brand->id) selected @endif>{{ $brand->json['name'] }}</option>
        @endforeach
      </select>
    </div>
  </div>
</div>