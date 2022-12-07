@extends('chuckcms-module-ecommerce::backend.settings.index')

@section('tab')
<form action="{{ route('dashboard.module.ecommerce.settings.index.integrations.update') }}" method="POST">
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
                <select class="form-control" data-init-plugin="select2" name="integrations[mollie][methods][]" data-minimum-results-for-search="5" multiple required>
                    @foreach(config('chuckcms-module-ecommerce.integrations.mollie.methods') as $methodKey => $method)
                    <option value="{{ $methodKey }}" {{ array_key_exists('integrations', $module->json['settings']) ? in_array($methodKey, $module->json['settings']['integrations']['mollie']['methods']) ? 'selected' : '' : '' }}>{{ $method['display_name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    
    <hr>
    
    <div class="row">
        <div class="col-sm-12">
            <h6><b>banktransfer</b></h6>
        </div>
        <div class="col-lg-12">
            <div class="form-group form-group-default">
                <label class="sr-only" for="">Actief?</label>
                <div class="w-100 d-block mb-lg-1"></div>
                <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="integrations[banktransfer][active]" @if(ChuckEcommerce::getSetting('integrations.banktransfer.active') == true) disabled @endif>
                <label for="banktransfer_active">
                    <input type="checkbox" class="boolean_checkbox_input" id="banktransfer_active" value="{{ ChuckEcommerce::getSetting('integrations.banktransfer.active') == true ? 1 : 0 }}" name="integrations[banktransfer][active]" @if(ChuckEcommerce::getSetting('integrations.banktransfer.active') == true) checked @endif /> Actief?
                </label>
            </div>
            <div class="form-group form-group-default">
                <label>Naam rekeninghouder</label>
                <input type="text" class="form-control" placeholder="eg ChuckCMS" name="integrations[banktransfer][name]" value="{{ ChuckEcommerce::getSetting('integrations.banktransfer.name') }}">
            </div>
            <div class="form-group form-group-default">
                <label>IBAN</label>
                <input type="text" class="form-control" placeholder="eg BE00 0000 0000 0000" name="integrations[banktransfer][iban]" value="{{ ChuckEcommerce::getSetting('integrations.banktransfer.iban') }}">
            </div>
            <div class="form-group form-group-default">
                <label>Bank</label>
                <input type="text" class="form-control" placeholder="eg ChuckCMS" name="integrations[banktransfer][bank]" value="{{ ChuckEcommerce::getSetting('integrations.banktransfer.bank') }}">
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-12">
            <h6><b>dymo printer</b></h6>
        </div>
        <div class="col-lg-12">
            <div class="form-group form-group-default">
                <div class="">
                    <h6 class="font-weight-bold my-3">environment</h6>
                    <p class="mb-2">browser supported: <span data-bind="text: browserSupported, visible: environmentChecked()"></span></p>
                    <p class="mb-2">dymo framework installed: <span data-bind="text: frameworkInstalled, visible: environmentChecked()"></span></p>
                    <p class="mb-2">dymo webservice ready: <span data-bind="text: webServicePresent, visible: environmentChecked()"></span></p>
                    <h6 class="font-weight-bold my-3">printer</h6>
                    <p class="mb-2">
                        Name: 
                        <span data-bind="text: printerName, visible: printerChecked()"></span>
                    </p>
                    <h6 class="font-weight-bold my-3">label</h6>
                    <p class="mb-2">acquired: <span data-bind="text: lebelaAcquired, visible: lebelAjaxComplete()"></span></p>
                    <div>
                        <span data-bind="text: message">Loading</span>
                    </div>
                </div>
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