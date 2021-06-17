@extends('chuckcms-module-ecommerce::backend.settings.index')

@section('tab')
<div class="row column-seperation">
    <div class="col-lg-12">
        <div class="form-group form-group-default required">
          <label>Bestelling volgnummer</label>
          <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="order[number]" value="{{ array_key_exists('order', $module->json['settings']) ? $module->json['settings']['order']['number'] : '0' }}" placeholder="Bestelling volgnummer" required>
        </div>
        <div class="form-group form-group-default required">
          <label>Minimum bestelbedrag</label>
          <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="order[minimum]" value="{{ array_key_exists('order', $module->json['settings']) ? $module->json['settings']['order']['minimum'] : '0' }}" placeholder="Minimum bestelbedrag" required>
        </div>
        <div class="form-group form-group-default required">
          <label>Actieve landen</label>
          <select class="form-control" name="order[countries]" multiple required>
            @foreach(config('chuckcms-module-ecommerce.countries') as $countryKey => $country)
              <option value="{{ $countryKey }}" {{ array_key_exists('order', $module->json['settings']) ? in_array($countryKey, $module->json['settings']['order']['countries']) ? 'selected' : '' : '' }}>{{ $country }}</option>
            @endforeach
          </select>
        </div>
    </div>
    <div class="col-sm-12">
        <hr>
    </div>



    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col">Titel</th>
                        <th scope="col">E-mail?</th>
                        <th scope="col">Betaald?</th>
                        <th scope="col">Geleverd?</th>
                        <th scope="col">Factuur?</th>
                        <th scope="col" style="min-width:170px">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(ChuckEcommerce::getSetting('order.statuses') as $statusKey => $status)
                    <tr class="order_status_line" data-key="{{ $statusKey }}">
                        <td>{{ $status['display_name'][ChuckSite::getFeaturedLocale()] }} <span class="badge badge-secondary badge-pill">{{ $status['short'][ChuckSite::getFeaturedLocale()] }}</span></td>
                        <td><span class="badge badge-{{ $status['send_email'] ? 'success' : 'danger' }} badge-pill">{{ $status['send_email'] ? '✓' : '✕' }}</span></td>
                        <td><span class="badge badge-{{ $status['paid'] ? 'success' : 'danger' }} badge-pill">{{ $status['paid'] ? '✓' : '✕' }}</span></td>
                        <td><span class="badge badge-{{ $status['delivery'] ? 'success' : 'danger' }} badge-pill">{{ $status['delivery'] ? '✓' : '✕' }}</span></td>
                        <td><span class="badge badge-{{ $status['invoice'] ? 'success' : 'danger' }} badge-pill">{{ $status['invoice'] ? '✓' : '✕' }}</span></td>
                        <td>
                            @can('edit forms')
                            <a href="{{ route('dashboard.module.ecommerce.settings.index.orders.statuses.edit', ['status' => $statusKey]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
                                <i class="fa fa-pen"></i> edit 
                            </a>
                            @endcan
                            <a href="#" class="btn btn-sm btn-outline-danger rounded d-inline-block form_delete" data-id="0">
                                <i class="fa fa-trash"></i> delete 
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection