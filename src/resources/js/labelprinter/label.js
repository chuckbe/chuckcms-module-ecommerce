function PrinterViewModel() {
    // DYMO Environment
    this.browserSupported = ko.observable(false);
    this.frameworkInstalled = ko.observable(false);
    this.webServicePresent = ko.observable(false);
    this.environmentChecked = ko.observable(false);
    this.environmentStable = ko.computed(function() {
        return (this.browserSupported() && this.frameworkInstalled() && this.webServicePresent());
    }, this, { pure: true });

    // DYMO Printer
    this.printerName = ko.observable("");
    this.printerConnected = ko.observable(false);
    this.printerChecked = ko.observable(false);

    // DYMO label
    this.lebelAjaxComplete = ko.observable(false);
    this.lebelajaxResponseCode = ko.observable(-1);
    this.lebelaAcquired = ko.computed(function() {
        return (this.lebelajaxResponseCode() >= 200 && this.lebelajaxResponseCode() < 300);
    }, this, { pure: true });



    // UI Message
    this.message = ko.observable("Loading");
    this.state = ko.computed(function() { 
        if (!this.environmentChecked() || !this.printerChecked() || !this.lebelAjaxComplete()) {
            this.message("Loading");
        } else if (this.environmentStable() && this.printerConnected() && this.lebelaAcquired()) {
            this.message("Ready");
        } else {
            if (!this.printerConnected()) {
                this.message("Not connected")
            } else {
                this.message("Error");
            }
        }
    }, this);
}

var printerViewModel = new PrinterViewModel();
var shippingLabelTemplate;

// function checkEan(eanCode) {
//     // Check if only digits
//     var ValidChars = "0123456789";
//     for (i = 0; i < eanCode.length; i++) {
//         digit = eanCode.charAt(i);
//         if (ValidChars.indexOf(digit) == -1) {
//             return false;
//         }
//     }

//     // Add five 0 if the code has only 8 digits
//     if (eanCode.length == 8) {
//         eanCode = "00000" + eanCode;
//     }
//     // Check for 13 digits otherwise
//     else if (eanCode.length != 13) {
//         return false;
//     }

//     // Get the check number
//     originalCheck = eanCode.substring(eanCode.length - 1);
//     eanCode = eanCode.substring(0, eanCode.length - 1);

//     // Add even numbers together
//     even = Number(eanCode.charAt(1)) +
//         Number(eanCode.charAt(3)) +
//         Number(eanCode.charAt(5)) +
//         Number(eanCode.charAt(7)) +
//         Number(eanCode.charAt(9)) +
//         Number(eanCode.charAt(11));
//     // Multiply this result by 3
//     even *= 3;

//     // Add odd numbers together
//     odd = Number(eanCode.charAt(0)) +
//         Number(eanCode.charAt(2)) +
//         Number(eanCode.charAt(4)) +
//         Number(eanCode.charAt(6)) +
//         Number(eanCode.charAt(8)) +
//         Number(eanCode.charAt(10));

//     // Add two totals together
//     total = even + odd;

//     // Calculate the checksum
//     // Divide total by 10 and store the remainder
//     checksum = total % 10;
//     // If result is not 0 then take away 10
//     if (checksum != 0) {
//         checksum = 10 - checksum;
//     }

//     // Return the result
//     if (checksum != originalCheck) {
//         return false;
//     }

//     return true;
// }

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

$(function() {
    ko.applyBindings(printerViewModel);
    dymo.label.framework.init(dymoLoad);

});



function dymoLoad() {
    dymoEnvironment();
    dymoPrinter();
    dymoTemplate();
}

function dymoEnvironment() {
    var result = dymo.label.framework.checkEnvironment();
    printerViewModel.browserSupported(result.isBrowserSupported);
    printerViewModel.frameworkInstalled(result.isFrameworkInstalled);
    printerViewModel.webServicePresent(result.isWebServicePresent);
    printerViewModel.environmentChecked(true);
}

function dymoPrinter() {
    try {
        dymo.label.framework.getPrintersAsync().then(function(printers) {
            if (printers.length >= 1) {
                printerViewModel.printerName(printers[0].name);
                if(printers[0].isConnected){
                    printerViewModel.printerConnected('<div class="online bg-success" data-toggle="tooltip" data-placement="top" title="online"></div>');
                }else{
                    printerViewModel.printerConnected('<div class="offline bg-danger"data-toggle="tooltip" data-placement="top" title="offline"></div>');
                }
                
            }
            printerViewModel.printerChecked(true);
        });
    } catch (err) {
        printerViewModel.message(err.message);
    }
}

function dymoTemplate() {
    $.ajax({
        url: "/chuckbe/chuckcms-module-ecommerce/test.label",
        dataType: "text"
    }).then(function(data, textStatus, jqXHR) {
        shippingLabelTemplate = data;
        printerViewModel.lebelajaxResponseCode(jqXHR.status);
        printerViewModel.lebelAjaxComplete(true);
    });
}

