@extends('chuckcms-module-ecommerce::backend.settings.index')

@section('tab')
<form action="{{ route('dashboard.module.ecommerce.settings.index.layout.update') }}" method="POST">
    <div class="row column-seperation">
        <div class="col-lg-12">
            <div class="form-group required">
                <label>Template</label>
                <select class="form-control" name="layout[template]" required>
                    @foreach($templates as $tmpl)
                    <option value="{{ $tmpl->slug }}" {{ array_key_exists('layout', $module->json['settings']) ? $module->json['settings']['layout']['template'] == $tmpl->slug ? 'selected' : '' : '' }}>{{ $tmpl->name }} (v{{ $tmpl->version }})</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-right">
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <button class="btn btn-outline-success" type="submit">Opslaan</button>
        </div>
    </div>
</form>
@endsection