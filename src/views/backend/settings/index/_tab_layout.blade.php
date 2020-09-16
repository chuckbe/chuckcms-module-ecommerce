@extends('chuckcms-module-ecommerce::backend.settings.index')

@section('tab')
<div class="row column-seperation">
  <div class="col-lg-12">
      <div class="form-group required">
        <label>Template</label>
        <select class="form-control" name="layout[template]" required>
          @foreach($templates as $tmpl)
            <option value="{{ $tmpl->id }}" {{ array_key_exists('layout', $module->json['settings']) ? $module->json['settings']['layout']['template'] == $tmpl->slug ? 'selected' : '' : '' }}>{{ $tmpl->name }} (v{{ $tmpl->version }})</option>
          @endforeach
        </select>
      </div>
  </div>
</div>
@endsection