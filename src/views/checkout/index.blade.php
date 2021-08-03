@extends($template->hintpath.'::templates.' . $template->slug . '.layouts.raw')

@section('title')
    Checkout
@endsection

@section('meta')
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    @php 
    	$lang = \LaravelLocalization::getCurrentLocale();
    @endphp

    <link rel="canonical" href="{{ ChuckSite::getSetting('domain') . '/shopping-cart' }}">
    <meta property="og:url" content="{{ ChuckSite::getSetting('domain') . '/shopping-cart' }}">
    <meta name="twitter:url" content="{{ ChuckSite::getSetting('domain') . '/shopping-cart' }}">

    @if(ChuckSite::getSite('name') !== null)
    <meta property="og:site_name" content="{{ ChuckSite::getSite('name') }}">
    @endif
    <meta property="og:locale" content="{{ $lang }}">
@endsection

@section('css')
<style>
.container {
  max-width: 960px;
}

.lh-condensed { line-height: 1.25; }

.custom-select.is-invalid, .form-control.is-invalid, .was-validated .custom-select:invalid, .was-validated .form-control:invalid { border-color: #dc3545!important; }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('body').on('change', '#ce_shippingEqualToBilling', function (event) {
        var shippingAddress = $('#ce_shippingAddress');

        if (this.checked) {
            shippingAddress.addClass('d-none');
            $('.shipping_address_input').prop('disabled', true);
            $('.shipping_address_input').prop('required', false);
        } else {
            shippingAddress.removeClass('d-none');
            $('.shipping_address_input').prop('disabled', false);
            $('.shipping_address_input').prop('required', true);
        }
    });

    $('body').on('change', '#ce_checkoutAsGuest', function (event) {
        var accountDetails = $('#ce_checkoutAccountDetails');

        if (this.checked) {
            accountDetails.addClass('d-none');
            $('.account_details_input').prop('disabled', true);
            $('.account_details_input').prop('required', false);
        } else {
            accountDetails.removeClass('d-none');
            $('.account_details_input').prop('disabled', false);
            $('.account_details_input').prop('required', true);
        }
    });

    $('body').on('change', '.country_select_input', function (event) {
        if($('#ce_shippingEqualToBilling').is(':checked')) {
            var country = $('#country').val();
        } else {
            var country = $('#shipping_country').val();
        }

        checkCarrierAvailability(country);
    });

    function checkCarrierAvailability(country) {
        var reset_check = false;

        $('input[name="shippingMethod"]').each(function(element) {
            available_in = $(this).attr('data-carrier-countries');
            
            if ($.inArray(country, available_in.split('|')) === -1) {
                if($(this).is(':checked')) {
                    $(this).prop('checked', false);
                    reset_check = true;
                }
                $(this).prop('disabled', true);
            } else {
                $(this).prop('disabled', false);
            }
        });

        if (reset_check) {
            var flow_for_check = true;
            $('input[name="shippingMethod"]').each(function(element) {
                if(!$(this).is(':disabled') && flow_for_check) {
                    $(this).prop('checked', true);
                    $(this).trigger('change');
                    flow_for_check = false;
                }
            });
        }
    }

    $('body').on('change', 'input[name="shippingMethod"]', function (event) {
        var subtotal_price = $('.ce_checkoutSubtotalPrice').attr('data-sub-total');
        var shipping_method = $(this).val();
        var shipping_price = $(this).attr('data-carrier-cost');
        var shipping_price_tax = $(this).attr('data-carrier-cost-tax');

        $('.ce_checkoutShippingPrice').attr('data-shipping-price', parseFloat(shipping_price));
        $('.ce_checkoutShippingPrice').attr('data-tax-shipping', parseFloat(shipping_price_tax));
        if (parseFloat(shipping_price) > 0) {
            $('.ce_checkoutShippingPrice').text('€ ' + parseFloat(shipping_price).toFixed(2).replace('.',','));
        } else {
            $('.ce_checkoutShippingPrice').text('gratis');
        }

        var new_total_price = Number(subtotal_price) + Number(shipping_price);
        $('.ce_checkoutTotalPrice').text('€ ' + new_total_price.toFixed(2).replace('.',',')); //@TODO: FIX THIS

        var tax_price = $('.ce_checkoutTaxPrice').attr('data-tax-price');
        $('.ce_checkoutTaxPrice').text('€ ' + (parseFloat(tax_price) + parseFloat(shipping_price_tax)).toFixed(2).replace('.',','));
    });

    $('body').on('click', '#ce_checkoutFinalOrderBtn', function (event) {
        $(this).html('<i class="far fa-sync-alt fa-spin"></i> Even geduld U wordt zo dadelijk doorverwezen');
        $(this).prop('disabled', true);

        if( !validateForm() ) {
            $(this).html('Bestelling bevestigen en betalen');
            $(this).prop('disabled', false);
            return false;
        }

        placeOrder();
    });

    function validateForm() {
        $('.form-control').removeClass('is-invalid');
        addTaxToCart();

        form_element = document.getElementById('ce_checkoutFormElement');
        form_element.classList.remove('was-validated');

        if( form_element.checkValidity() === false ) {
            form_element.classList.add('was-validated');
            return false;
        }

        form_element.classList.add('was-validated');

        form_element = $('#ce_checkoutFormElement');

        var countries_data = JSON.parse('{!! json_encode(config('chuckcms-module-ecommerce.countries_data')) !!}');
        var default_country = "{{ config('chuckcms-module-ecommerce.default_country') }}";
        postalcode = $('#postalcode').val();
        shipping_postalcode = $('#shipping_postalcode').val();
        country = $('#country').val();
        shipping_country = $('#shipping_country').val();
        final_shipping_country = $('#country').val();

        $('#postalcode').val(postalcode.replace(/[_\s]/g, ''));
        zip = new RegExp(countries_data[country]['postalcode']['regex']);
        if( !zip.test($('#postalcode').val()) ) {
            form_element.removeClass('was-validated');
            $('#postalcode').addClass('is-invalid');
            return false;
        }

        if ($('#ce_shippingEqualToBilling:not(:checked)').length > 0) {
            final_shipping_country = $('#shipping_country').val();
            $('#shipping_postalcode').val(shipping_postalcode.replace(/[_\s]/g, ''));
            zip = new RegExp(countries_data[shipping_country]['postalcode']['regex']);
            if( !zip.test($('#shipping_postalcode').val()) ) {
                form_element.removeClass('was-validated');
                $('#shipping_postalcode').addClass('is-invalid');
                return false;
            }
        }
        

        if ( $('#company').val().length > 0 ) {
            vat = new RegExp(countries_data[country]['vat']['regex']);
            if( !vat.test(document.getElementById('companyVat').value) ) {
                form_element.removeClass('was-validated');
                $('.company_vat_feedback_country').text(countries_data[country]['native']);
                $('.company_vat_feedback_format').text(countries_data[country]['vat']['format']);
                $('#companyVat').addClass('is-invalid');
                return false;
            } else {
                if( (String(document.getElementById('companyVat').value).indexOf(country) == -1) ) {
                    form_element.removeClass('was-validated');
                    $('#companyVat').addClass('is-invalid');
                    $('.company_vat_feedback_country').text(countries_data[country]['native']);
                    $('.company_vat_feedback_format').text(countries_data[country]['vat']['format']);
                    return false;
                }

                if ( final_shipping_country !== default_country ) {
                    removeTaxFromCart();
                }
            }
        }

        return true;
    }

    function placeOrder () {

        if($('input[name=promo_approval]').is(':checked')) {
            promo = "1";
        } else {
            promo = "0";
        }

        if($('#ce_checkoutAsGuest').length > 0) {
            if($('#ce_checkoutAsGuest').is(':checked')) {
                check_out_as_guest = "1";
            } else {
                check_out_as_guest = "0";
            }
        } else {
            check_out_as_guest = "-1";
        }

        if($('input[type=checkbox][name=customer_shipping_equal_to_billing]').is(':checked')) {
            customer_shipping_equal_to_billing = "1";
        } else {
            customer_shipping_equal_to_billing = "0";
        }

        $.ajax({
            method: 'POST',
            url: "{{ route('module.ecommerce.checkout.finalize') }}",
            data: { 
                customer_surname: $('input[name=customer_surname]').val(), 
                customer_name: $('input[name=customer_name]').val(),
                customer_email: $('input[name=customer_email]').val(),
                customer_tel: $('input[name=customer_tel]').val(),
                
                customer_street: $('input[name=customer_street]').val(),
                customer_housenumber: $('input[name=customer_housenumber]').val(),
                customer_postalcode: $('input[name=customer_postalcode]').val(),
                customer_city: $('input[name=customer_city]').val(),
                customer_country: $('select[name=customer_country]').val(),
                
                customer_company_name: $('input[name=customer_company_name]').val(),
                customer_company_vat: $('input[name=customer_company_vat]').val(),

                customer_shipping_equal_to_billing: customer_shipping_equal_to_billing,

                customer_shipping_street: $('input[name=customer_shipping_street]').val(),
                customer_shipping_housenumber: $('input[name=customer_shipping_housenumber]').val(),
                customer_shipping_postalcode: $('input[name=customer_shipping_postalcode]').val(),
                customer_shipping_city: $('input[name=customer_shipping_city]').val(),
                customer_shipping_country: $('select[name=customer_shipping_country]').val(),

                check_out_as_guest: check_out_as_guest,

                customer_password: (check_out_as_guest == "0") ? $('input[name=customer_password]').val() : null,
                customer_password_repeat: (check_out_as_guest == "0") ? $('input[name=customer_password_repeat]').val() : null,

                shipping_method: $('input[name=shippingMethod]:checked').val(),
                payment_method: $('input[name=paymentMethod]:checked').val(),
                
                legal_approval: $('input[name=legal_approval]').val(),
                promo_approval: promo,
                
                _token: a_token
            }
        })
        .done(function(data) {
            if (data.status == "availability"){
                console.log(data.notification);

                $('.ce_checkoutAlertItemsOutOfStockItem:not(:first)').remove();
                for (var i = 0; i < data.notification.length; i++) {
                    if(i > 0) {
                        $('.ce_checkoutAlertItemsOutOfStockItem:first').clone()
                                        .appendTo($('.ce_checkoutAlertItemsOutOfStockList'));
                    }

                    $('.ce_checkoutAlertItemsOutOfStockItem:last').html(data.notification[i]['name'] + ' - <b>' + data.notification[i]['availability'] + ' beschikbaar</b>');
                };
                $('.ce_checkoutAlertItemsOutOfStock').removeClass('d-none');
                $('#ce_checkoutFinalOrderBtn').html('Bestelling bevestigen en betalen');
                $('#ce_checkoutFinalOrderBtn').prop('disabled', false);

            }

            if (data.status == "success"){
                $('#ce_checkoutFinalOrderBtn').html('U wordt doorverwezen...');
                window.location.href = data.url;
            }
            else{
                $(this).html('Bestellen');
                $(this).prop('disabled', false);

                $('.error_span:first').html(' Er is iets misgelopen, probeer het later nog eens!');
                $('.error_bag:first').removeClass('hidden');
            }
        });
    }

    function removeTaxFromCart () { //@TODO: ADD DISCOUNT PRICE SWAP
        subtotal = $('.ce_checkoutSubtotalPrice').attr('data-sub-total');
        subtotal_no_tax = $('.ce_checkoutSubtotalPrice').attr('data-sub-total-no-tax');
        $('.ce_checkoutSubtotalPrice').text('€ ' + parseFloat(subtotal).toFixed(2).replace('.',','));

        shipping = parseFloat($('.ce_checkoutShippingPrice').attr('data-shipping-price')) - parseFloat($('.ce_checkoutShippingPrice').attr('data-tax-shipping'));
        if(parseFloat(shipping) > 0) {
            $('.ce_checkoutShippingPrice').text('€ ' + parseFloat(shipping).toFixed(2).replace('.',','));
        } else {
            $('.ce_checkoutShippingPrice').text('gratis');
        }

        tax = parseFloat(0);
        $('.ce_checkoutTaxPrice').text('€ ' + parseFloat(tax).toFixed(2).replace('.',','));

        total = parseFloat(subtotal_no_tax);
        $('.ce_checkoutTotalPrice').text('€ ' + parseFloat(total).toFixed(2).replace('.',','));
    }

    function addTaxToCart () { //@TODO: ADD DISCOUNT PRICE SWAP
        total = $('.ce_checkoutSubtotalPrice').attr('data-total');
        subtotal = $('.ce_checkoutSubtotalPrice').attr('data-sub-total');
        $('.ce_checkoutSubtotalPrice').text('€ ' + parseFloat(total).toFixed(2).replace('.',','));

        shipping = $('.ce_checkoutShippingPrice').attr('data-shipping-price');
        if(parseFloat(shipping) > 0) {
            $('.ce_checkoutShippingPrice').text('€ ' + parseFloat(shipping).toFixed(2).replace('.',','));
        } else {
            $('.ce_checkoutShippingPrice').text('gratis');
        }

        tax = parseFloat($('.ce_checkoutTaxPrice').attr('data-tax-price')) + parseFloat($('.ce_checkoutShippingPrice').attr('data-tax-shipping'));
        $('.ce_checkoutTaxPrice').text('€ ' + parseFloat(tax).toFixed(2).replace('.',','));

        final_price = parseFloat(subtotal) + parseFloat($('.ce_checkoutShippingPrice').attr('data-shipping-price'));
        $('.ce_checkoutTotalPrice').text('€ ' + parseFloat(final_price).toFixed(2).replace('.',','));
    }
});
</script>
<script>
(function() {

    const idleDurationSecs = 1200;    // X number of seconds
    const redirectUrl = '/shopping-cart';  // Redirect idle users to this URL
    let idleTimeout; // variable to hold the timeout, do not modify

    const resetIdleTimeout = function() {

        // Clears the existing timeout
        if(idleTimeout) clearTimeout(idleTimeout);

        // Set a new idle timeout to load the redirectUrl after idleDurationSecs
        idleTimeout = setTimeout(() => location.href = redirectUrl, idleDurationSecs * 1000);
    };

    // Init on page load
    resetIdleTimeout();

    // Reset the idle timeout on any of the events listed below
    ['click', 'touchstart', 'mousemove'].forEach(evt => 
        document.addEventListener(evt, resetIdleTimeout, false)
    );

})();
</script>
@endsection

