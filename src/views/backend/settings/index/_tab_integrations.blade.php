@extends('chuckcms-module-ecommerce::backend.settings.index')

@section('tab')
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
      <select class="form-control" data-init-plugin="select2" name="integrations[mollie][methods]" data-minimum-results-for-search="5" multiple required>
        @foreach(config('chuckcms-module-ecommerce.integrations.mollie.methods') as $methodKey => $method)
          <option value="{{ $methodKey }}" {{ array_key_exists('integrations', $module->json['settings']) ? in_array($methodKey, $module->json['settings']['integrations']['mollie']['methods']) ? 'selected' : '' : '' }}>{{ $method['display_name'] }}</option>
        @endforeach
      </select>
    </div>
  </div>
</div>
@endsection