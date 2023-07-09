<script>
    // var clientPrinters = null;
    // var _this = this;

    // //WebSocket settings
    // JSPM.JSPrintManager.auto_reconnect = true;
    // JSPM.JSPrintManager.start();
    // JSPM.JSPrintManager.WS.onStatusChanged = function () {
    //     if (jspmWSStatus()) {
    //         //get client installed printers
    //         JSPM.JSPrintManager.getPrinters().then(function (printersList) {
    //             clientPrinters = printersList;
    //             var options = '';
    //             for (var i = 0; i < clientPrinters.length; i++) {
    //                 options += '<option>' + clientPrinters[i] + '</option>';
    //             }
    //             $('#printerName').html(options);
    //         });
    //     }
    // };

    // //Check JSPM WebSocket status
    // function jspmWSStatus() {
    //     if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open)
    //         return true;
    //     else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Closed) {
    //         console.warn('JSPrintManager (JSPM) is not installed or not running! Download JSPM Client App from https://neodynamic.com/downloads/jspm');
    //         return false;
    //     }
    //     else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Blocked) {
    //         alert('JSPM has blocked this website!');
    //         return false;
    //     }
    // }
</script>

<script>
function printJob(job) {
    var escpos = Neodynamic.JSESCPOSBuilder;
    var doc = new escpos.Document();
    escpos.ESCPOSImage.load("{{ChuckSite::module('chuckcms-module-ecommerce')->getSetting('pos.ticket_logo')}}")
        .then(logo => {

            // logo image loaded, create ESC/POS commands
            doc.setCharacterCodeTable(19)
                .align(escpos.TextAlignment.Center)
                .image(logo, escpos.BitmapDensity.D24)
                .feed(2)
                .font(escpos.FontFamily.A)
                .align(escpos.TextAlignment.Center)
                .style([escpos.FontStyle.Bold])
                .size(0, 0)
                .text(job.location.name)
                .font(escpos.FontFamily.B)
                .size(0, 0)
                .text(job.location.address1);

            if (job.location.address2 !== null) {
                doc.text(job.location.address2);
            }

            let dformat = job.date.replace('/','.').replace('/','.')+'                              ' + job.time;

            doc.text(job.location.vat)
                .feed(2)
                .font(escpos.FontFamily.A)
                .size(0, 0)
                .text(job.location.receipt_title)
                .align(escpos.TextAlignment.LeftJustification)
                .feed(2)
                .text(dformat)
                .drawLine();

            for (var jit = 0, len = job.items.length; jit < len; jit++) {
                doc.align(escpos.TextAlignment.LeftJustification).text(job.items[jit]);
            }

            if (job.discount > 0) {
                let subtotalPriceLine = "€ "+(job.subtotal.toFixed(2))+"  ";
                let subTotalLine = "SUBTOTAAL";
                let neededSubtotalLineLength = (48 - subTotalLine.length - subtotalPriceLine.length);
                for (var ell = 0; ell < neededSubtotalLineLength; ell++) {
                    subTotalLine += " ";
                };
                subTotalLine += subtotalPriceLine;

                doc.drawLine();
                doc.align(escpos.TextAlignment.LeftJustification).text(subTotalLine);

                let discountPriceLine = "- € "+(job.discount.toFixed(2))+"  ";
                let discountLine = "KORTING";
                let neededDiscountLineLength = (48 - discountLine.length - discountPriceLine.length);
                for (var ell = 0; ell < neededDiscountLineLength; ell++) {
                    discountLine += " ";
                };
                discountLine += discountPriceLine;

                doc.align(escpos.TextAlignment.LeftJustification).text(discountLine);
            }

            doc.drawLine()
                .font(escpos.FontFamily.B)
                .style([escpos.FontStyle.Bold])
                .size(1, 1)
                .drawTable(["Totaal", "€ "+(job.total.toFixed(2))]);

            if (job.payments.length > 0) {
                doc.feed(1);
                let paymentsLines = getFormattedPaymentLines(job.payments);
                for (var payl = 0; payl < paymentsLines.length; payl++) {
                    doc.font(escpos.FontFamily.A)
                        .size(0, 0)
                        .align(escpos.TextAlignment.LeftJustification)
                        .text(paymentsLines[payl]);
                };
            }

            doc.feed(2);
            for (var jvq = 0; jvq < job.vat.length; jvq++) {
                doc.font(escpos.FontFamily.A)
                        .size(0, 0)
                        .align(escpos.TextAlignment.LeftJustification)
                        .text(job.vat[jvq]);
            };

            if (job.customer !== 1) {
                doc.feed(2);
                doc.font(escpos.FontFamily.A)
                    .size(0, 0)
                    .align(escpos.TextAlignment.Center)
                    .text("Voor deze bestelling krijgt u")
                    .text(""+Math.floor(job.total)+" punten")
                    .text(" ")
                    .text("U heeft nu "+getCustomerPoints(job.customer)+" punten in totaal.");
            }

            if (job.location.receipt_footer1 !== null || job.location.receipt_footer2 !== null || job.location.receipt_footer3 !== null) {
                doc.feed(2)
                    .font(escpos.FontFamily.A)
                    .size(0, 0);
            }


            if (job.location.receipt_footer1 !== null) {
                if (job.location.receipt_footer1.indexOf("qrcode:") !== -1) {
                    qrcodevalue = job.location.receipt_footer1.slice(7, -1);
                    doc.qrCode(qrcodevalue, new escpos.BarcodeQROptions(escpos.QRLevel.L, 6));
                } else {
                    doc.align(escpos.TextAlignment.Center).text(job.location.receipt_footer1);
                }
            }

            if (job.location.receipt_footer2 !== null) {
                if (job.location.receipt_footer2.indexOf("qrcode:") !== -1) {
                    qrcodevalue = job.location.receipt_footer2.slice(7, -1);
                    doc.qrCode(qrcodevalue, new escpos.BarcodeQROptions(escpos.QRLevel.L, 6));
                } else {
                    doc.align(escpos.TextAlignment.Center).text(job.location.receipt_footer2);
                }
            }

            if (job.location.receipt_footer3 !== null) {
                if (job.location.receipt_footer3.indexOf("qrcode:") !== -1) {
                    qrcodevalue = job.location.receipt_footer3.slice(7, -1);
                    doc.qrCode(qrcodevalue, new escpos.BarcodeQROptions(escpos.QRLevel.L, 6));
                } else {
                    doc.align(escpos.TextAlignment.Center).text(job.location.receipt_footer3);
                }
            }

            // doc.feed(2)
            //     .font(escpos.FontFamily.A)
            //     .size(0, 0)
            //     .align(escpos.TextAlignment.Center)
            //     .text("Bedankt voor uw bezoek aan Donuttello!")
            //     .text("Geef uw mening over uw bezoek:")
            //     .qrCode('https://donuttello.com', new escpos.BarcodeQROptions(escpos.QRLevel.L, 6))


            var escposCommands = doc.feed(5).cashDraw().cut().generateUInt8Array();

            var printSocket = new WebSocket("ws://localhost:5555", ["binary"]);
            printSocket.binaryType = 'arraybuffer';

            printData = escposCommands.buffer;

            if (!(printData instanceof ArrayBuffer)) {
              console.log("directPrint(): Argument type must be ArrayBuffer.")
              return false;
            }

            printSocket.onopen = function (event) {
              console.log("Socket is connected.");

              // Serialise, send.
              console.log("Sending " + printData.byteLength + " bytes of print data.");
              printSocket.send(printData);
              //return true;

              setInterval(function() {
                if (printSocket.bufferedAmount == 0)
                  printSocket.close();
              }, 50);


              //directPrintUint8ArrayBuffer(printSocket, escposCommands.buffer);

              //printSocket.close();

            }
            printSocket.onerror = function(event) {
              console.log('Socket error', event);
            };
            printSocket.onclose = function(event) {
              console.log('Socket is closed');
            }
    });
}
</script>
