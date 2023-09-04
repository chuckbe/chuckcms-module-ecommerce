<script src="{{ asset('chuckbe/chuckcms-module-ecommerce/js/labelprinter/DYMO.Label.Framework.3.0.js') }}"></script>
<script>
$(function() {
    dymo.label.framework.init(dymoLoad);
});

const sessionToken = '{{ Session::token() }}';
const dymoLabelSrc = "{{ asset(ChuckEcommerce::getSetting('integrations.label.src') ?? '/chuckbe/chuckcms-module-ecommerce/test.label') }}";

let dymoPrinterName = "Geen printer gevonden";
let dymoEnvironmentStatus = false;
let dymoLabelTemplate;

function dymoLoad() {
    dymoEnvironment();
    dymoTemplate();
    dymoPrinter();
}

function dymoEnvironment() {
    const dymoEnvironment = dymo.label.framework.checkEnvironment();

    dymoEnvironmentStatus = dymoEnvironment.isBrowserSupported &&
        dymoEnvironment.isFrameworkInstalled &&
        dymoEnvironment.isWebServicePresent;
}

function dymoPrinter() {
    try {
        dymo.label.framework.getPrintersAsync().then(function(printers) {
            if (printers.length >= 1) {
                dymoPrinterName = printers[0].name;
                if (printers[0].isConnected){
                    printerOnline(true);
                } else {
                    printerOnline(false);
                }

            }
        });
    } catch (err) {
        printerOnline(false);
        console.log(err.message);
    }
}

function dymoTemplate() {
    $.ajax({
        url: dymoLabelSrc,
        dataType: "text"
    }).then(function(data, textStatus, jqXHR) {
        dymoLabelTemplate = data;
    });
}

function printerOnline(printerStatus = false) {
    if (dymoEnvironmentStatus && printerStatus) {
        $('#labelPrinterStatus').removeClass('btn-outline-danger').addClass('btn-outline-success');
        $('#labelPrinterStatus').find('span').text(dymoPrinterName);
        //@TODO: add environment variables to status modal
    } else {
        $('#labelPrinterStatus').removeClass('btn-outline-success').addClass('btn-outline-danger');
        $('#labelPrinterStatus').find('span').text(dymoPrinterName);
    }
}

$( document ).ready(function (){
    $('body').on('click', '.openLabelModalBtn', function (e) {
        let productId = $(this).data('productId');

        let labelModal = $('#labelModal');

        $.ajax({
           type: 'POST',
           url: "{{ route('dashboard.module.ecommerce.products.label') }}",
           data: {
                id: productId,
                _token: sessionToken
            }
        }).done(function (data) {
            if (data.status == 'success') {
                labelModal.find('.modal-content').replaceWith(data.modal);

                labelModal.modal('show');

                return;
            } else {
                console.log(data);
            }
        });
    });

    $('body').on('click','#labelModal .print-btn', function(e){
        let modalbody = $('#labelModal').find('.modal-body');
        let manufacturer;
        let product_name;
        let barcode;
        let price;
        let quantity;
        let attributes;
        let jobName;

        if ($(this).attr('data-product-type') == 'single'){
            manufacturer = $(modalbody).find('input#brandname').val();
            product_name = $(modalbody).find('input#product_name').val();
            product_name = product_name.replace(/.{10}\S*\s+/g, "$&@").split(/\s+@/).join('\n');
            barcode = $(modalbody).find('input#barcode').val();
            price = $(modalbody).find('input#price').val();
            quantity = $(modalbody).find('input#quantity').val();
            attributes = $(modalbody).find('input#attributes').val();
            jobName = 'customJob';

            if (checkEan(barcode)) {
                if (barcode.length == 13) {
                    barcode = barcode.slice(0, -1)
                }

                if (quantity > 0) {
                    printLabel(
                        manufacturer,
                        product_name,
                        barcode,
                        attributes,
                        price,
                        quantity,
                        jobName
                    );
                }else{
                    console.log('Quantity set to 0');
                }
            } else {
                console.log('Invalid barcode');
            }
        }

        if($(this).attr('data-product-type') == 'single_combi_product'){
            manufacturer = $(modalbody).find('input#brandname').val();
            let ci = $(this).closest('tr.combination_item')

            product_name = $(ci).attr('data-product');
            product_name = product_name.replace(/.{10}\S*\s+/g, "$&@").split(/\s+@/).join('\n');
            barcode = $(ci).attr('data-ean');
            price = $(ci).find('.combination-price').text();;
            quantity = $(ci).find('.combination-quantity').val();
            attributes = $(ci).attr('data-attr');
            jobName = 'customJob';

            if(checkEan(barcode)){
                if (barcode.length == 13) {
                    barcode = barcode.slice(0, -1)
                }
                if (quantity > 0) {
                    printLabel(
                        manufacturer,
                        product_name,
                        barcode,
                        attributes,
                        price,
                        quantity,
                        jobName
                    );
                } else {
                    console.log('Quantity set to 0');
                }
            } else {
                console.log('Invalid barcode');
            }
        }

        if($(this).attr('data-product-type') == 'multi_combi_products'){
            manufacturer = $(modalbody).find('input#brandname').val();
            let combinations = $('.combinations_row tr.combination_item');
            if (combinations.length > 0) {
                for (let i = 0; i < combinations.length; i++) {
                    let active = $(combinations[i]).find('.combination_active').is(":checked")
                    if (active) {
                        let ci = combinations[i]
                        product_name = $(ci).attr('data-product');
                        product_name = product_name.replace(/.{10}\S*\s+/g, "$&@").split(/\s+@/).join('\n');
                        barcode = $(ci).attr('data-ean');
                        price = $(ci).find('.combination-price').text();
                        quantity = $(ci).find('.combination-quantity').val();
                        attributes = $(ci).attr('data-attr');
                        jobName = 'customJob';
                        if (checkEan(barcode)) {
                            if (barcode.length == 13) {
                                barcode = barcode.slice(0, -1)
                            }
                            if (quantity > 0) {
                                // printLabel
                                printLabel(manufacturer,product_name,barcode,attributes,price,quantity, jobName);
                            } else {
                                console.log('Quantity set to 0');
                            }
                        } else {
                            console.log('Invalid barcode');
                        }
                    }
                }
            } else {
                console.log('No combinations found');
            }
        }
    });

    $('#labelModal').on('hide.bs.modal', function (e) {
        let modal =  $('#labelModal');
        let modal_body = $(modal).find('.modal-body');
        modal_body.find('.combination_area .combination_item').not(':first').remove();
    });

    $('body').on('keyup', ['.combination_item .combination-quantity', '.single_product_area .quantity'], function(e){
        let max = $(e.target).attr('max');
        if($(e.target).val() !== '' && $(e.target).val() > max) {
            $(e.target).val(max);
        }
    });
});

