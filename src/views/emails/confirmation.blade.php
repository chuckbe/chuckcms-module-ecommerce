<!DOCTYPE html>
<html>
<head>
<title>Bedankt voor uw bestelling #{{ $order->json['order_number'] }}</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<style type="text/css">
    /* CLIENT-SPECIFIC STYLES */
    body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
    table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;} /* Remove spacing between tables in Outlook 2007 and up */
    img{-ms-interpolation-mode: bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */

    /* RESET STYLES */
    img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
    table{border-collapse: collapse !important;}
    body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}

    /* iOS BLUE LINKS */
    a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
    }

    /* MOBILE STYLES */
    @media screen and (max-width: 525px) {

        /* ALLOWS FOR FLUID TABLES */
        .wrapper {
          width: 100% !important;
            max-width: 100% !important;
        }

        /* ADJUSTS LAYOUT OF LOGO IMAGE */
        .logo img {
          margin: 0 auto !important;
        }

        /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
        .mobile-hide {
          display: none !important;
        }

        .img-max {
          max-width: 100% !important;
          width: 100% !important;
          height: auto !important;
        }

        /* FULL-WIDTH TABLES */
        .responsive-table {
          width: 100% !important;
        }

        /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
        .padding {
          padding: 10px 5% 15px 5% !important;
        }

        .padding-meta {
          padding: 30px 5% 0px 5% !important;
          text-align: center;
        }

        .padding-copy {
             padding: 10px 5% 10px 5% !important;
          text-align: center;
        }

        .no-padding {
          padding: 0 !important;
        }

        .section-padding {
          padding: 50px 15px 50px 15px !important;
        }

        /* ADJUST BUTTONS ON MOBILE */
        .mobile-button-container {
            margin: 0 auto;
            width: 100% !important;
        }

        .mobile-button {
            padding: 15px !important;
            border: 0 !important;
            font-size: 16px !important;
            display: block !important;
        }

    }

    /* ANDROID CENTER FIX */
    div[style*="margin: 16px 0;"] { margin: 0 !important; }
</style>
</head>
<body style="margin: 0 !important; padding: 0 !important;">

<!-- HIDDEN PREHEADER TEXT -->
<div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
    Bevestiging van uw bestelling #{{ $order->order_number }}.
</div>

<!-- HEADER -->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    @if(ChuckSite::getSetting('logo.href') !== null)    
    <tr>
        <td bgcolor="#ffffff" align="center">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
            <tr>
            <td align="center" valign="top" width="500">
            <![endif]-->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;" class="wrapper">
                <tr>
                    <td align="center" valign="top" style="padding: 15px 0;" class="logo">
                        <a href="{{ URL::to('/') }}" target="_blank">
                            <img alt="Logo" src="{{ URL::to('/') }}{{ ChuckSite::getSetting('logo.href') }}" height="30" width="200" style="display: block; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 16px;" border="0">
                        </a>
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
    @endif
    <tr>
        <td bgcolor="#eaeaea" align="center" style="padding: 70px 15px 70px 15px;" class="section-padding">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
            <tr>
            <td align="center" valign="top" width="500">
            <![endif]-->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;" class="responsive-table">
                <tr>
                    <td>
                        <!-- HERO IMAGE -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <!-- COPY -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding">
                                                Beste {{ $order->surname . ' ' . $order->name }}<br><br>

                                                Bedankt voor uw bestelling. We gaan direct aan de slag om deze rond te werken. Heeft u nog vragen? Neem gerust contact met ons op.
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <!-- COPY -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td style="font-size: 22px; font-family: Helvetica, Arial, sans-serif; color: #333333; padding-top: 30px;" class="padding">Overzicht van uw bestelling</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding">
                                                
                                                <b>Verzending:</b> {{ $order->json['shipping']['name'] }} <br>
                                                <b>Verzendtijd:</b> {{ $order->json['shipping']['transit_time'] }} 
                                                <br><br>

                                                <b>Overzicht: </b> <br>
                                                @foreach($order->json['products'] as $sku => $product)
                                                    <p>{{ $product['quantity'] }}x "{{ $product['title'] . ' ' . $product['options_text'] }}" ({{ ChuckEcommerce::formatPrice((float)$product['price_tax']) }}) => {{ ChuckEcommerce::formatPrice((float)$product['total']) }}</p>
                                                    <hr>
                                                @endforeach

                                                <br>

                                                <b>Verzendkosten</b>: {{ $order->shipping > 0 ? ChuckEcommerce::formatPrice($order->shipping + $order->shipping_tax) : 'gratis' }} <br><br>
                                                <b>Totaal</b>: {{ ChuckEcommerce::formatPrice($order->final) }}
                                               
                                                <br><br>

                                                @if($order->json['address']['shipping_equal_to_billing'])
                                                <b>Facturatie & Verzendadres: </b>
                                                @else 
                                                <b>Facturatie adres: </b>
                                                @endif  
                                                <br>

                                                Naam: {{ $order->surname . ' ' . $order->name }} <br>
                                                E-mail: {{ $order->email }} 
                                                @if(!is_null($order->tel)) 
                                                <br>
                                                Tel: {{ $order->tel }} 
                                                @endif
                                                <br>
                                                @if(!is_null($order->customer->json['company']['name']))
                                                Bedrijfsnaam: {{ $order->customer->json['company']['name'] }} <br>
                                                BTW-nummer: {{ $order->customer->json['company']['vat'] }} <br>
                                                @endif
                                                Adres: <br> {{ $order->json['address']['billing']['street'] . ' ' . $order->json['address']['billing']['housenumber'] }}, <br> {{ $order->json['address']['billing']['postalcode'] . ' ' . $order->json['address']['billing']['city'] .', '. config('chuckcms-module-ecommerce.countries_data.'.$order->json['address']['billing']['country'].'.native') }} <br>
                                                @if(!$order->json['address']['shipping_equal_to_billing'])
                                                <br>
                                                <b>Verzend adres: </b><br> 
                                                {{ $order->json['address']['shipping']['street'] . ' ' . $order->json['address']['shipping']['housenumber'] }}, <br> {{ $order->json['address']['shipping']['postalcode'] . ' ' . $order->json['address']['shipping']['city'] .', '. config('chuckcms-module-ecommerce.countries_data.'.$order->json['address']['shipping']['country'].'.native') }} <br>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <!-- COPY -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding">
                                                Heeft u vragen over uw bestelling? U kan ons steeds contacteren.
                                                <br><br>
                                                <a href="mailto:{{ config('chuckcms-module-ecommerce.company.email') }}">{{ config('chuckcms-module-ecommerce.company.email') }}</a>
                                                <br>
                                                <br>
                                                {{ config('chuckcms-module-ecommerce.company.name') }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
    <tr>
        <td bgcolor="#ffffff" align="center" style="padding: 20px 0px;">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
            <tr>
            <td align="center" valign="top" width="500">
            <![endif]-->
            <!-- UNSUBSCRIBE COPY -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="max-width: 500px;" class="responsive-table">
                <tr>
                    <td align="center" style="font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666;">
                        {{ config('chuckcms-module-ecommerce.company.name') }} - {{ config('chuckcms-module-ecommerce.company.vat') }}<br>
                        {{ config('chuckcms-module-ecommerce.company.address1') }}, {{ config('chuckcms-module-ecommerce.company.address2') }}
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
</table>

</body>
</html>