@section('content')

<div class="container">
  <div class="py-5 text-left">
    <img class="d-block mb-4" src="{{ asset(ChuckSite::getSetting('logo.href')) }}" width="150" alt="Logo">
    {{-- <h2>Checkout</h2> --}}
    <p class="lead">Vul uw gegevens in om uw bestelling te vervolledigen.</p>
  </div>

  <div class="row">
    <div class="col-md-4 order-md-2 mb-4">
      <h4 class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-muted">Shopping Cart</span>
        <span class="badge badge-secondary badge-pill">{{ ChuckCart::instance('shopping')->content()->count() }}</span>
      </h4>
      <ul class="list-group mb-3">
        @foreach(ChuckCart::instance('shopping')->content() as $item)
        <li class="list-group-item d-flex justify-content-between lh-condensed">
          <div>
            <h6 class="my-0">{{ $item->name }}</h6>
            <small class="text-muted">
                @foreach($item->options as $oKey => $oValue)
                {{ ($loop->first ? '(' : '') . $oKey }}: {{ $oValue . ($loop->last ? ')' : ', ') }} 
                @endforeach
            </small><br>
            <small class="text-muted">
            @foreach($item->extras as $eKey => $eValue)
            {{ $eValue['qty'].'x '.$eKey }}{!! !$loop->last ? '<br>' : '' !!}
            @endforeach 
          </small>
            <div class="w-100"></div>
            <hr class="my-0 mt-1">
            <small class="text-muted">{{ $item->qty }}x {{ ChuckEcommerce::formatPrice($item->_unit) }}</small>
          </div>
          <span class="text-muted">{{ ChuckEcommerce::formatPrice($item->_total) }}</span>
        </li>
        @endforeach


        <li class="list-group-item d-flex justify-content-between">
          <span>Subtotaal (EUR)</span>
          <strong class="ce_checkoutSubtotalPrice" data-sub-total="{{ ChuckCart::instance('shopping')->final() }}" data-total="{{ ChuckCart::instance('shopping')->total() }}" data-sub-total-no-tax="{{ (ChuckCart::instance('shopping')->final() - ChuckCart::instance('shopping')->tax()) }}">{{ ChuckEcommerce::formatPrice(ChuckCart::instance('shopping')->total()) }}</strong>
        </li>

        @if(ChuckCart::instance('shopping')->hasDiscount())
        <li class="list-group-item d-flex justify-content-between">
          <span>Korting (EUR)</span>
          <strong class="ce_checkoutDiscountPrice" data-discount="{{ ChuckCart::instance('shopping')->discount() }}">-{{ ChuckEcommerce::formatPrice(ChuckCart::instance('shopping')->discount()) }}</strong>
        </li>
        @endif

        <li class="list-group-item d-flex justify-content-between">
          <span>Verzending (EUR)</span>
          <strong class="ce_checkoutShippingPrice" data-tax-shipping="{{ ChuckEcommerce::taxFromPrice(ChuckEcommerce::getDefaultShippingPriceForCart('shopping'), 21) }}" data-shipping-price="{{ ChuckEcommerce::getDefaultShippingPriceForCart('shopping') }}">{{ (float)ChuckEcommerce::getDefaultShippingPriceForCart('shopping') > 0 ?ChuckEcommerce::formatPrice(ChuckEcommerce::getDefaultShippingPriceForCart('shopping')) : 'gratis' }}</strong>
        </li>

        <li class="list-group-item d-flex justify-content-between">
            <span>Totaal (EUR) <br> 
                <small>
                <span class="ce_checkoutTaxPrice" data-tax-price="{{ ChuckCart::instance('shopping')->tax() }}">{{ ChuckEcommerce::formatPrice(ChuckCart::instance('shopping')->tax() + ChuckEcommerce::taxFromPrice(ChuckEcommerce::getDefaultShippingPriceForCart('shopping'), 21)) }}</span> BTW inbegrepen. 
                </small>
            </span>
          <strong class="ce_checkoutTotalPrice">{{ ChuckEcommerce::formatPrice(ChuckCart::instance('shopping')->final() + ChuckEcommerce::getDefaultShippingPriceForCart('shopping')) }}</strong>
        </li>
      </ul>

    </div>
    <div class="col-md-8 order-md-1">
      <h4 class="mb-3">Facturatie adres</h4>
      <form class="needs-validation" id="ce_checkoutFormElement" novalidate>
        <div class="row">
          <div class="col-sm-6 mb-3">
            <label for="firstName">Voornaam *</label>
            <input type="text" class="form-control" id="firstName" name="customer_surname" placeholder="" value="{{ Auth::check() ? ChuckCustomer::get()->surname : old('customer_surname') }}" {{ Auth::check() ? 'readonly' : '' }} required>
            <div class="invalid-feedback">
              Voornaam is een verplicht veld.
            </div>
          </div>
          <div class="col-sm-6 mb-3">
            <label for="lastName">Achternaam *</label>
            <input type="text" class="form-control" id="lastName" name="customer_name" placeholder="" value="{{ Auth::check() ? ChuckCustomer::get()->name : old('customer_name') }}" {{ Auth::check() ? 'readonly' : '' }} required>
            <div class="invalid-feedback">
              Achternaam is een verplicht veld.
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="email">Email *</label>
          <input type="email" class="form-control" id="email" name="customer_email" placeholder="jouwnaam@voorbeeld.com" value="{{ Auth::check() ? Auth::user()->email : old('customer_email') }}" {{ Auth::check() ? 'readonly' : '' }} required>
          <div class="invalid-feedback">
            Gelieve een geldig e-mailadres in te vullen.
          </div>
        </div>

        


        <div class="row">
            <div class="col-7 mb-3">
                <label for="street">Adres *</label>
                <input type="text" class="form-control" id="street" name="customer_street" placeholder="Straatnaam" value="{{ old('customer_street', ChuckCustomer::address()['street']) }}" required>
                <div class="invalid-feedback">
                    Adres is een verplicht veld.
                </div>
            </div>
            <div class="col-5 mb-3">
                <label for="housenumber">Huisnummer *</label>
                <input type="text" class="form-control" id="housenumber" name="customer_housenumber" placeholder="123" value="{{ old('customer_housenumber', ChuckCustomer::address()['housenumber']) }}" required>
                <div class="invalid-feedback">
                    Huisnummer is een verplicht veld.
                </div>
            </div>
        </div>

        <div class="row">
          <div class="col-md-3 col-sm-6 mb-3">
            <label for="postalcode">Postcode *</label>
            <input type="text" class="form-control" id="postalcode" name="customer_postalcode" placeholder="" value="{{ old('customer_postalcode', ChuckCustomer::address()['postalcode']) }}" required>
            <div class="invalid-feedback">
              Postcode is een verplicht veld.
            </div>
          </div>
          <div class="col-md-4 col-sm-6 mb-3">
            <label for="city">Gemeente *</label>
            <input type="text" class="form-control" id="city" name="customer_city" placeholder="" value="{{ old('customer_city', ChuckCustomer::address()['city']) }}" required>
            <div class="invalid-feedback">
              Gemeente is een verplicht veld.
            </div>
          </div>
          <div class="col-md-5 mb-3">
            <label for="country">Land *</label>
            <select class="custom-select d-block w-100 country_select_input" id="country" name="customer_country" required>
              <option selected disabled>Kies...</option>
              @foreach(ChuckEcommerce::getSetting('order.countries') as $country)
              <option value="{{ $country }}" {{ old('customer_country', ChuckCustomer::address()['country']) == $country ? 'selected' : '' }} >{{ config('chuckcms-module-ecommerce.countries')[$country] }}</option>
              @endforeach
            </select>
            <div class="invalid-feedback">
              Gelieve uw land te selecteren.
            </div>
          </div>
        </div>
        <hr class="mb-3">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="company">Bedrijf</label>
            <input type="text" class="form-control" id="company" name="customer_company_name" placeholder="" value="{{ old('customer_company_name', ChuckCustomer::company()['name']) }}">
          </div>
          <div class="col-md-6 mb-3">
            <label for="companyVat">BTW-nummer</label>
            <input type="text" class="form-control" id="companyVat" name="customer_company_vat" placeholder="" value="{{ old('customer_company_vat', ChuckCustomer::company()['vat']) }}">
            <div class="invalid-feedback">
              Uw BTW-nummer heeft niet het juiste formaat. Voor <span class="company_vat_feedback_country">België</span> is het formaat: <span class="company_vat_feedback_format">BE0123456789</span>
            </div>
          </div>
        </div>
        <hr class="mb-4">
        <div class="custom-control custom-checkbox">
            <input type="hidden" name="customer_shipping_equal_to_billing" value="0">
            <input type="checkbox" class="custom-control-input" name="customer_shipping_equal_to_billing" id="ce_shippingEqualToBilling" value="1" {{ ChuckCustomer::isShippingEqualToBilling() ? 'checked' : '' }}>
            <label class="custom-control-label" for="ce_shippingEqualToBilling">Verzendadres is gelijk aan mijn facturatie adres</label>
        </div>
        <div class="row {{ ChuckCustomer::isShippingEqualToBilling() ? 'd-none' : '' }}" id="ce_shippingAddress">
            <div class="col-sm-12">
                <h4 class="mb-3">Verzendadres</h4>
            </div>
            <div class="col-7 mb-3">
                <label for="shipping_street">Adres *</label>
                <input type="text" class="form-control shipping_address_input" id="shipping_street" name="customer_shipping_street" placeholder="Straatnaam" value="{{ old('customer_shipping_street', ChuckCustomer::address(false)['street']) }}" {{ ChuckCustomer::isShippingEqualToBilling() ? 'disabled' : 'required' }}>
                <div class="invalid-feedback">
                    Adres is een verplicht veld.
                </div>
            </div>
            <div class="col-5 mb-3">
                <label for="shipping_housenumber">Huisnummer *</label>
                <input type="text" class="form-control shipping_address_input" id="shipping_housenumber" name="customer_shipping_housenumber" placeholder="123" value="{{ old('customer_shipping_housenumber', ChuckCustomer::address(false)['housenumber']) }}" {{ ChuckCustomer::isShippingEqualToBilling() ? 'disabled' : 'required' }}>
                <div class="invalid-feedback">
                    Huisnummer is een verplicht veld.
                </div>
            </div>
          <div class="col-md-3 col-sm-6 mb-3">
            <label for="shipping_postalcode">Postcode *</label>
            <input type="text" class="form-control shipping_address_input" id="shipping_postalcode" placeholder="" name="customer_shipping_postalcode" value="{{ old('customer_shipping_postalcode', ChuckCustomer::address(false)['postalcode']) }}" {{ ChuckCustomer::isShippingEqualToBilling() ? 'disabled' : 'required' }}>
            <div class="invalid-feedback">
              Postcode is een verplicht veld.
            </div>
          </div>
          <div class="col-md-4 col-sm-6 mb-3">
            <label for="shipping_city">Gemeente *</label>
            <input type="text" class="form-control shipping_address_input" id="shipping_city" placeholder="" name="customer_shipping_city" value="{{ old('customer_shipping_city', ChuckCustomer::address(false)['city']) }}" {{ ChuckCustomer::isShippingEqualToBilling() ? 'disabled' : 'required' }}>
            <div class="invalid-feedback">
              Gemeente is een verplicht veld.
            </div>
          </div>
          <div class="col-md-5 mb-3">
            <label for="shipping_country">Land *</label>
            <select class="custom-select d-block w-100 shipping_address_input country_select_input" id="shipping_country" name="customer_shipping_country" {{ ChuckCustomer::isShippingEqualToBilling() ? 'disabled' : 'required' }}>
              <option value="">Kies...</option>
              @foreach(ChuckEcommerce::getSetting('order.countries') as $country)
              <option value="{{ $country }}" {{ old('customer_shipping_country', ChuckCustomer::address(false)['country']) == $country ? 'selected' : '' }}>{{ config('chuckcms-module-ecommerce.countries')[$country] }}</option>
              @endforeach
            </select>
            <div class="invalid-feedback">
              Gelieve uw land te selecteren.
            </div>
          </div>
        </div>

        @if(!Auth::check())
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="ce_checkoutAsGuest" checked>
          <label class="custom-control-label" for="ce_checkoutAsGuest">Afrekenen als gast</label>
        </div>
        <div class="row d-none" id="ce_checkoutAccountDetails">
            <div class="col-sm-7 mb-3">
                <label for="password">Kies een sterk wachtwoord *</label>
                <input type="password" class="form-control account_details_input" name="customer_password" id="password" disabled>
                <div class="invalid-feedback">
                    Wachtwoord is een verplicht veld.
                </div>
            </div>
            <div class="col-sm-5 mb-3">
                <label for="password_repeat">Herhaal wachtwoord *</label>
                <input type="password" class="form-control account_details_input" name="customer_password_repeat" id="password_repeat" disabled>
                <div class="invalid-feedback">
                    Veld moet gelijk zijn aan gekozen wachtwoord.
                </div>
            </div>
        </div>
        @endif

        <hr class="mt-3 mb-4">

        <h4 class="mb-3">Verzendmethode</h4>

        <div class="d-block my-3">
            @foreach(ChuckEcommerce::getCarriersForCart('shopping') as $carrierKey => $carrier)
            <div class="custom-control custom-radio">
                <input id="{{ $carrierKey }}" name="shippingMethod" value="{{ $carrierKey }}" type="radio" class="custom-control-input" data-carrier-key="{{ $carrierKey }}" data-carrier-cost="{{ ChuckEcommerce::getCarrierTotalForCart($carrierKey, 'shopping') }}" data-carrier-min-weight="{{ array_key_exists('min_weight', $carrier) ? $carrier['min_weight'] : '0.000' }}" data-carrier-max-weight="{{ array_key_exists('max_weight', $carrier) ? $carrier['max_weight'] : '0.000' }}" data-carrier-countries="{{ implode('|',$carrier['countries']) }}" data-carrier-cost-tax="{{ ChuckEcommerce::taxFromPrice(ChuckEcommerce::getCarrierTotalForCart($carrierKey, 'shopping'), 21) }}" {{ $carrier['default'] || $loop->count == 1 ? 'checked' : '' }} required>
                <label class="custom-control-label" for="{{ $carrierKey }}">{{ $carrier['name'] }} ({{ $carrier['transit_time'] }}) — {{ (float)ChuckEcommerce::getCarrierTotalForCart($carrierKey, 'shopping') > 0 ? ChuckEcommerce::formatPrice(ChuckEcommerce::getCarrierTotalForCart($carrierKey, 'shopping')) : 'free' }}</label>
            </div>
            @endforeach
          
        </div>

        <hr class="mb-4">

        <h4 class="mb-3">Betaling</h4>

        <p>Alle transacties worden beveiligd en versleuteld.</p>

        <div class="d-block my-3">
            @foreach(ChuckEcommerce::getSetting('integrations.mollie.methods') as $method)
            <div class="custom-control custom-radio mt-2">
                <input id="{{ $method }}" name="paymentMethod" value="{{ $method }}" type="radio" class="custom-control-input" {{ $loop->first ? 'checked' : '' }} required>
                <label class="custom-control-label" for="{{ $method }}"><img src="{{ asset(config('chuckcms-module-ecommerce.integrations.mollie.methods.'.$method.'.logo')) }}" alt="{{ config('chuckcms-module-ecommerce.integrations.mollie.methods.'.$method.'.display_name') }} logo" width="30"> {{ config('chuckcms-module-ecommerce.integrations.mollie.methods.'.$method.'.display_name') }}</label>
            </div>
            @endforeach
        </div>
        <hr class="mb-4">
        <div class="custom-control custom-checkbox mb-2">
          <input type="checkbox" class="custom-control-input" id="ce_checkoutAcceptPolicy" name="legal_approval" required>
          <label class="custom-control-label" for="ce_checkoutAcceptPolicy">Ik bevestig hierbij de bestelling en bevestig dat ik instem met de algemene voorwaarden.</label>
        </div>
        <div class="alert alert-warning ce_checkoutAlertItemsOutOfStock mb-2 d-none" role="alert">
            <h4 class="alert-heading">Sorry, we hebben helaas niet meer alle items in stock.</h4>
            <ul class="ce_checkoutAlertItemsOutOfStockList">
                <li class="ce_checkoutAlertItemsOutOfStockItem">Product</li>
            </ul>
            <hr class="mb-1">
            <p>Wil je je bestelling afronden met de nog beschikbare stock? <br> Dan kan je gewoon opnieuw op de knop 'Bestelling bevestigen' drukken.</p>
        </div>
        <button class="btn btn-primary btn-lg btn-block" id="ce_checkoutFinalOrderBtn" type="submit">Bestelling bevestigen en betalen</button>
      </form>
    </div>
  </div>

  <footer class="my-5 pt-5 text-muted text-center text-small">
    <p class="mb-1">&copy; {{ date('Y') }} {{ ChuckSite::getSetting('company.name') }}</p>
  </footer>
</div>

@endsection