function checkEan(eanCode){
    code = eanCode.trim();
    const digits = () => /^\d{8,13}$/g.test(code);
    const validlengths = [8, 12, 13];
    if (!digits() || !validlengths.includes(code.length)) return false;

    let checksum = 0;
    const codelist = code.split("");
    const checkdigit = parseInt(codelist.pop(), 10);
    codelist.map((value, index) => {
      const digit = parseInt(value, 10);
      if (code.length % 2 === 1) checksum += index % 2 ? digit * 3 : digit;
      else checksum += index % 2 ? digit : digit * 3;
    });

    let check = checksum % 10;
    if (check !== 0) check = 10 - check;
    if (check === checkdigit) return true;
    return false;
}


function printLabel($manufacturer,$product_name,$barcode,$attributes,$price,$quantity,$printJobName) {
    var label = dymo.label.framework.openLabelXml(dymoLabelTemplate);

    let manufacturer_name = $manufacturer;
    let product = $product_name;
    let barcode = $barcode;
    let attributes = $attributes;
    let price = $price;
    let quantity = $quantity;

    let baseString = "<";
    baseString += "?";
    baseString += 'xml version="1.0" encoding="utf-8"';
    baseString += "?";
    baseString += ">";
    baseString += '\n';
    let basicPrintParamsXML = baseString +
    '<LabelWriterPrintParams>\n' +
    '  <Copies>'+quantity+'</Copies>\n' +
    '  <JobTitle>'+$printJobName+'</JobTitle>\n' +
    '  <FlowDirection>LeftToRight</FlowDirection>\n' +
    '  <PrintQuality>Auto</PrintQuality>\n' +
    '  <TwinTurboRoll>Auto</TwinTurboRoll>\n' +
    '</LabelWriterPrintParams>';

    label.setObjectText('brand_name', manufacturer_name);
    label.setObjectText('product_name', product);
    label.setObjectText('barcode', barcode);
    label.setObjectText('attribute', attributes);
    label.setObjectText('price', price);

    label.print(dymoPrinterName, basicPrintParamsXML);

    // label.printAsync(printerViewModel.printerName(), basicPrintParamsXML).then(function(state) {
    //     if (state) {
    //         printerViewModel.message("Printing");
    //         setTimeout(function() {
    //             printerViewModel.message("Ready");
    //         }, 2000);
    //     } else {
    //         printerViewModel.message("Error");
    //     }
    // });
    // return false;
}
</script>
