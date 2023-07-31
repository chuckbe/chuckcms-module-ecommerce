@include('chuckcms-module-ecommerce::pos.scripts.urls')
<script>
$(document).ready(function() {
    $.fn.numpad.defaults.gridTpl = '<table class="table bg-white"></table>';
    $.fn.numpad.defaults.backgroundTpl = '<div class=""></div>';
    $.fn.numpad.defaults.displayTpl = '<input type="text" class="form-control" />';
    $.fn.numpad.defaults.buttonNumberTpl =  '<button type="button" class="btn btn-default"></button>';
    $.fn.numpad.defaults.buttonFunctionTpl = '<button type="button" class="btn" style="width: 100%;"></button>';
    $.fn.numpad.defaults.onKeypadCreate = function(){$(this).find('.done').addClass('btn-primary');};

    $('input.numpad').numpad({decimalSeparator: '.'});













    var cof_pos_processing_order = false;

    var cof_pos_active_location = $('#cof_pos_location').attr('data-active-location');

    //init();

    //getOrderFromStorage();



$('body').on('click', '#pos_listOrdersBtn', function (event) {
    $('#ordersModal').modal('show');
    return;
});

$('body').on('click', '.locationDropdownSelect', function (event) {
    event.preventDefault();

    changeActiveLocation($(this));
});

$('body').on('click', '.pos_addProduct', function (event) {
    event.preventDefault();

    let id = $(this).data('id');
    let code = $(this).data('code');

    let hasCollections = !!$(this).data('hasCollections');

    if (hasCollections) {
        return getCombinations(id);
    }

    addToCart(id, code.sku, [], [], 1).done(function(data) {
        if (data.status == "success") {
            $('#pos_cart').replaceWith(data.cart);
        }

        if (data.status == "error") {
            popNotification(data.notification);
        }
    });
});

$('body').on('click', '#addCombinationToCart', function (event) {
    event.preventDefault();

    let id = $(this).data('id');
    let sku = $('input.combinationRadio:checked').val();

    let options = [];
    $('.ce_optionSelectInput').each(function() {
        options.push(
            $(this)
                .children("option:selected")
                .attr('data-option-key')+'%|%'+$(this).children("option:selected").val()
        );
    });

    let extras = [];
    $('.ce_extraSelectInput').each(function() {
        extras.push(
            $(this)
                .children("option:selected")
                .attr('data-extra-key')+'%|%'+$(this).children("option:selected").val()
        );
    });

    addToCart(id, sku, options, extras, 1).done(function(data) {
        let combinationsModal = $('#combinationsModal');

        if (data.status == "success") {
            $('#pos_cart').replaceWith(data.cart);
        }

        if (data.status == "error") {
            popNotification(data.notification);
        }

        combinationsModal.modal('hide');
    });
});

$('body').on('click', '.pos_addProductQuantity', function (event) {
    event.preventDefault();

    let sku = $(this).parent().closest('.pos_cartItem').data('sku');
    let rowId = $(this).parent().closest('.pos_cartItem').data('rowId');
    let quantity = parseInt($(this).parent().closest('.pos_cartItem').data('quantity')) + 1;

    updateCartItem(sku, rowId, quantity).done(function(data) {
        if (data.status == "success") {
            $('#pos_cart').replaceWith(data.cart);
        }

        if (data.status == "error") {
            popNotification(data.notification);
        }
    });
});

$('body').on('click', '.pos_subtractProductQuantity', function (event) {
    event.preventDefault();

    let sku = $(this).parent().closest('.pos_cartItem').data('sku');
    let rowId = $(this).parent().closest('.pos_cartItem').data('rowId');
    let quantity = parseInt($(this).parent().closest('.pos_cartItem').data('quantity')) - 1;

    updateCartItem(sku, rowId, quantity).done(function(data) {
        if (data.status == "success") {
            $('#pos_cart').replaceWith(data.cart);
        }

        // if (data.status == "error") {
        //     popNotification(data.notification);
        // }
    });
});

$('body').on('click', '.pos_removeProduct', function (event) {
    event.preventDefault();

    let sku = $(this).parent().closest('.pos_cartItem').data('sku');
    let rowId = $(this).parent().closest('.pos_cartItem').data('rowId');

    removeCartItem(sku, rowId).done(function(data) {
        if (data.status == "success") {
            $('#pos_cart').replaceWith(data.cart);

            popNotification(data.notification);
        }

        // if (data.status == "error") {
        //     popNotification(data.notification);
        // }
    });
});

// CART TRIGGERS
$('body').on('click', '#pos_checkoutBtn', function (event) {
    event.preventDefault();

    $(this).text('PLEASE HOLD...');
    $(this).prop('disabled', true);

    let paymentModal = $('#paymentModal');

    initiatePayment().done(function (data) {
        if (data.status == "success") {
            localStorage.setItem('pos_order', JSON.stringify(data.order.id));

            paymentModal.find('.modal-body').html(data.body);

            $('input.numpad').numpad({decimalSeparator: '.'});

            paymentModal.modal('show');

            $('#pos_checkoutBtn').text('CHECKOUT & PAY');
            $('#pos_checkoutBtn').prop('disabled', false);
        }

        if (data.status == "availability") {
            $('#pos_cart').replaceWith(data.cart);

            popHtmlNotification(data.notification);
        }

        if (data.status == "failed" || data.status == "error" || data.status == "subtraction") {
            popNotification(data.notification);

            $('#pos_checkoutBtn').text('CHECKOUT & PAY');
            $('#pos_checkoutBtn').prop('disabled', false);
        }
    });
});



$('body').on('click', '.pos_checkoutCashFitPayment', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }

    let final = Number($('.pos_checkoutPendingAmount').data('amount')).toFixed(2);

    $('.pos_checkoutCashInput').val(final);

});

$('body').on('click', '.pos_checkoutCardFitPayment', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }

    let final = Number($('.pos_checkoutPendingAmount').data('amount')).toFixed(2);

    $('.pos_checkoutCardInput').val(final);
});

$('body').on('click', '.pos_checkoutCashAddPayment', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }

    let newCashAmount = ( Number($('.pos_checkoutCashInput').val()) + parseInt($(this).attr('data-amount')) );
    $('.pos_checkoutCashInput').val(newCashAmount);
});

$('body').on('click', '.pos_checkoutCashPaymentReset', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }

    $('.pos_checkoutCashInput').val('0.00');
});

$('body').on('click', '.pos_checkoutCardPaymentReset', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }

    $('.pos_checkoutCardInput').val('0.00');
});





$('body').on('click', '.pos_checkoutCashToPayments', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }

    let payment = Number($('.pos_checkoutCashInput').val());

    if (payment == 0) {
        return;
    }

    addPaymentToList('cash', payment);

    $('.pos_checkoutCashInput').val('0.00');
});

$('body').on('click', '.pos_removeCashPayment', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }

    let paymentListItem = $(this).closest('.pos_paymentListItem');

    let paymentId = paymentListItem.data('paymentId');

    removePayment(paymentId).done(function (data) {
        if (data.status == "success") {
            paymentListItem.remove();

            if ($('.pos_paymentListItem').length == 0) {
                $('#pos_paymentListWrapper').addClass('d-none');
                //removePaymentsFromStorage();
            }

            updateTetheredAmount();
        }

        if (data.status == "error") {
            popNotification(data.notification)
        }
    });


});

$('body').on('click', '.pos_checkoutCardToPayments', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }

    let payment = Number($('.pos_checkoutCardInput').val());

    if (payment == 0) {
        return;
    }

    addPaymentToList('card', payment);

    $('.pos_checkoutCardInput').val('0.00');
});




$('body').on('click', '#pos_cancelOrderBtn', function (event) {
    event.preventDefault();

    cof_pos_processing_order = false;

    let orderId = $(this).data('order');

    $(this).text('Even geduld...');
    $(this).prop('disabled', true);

    cancelOrder(orderId).done(function (data) {
        if (data.status == 'success') {
            $('#pos_finalizeOrderBtn').prop('disabled', true);
            $('#pos_finalizeOrderBtn').html('Bestelling voltooien');

            $('#paymentModal').find('.modal-body').html('');
            $('#paymentModal').modal('hide');
        }
    });
});

$('body').on('click', '#pos_finalizeOrderBtn', function (event) {
    event.preventDefault();

    cof_pos_processing_order = true;

    $(this).prop('disabled', true);
    $(this).html('<i class="fa fa-sync-alt fa-spin"></i> Verwerken... ');

    let orderId = $(this).data('order');
    let change = $('.pos_checkoutPendingAmount').data('amount');

    finalizeOrder(orderId, change).done(function (data) {
        if (data.status == 'success') {
            $('#pos_cart').replaceWith(data.cart);

            let ogDate = new Date,
            order_date = [ogDate.getDate().padLeft(),
                       (ogDate.getMonth()+1).padLeft(),
                       ogDate.getFullYear()].join('/'),
            order_time = [ogDate.getHours().padLeft(),
                       ogDate.getMinutes().padLeft(),
                       ogDate.getSeconds().padLeft()].join(':');

            printTicketFromOrder(data.order, data.payments, order_date, order_time);

            $('#paymentModal').find('.modal-body').html('');
            $('#paymentModal').modal('hide');
        }

        if (data.status == 'error') {
            popNotification(data.notification);
        }
    });
});
// CART TRIGGERS








function getCombinations(id) {
    let combinationsModal = $('#combinationsModal');

    $.ajax({
        method: 'POST',
        url: PosURL.getCombinations,
        data: {
            id: id,
            _token: sessionToken,
        }
    }).done(function(data) {
        if (data.status == "success") {
            combinationsModal.modal('show');

            $('#addCombinationToCart').data('id', id);

            combinationsModal.find('.modal-body').html(data.body);
        }

        if (data.status == "error") {
            popNotification(data.notification);
        }
    });
}

function addToCart(id, sku, options, extras, quantity) {
    return $.ajax({
        method: 'POST',
        url: PosURL.addToCart,
        data: {
            id: id,
            sku: sku,
            options: options,
            extras: extras,
            quantity: quantity,
            _token: sessionToken
        }
    });
}

function updateCartItem(sku, rowId, quantity) {
    return $.ajax({
        method: 'POST',
        url: PosURL.updateCartItem,
        data: {
            sku: sku,
            row_id: rowId,
            quantity: quantity,
            _token: sessionToken
        }
    });
}

function removeCartItem(sku, rowId) {
    return $.ajax({
        method: 'POST',
        url: PosURL.removeCartItem,
        data: {
            sku: sku,
            row_id: rowId,
            _token: sessionToken
        }
    });
}



// PAYMENT FUNCTIONS
function initiatePayment() {
    let customerId = $('#pos_selectCustomerAccount').data('guest');
    let location = $('#pos_activeLocation').data('activeLocation');

    return $.ajax({
        method: 'POST',
        url: PosURL.initiatePayment,
        data: {
            customer_id: customerId,
            location: location,
            _token: sessionToken
        }
    });
}

function cancelOrder(orderId) {
    return $.ajax({
        method: 'POST',
        url: PosURL.cancelOrder,
        data: {
            order: orderId,
            _token: sessionToken
        }
    });
}

function finalizeOrder(orderId, change) {
    return $.ajax({
        method: 'POST',
        url: PosURL.finalizeOrder,
        data: {
            order: orderId,
            change: change,
            _token: sessionToken
        }
    });
}




function getOrderFromStorage() {
    let order = null;

    if(localStorage.getItem('pos_order') === null) {
        return order;
    } else {
        order = JSON.parse(localStorage.getItem('pos_order'));

        return order;
        //restorePayments(payments);
    }
}

function removeOrderFromStorage() {
    localStorage.removeItem('pos_order');
}

// function removePaymentsFromStorage() {
//     localStorage.removeItem('pos_payments');
// }

// function restorePayments(payments) {
//     for (let i = 0; i < payments.length; i++) {
//         let type = payments[i].type;
//         let amount = payments[i].amount;
//         let status = payments[i].status;

//         let paymentId = payments[i].id;
//         let mollieId = payments[i].mollieId;


//         let formattedAmount = "€ "+amount.toFixed(2).replace('.', ',');

//         if (type == "cash") {
//             type = "Cash";

//             let paymentListItem = `<li class="pos_paymentListItem list-group-item d-flex justify-content-between align-items-center" data-type="cash" data-amount="${amount}" data-status="success"><span>${type}: ${formattedAmount}</span><span class="badge badge-info py-1 pos_removeCashPayment">Verwijderen</span><span class="badge badge-success py-1 paymentStatus">Betaald</span></li>`;

//             $('#pos_paymentList').append(paymentListItem);
//             $('#pos_paymentListWrapper').removeClass('d-none');
//         }

//         if (type == "card") {
//             type = "Kaart";

//             let paymentListItem = `<li class="pos_paymentListItem list-group-item d-flex justify-content-between align-items-center" data-type="pointofsale" data-payment-id="${paymentId}" data-mollie-id="${mollieId}" data-amount="${amount}" data-status="open"><span>${type}: ${formattedAmount}</span><span class="badge badge-info py-1 paymentStatus"><i class="fa fa-sync-alt fa-spin"></i> Verwerken...</span></li>`;

//             $('#pos_paymentList').append(paymentListItem);
//             $('#pos_paymentListWrapper').removeClass('d-none');

//             checkTerminalPayment(paymentId);
//         }
//     }

//     updateTetheredAmount();

//     $('#paymentModal').modal('show');
// }


function addPaymentToList(type, amount) {
    let order = getOrderFromStorage();

    if (type == "cash") {
        let type = "Cash";
        let formattedAmount = "€ "+amount.toFixed(2).replace('.', ',');

        let cashPayment = getCashPayment(order, amount).done(function (data) {
            if (data.status == 'error') {
                popNotifcation(data.notification);
            }

            if (data.status == 'success') {
                let paymentId = data.payment.id;

                let paymentListItem = `<li class="pos_paymentListItem list-group-item d-flex justify-content-between align-items-center" data-type="cash" data-payment-id="${paymentId}" data-amount="${amount}" data-status="success"><span>${type}: ${formattedAmount}</span><span class="badge badge-info py-1 pos_removeCashPayment">Verwijderen</span><span class="badge badge-success py-1 paymentStatus">Betaald</span></li>`;

                $('#pos_paymentList').append(paymentListItem);
                $('#pos_paymentListWrapper').removeClass('d-none');

                updateTetheredAmount();
            }
        });
    }

    if (type == "card") {
        let type = "Kaart";
        let formattedAmount = "€ "+amount.toFixed(2).replace('.', ',');

        let terminalPayment = getTerminalPayment(order, amount).done(function (data) {
            if (data.status == 'error') {
                popNotifcation(data.notification);
            }

            if (data.status == 'success') {
                let paymentId = data.payment.id;
                let mollieId = data.payment.mollie_id;

                let paymentListItem = `<li class="pos_paymentListItem list-group-item d-flex justify-content-between align-items-center" data-type="pointofsale" data-payment-id="${paymentId}" data-mollie-id="${mollieId}" data-amount="${amount}" data-status="open"><span>${type}: ${formattedAmount}</span><span class="badge badge-info py-1 paymentStatus"><i class="fa fa-sync-alt fa-spin"></i> Verwerken...</span></li>`;

                $('#pos_paymentList').append(paymentListItem);
                $('#pos_paymentListWrapper').removeClass('d-none');

                checkTerminalPayment(paymentId);
            }
        });
    }
}

function getCashPayment(order, amount) {
    return $.ajax({
        method: 'POST',
        url: PosURL.cashPayment,
        data: {
            order: order,
            amount: amount,
            _token: sessionToken
        }
    });
}

function getTerminalPayment(order, amount) {
    let location = $('#pos_activeLocation').data('activeLocation');

    return $.ajax({
        method: 'POST',
        url: PosURL.terminalPayment,
        data: {
            order: order,
            amount: amount,
            location: location,
            _token: sessionToken
        }
    });
}

function checkTerminalPayment(paymentId) {
    $.ajax({
        method: 'POST',
        url: PosURL.checkTerminalPayment,
        data: {
            payment: paymentId,
            _token: sessionToken
        }
    }).done(function (data) {
        if (data.status == "awaiting") {
            setTimeout(() => {
                checkTerminalPayment(paymentId);
            }, 1500);
        }

        if (data.status == "canceled") {
            $('.pos_paymentListItem[data-payment-id="'+paymentId+'"]').find('span.paymentStatus').removeClass('badge-info').addClass('badge-danger');
            $('.pos_paymentListItem[data-payment-id="'+paymentId+'"]').find('span.paymentStatus').text('Geannuleerd');
            $('.pos_paymentListItem[data-payment-id="'+paymentId+'"]').attr('data-status', 'canceled');
        }

        if (data.status == "error") {
            $('.pos_paymentListItem[data-payment-id="'+paymentId+'"]').find('span.paymentStatus').removeClass('badge-info').addClass('badge-danger');
            $('.pos_paymentListItem[data-payment-id="'+paymentId+'"]').find('span.paymentStatus').text('Mislukt');
            $('.pos_paymentListItem[data-payment-id="'+paymentId+'"]').attr('data-status', 'error');
        }

        if (data.status == "paid") {
            $('.pos_paymentListItem[data-payment-id="'+paymentId+'"]').find('span.paymentStatus').removeClass('badge-info').addClass('badge-success').text('Betaald');
            $('.pos_paymentListItem[data-payment-id="'+paymentId+'"]').attr('data-status', 'success');

            updateTetheredAmount();
            //update tethered amount and create order if fully paid!
        }
    });
}

function removePayment(paymentId) {
    return $.ajax({
        method: 'POST',
        url: PosURL.removePayment,
        data: {
            payment: paymentId,
            _token: sessionToken
        }
    });
}

function updateTetheredAmount() {
    let final = Number($('#pos_checkoutBtn').data('final')).toFixed(2);

    let paid = 0;
    // let payments = [];

    let loop = 0;
    $('.pos_paymentListItem[data-status="success"]').each(function () {
        paid += Number($(this).data('amount'));
        // payments.push({
        //     id: $(this).data('type') == 'cash' ? loop : $(this).data('paymentId'),
        //     mollieId: $(this).data('type') == 'cash' ? null : $(this).data('mollieId'),
        //     type: $(this).data('type'),
        //     amount: $(this).data('amount'),
        //     status: $(this).data('status')
        // });
    });

    let pendingAmount = final - paid;

    let formatted = "€ "+pendingAmount.toFixed(2).replace('.', ',');

    $('.pos_checkoutPendingAmount').text(formatted);
    $('.pos_checkoutPendingAmount').data('amount', pendingAmount);

    $('.pos_checkoutCashFitPayment').text(formatted);
    $('.pos_checkoutCardFitPayment').text(formatted);

    if(pendingAmount <= 0) {
        $('#pos_finalizeOrderBtn').prop('disabled', false);
    } else {
        $('#pos_finalizeOrderBtn').prop('disabled', true);
    }
}

function printTicketFromOrder(order, payments, order_date, order_time) {
    let items = getFormattedItemsForTicket(order.json.products, order.json.discounts);
    let vat = getFormattedVatItemsForTicket(order.json.products);
    let location = getActiveLocationDetails($('#pos_activeLocation').data('activeLocation'));
    let orderPayments = getPaymentsForOrder(order, payments);

    let job = {
        location: location,
        items: items,
        subtotal: order.subtotal,
        discount: order.discount,
        total: order.total,
        vat: vat,
        payments: orderPayments,
        date: order_date,
        time: order_time,
        customer: order.customer_id
    };

    printJob(job);
}

function getActiveLocationDetails(location_id) {
    let elem = $('#pos_activeLocation');
    let location = {
        id: location_id,
        name: elem.attr('data-pos-name'),
        address1: elem.attr('data-pos-address'),
        address2: (elem.attr('data-pos-address-t') !== undefined ? elem.attr('data-pos-address-t') : null),
        vat: elem.attr('data-pos-vat'),
        receipt_title: elem.attr('data-pos-receipt-title'),
        receipt_footer1: (elem.attr('data-pos-receipt-footer-line') !== undefined ? elem.attr('data-pos-receipt-footer-line') : null),
        receipt_footer2: (elem.attr('data-pos-receipt-footer-line-t') !== undefined ? elem.attr('data-pos-receipt-footer-line-t') : null),
        receipt_footer3: (elem.attr('data-pos-receipt-footer-line-tt') !== undefined ? elem.attr('data-pos-receipt-footer-line-tt') : null)
    };

    return location;
}

function getPaymentsForOrder(order, payments) {
    let orderPayments = [];

    for (let p = 0; p < payments.length; p++) {
        if (payments[p].status !== "paid") {
            continue;
        }

        let paymentType;

        if (payments[p].type == "cash") {
            paymentType = 'cash';
        }

        if (payments[p].type == "pointofsale") {
            paymentType = 'card';
        }

        if (payments[p].type == "change") {
            paymentType = 'change';
        }

        let paymentAmount = payments[p].amount;

        orderPayments.push({
            type: paymentType,
            value: paymentAmount
        });
    }

    return orderPayments;

    // let paidByCard = $('.cof_checkoutCardInput').val();
    // let paidByCash = $('.cof_checkoutCashInput').val();

    // let total_tethered = Number(paidByCard) + Number(paidByCash);
    // let pendingAmount = total - total_tethered;

    // if (Number(paidByCash) > 0) {
    //     payments.push({type:"cash",value:Number(parseFloat(paidByCash).toFixed(2))});
    // }

    // if (Number(paidByCard) > 0) {
    //     payments.push({type:"card",value:Number(parseFloat(paidByCard).toFixed(2))});
    // }

    // payments.push({type:"change",value:Number(parseFloat(pendingAmount).toFixed(2))});

    // return payments;
}

function getFormattedItemsForTicket(products, discounts) {
    let items = [];

    let productsArray = Object.values(products);

    for (var p = 0; p < productsArray.length; p++) {
        if ( (p + 1) == productsArray.length) {
            items = items.concat(formatLinesForProduct(productsArray[p], true, false));
        } else {
            items = items.concat(formatLinesForProduct(productsArray[p], false, checkIfProductHasOptionsOrExtrasOrDiscounts(productsArray[p+1])));
        }
    };
    return items;
}

function getFormattedVatItemsForTicket(products) {
    let vatLines = [];
    let vatLine = "";

    totalPricesPerVatRate = {};
    totalVat = 0;

    for (var i = 0; i < products.length; i++) {
        let vat_rates = getAllVatPercentagesForProduct(products[i]);
        for (var vrate = 0; vrate < vat_rates.length; vrate++) {
            vat_rate = vat_rates[vrate];

            if(vat_rate in totalPricesPerVatRate) {
                totalPricesPerVatRate[vat_rate] = (totalPricesPerVatRate[vat_rate] + getVatForRateForProduct(vat_rate, products[i]));
            } else {
                totalPricesPerVatRate[vat_rate] = getVatForRateForProduct(vat_rate, products[i]);
            }
        };
    };

    Object.keys(totalPricesPerVatRate).forEach(function(rate) {
        vatLine = "";

        if (rate == 21) {
            vatLine += "  A     ";
        }
        if (rate == 12) {
            vatLine += "  B     ";
        }
        if (rate == 6) {
            vatLine += "  C     ";
        }
        if (rate == 0) {
            vatLine += "  D     ";
        }

        totalPriceWithoutVatRaw = Number(totalPricesPerVatRate[rate].toFixed(2)) - Number(getVatFromPriceAndRate(totalPricesPerVatRate[rate], rate).toFixed(2));

        if (totalPriceWithoutVatRaw <= 9.99) {
            vatLine += " ";
        }

        totalPriceWithoutVat = Number(totalPriceWithoutVatRaw.toFixed(2)).toFixed(2);

        vatLine += totalPriceWithoutVat;
        vatLine += "  @  ";
        if (rate < 10) {
            vatLine += " ";
        }
        vatLine += rate+"%";

        vatLine += "  ";
        if (Number(getVatFromPriceAndRate(totalPricesPerVatRate[rate], rate).toFixed(2)) <= 9.99) {
            vatLine += " ";
        }
        vatLine += Number(getVatFromPriceAndRate(totalPricesPerVatRate[rate], rate).toFixed(2)).toFixed(2);

        vatLines.push(vatLine);

        totalVat = totalVat + Number(getVatFromPriceAndRate(totalPricesPerVatRate[rate], rate).toFixed(2));

        // vatLine = "";
        // vatLine += "VAT                    ";

        // if (Number(getVatFromPriceAndRate(totalPricesPerVatRate[rate], rate).toFixed(2)) <= 9.99) {
        //     vatLine += " ";
        // }

        // vatLine += Number(getVatFromPriceAndRate(totalPricesPerVatRate[rate], rate).toFixed(2));

        // vatLines.push(vatLine);
    });

    vatLine = "";
    vatLine += "VAT                            ";
    vatLine += Number((totalVat).toFixed(2)).toFixed(2);

    vatLines.push(vatLine);

    return vatLines;
}

function formatLinesForProduct(product, isLastProduct, nextProductHasOptionsOrExtrasOrDiscounts) {
    let lines = [];
    let line = "";

    line += product.quantity;
    line += " ";
    line += product.title;

    let productHasExtras = false;
    for (var phex = 0; phex < product.extras.length; phex++) {
        if (!$.isEmptyObject(product.extras[phex])) {
            productHasExtras = true;
        }
    };

    // let productHasSubproducts = false;
    // for (var phex = 0; phex < product.subproducts.length; phex++) {
    //     if (!$.isEmptyObject(product.subproducts[phex])) {
    //         productHasSubproducts = true;
    //     }
    // };

    // if (product.attribute !== "" && product.attribute.length > 0 && !productHasExtras ) {
    //     line += ": ";
    //     line += product.attribute;
    // }

    if (line.length < 41) {
        productLineLength = (40 - line.length);
        for (var ll = 0; ll < productLineLength; ll++) {
            line += " ";
        };
    }

    if (line.length > 40) {
        line = truncateString(line, 37);
    }

    if (product._price._final > 9.99 && product._price._final < 100 ) {
        line += " ";
    }

    if (product._price._final > 0.99 && product._price._final < 10) {
        line += "  ";
    }

    line += product.total;

    if (productHasExtras) {
        line += "  ";
    } else {

        if (getVatPercentageForProduct(product.id) == 21) {
            line += " A";
        }

        if (getVatPercentageForProduct(product.id) == 12) {
            line += " B";
        }

        if (getVatPercentageForProduct(product.id) == 6) {
            line += " C";
        }

        if (getVatPercentageForProduct(product.id) == 0) {
            line += " D";
        }
    }

    lines.push(line);



    // if ( (product.attribute !== "" && product.attribute.length > 0) && productHasExtras ) {
    //     let unitExtrasPrice = 0;
    //     for (var pextra = 0; pextra < product.extras.length; pextra++) {
    //         if (!$.isEmptyObject(product.extras[pextra])) {
    //             unitExtrasPrice = unitExtrasPrice + Number(parseFloat(product.extras[pextra].value).toFixed(2));
    //         }
    //     }
    //     let attributePrice = Number((product.quantity * (product.current_price - unitExtrasPrice)).toFixed(2));

    //     let attributePriceLine = attributePrice.toFixed(2)+"";
    //     if (getVatPercentageForProduct(product.id) == 21) {
    //         attributePriceLine += " A";
    //     }

    //     if (getVatPercentageForProduct(product.id) == 12) {
    //         attributePriceLine += " B";
    //     }

    //     if (getVatPercentageForProduct(product.id) == 6) {
    //         attributePriceLine += " C";
    //     }

    //     if (getVatPercentageForProduct(product.id) == 0) {
    //         attributePriceLine += " D";
    //     }

    //     let attributeLine = "  1 "+product.attribute;

    //     let neededAttributeLineLength = (48 - attributeLine.length - attributePriceLine.length);
    //     for (var ell = 0; ell < neededAttributeLineLength; ell++) {
    //         attributeLine += " ";
    //     };
    //     attributeLine += attributePriceLine;

    //     lines.push(attributeLine);
    // }



    if (product.options.length > 0) {
        //@TODO:  loop over object to get key and value
        for (var pop = 0; pop < product.options.length; pop++) {
            let optionLine = "";
            optionLine += "    ";
            optionLine += product.options[pop].name+": "+product.options[pop].value;

            lines.push(optionLine);
        }
    }

    if (product.extras.length > 0) {
        for (var pex = 0; pex < product.extras.length; pex++) {
            if (!$.isEmptyObject(product.extras[pex])) {
                let extraLine = "";
                extraLine += "  1 ";
                extraLine += product.extras[pex].name;

                if (extraLine.length < 41) {
                    let neededLineLength = (40 - extraLine.length);
                    for (var ell = 0; ell < neededLineLength; ell++) {
                        extraLine += " ";
                    };
                }

                if (extraLine.length > 40) {
                    extraLine = truncateString(extraLine, 37);
                }

                let extrasUnitPrice = Number(parseFloat(product.extras[pex].value).toFixed(2));
                let extrasPrice = Number((product.quantity * extrasUnitPrice).toFixed(2));

                if (extrasPrice > 9.99 && extrasPrice < 100 ) {
                    extraLine += " ";
                }

                if (extrasPrice > 0.99 && extrasPrice < 10) {
                    extraLine += "  ";
                }

                if (extrasPrice == 0) {
                    extraLine += "  ";
                }

                extraLine += (extrasPrice).toFixed(2);

                if (getVatPercentageForExtra(product.extras[pex]) == 21) {
                    extraLine += " A";
                }

                if (getVatPercentageForExtra(product.extras[pex]) == 12) {
                    extraLine += " B";
                }

                if (getVatPercentageForExtra(product.extras[pex]) == 6) {
                    extraLine += " C";
                }

                if (getVatPercentageForExtra(product.extras[pex]) == 0) {
                    extraLine += " D";
                }

                lines.push(extraLine);
            }
        };
    }

    // if (product.subproducts.length > 0) {
    //     for (var psub = 0; psub < product.subproducts.length; psub++) {
    //         if (!$.isEmptyObject(product.subproducts[psub])) {
    //             let extraLine = "";
    //             extraLine += "  1 ";
    //             let products = product.subproducts[psub].products;
    //             for(var psubp = 0; psubp < products.length; psubp++){
    //                 extraLine += products[psubp].p_name+" x "+products[psubp].p_qty+": ";
    //                 if (extraLine.length < 41) {
    //                 let neededLineLength = (40 - extraLine.length);
    //                     for (var ell = 0; ell < neededLineLength; ell++) {
    //                         extraLine += " ";
    //                     };
    //                 }

    //                 if (extraLine.length > 40) {
    //                     extraLine = truncateString(extraLine, 37);
    //                 }

    //                 let extraSubProductPrice = parseFloat(products[psubp].p_qty*products[psubp].p_extra_price).toFixed(2);

    //                 if (extraSubProductPrice > 9.99 && extraSubProductPrice < 100 ) {
    //                     extraLine += " ";
    //                 }

    //                 if (extraSubProductPrice > 0.99 && extraSubProductPrice < 10) {
    //                     extraLine += "  ";
    //                 }

    //                 if (extraSubProductPrice == 0) {
    //                     extraLine += "  ";
    //                 }
    //                 extraLine += extraSubProductPrice;
    //             }

    //             lines.push(extraLine);
    //         }
    //     };
    // }

    if (product.discounts.length > 0 && product.discount > 0) {
        let discountLine = "";
        discountLine += "    KORTING: ";

        for (var pds = 0; pds < product.discounts.length; pds++) {
            if (pds > 0) {
                discountLine += ", ";
            }
            discountLine += product.discounts[pds].name;
        };

        if (discountLine.length < 41) {
            let neededDiscountLineLength = (40 - discountLine.length);
            for (var dll = 0; dll < neededDiscountLineLength; dll++) {
                discountLine += " ";
            };
        }

        if (discountLine.length > 40) {
            discountLine = truncateString(discountLine, 37);
        }

        let discountPrice = Number(parseFloat(product.discount).toFixed(2));

        if (discountPrice > 9.99 && discountPrice < 100 ) {
            discountLine += "-";
        }

        if (discountPrice > 0.99 && discountPrice < 10) {
            discountLine += " -";
        }

        if (discountPrice == 0) {
            discountLine += " -";
        }

        discountLine += (discountPrice).toFixed(2);

        if (getVatPercentageForProduct(product.id) == 21) {
            discountLine += " A";
        }

        if (getVatPercentageForProduct(product.id) == 12) {
            discountLine += " B";
        }

        if (getVatPercentageForProduct(product.id) == 6) {
            discountLine += " C";
        }

        if (getVatPercentageForProduct(product.id) == 0) {
            discountLine += " D";
        }

        lines.push(discountLine);
    }

    if ( ((product.options.length > 0 || productHasExtras) && !isLastProduct) || nextProductHasOptionsOrExtrasOrDiscounts) {
        lines.push(" ");
    }

    return lines;
}
// PAYMENT FUNCTIONS



















// $('body').on('click', '#pos_addNewCart', function (event) {
//     event.preventDefault();

//     cartId = getNewCartId();
//     addNewCartTabLink(cartId);
//     addNewCartTab(cartId);
//     storeNewCart(cartId);
//     activateCart(cartId);
// });

// $('body').on('click', '.pos_cartTabListBtn', function (event) {
//     cart_id = $(this).attr('data-cart-id');
//     activateCart(cart_id);
// });

$('body').on('click', '.cof_pos_product_card', function (event) {
    event.preventDefault();
    product_id = $(this).attr('data-product-id');

    if (productNeedsModal(product_id)) {
        addProductToModal($(this));
        return;
    }

    product = getProductDetails(product_id)
    addToCart(product);
});

$('body').on('click', '#addProductFromModalToCartButton', function (event) {
    event.preventDefault();
    product_id = $(this).attr('data-product-id');


    if(!$('#subproductModalBody').hasClass('d-none')) {
        let checker = [];
        $('.subproduct_group_modal_row').each(function() {
            let selected = parseInt(
                $(this).find(".subproduct_product_group_selected").text()
            );
            let maximum = parseInt(
                $(this).find(".subproduct_product_group_max").text()
            );
            if (selected !== maximum){

                checker.push(false);
                $(this)
                    .find('.subproduct_product_group_selected')
                    .addClass('text-danger');

            } else{
                checker.push(true);
            }
        });
        if (checker.includes(false)) {
            return;
        }
    }

    product = getProductDetailsFromModal(product_id);

    addToCart(product);

    $('#optionsModal').modal('hide');
    return;
});

$('body').on('click', '.cof_cartProductListItemAddition', function (event) {
    event.preventDefault();

    let cart_id = getActiveCart();
    let product = getProductDetailsFromCartItemElement(cart_id, $(this), false);

    addToCart(product);
});

$('body').on('click', '.cof_cartProductListItemSubtraction', function (event) {
    event.preventDefault();

    let cart_id = getActiveCart();
    let product = getProductDetailsFromCartItemElement(cart_id, $(this), -1);

    addToCart(product);
});

$('body').on('click', '.cof_cartTabRemove', function (event) {
    event.preventDefault();
    event.stopPropagation();

    cart_id = $(this).parent().attr('data-cart-id');
    removeCart(cart_id);

});

$('body').on('click', '#cof_selectCustomerAccount', function (event) {
    event.preventDefault();

    $('#customerModal').modal('show');
});

$('body').on('click', '#cof_selectCustomerForCartBtn', function (event) {
    event.preventDefault();

    cart_id = getActiveCart();
    customer_id = getSelectedCustomer();

    updateCustomerForCart(customer_id, cart_id);

    $('#customerModal').modal('hide');
});

$('body').on('click', '#openCouponsModal', function (event) {
    event.preventDefault();

    $('#couponsModal').modal('show');
});

$('body').on('click', '#cof_addSelectedCouponToCartBtn', function (event) {
    event.preventDefault();

    cart_id = getActiveCart();
    addSelectedCouponToCart(cart_id);
});

$('body').on('click', '#cof_cancelSelectCouponBtn', function (event) {
    event.preventDefault();

    closeCouponModal();
});

$('body').on('click', '.cof_cartCouponItemRemoveBtn', function (event) {
    event.preventDefault();

    cart_id = getActiveCart();
    removeCouponFromCart($(this).parent().attr('data-coupon'), cart_id, true);
});



$('body').on('click', '#options-form .options_modal_item_radio label.form-check-label', function (event) {
    $(this).siblings('input').first().prop('checked', true);
});

$('body').on('click', '.betaalArea #cof_placeOrderBtnNow', function (event) {
    event.preventDefault();

    openPaymentModal(getActiveCart());
});

$('body').on('click', '.cof_checkoutCashFitPayment', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }

    cartId = getActiveCart();
    $('.cof_checkoutCashInput').val(getTotalPrice(cartId));
    $('.cof_checkoutCashInput').trigger('change');

});

$('body').on('click', '.cof_checkoutCardFitPayment', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }

    cartId = getActiveCart();
    $('.cof_checkoutCardInput').val(getTotalPrice(cartId));
    $('.cof_checkoutCardInput').trigger('change');

});

$('body').on('click', '.cof_checkoutCashAddPayment', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }

    let newCashAmount = ( Number($('.cof_checkoutCashInput').val()) + parseInt($(this).attr('data-amount')) );
    $('.cof_checkoutCashInput').val(newCashAmount);
    $('.cof_checkoutCashInput').trigger('change');
});

$('body').on('click', '.cof_checkoutCashPaymentReset', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }

    $('.cof_checkoutCashInput').val('0.00');
    $('.cof_checkoutCashInput').trigger('change');
});

$('body').on('click', '.cof_checkoutCardPaymentReset', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }

    $('.cof_checkoutCardInput').val('0.00');
    $('.cof_checkoutCardInput').trigger('change');
});

$('body').on('change', '.cof_checkoutCashInput,.cof_checkoutCardInput', function (event) {
    event.preventDefault();

    if(cof_pos_processing_order) {
        return;
    }

    let paidByCard = $('.cof_checkoutCardInput').val();
    let paidByCash = $('.cof_checkoutCashInput').val();

    let total_tethered = Number(paidByCard) + Number(paidByCash);

    let cartId = getActiveCart();

    let pendingAmount = getTotalPrice(cartId) - total_tethered;

    $('.cof_checkoutPendingAmount').text(formatPrice(pendingAmount));

    if(pendingAmount <= 0) {
        $('#cof_finalizeOrderBtn').prop('disabled', false);
    } else {
        $('#cof_finalizeOrderBtn').prop('disabled', true);
    }
});

$('body').on('click', '#cof_cancelOrderBtn', function (event) {
    event.preventDefault();

    cof_pos_processing_order = false;

    $('#cof_finalizeOrderBtn').prop('disabled', true);
    $('#cof_finalizeOrderBtn').html('Bestelling voltooien');

    $('#paymentModal').modal('hide');
});

// $('body').on('click', '#cof_finalizeOrderBtn', function (event) {
//     event.preventDefault();

//     cof_pos_processing_order = true;

//     $(this).prop('disabled', true);
//     $(this).html('<i class="fa fa-sync-alt fa-spin"></i> Verwerken... ');

//     let cartId = getActiveCart();

//     if(validateCartForOrder(cartId)) {
//         placeOrderFromCart(cartId)
//     }
// });


// $('body').on('click', '.subproduct_group_product_qty .addbtn', function(event){
//     event.preventDefault();

//     let upperLimit = parseInt($(this).closest('.subproduct_group_modal_row').find('.subproduct_product_group_max').text()) - parseInt($(this).closest('.subproduct_group_modal_row').find('.subproduct_product_group_selected').text());
//     let max = $(this).closest('.subproduct_group_product').attr('data-max-qty');
//     let current_total_price = $('#optionsModal #addProductFromModalToCartButton').attr('data-total-price');
//     let this_extra_price = parseFloat($(this).closest('.subproduct_group_product').attr('data-extra-price'));
//     let newValue = parseInt($(this).closest('.subproduct_group_product_qty').find('.product_qty').val()) + 1;

//     product_id = $('#optionsModal #addProductFromModalToCartButton').attr('data-product-id');

//     if (upperLimit !== 0 && (max == '-1' || newValue < max)){
//         $(this).closest('.subproduct_group_modal_row').find('.subproduct_product_group_selected').removeClass('text-danger');
//         $(this).closest('.subproduct_group_product_qty').find('.product_qty').val(newValue);
//         $(this).closest('.subproduct_group_modal_row').find('.subproduct_product_group_selected').text(parseInt($(this).closest('.subproduct_group_modal_row').find('.subproduct_product_group_selected').text()) + 1);
//         // $('#optionsModal #addProductFromModalToCartButton').attr('data-total-price', parseFloat(current_total_price) + this_extra_price);
//         let dcp = parseFloat($('#subproduct_group_total_price').attr('data-current-price')) + this_extra_price;
//         $('#optionsModal #subproduct_group_total_price').attr('data-current-price', dcp)
//         $('#optionsModal #subproduct_group_total_price').text('€ '+ dcp.toFixed('2').replace('.', ','));
//     }
// });
// $('body').on('click', '.subproduct_group_product_qty .reducebtn', function(event){
//     event.preventDefault();
//     // let current_total_price = $('#optionsModal #addProductFromModalToCartButton').attr('data-total-price');
//     let this_extra_price = parseFloat($(this).closest('.subproduct_group_product').attr('data-extra-price'));
//     let newValue = parseInt($(this).closest('.subproduct_group_product_qty').find('.product_qty').val()) - 1;

//     if (newValue >= 0){
//         $(this).closest('.subproduct_group_product_qty').find('.product_qty').val(newValue);
//         $(this).closest('.subproduct_group_modal_row').find('.subproduct_product_group_selected').text(parseInt($(this).closest('.subproduct_group_modal_row').find('.subproduct_product_group_selected').text()) - 1);

//         let dcp = parseFloat($('#subproduct_group_total_price').attr('data-current-price')) - this_extra_price;
//         // $('#optionsModal #addProductFromModalToCartButton').attr('data-total-price', parseFloat(current_total_price) - this_extra_price);
//         $('#optionsModal #subproduct_group_total_price').attr('data-current-price', dcp)
//         $('#optionsModal #subproduct_group_total_price').text('€ '+ dcp.toFixed('2').replace('.', ','));
//     }
// });













/* INIT */
/* INIT */
/* INIT */
/* INIT */
/* INIT */
/* INIT */
/* INIT */
/* INIT */
function init() {
    //restoreCartsFromStorage();
    //calculateProductAvailability();
}
/* END OF INIT */
/* END OF INIT */
/* END OF INIT */
/* END OF INIT */
/* END OF INIT */
/* END OF INIT */
/* END OF INIT */
/* END OF INIT */








/* LOCATION FUNCTIONS */
/* LOCATION FUNCTIONS */
/* LOCATION FUNCTIONS */
/* LOCATION FUNCTIONS */
/* LOCATION FUNCTIONS */
/* LOCATION FUNCTIONS */
/* LOCATION FUNCTIONS */
/* LOCATION FUNCTIONS */
function changeActiveLocation(elem) {
    let locationId = elem.attr('data-location-id');
    let locationName = elem.text();

    let locationPosName = elem.attr('data-pos-name');
    let locationPosAddress1 = elem.attr('data-pos-address');
    let locationPosAddress2 = elem.attr('data-pos-address-t');
    let locationPosVat = elem.attr('data-pos-vat');
    let locationPosReceiptTitle = elem.attr('data-pos-receipt-title');
    let locationPosReceiptFooterLine1 = elem.attr('data-pos-receipt-footer-line');
    let locationPosReceiptFooterLine2 = elem.attr('data-pos-receipt-footer-line-t');
    let locationPosReceiptFooterLine3 = elem.attr('data-pos-receipt-footer-line-tt');

    $('#cof_pos_location').attr('data-active-location', locationId);

    $('#cof_pos_location').attr('data-pos-name', locationPosName);
    $('#cof_pos_location').attr('data-pos-address', locationPosAddress1);
    $('#cof_pos_location').attr('data-pos-address-t', locationPosAddress2);
    $('#cof_pos_location').attr('data-pos-vat', locationPosVat);
    $('#cof_pos_location').attr('data-pos-receipt-title', locationPosReceiptTitle);
    $('#cof_pos_location').attr('data-pos-receipt-footer-line', locationPosReceiptFooterLine1);
    $('#cof_pos_location').attr('data-pos-receipt-footer-line-t', locationPosReceiptFooterLine2);
    $('#cof_pos_location').attr('data-pos-receipt-footer-line-tt', locationPosReceiptFooterLine3);

    $('#cof_pos_location').text(locationName);
    cof_pos_active_location = locationId;
}
/* END OF LOCATION FUNCTIONS */
/* END OF LOCATION FUNCTIONS */
/* END OF LOCATION FUNCTIONS */
/* END OF LOCATION FUNCTIONS */
/* END OF LOCATION FUNCTIONS */
/* END OF LOCATION FUNCTIONS */
/* END OF LOCATION FUNCTIONS */
/* END OF LOCATION FUNCTIONS */








/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
function addNewCartTabLink(cartId) {

    $('.pos_cartTabListBtn:first').clone().appendTo('.cof_cartTabList:first').insertAfter($('#pos_addNewCart'));
    $('.pos_cartTabListBtn:not(:first)').removeClass('active');
    $('.pos_cartTabListBtn:not(:first)').attr('aria-selected', false);

    $('.pos_cartTabListBtn:first').attr('id', 'cof_cart_'+cartId+'_Tab');
    $('.pos_cartTabListBtn:first').attr('data-cart-id', cartId);
    $('.pos_cartTabListBtn:first').attr('href', '#cof_cart_'+cartId+'_');
    $('.pos_cartTabListBtn:first').attr('aria-controls', 'cof_cart_'+cartId+'_Tab');

    $('.pos_cartTabListBtn:first').find('span:first').html('Cart: #'+cartId+' (<span class="cof_cartTotalQuanity" data-cof-quantity="0">0</span>)')
}

function addNewCartTab(cartId) {
    $('.cof_cartTab:last').clone().appendTo('#bestelNavigationTabContent');
    $('.cof_cartTab:not(:last)').removeClass('show').removeClass('active');
    $('.cof_cartTab:last').addClass('show').addClass('active');

    $('.cof_cartTab:last').attr('id', 'cof_cart_'+cartId+'_');
    $('.cof_cartTab:last').attr('aria-labelledby', 'cof_cart_'+cartId+'_Tab');
    $('.cof_cartTab:last').attr('data-cart-id', cartId);

    $('#cof_selectedCustomerEmail').text(getCustomerEmail(getGuestCustomer()));

    resetCartTab(cartId);

    carts = getAllCartsFromStorage();
}

function removeCart(cartId) {
    let carts = getAllCartsFromStorage();

    if (carts.length > 1) {
        removeCartTab(cartId);
        removeCartFromStorage(cartId);
        $('.pos_cartTabListBtn:first').trigger('click');
    } else if(carts.length == 1) {
        removeCartFromStorage(cartId);
        restoreCartsFromStorage();
    }
}

function removeCartTab(cartId) {
    $('.pos_cartTabListBtn[data-cart-id='+cartId+']').remove();
    $('.cof_cartTab[data-cart-id='+cartId+']').remove();
}

function removeCartFromStorage(cartId) {
    let carts = getAllCartsFromStorage();

    for (var i = 0; i < carts.length; i++) {
        if( carts[i].id == cartId) {
            carts.splice(i, 1);
            localStorage.setItem('cof_carts', JSON.stringify(carts));
            return;
        }
    };
}

function activateCart(cartId) {
    calculateTotalPrice(cartId);
    displayDiscountsForCart(cartId);
    deactivateAllCartsBut(cartId);
    $('#cof_selectedCustomerEmail').text(getCustomerEmail(getCustomerForCartFromStorage(cartId)));
}

function deactivateAllCartsBut(cartId) {
    let carts = getAllCartsFromStorage();

    for (var i = 0; i < carts.length; i++) {
        carts[i].active = false;
        if( carts[i].id == cartId ) {
            carts[i].active = true;
        }
    };

    localStorage.setItem('cof_carts', JSON.stringify(carts));
}

function getAllCartsFromStorage() {
    if(localStorage.getItem('cof_carts') === null) {
        carts = [];
        localStorage.setItem('cof_carts', JSON.stringify(carts));
    } else {
        carts = JSON.parse(localStorage.getItem('cof_carts'));
    }

    return carts;
}

function getCartFromStorage(cartId) {
    let carts = getAllCartsFromStorage();

    for (var g = 0; g < carts.length; g++) {
        if(carts[g].id == cartId) {
            return carts[g];
        }
    }
}

function getNewCartId() {
    if(localStorage.getItem('cof_carts_daily_count') === null) {
        carts_count = 1;
    } else {
        carts_count = parseInt(JSON.parse(localStorage.getItem('cof_carts_daily_count'))) + 1;
    }

    localStorage.setItem('cof_carts_daily_count', JSON.stringify(carts_count));

    return carts_count;
}

function getActiveCart() {
    cart_id = $('.pos_cartTabListBtn.active').attr('data-cart-id');
    return cart_id;
    // carts = getAllCartsFromStorage();
    // cartObject = [];
    // carts.each((cart)=>{
    //     if(cart.id == cart_id) {
    //         cartObject = cart;
    //     }
    // });

    // return cartObject;
}

function OLDaddToCart(product, cartId = null) {
    product_id = product.id
    cart_id = cartId === null ? getActiveCart() : cartId;

    cart_count = cartCount(cart_id);

    if (isCartEmpty(cart_id)) {
        $('.cof_cartTab[data-cart-id='+cart_id+']').find('.cof_CartProductList').show();

        selector = '.cof_cartProductListItem:first';
        updateProductListItemAttributes(
            cart_id,
            selector,
            product_id,
            product.name,
            product.attribute,
            JSON.stringify(product.options),
            JSON.stringify(product.extras),
            JSON.stringify(product.subproducts),
            product.quantity,
            product.current_price,
            product.total_price
        );

        if (cartId === null) {
            addProductToCartInStorage(product, cart_id);
        }
    } else if (cartHasProduct(cart_id, product)) {
        selector = cartProductListItemUniqueSelector(cart_id, product);
        selector = ".cof_cartProductListItem[data-product-id="+product_id+"][data-unique-el="+selector+"]";
        status = updateProductListItemQuantity(cart_id, selector, product.quantity, product.total_price);

        if (cartId === null && status == 'removed') {
            removeProductToCartInStorage(product, cart_id);
        } else if (cartId === null && status == 'updated') {
            updateProductToCartInStorage(product, cart_id);
        }
    } else {
        $('.cof_cartTab[data-cart-id='+cart_id+']').find('.cof_cartProductListItem:first').clone()
        .appendTo($('.cof_cartTab[data-cart-id='+cart_id+']').find('.cof_CartProductList'));

        selector = '.cof_cartProductListItem:last';

        updateProductListItemAttributes(
            cart_id,
            selector,
            product_id,
            product.name,
            product.attribute,
            JSON.stringify(product.options),
            JSON.stringify(product.extras),
            JSON.stringify(product.subproducts),
            product.quantity,
            product.current_price,
            product.total_price);

        if (cartId === null) {
            addProductToCartInStorage(product, cart_id);
        }

        $('#cof_CartProductListPriceLine').insertAfter($('.cof_cartProductListItem:last'));
        $('#cof_CartProductListShippingLine').insertAfter($('.cof_cartProductListItem:last'));
    }

    updateCartCount(cart_id, (cart_count + parseInt(product.quantity)))

    //$('#cof_cartTotalQuanity').attr('data-cof-quantity', cart_count + parseInt(product.quantity));
    //$('#cof_cartTotalQuanity').text(cart_count + parseInt(product.quantity));

    //reset original product tile qty input to 1
    $('.cof_productQuantityInput[data-product-id='+product_id+']').val(1);

}

function addProductToCartInStorage(product, cartId) {
    let carts = getAllCartsFromStorage();

    for (var i = 0; i < carts.length; i++) {
        if(carts[i].id == cartId) {
            products = carts[i].products;
            products.push({
                id: product.id,
                name: product.name,
                attribute: product.attribute,
                options: product.options,
                extras: product.extras,
                subproducts: product.subproducts,
                quantity: product.quantity,
                current_price: product.current_price,
                total_price: product.total_price,
                vat: product.vat
            });

            carts[i].products = products;
            localStorage.setItem('cof_carts', JSON.stringify(carts));
            calculateTotalPrice(cartId)
            return;
        }
    };
}

function removeProductToCartInStorage(product, cartId) {
    let carts = getAllCartsFromStorage();

    for (var g = 0; g < carts.length; g++) {
        if(carts[g].id == cartId) {

            let products = carts[g].products;

            for (var i = 0; i < products.length; i++) {
                if (products[i].id == product.id && products[i].attribute == product.attribute && JSON.stringify(products[i].options) == JSON.stringify(product.options) && JSON.stringify(products[i].extras) == JSON.stringify(product.extras) ) {
                    productIndex = i;
                    productPrice = product.total_price;
                }
            };

            products.splice(productIndex, 1);

            carts[g].products = products;
            localStorage.setItem('cof_carts', JSON.stringify(carts));
            resetCouponsForCart(cartId);
            calculateTotalPrice(cartId);
            return;
        }
    };
}

function updateProductToCartInStorage(product, cartId) {
    let carts = getAllCartsFromStorage();

    for (var g = 0; g < carts.length; g++) {
        if(carts[g].id == cartId) {
            total_price = 0;
            //vat_total_price = 0;
            let products = carts[g].products;

            for (var i = 0; i < products.length; i++) {
                if (products[i].id == product.id && products[i].attribute == product.attribute && JSON.stringify(products[i].options) == JSON.stringify(product.options) && JSON.stringify(products[i].extras) == JSON.stringify(product.extras) && JSON.stringify(products[i].subproducts) == JSON.stringify(product.subproducts) ) {
                    products[i].quantity = parseInt(products[i].quantity) + parseInt(product.quantity);
                    products[i].total_price = parseFloat(products[i].total_price) + parseFloat(product.total_price);
                }
                total_price = total_price + products[i].total_price;
            };
            carts[g].products = products;
            localStorage.setItem('cof_carts', JSON.stringify(carts));
            calculateTotalPrice(cartId);
            return;
        }
    };
}

function cartCount(cart_id) {
    // carts = getAllCartsFromStorage();
    // cart_count = 0;
    // carts.each((cart)=>{
    //     if(cart.id == cart_id) {
    //         cart_count = cart.count;
    //     }
    // });

    // return parseInt(cart_count);
    return parseInt($('.pos_cartTabListBtn[data-cart-id='+cart_id+']').find('.cof_cartTotalQuanity').attr('data-cof-quantity'));
}

function updateCartCount(cart_id, cart_count) {
    $('.pos_cartTabListBtn[data-cart-id='+cart_id+']').find('.cof_cartTotalQuanity').attr('data-cof-quantity', cart_count);
    $('.pos_cartTabListBtn[data-cart-id='+cart_id+']').find('.cof_cartTotalQuanity').text(cart_count);
}

function isCartEmpty(cart_id) {
    if (cartCount(cart_id) > 0) {
        return false;
    }

    return true;
}

function cartHasProduct(cart_id, product) {
    checker = false;

    if (!$('.cof_cartTab[data-cart-id='+cart_id+']').find('.cof_cartProductListItem[data-product-id='+product.id+']').length > 0) {
        return checker;
    }
    product_extras_json = JSON.stringify(product.extras) == '[]' ? '' : JSON.stringify(product.extras);
    product_subproducts_json = JSON.stringify(product.subproducts) == '[]' ? '' : JSON.stringify(product.subproducts);
    product_options_json = JSON.stringify(product.options) == '[]' ? '' : JSON.stringify(product.options);

    $('.cof_cartTab[data-cart-id='+cart_id+']').find('.cof_cartProductListItem[data-product-id='+product.id+']').each(function() {
        if(product.attribute == $(this).attr('data-attribute-name') && ""+product_options_json+"" == $(this).attr('data-product-options') && ""+product_extras_json+"" == $(this).attr('data-product-extras') && ""+product_subproducts_json+"" == $(this).attr('data-product-subproducts')) {
            checker = true;
            return true;
        }
    });

    return checker;
}

function cartProductListItemUniqueSelector(cart_id, product) {
    random = false;

    product_extras_json = JSON.stringify(product.extras) == '[]' ? '' : JSON.stringify(product.extras);
    product_options_json = JSON.stringify(product.options) == '[]' ? '' : JSON.stringify(product.options);
    product_subproducts_json = JSON.stringify(product.subproducts) == '[]' ? '' : JSON.stringify(product.subproducts);

    $('.cof_cartTab[data-cart-id='+cart_id+']').find('.cof_cartProductListItem[data-product-id='+product.id+']').each(function() {
        if(product.attribute == $(this).attr('data-attribute-name') && ""+product_options_json+"" == $(this).attr('data-product-options') && ""+product_extras_json+"" == $(this).attr('data-product-extras') && ""+product_subproducts_json+"" == $(this).attr('data-product-subproducts')) {
            random = Math.random().toString(36).substr(2, 5);
            $(this).attr('data-unique-el', random);
            return false;
        }
    });

    return random;
}

function resetCartTab(cartId) {
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:not(:first)').remove();
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first')

    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').attr('data-product-id', 0);
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').attr('data-quantity', 0);
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').attr('data-product-name', '');
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').attr('data-attribute-name', '');
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').attr('data-product-options', '');
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').attr('data-unit-price', 0);
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').attr('data-total-price', 0);
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').attr('data-unique-el', '');
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').find('.cof_cartProductListItemOptions:not(:first)').remove();
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').find('.cof_cartProductListItemOptions:first').removeClass('d-block').addClass('d-none');
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').find('.cof_cartProductListItemExtras:not(:first)').remove();
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').find('.cof_cartProductListItemExtras:first').removeClass('d-block').addClass('d-none');
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').find('.cof_cartProductListItemSubproducts:not(:first)').remove();
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_cartProductListItem:first').find('.cof_cartProductListItemSubproducts:first').removeClass('d-block').addClass('d-none');
    $('.cof_cartTab[data-cart-id='+cartId+']').find('.cof_CartProductList').hide();
}

function removeProductFromCart(cart_id, selector) {
    productListItem = $('.cof_cartTab[data-cart-id='+cart_id+']').find(''+selector+'');


    if($('.cof_cartTab[data-cart-id='+cart_id+']').find('.cof_cartProductListItem').length > 1) {
        //more than one item in the list so remove the correct element
        product_quantity = productListItem.attr('data-quantity');
        productListItem.remove();

        //subtract product qty from the cart qty and update Cart Count
        cart_count = cartCount(cart_id);
        new_count = cart_count - parseInt(product_quantity);
        updateCartCount(cart_id, new_count);

    } else if( $('.cof_cartTab[data-cart-id='+cart_id+']').find('.cof_cartProductListItem').length == 1) {
        //reset the one item in the cart so it's blank
        resetCartTab(cart_id);

        //update the cart count to zero
        updateCartCount(cart_id, 0);

    }

    calculateTotalPrice(cart_id);
}

function restoreCartsFromStorage() {
    let carts = getAllCartsFromStorage();
    og_cart_id = $('.pos_cartTabListBtn:first').attr('data-cart-id');
    active_cart_id = undefined;

    for (var i = 0; i < carts.length; i++) {
        cartId = carts[i].id;
        addNewCartTabLink(cartId);
        addNewCartTab(cartId);
        //activateCart(cartId);
        addProductsToCartFromStorage(cartId);
        addCustomerToCartFromStorage(cartId);
        //activateCart(cartId);

        if(carts[i].active) {
            active_cart_id = carts[i].id;
        }
    };



    if(carts.length == 0) {
        cartId = getNewCartId();
        addNewCartTabLink(cartId);
        addNewCartTab(cartId);
        storeNewCart(cartId);
        active_cart_id = cartId;
        //$('.pos_cartTabListBtn[data-cart-id='+cartId+']').trigger('click');
        //activateCart(cartId);
    }

    removeCartTab(og_cart_id);

    if(active_cart_id !== undefined) {
        $('.pos_cartTabListBtn[data-cart-id='+active_cart_id+']').trigger('click');
        activateCart(active_cart_id);
    }
}

function addProductsToCartFromStorage(cartId) {
    products = getProductsForCartFromStorage(cartId);

    for (var i = 0; i < products.length; i++) {
        addToCart(products[i], cartId);
    };
}








/* CUSTOMER FUNCTIONS */
/* CUSTOMER FUNCTIONS */
/* CUSTOMER FUNCTIONS */
/* CUSTOMER FUNCTIONS */
/* CUSTOMER FUNCTIONS */
/* CUSTOMER FUNCTIONS */
/* CUSTOMER FUNCTIONS */
/* CUSTOMER FUNCTIONS */
function addCustomerToCartFromStorage(cartId) {
    customer_id = getCustomerForCartFromStorage(cartId);
    $('#cof_selectedCustomerEmail').text(getCustomerEmail(customer_id));
}

function getProductsForCartFromStorage(cartId) {
    let carts = getAllCartsFromStorage();
    products = [];

    for (var i = 0; i < carts.length; i++) {
        if (carts[i].id == cartId) {
            products = carts[i].products;
        }
    };

    return products;
}

function getCustomerForCartFromStorage(cartId) {
    let carts = getAllCartsFromStorage();
    customer_id = '';

    for (var i = 0; i < carts.length; i++) {
        if (carts[i].id == cartId) {
            customer_id = carts[i].customer_id;
        }
    };

    return customer_id;
}

function getSelectedCustomer() {
    return $('.cof_customerSelectInputOption:selected').first().val();
}

function getCustomerByEan(ean_digit) {
    let ean = ean_digit.slice(0, -1);
    if ($('.cof_customerSelectInputOption[data-ean="'+ean+'"]').length > 0) {
        return $('.cof_customerSelectInputOption[data-ean="'+ean+'"]').first().val();
    }
    return getGuestCustomer();
}

function getGuestCustomer() {
    return parseInt($('#cof_selectCustomerAccount').attr('data-guest'));
}

function getCustomerEmail(customer_id) {
    customer_email = '';
    $('.cof_customerSelectInputOption').each(function () {
        if ($(this).val() == customer_id) {
            customer_email = $(this).attr('data-customer-email');
        }
    });

    return customer_email;
}

function addCustomerPointsAndCoupons(cart_id) {
    let cart = getCartFromStorage(cart_id);
    let points = Math.floor(cart.total);
    let customer_id = cart.customer_id;
    let coupons = cart.coupons;

    $('.cof_customerSelectInputOption').each(function () {
        if ($(this).val() == customer_id) {
            let old_points = parseInt($(this).attr('data-points'));
            $(this).attr('data-points', (old_points+points));

            if (coupons.length > 0) {
                allcoupons = $(this).data('coupons');
                for (var i = 0; i < coupons.length; i++) {
                    coupons[i].status = "used";

                    for (var ac = 0; ac < allcoupons.length; ac++) {
                        if (allcoupons[ac].id == coupons[i].id) {
                            allcoupons[ac] = coupons[i];
                        }
                    };
                };
                $(this).attr('data-coupons', JSON.stringify(allcoupons));
            }
        }
    });
}

function getCustomerPoints(customer_id) {
    customer_points = '';
    $('.cof_customerSelectInputOption').each(function () {
        if ($(this).val() == customer_id) {
            customer_points = parseInt($(this).attr('data-points'));
        }
    });

    return customer_points;
}

function updateCustomerForCart(customer_id, cartId) {
    let carts = getAllCartsFromStorage();

    for (var i = 0; i < carts.length; i++) {
        if(carts[i].id == cartId) {
            carts[i].customer_id = parseInt(customer_id);
            localStorage.setItem('cof_carts', JSON.stringify(carts));
            $('#cof_selectedCustomerEmail').text(getCustomerEmail(customer_id));
            resetCouponsForCart(cartId);
            return;
        }
    };
}
/* END OF CUSTOMER FUNCTIONS */
/* END OF CUSTOMER FUNCTIONS */
/* END OF CUSTOMER FUNCTIONS */
/* END OF CUSTOMER FUNCTIONS */
/* END OF CUSTOMER FUNCTIONS */
/* END OF CUSTOMER FUNCTIONS */
/* END OF CUSTOMER FUNCTIONS */
/* END OF CUSTOMER FUNCTIONS */








/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
/* CART FUNCTIONS */
function storeNewCart(cartId) {
    let carts = getAllCartsFromStorage();

    cart = {
        id: cartId,
        active: true,
        customer_id: parseInt(getGuestCustomer()),
        products: [],
        discounts: [],
        coupons: [],
        subtotal: 0,
        discount: 0,
        vat: 0,
        total: 0
    }

    carts.push(cart);
    localStorage.setItem('cof_carts', JSON.stringify(carts));
}

function validateCartForOrder(cartId) {
    return true;
    // check if internet is available
    // check if printer is available
    // check if customer is selected
    // check if order has value?
}

// function placeOrderFromCart(cartId) {
//     var order_pos_url = "{{ route('dashboard.module.ecommerce.pos.order.finalize') }}";
//     let a_token = "{{ Session::token() }}";

//     cart = undefined;
//     products = undefined;
//     let carts = getAllCartsFromStorage();
//     for (var i = 0; i < carts.length; i++) {
//         if(carts[i].id == cartId) {
//             cart = carts[i];
//             products = carts[i].products;
//         }
//     };

//     var ogDate = new Date,
//         order_date = [ogDate.getDate().padLeft(),
//                    (ogDate.getMonth()+1).padLeft(),
//                    ogDate.getFullYear()].join('/'),
//         order_time = [ogDate.getHours().padLeft(),
//                    ogDate.getMinutes().padLeft(),
//                    ogDate.getSeconds().padLeft()].join(':');

//     $.ajax({
//         method: 'POST',
//         url: order_pos_url,
//         data: {
//             order_date: order_date,
//             order_time: order_time,
//             location: cof_pos_active_location,
//             customer_id: cart.customer_id,
//             products: products,
//             discounts: cart.discounts,
//             subtotal: cart.subtotal,
//             coupons: cart.coupons,
//             discount: cart.discount,
//             total: cart.total,
//             vat: cart.vat,
//             _token: a_token
//         }
//     })
//     .done(function(data) {
//         if (data.status == "success"){
//             orderSuccesfullyPlacedFromCart(cartId, data.order_number, order_date, order_time, data.order_table_line);
//         }
//         else{
//             $('#cof_placeOrderBtnNow').html('Bestellen');
//             $('#cof_placeOrderBtnNow').prop('disabled', false);

//             $('.error_span:first').html(' Er is iets misgelopen, probeer het later nog eens!');
//             $('.error_bag:first').removeClass('hidden');
//         }
//     });
// }

function orderSuccesfullyPlacedFromCart(cartId, order_number, order_date, order_time, order_table_line) {
    cof_pos_processing_order = false;
    addCustomerPointsAndCoupons(cartId);
    printTicketFromCart(cartId, order_number, order_date, order_time);
    resetPaymentModal();
    addOrderLineToTable(order_table_line);
    //addCartToOrder(cartId, order_number);
    //removeCart(cartId);
}

function addOrderLineToTable(order_table_line) {
    $('#ordersModal').find('tbody').prepend(order_table_line);
}
/* END OF CART FUNCTIONS */
/* END OF CART FUNCTIONS */
/* END OF CART FUNCTIONS */
/* END OF CART FUNCTIONS */
/* END OF CART FUNCTIONS */
/* END OF CART FUNCTIONS */
/* END OF CART FUNCTIONS */
/* END OF CART FUNCTIONS */








/* COUPON FUNCTIONS */
/* COUPON FUNCTIONS */
/* COUPON FUNCTIONS */
/* COUPON FUNCTIONS */
/* COUPON FUNCTIONS */
/* COUPON FUNCTIONS */
/* COUPON FUNCTIONS */
/* COUPON FUNCTIONS */
function addSelectedCouponToCart(cartId) {
    let coupon = {
        id: $('input[name="coupon_selector"]:checked').val(),
        name: $('input[name="coupon_selector"]:checked').attr('data-name'),
        active: $('input[name="coupon_selector"]:checked').attr('data-active') == 1 ? true : false,
        valid_from: parseInt($('input[name="coupon_selector"]:checked').attr('data-valid-from')),
        valid_until: parseInt($('input[name="coupon_selector"]:checked').attr('data-valid-until')),
        customers: $('input[name="coupon_selector"]:checked').attr('data-customers').length > 0 ? $('input[name="coupon_selector"]:checked').attr('data-customers').split(',') : [],
        minimum: parseInt($('input[name="coupon_selector"]:checked').attr('data-minimum')),
        available: {
            total: parseInt($('input[name="coupon_selector"]:checked').attr('data-available-total')),
            customer: parseInt($('input[name="coupon_selector"]:checked').attr('data-available-customer'))
        },
        conditions: JSON.parse($('input[name="coupon_selector"]:checked').attr('data-conditions')),
        type: $('input[name="coupon_selector"]:checked').attr('data-discount-type'),
        value: $('input[name="coupon_selector"]:checked').attr('data-discount-value'),
        apply_on: $('input[name="coupon_selector"]:checked').attr('data-apply-on'),
        apply_product: $('input[name="coupon_selector"]:checked').attr('data-apply-product').length > 0 ? $('input[name="coupon_selector"]:checked').attr('data-apply-product') : null,
        uncompatible_discounts: $('input[name="coupon_selector"]:checked').attr('data-uncompatible-discounts').length > 0 ? $('input[name="coupon_selector"]:checked').attr('data-uncompatible-discounts').split(',') : [],
        remove_incompatible: $('input[name="coupon_selector"]:checked').attr('data-remove-incompatible') == 1 ? true : false,
        used_by: []
    }

    removeCouponErrorText();

    if (!isCouponValidForCart(coupon, cartId)) {
        updateCouponErrorText(isCouponValidForCart(coupon, cartId, false));
        return false;
    }

    addDiscountToCart(coupon, cartId);
    closeCouponModal();
}

function addDiscountToCart(discount, cart_id, scan = false, coupon = null) {
    if (discount.type == 'gift' && scan) {
        product_id = discount.apply_product;

        if (productNeedsModal(product_id)) {
            return;
        }

        product = getProductDetails(product_id)
        addToCart(product);
    }

    let cart = getCartFromStorage(cart_id);

    if (discount.remove_incompatible) {
        if (discount.uncompatible_discounts.length > 0) {
            for (var cud = 0; cud < discount.uncompatible_discounts.length; cud++) {
                removeCouponFromCart(discount.uncompatible_discounts[cud], cart_id);
            };
        }

        for (var cd = 0; cd < cart.discounts.length; cd++) {
            for (var cdud = 0; cdud < cart.discounts[cd].uncompatible_discounts.length; cdud++) {
                if (cart.discounts[cd].uncompatible_discounts[cdud] == discount.id) {
                    removeCouponFromCart(cart.discounts[cd].id, cart_id);
                }
            };
        };
    }

    let carts = getAllCartsFromStorage();

    for (var q = 0; q < carts.length; q++) {
        if(carts[q].id == cart_id) {
            carts[q].discounts.push(discount);
            if (coupon !== null) {
                carts[q].coupons.push(coupon);
            }
            break;
        }
    };
    localStorage.setItem('cof_carts', JSON.stringify(carts));
    calculateTotalPrice(cart_id);
    displayDiscountsForCart(cart_id);
}

function removeCouponFromCart(coupon, cart_id, remove_gift = false) {
    let carts = getAllCartsFromStorage();
    let discounts = {};
    for (var q = 0; q < carts.length; q++) {
        if(carts[q].id == cart_id) {
            discounts = carts[q].discounts;
            coupons = carts[q].coupons;

            for (var k = 0; k < discounts.length; k++) {
                if (discounts[k].id == coupon) {
                    if (discounts[k].type == 'gift') {
                        product_id = discounts[k].apply_product;
                        if ($('.cof_cartProductListItem[data-product-id='+product_id+']').length > 0 && remove_gift) {
                            let product = getProductDetailsFromCartItem(product_id, -1);

                            addToCart(product);

                            for (var cp = 0; cp < coupons.length; cp++) {
                                if (coupons[cp].json.discount == coupon) {
                                    coupons.splice(cp, 1);
                                    break;
                                }
                            };
                        }
                    }

                    discounts.splice(k, 1);
                    break;
                }
            };
            break;
        }
    };

    carts = getAllCartsFromStorage();
    for (var q = 0; q < carts.length; q++) {
        if(carts[q].id == cart_id) {
            carts[q].discounts = discounts;
            carts[q].coupons = coupons;
            break;
        }
    };

    localStorage.setItem('cof_carts', JSON.stringify(carts));
    calculateTotalPrice(cart_id);
    displayDiscountsForCart(cart_id);
}

function resetCouponsForCart(cart_id) {
    let cart = getCartFromStorage(cart_id);
    let discounts = getAllDiscountsForCart(cart_id);

    for (var k = 0; k < discounts.length; k++) {
        if (discounts[k].type == 'gift' && cart.coupons.length > 0) {
            coupon = cart.coupons.filter(function(e) { return e.json.discount == discounts[k].id; }).shift();
            if (coupon.customer_id !== cart.customer_id) {
                removeCouponFromCart(discounts[k].id, cart_id, true);
            } else {
                removeCouponFromCart(discounts[k].id, cart_id);
            }
        } else {
            removeCouponFromCart(discounts[k].id, cart_id);
        }
    };

    for (var k = 0; k < discounts.length; k++) {
        if (isCouponValidForCart(discounts[k], cart_id)) {
            if (discounts[k].type == 'gift' && cart.coupons.length > 0) {
                coupon = cart.coupons.filter(function(e) { return e.json.discount == discounts[k].id; }).shift();
                if (coupon.customer_id == cart.customer_id) {
                    addDiscountToCart(discounts[k], cart_id);
                }
            }

            if (discounts[k].type != 'gift') {
                addDiscountToCart(discounts[k], cart_id);
            }
        }
    };
}

function displayDiscountsForCart(cart_id) {
    let discounts = getAllDiscountsForCart(cart_id);

    $('.cof_cartCouponItem:not(:first)').remove();
    $('.cof_cartCouponItem:first').addClass('d-none');
    for (var k = 0; k < discounts.length; k++) {
        if(k > 0) {
            $('.cof_cartCouponItem:first').clone().appendTo('#cof_cartCouponWrapper');
        }

        $('.cof_cartCouponItem:last').attr('data-coupon', discounts[k].id);
        $('.cof_cartCouponItem:last').find('.cof_couponText').text(discounts[k].name);
        $('.cof_cartCouponItem:last').removeClass('d-none');
    };
}

function isCouponValidForCart(coupon, cartId, status = true) {
    let _now = Math.floor(Date.now() / 1000);

    if (!coupon.active) {
        return status ? false : 'Coupon is niet meer actief';
    }

    if (coupon.valid_from > _now) {
        return status ? false : 'Coupon is nog niet geldig';
    }

    if (coupon.valid_until < _now) {
        return status ? false : 'Coupon is niet meer geldig';
    }

    if (coupon.available.total == 0) {
        return status ? false : 'Coupon kan niet meer gebruikt worden';
    }

    if (!isCouponValidForCustomer(coupon, cartId)) {
        return status ? false : 'Coupon is niet geldig voor geselecteerde klant';
    }

    if (!isCouponCompatibleWithCart(coupon, cartId)) {
        return status ? false : 'Coupon kan niet gecombineerd worden met bestaande coupons';
    }

    if (!isCartMinimumReachedForCoupon(coupon, cartId)) {
        return status ? false : 'Winkelwagen heeft niet genoeg winkelwaarde voor coupon';
    }

    if (!isCouponPassingConditions(coupon, cartId)) {
        return status ? false : 'Winkelwagen heeft niet de juiste inhoud voor coupon';
    }

    return status ? true : '';
}


function isCouponValidForCustomer(coupon, cartId) {
    let cart = getCartFromStorage(cartId);

    if (coupon.customers == "") {
        return true;
    }

    if (!Array.isArray(coupon.customers)) {
        return true;
    }

    if (Array.isArray(coupon.customers) && coupon.customers.length == 0) {
        return true;
    }

    for (var cc = 0; cc < coupon.customers.length; cc++) {
        if (cart.customer_id == parseInt(coupon.customers[cc])) {
            return true;
        }
    };

    //@TODO:coupon.available.customer ++ coupon.used_by (if not guest)

    return false;
}

function isCouponCompatibleWithCart(coupon, cartId) {
    let cart = getCartFromStorage(cartId);

    if (coupon.uncompatible_discounts == "") {
        return true;
    }

    if (!Array.isArray(coupon.uncompatible_discounts)) {
        return true;
    }

    if (Array.isArray(coupon.uncompatible_discounts) && coupon.uncompatible_discounts.length == 0) {
        return true;
    }

    if (coupon.remove_incompatible) {
        return true;
    }

    for (var cc = 0; cc < coupon.uncompatible_discounts.length; cc++) {
        for (var cd = 0; cd < cart.discounts.length; cd++) {
            if (coupon.uncompatible_discounts[cc] == cart.discounts[cd].id) {
                return false;
            }
        };
    };

    for (var cd = 0; cd < cart.discounts.length; cd++) {
        for (var cdud = 0; cdud < cart.discounts[cd].uncompatible_discounts.length; cdud++) {
            if (cart.discounts[cd].uncompatible_discounts[cdud] == coupon.id) {
                return false;
            }
        };
    };

    return true;
}

function isCartMinimumReachedForCoupon(coupon, cartId) {
    let cart = getCartFromStorage(cartId);

    if(coupon.minimum > cart.subtotal) {
        return false;
    }

    return true;
}

function isCouponPassingConditions(coupon, cartId) {
    let cart = getCartFromStorage(cartId);
    let products = getProductsForCartFromStorage(cartId);

    if (coupon.conditions == "") {
        return true;
    }

    if (!Array.isArray(coupon.conditions)) {
        return true;
    }

    if (Array.isArray(coupon.conditions) && coupon.conditions.length == 0) {
        return true;
    }

    for (var ccs = 0; ccs < coupon.conditions.length; ccs++) {
        if (!isCouponConditionPassedByCart(coupon.conditions[ccs], cart)) {
            return false;
        }
    };

    return true;
}

function isCouponConditionPassedByCart(condition, cart) {
    let productsThatPassed = [];
    let productsNeeded = condition.min_quantity;

    for (var cr = 0; cr < condition.rules.length; cr++) {
        if (isCouponConditionRulePassedByCart(condition.rules[cr], cart)) {
            productsThatPassed = productsThatPassed.concat(getProductsFromCouponConditionRuleByCart(condition.rules[cr], cart));
        }
    };

    if (condition.rules.length > 0 && productsNeeded <= productsThatPassed.length) {
        return true;
    } else if (condition.rules.length == 0) {
        return true;
    }

    return false;
}

function isCouponConditionRulePassedByCart(rule, cart) {
    if (rule.type == 'product') {
        for (var cprl = 0; cprl < cart.products.length; cprl++) {
            if (rule.value == cart.products[cprl].id) {
                return true;
            }
        };

        return false;
    }

    if (rule.type == 'collection') {
        for (var i = 0; i < cart.products.length; i++) {
            if (rule.value == getCategoryIdForProduct(cart.products[i].id)) {
                return true;
            }
        };

        return false;
    }

    return false;
}

function getProductsFromCouponConditionRuleByCart(rule, cart) {
    let productsIds = [];

    if (rule.type == 'product') {
        for (var i = 0; i < cart.products.length; i++) {
            if (rule.value == cart.products[i].id) {
                for (var q = 0; q < cart.products[i].quantity; q++) {
                    productsIds.push(cart.products[i].id);
                };
            }
        };
    }

    if (rule.type == 'collection') {
        for (var i = 0; i < cart.products.length; i++) {
            if (rule.value == getCategoryIdForProduct(cart.products[i].id)) {
                for (var q = 0; q < cart.products[i].quantity; q++) {
                    productsIds.push(cart.products[i].id);
                };
            }
        };
    }

    return productsIds;
}

function updateCouponErrorText(text) {
    $('#cof_couponErrorText').text(text);
    $('#cof_couponErrorText').removeClass('d-none');
}

function removeCouponErrorText() {
    $('#cof_couponErrorText').text('');
    $('#cof_couponErrorText').addClass('d-none');
}

function closeCouponModal() {
    removeCouponErrorText();
    $('#couponsModal').modal('hide');
}

function getAllDiscountsForCart(cart_id) {
    let carts = getAllCartsFromStorage();
    let discounts = {};
    for (var n = 0; n < carts.length; n++) {
        if(carts[n].id == cart_id) {
            discounts = carts[n].discounts;
        }
    };

    return discounts;
}



//NOTE: COUPONS MENTIONED ABOVE THIS LINE ARE ACTUALLY DISCOUNTS (@TODO: CHANGE THIS)
//NOTE: COUPONS MENTIONED BELOW THIS LINE ARE ACTUALLY COUPONS (AS DEFINED IN THE LOYALTY SYSTEM)
function getCouponByEan(ean_digit) {
    let ean = ean_digit;
    let coupon = null;

    if ($('.cof_customerSelectInputOption:not([data-coupons="[]"])').length > 0) {
        $('.cof_customerSelectInputOption:not([data-coupons="[]"])').each(function () {
            let coupons = $(this).data('coupons');
            for (var cpns = 0; cpns < coupons.length; cpns++) {
                if (coupons[cpns].number == ean) {
                    coupon = coupons[cpns];
                    return false;
                }
            };
        });
    }

    if (coupon != null) {
        return coupon;
    }

    return false;
}

function addScannedCouponToCart(coupon) {
    let cart_id = getActiveCart();
    let discount_id = coupon.json.discount;
    let selector = 'input[name="coupon_selector"][value="'+discount_id+'"]';

    let discount = {
        id: $(selector).val(),
        name: $(selector).attr('data-name'),
        active: $(selector).attr('data-active') == 1 ? true : false,
        valid_from: parseInt($(selector).attr('data-valid-from')),
        valid_until: parseInt($(selector).attr('data-valid-until')),
        customers: $(selector).attr('data-customers').length > 0 ? $(selector).data('customers').split(',') : [],
        minimum: parseInt($(selector).attr('data-minimum')),
        available: {
            total: parseInt($(selector).attr('data-available-total')),
            customer: parseInt($(selector).attr('data-available-customer'))
        },
        conditions: JSON.parse($(selector).attr('data-conditions')),
        type: $(selector).attr('data-discount-type'),
        value: $(selector).attr('data-discount-value'),
        apply_on: $(selector).attr('data-apply-on'),
        apply_product: $(selector).attr('data-apply-product').length > 0 ? $(selector).attr('data-apply-product') : null,
        uncompatible_discounts: $(selector).attr('data-uncompatible-discounts').length > 0 ? $(selector).attr('data-uncompatible-discounts').split(',') : [],
        remove_incompatible: $(selector).attr('data-remove-incompatible') == 1 ? true : false,
        used_by: []
    }

    addDiscountToCart(discount, cart_id, true, coupon);
}

function isCouponInCart(coupon, cart_id) {
    let cart = getCartFromStorage(cart_id);

    if (coupon == null || cart.coupons.length == 0) {
        return false;
    }

    if (cart.coupons.filter(function(e) { return e.id == coupon.id && e.number == coupon.number; }).length > 0) {
        return true;
    }

    return false;
}

function isCouponInAnyCart(coupon) {
    let carts = getAllCartsFromStorage();

    for (var i = 0; i < carts.length; i++) {
        if(isCouponInCart(coupon, carts[i].id)) {
            return true;
        }
    };

    return false;
}

/* END OF COUPON FUNCTIONS */
/* END OF COUPON FUNCTIONS */
/* END OF COUPON FUNCTIONS */
/* END OF COUPON FUNCTIONS */
/* END OF COUPON FUNCTIONS */
/* END OF COUPON FUNCTIONS */
/* END OF COUPON FUNCTIONS */
/* END OF COUPON FUNCTIONS */








/* PRODUCT FUNCTIONS */
/* PRODUCT FUNCTIONS */
/* PRODUCT FUNCTIONS */
/* PRODUCT FUNCTIONS */
/* PRODUCT FUNCTIONS */
/* PRODUCT FUNCTIONS */
/* PRODUCT FUNCTIONS */
/* PRODUCT FUNCTIONS */
function productNeedsModal(product_id) {
    let productNeedsModal = false;

    if(productHasAttributes(product_id)) {
        productNeedsModal = true;
    }

    if(productHasOptions(product_id)) {
        productNeedsModal = true;
    }

    if(productHasExtras(product_id)) {
        productNeedsModal = true;
    }

    if(productHasSubproducts(product_id)) {
        productNeedsModal = true;
    }

    return productNeedsModal;
}

function productHasAttributes(product_id) {
    attributes = $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-attributes');
    if(attributes == '[]') {
        return false;
    }

    return true;
}

function productHasOptions(product_id) {
    options = $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-options');

    if(options == '[]') {
        return false;
    }

    return true;
}

function productHasExtras(product_id) {
    extras = $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-extras');

    if(extras == '[]') {
        return false;
    }

    return true;
}

function productHasSubproducts(product_id) {
    subproducts = $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-subproducts');

    if(subproducts == '[]' || typeof subproducts === "undefined") {
        return false;
    }

    return true;
}

function addProductToModal(elem) {
    product_id = elem.attr('data-product-id');
    product_name = elem.attr('data-product-name');
    current_price = elem.attr('data-current-price');
    quantity = 1;
    total_price = parseFloat(current_price) * parseInt(quantity);

    product_attributes = JSON.parse(elem.attr('data-product-attributes'));
    product_options = JSON.parse(elem.attr('data-product-options'));
    if ( elem.attr('data-product-extras') !== undefined ) {
        product_extras = JSON.parse(elem.attr('data-product-extras'));
    } else {
        product_extras = [];
    }

    if ( elem.attr('data-product-subproducts') !== undefined ) {
        product_subproducts = JSON.parse(elem.attr('data-product-subproducts'));
    } else {
        product_subproducts = [];
    }

    resetOptionsModal();
    setOptionsModal(product_id, product_name, current_price, quantity, total_price, product_attributes, product_options, product_extras, product_subproducts)
    $('#optionsModal').modal();
}

function getProductDetails(product_id) {
    let product = {
        id: product_id,
        name: $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-name'),
        attribute: '',
        options: [],
        extras: [],
        subproducts: [],
        quantity: 1,
        vat: {
            delivery: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-delivery')),
            takeout: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-takeout')),
            on_the_spot: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-on-the-spot'))
        },
        //vat_price: Number(getVatPriceFromProductId(product_id)),
        //vat_total_price: Number(getVatPriceFromProductId(product_id)),
        current_price: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-current-price')),
        total_price: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-current-price'))

    }

    return product;
}

function getProductDetailsFromModal(product_id) {
    let product = {
        id: product_id,
        name: $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-name'),
        attribute: getSelectedProductAttribute(product_id),
        options: getSelectedProductOptions(),
        extras: getSelectedProductExtras(),
        subproducts: getSelectedProductSubproducts(),
        quantity: getSelectedProductQuantity(product_id),
        vat: {
            delivery: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-delivery')),
            takeout: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-takeout')),
            on_the_spot: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-on-the-spot'))
        },
        //vat_price: Number(getVatPriceFromSelectedProduct(product_id)),
        //vat_total_price:(getSelectedProductQuantity(product_id) * Number(getVatPriceFromSelectedProduct(product_id))),
        current_price: getSelectedProductUnitPrice(product_id),
        total_price: (getSelectedProductQuantity(product_id) * getSelectedProductUnitPrice(product_id))
    }

    return product;
}

function getProductDetailsFromCartItemElement(cart_id, elem, copy_quantity = true) {
    product_id = elem.parents('.cof_cartProductListItem').first().attr('data-product-id');

    if(copy_quantity === -1) {
        quantity = -1;
    }

    if(copy_quantity === true) {
        quantity = parseInt(elem.parents('.cof_cartProductListItem').first().attr('data-quantity'))
    } else if(copy_quantity === false) {
        quantity = 1;
    }

    let product = {
        id: product_id,
        name: $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-name'),
        attribute: elem.parents('.cof_cartProductListItem').first().attr('data-attribute-name').length > 0 ? elem.parents('.cof_cartProductListItem').first().attr('data-attribute-name') : '',
        options: elem.parents('.cof_cartProductListItem').first().attr('data-product-options').length > 0 ? JSON.parse(elem.parents('.cof_cartProductListItem').first().attr('data-product-options')) : JSON.parse('[]'),
        extras: elem.parents('.cof_cartProductListItem').first().attr('data-product-extras').length > 0 ? JSON.parse(elem.parents('.cof_cartProductListItem').first().attr('data-product-extras')) : JSON.parse('[]'),
        subproducts: elem.parents('.cof_cartProductListItem').first().attr('data-product-subproducts').length > 0 ? JSON.parse(elem.parents('.cof_cartProductListItem').first().attr('data-product-subproducts')) : JSON.parse('[]'),
        quantity: quantity,
        vat: {
            delivery: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-delivery')),
            takeout: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-takeout')),
            on_the_spot: Number($('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-vat-on-the-spot'))
        },
        //vat_price: Number(getVatPriceFromProductAndUnitPrice(product_id, Number(elem.parents('.cof_cartProductListItem').first().attr('data-unit-price')))),
        //vat_total_price: (quantity) * Number(getVatPriceFromProductAndUnitPrice(product_id, Number(elem.parents('.cof_cartProductListItem').first().attr('data-unit-price')))),
        current_price: Number(Number(elem.parents('.cof_cartProductListItem').first().attr('data-unit-price')).toFixed(2)),
        total_price: Number((Number(elem.parents('.cof_cartProductListItem').first().attr('data-unit-price')) * (quantity)).toFixed(2))
    }

    return product;
}

function getProductDetailsFromCartItem(product_id, copy_quantity = true) {
    selector = '.cof_cartProductListItem[data-product-id='+product_id+']';
    card_selector = '.cof_pos_product_card[data-product-id='+product_id+']';

    if(copy_quantity === -1) {
        quantity = -1;
    }

    if(copy_quantity === true) {
        quantity = parseInt($(selector).first().attr('data-quantity'))
    } else if(copy_quantity === false) {
        quantity = 1;
    }

    let product = {
        id: product_id,
        name: $(card_selector).attr('data-product-name'),
        attribute: $(selector).first().attr('data-attribute-name').length > 0 ? $(selector).first().attr('data-attribute-name') : '',
        options: $(selector).first().attr('data-product-options').length > 0 ? JSON.parse($(selector).first().attr('data-product-options')) : JSON.parse('[]'),
        extras: $(selector).first().attr('data-product-extras').length > 0 ? JSON.parse($(selector).first().attr('data-product-extras')) : JSON.parse('[]'),
        subproducts: $(selector).first().attr('data-product-subproducts').length > 0 ? JSON.parse($(selector).first().attr('data-product-subproducts')) : JSON.parse('[]'),
        quantity: quantity,
        vat: {
            delivery: Number($(card_selector).attr('data-vat-delivery')),
            takeout: Number($(card_selector).attr('data-vat-takeout')),
            on_the_spot: Number($(card_selector).attr('data-vat-on-the-spot'))
        },
        //vat_price: Number(getVatPriceFromProductAndUnitPrice(product_id, Number(elem.parents('.cof_cartProductListItem').first().attr('data-unit-price')))),
        //vat_total_price: (quantity) * Number(getVatPriceFromProductAndUnitPrice(product_id, Number(elem.parents('.cof_cartProductListItem').first().attr('data-unit-price')))),
        current_price: Number(Number($(selector).first().attr('data-unit-price')).toFixed(2)),
        total_price: Number((Number($(selector).first().attr('data-unit-price')) * quantity).toFixed(2))
    }

    return product;
}

function getCategoryIdForProduct(product_id) {
    return $('.cof_pos_product_card[data-product-id='+product_id+']').attr('data-product-category-id');
}

function calculateProductAvailability() {
    locationKey = cof_pos_active_location;
    $('.cof_pos_product_card').each(function() {
        productId = $(this).attr('data-product-id');
        q = $(this).attr('data-q').split(',')
        for (var i = 0; i < q.length; i++) {
            if(q[i].search(''+locationKey+'=') !== -1) {
                max_q = q[i].split('=').pop();
                $(this).attr('data-max-q', max_q);
                if(parseInt(max_q) == 0) {
                    $(this).addClass('unavailable');
                    $('.cof_btnAddProductToCart[data-product-id='+productId+']').prop('disabled', true);
                    $('.cof_btnAddProductOptionsToCart[data-product-id='+productId+']').prop('disabled', true);
                    $('.cof_btnAddProductAttributeToCart[data-product-id='+productId+']').prop('disabled', true);
                    $('.cof_btnAddProductAttributeOptionsToCart[data-product-id='+productId+']').prop('disabled', true);
                } else {
                    $(this).removeClass('unavailable');
                    $('.cof_btnAddProductToCart[data-product-id='+productId+']').prop('disabled', false);
                    $('.cof_btnAddProductOptionsToCart[data-product-id='+productId+']').prop('disabled', false);
                    $('.cof_btnAddProductAttributeToCart[data-product-id='+productId+']').prop('disabled', false);
                    $('.cof_btnAddProductAttributeOptionsToCart[data-product-id='+productId+']').prop('disabled', false);
                }
            }
        };
    });
    //@TODO: add a check for products already in cart
}

function getSelectedProductAttribute(product_id)
{
    if ($('.attributes_modal_item_button_group[data-product-id='+product_id+']').length) {
        attribute_name = $('.attributes_modal_item_button_group[data-product-id='+product_id+']').find("input:checked").first().attr('data-attribute-name') == undefined ? $('.attributes_modal_item_button_group[data-product-id='+product_id+']').find("input").first().attr('data-attribute-name') : $('.attributes_modal_item_button_group[data-product-id='+product_id+']').find("input:checked").first().attr('data-attribute-name');
    } else {
        attribute_name = '';
    }

    return attribute_name;
}

function getSelectedProductAttributePrice(product_id)
{
    attribute_price = 0.00;

    if ($('.attributes_modal_item_button_group[data-product-id='+product_id+']').length) {
        attribute_price = $('.attributes_modal_item_button_group[data-product-id='+product_id+']').find("input:checked").first().attr('data-attribute-name') == undefined ? $('.attributes_modal_item_button_group[data-product-id='+product_id+']').find("input").first().attr('data-attribute-price') : $('.attributes_modal_item_button_group[data-product-id='+product_id+']').find("input:checked").first().attr('data-attribute-price');
        if(attribute_price == undefined) {
            attribute_price = 0.00;
        }
    }

    return attribute_price;
}

function getSelectedProductOptions()
{
    product_options = [];
    if(!$('#optionsModalBody').hasClass('d-none')) {
        $('.options_modal_row').each(function() {

            option_name = $(this).attr('data-product-option-name');

            type = $(this).attr('data-product-option-type');
            input_name = $(this).attr('data-product-option-input-name');
            if(type == 'radio') {
                option_value = $(this).find('input[name='+input_name+']:checked').first().val();
            } else if(type == 'select') {
                option_value = $(this).find('select[name='+input_name+']').first().val();
            }

            option_object = {name: option_name, value: option_value};
            product_options.push(option_object)
        });
    }

    return product_options;
}

function getSelectedProductExtras()
{
    product_options = [];
    if(!$('#extrasModalBody').hasClass('d-none')) {
        $('.extras_modal_row').each(function() {
            o_el = $(this).find('input:checked').first();
            o_name = o_el.val();
            o_value = o_el.attr('data-product-extra-item-price');

            if (o_value !== undefined) {
                o_vat_delivery = Number(o_el.attr('data-product-extra-item-vat-delivery'));
                o_vat_takeout = Number(o_el.attr('data-product-extra-item-vat-takeout'));
                o_vat_on_the_spot = Number(o_el.attr('data-product-extra-item-vat-on-the-spot'));
            } else {
                o_vat_delivery = o_value;
                o_vat_takeout = o_value;
                o_vat_on_the_spot = o_value;
            }

            option_object = {
                name: o_name,
                value: o_value,
                vat_delivery: o_vat_delivery,
                vat_takeout: o_vat_takeout,
                vat_on_the_spot: o_vat_on_the_spot
            };
            product_options.push(option_object)
        });
    }

    return product_options;
}

function getSelectedProductSubproducts()
{
    product_subproducts = [];
    if(!$('#subproductModalBody').hasClass('d-none')) {
        $('.subproduct_group_modal_row').each(function() {
            group_name = $(this).attr('data-product-group-name');

            group_products = [];
            $(this).find('.subproduct_group_product').each(function(){
                if(parseInt($(this).find('.product_qty').val()) > 0){
                    p_name = $(this).find('.subproduct_group_product_name').text();
                    p_qty = parseInt($(this).find('.product_qty').val());
                    p_extra_price = parseFloat($(this).find('.product_extra_price').attr('data-extra-price'));
                    group_products.push({
                        'p_name': p_name,
                        'p_qty': p_qty,
                        'p_extra_price': p_extra_price
                    });
                }
            });
            group_object = {
                name: group_name,
                products: group_products
            };

            product_subproducts.push(group_object);
        });
    }

    return product_subproducts;
}

function getSelectedProductExtrasPrice(product_id)
{
    extras_price = 0;
    if(!$('#extrasModalBody').hasClass('d-none')) {
        $('.extras_modal_row').each(function() {
            extras_price = Number(extras_price) + ($(this).find('input:checked').first().attr('data-product-extra-item-price') !== undefined ? Number($(this).find('input:checked').first().attr('data-product-extra-item-price')) : 0);
        });
    }

    return extras_price;
}

function getSelectedProductSubproductsExtraPrice(product_id)
{
    subproducts_extras_price = 0;

    if($('#subproductModalBody').hasClass('d-none')) {
        return subproducts_extras_price;
    }

    $('.subproduct_group_product').each(function() {

        let sp_qty = parseInt($(this).find('.product_qty').val());

        if(sp_qty == 0){
            return;
        }

        sp_extra_price = 0;
        sp_extra_price_og = $(this).find('.product_extra_price').first().attr('data-extra-price');

        if (sp_extra_price_og !== undefined) {
            sp_extra_price = sp_extra_price_og;
        }

        subproducts_extras_price += (sp_qty * sp_extra_price);
    });


    return subproducts_extras_price;
}

function getSingleExtrasPriceFromProduct(product)
{
    let productExtras = product.extras;
    let productExtrasValue = 0;

    for (var i = 0; i < productExtras.length; i++) {
        if ( !$.isEmptyObject(productExtras[i]) ) {
            productExtrasValue = productExtrasValue + Number(parseFloat(parseFloat(productExtras[i]['value'])).toFixed(2))
        }
    };

    return productExtrasValue;
}

function getSelectedProductQuantity(product_id)
{
    return parseInt($('#addProductFromModalToCartButton[data-product-id='+product_id+']').attr('data-quantity'));
}

function getSelectedProductUnitPrice(product_id)
{
    base_price = $('#addProductFromModalToCartButton[data-product-id='+product_id+']').attr('data-current-price');
    attribute_price = getSelectedProductAttributePrice(product_id);
    extras_price = getSelectedProductExtrasPrice(product_id);
    subproducts_extra_price = getSelectedProductSubproductsExtraPrice(product_id);

    if(attribute_price > 0) {
        return Number(attribute_price) + Number(extras_price) + parseFloat(subproducts_extra_price);
    }

    return Number(base_price) + Number(extras_price) + parseFloat(subproducts_extra_price);
}


/* END OF PRODUCT FUNCTIONS */
/* END OF PRODUCT FUNCTIONS */
/* END OF PRODUCT FUNCTIONS */
/* END OF PRODUCT FUNCTIONS */
/* END OF PRODUCT FUNCTIONS */
/* END OF PRODUCT FUNCTIONS */
/* END OF PRODUCT FUNCTIONS */
/* END OF PRODUCT FUNCTIONS */








/* PRICE FUNCTIONS */
/* PRICE FUNCTIONS */
/* PRICE FUNCTIONS */
/* PRICE FUNCTIONS */
/* PRICE FUNCTIONS */
/* PRICE FUNCTIONS */
/* PRICE FUNCTIONS */
/* PRICE FUNCTIONS */
function calculateTotalPrice(cart_id) {

    // let total_price = getTotalPrice(cart_id) + 0;
    // $('.cof_cartTotalPrice').text('€ '+parseFloat(total_price).toFixed(2).replace('.', ','));

    // let total_vat = getTotalVat(cart_id);
    // $('.cof_cartTotalVatPrice').text('€ '+parseFloat(total_vat).toFixed(2).replace('.', ','));

    // let subtotal = Number((total_price - total_vat).toFixed(2));
    // $('.cof_cartSubtotalPrice').text('€ '+parseFloat(subtotal).toFixed(2).replace('.', ','));

    let subtotal = getSubtotalPrice(cart_id) + 0;
    $('.cof_cartSubtotalPrice').text('€ '+parseFloat(subtotal).toFixed(2).replace('.', ','));

    let discount_price = getTotalDiscount(cart_id);
    $('.cof_cartDiscountPrice').text('€ '+parseFloat( (discount_price <= subtotal ? discount_price : subtotal) ).toFixed(2).replace('.', ','));

    let total_price = Number((subtotal - (discount_price <= subtotal ? discount_price : subtotal)).toFixed(2));
    $('.cof_cartTotalPrice').text('€ '+parseFloat(total_price).toFixed(2).replace('.', ','));

    let total_vat = getTotalVat(cart_id);
    $('.cof_cartTotalVatPrice').text('€ '+parseFloat(total_vat).toFixed(2).replace('.', ','));

    let carts = getAllCartsFromStorage();
    for (var i = 0; i < carts.length; i++) {
        if(carts[i].id == cart_id) {
            carts[i].subtotal = subtotal;
            carts[i].discount = discount_price;
            carts[i].total = total_price;
            carts[i].vat = total_vat;
        }
    };
    localStorage.setItem('cof_carts', JSON.stringify(carts));
}

function getSubtotalPrice(cart_id) {
    products = getProductsForCartFromStorage(cart_id);
    totalPrice = 0;

    for (var i = 0; i < products.length; i++) {
        totalPrice = totalPrice + products[i].total_price;
    };

    return Number((totalPrice).toFixed(2));
}

function getTotalPrice(cart_id) {
    let subtotal = getSubtotalPrice(cart_id);
    let discount_price = getTotalDiscount(cart_id);

    return Number((subtotal - (discount_price <= subtotal ? discount_price : subtotal)).toFixed(2));
}

function getTotalVat(cart_id) {
    total_price = 0;
    let carts = getAllCartsFromStorage();
    for (var i = 0; i < carts.length; i++) {
        if(carts[i].id == cart_id) {
            products = carts[i].products;
        }
    };

    return getCartTotalVatFromProducts(products);
}

function calculateShippingPrice() {
    if($('.cof_location_radio:checked').attr('data-location-type') == 'delivery') {
        $('#cof_CartProductListShippingLine').removeClass('d-none');
        $('#cof_CartProductListShippingLine').removeClass('hidden');
    } else {
        $('#cof_CartProductListShippingLine').addClass('d-none');
        $('#cof_CartProductListShippingLine').addClass('hidden');
    }

    location_shipping_price = $('.cof_location_radio:checked').attr('data-delivery-cost');
    $('.cof_cartShippingPrice').text('€ '+parseFloat(location_shipping_price).toFixed(2).replace('.', ','));

    return parseFloat(location_shipping_price);
}

function getCartTotalVatFromProducts(products) {
    totalPricesPerVatRate = {};
    totalVat = 0;

    for (var i = 0; i < products.length; i++) {

        let vat_rates = getAllVatPercentagesForProduct(products[i]);
        for (var vrate = 0; vrate < vat_rates.length; vrate++) {
            vat_rate = vat_rates[vrate];

            if(vat_rate in totalPricesPerVatRate) {
                totalPricesPerVatRate[vat_rate] = (totalPricesPerVatRate[vat_rate] + getVatForRateForProduct(vat_rate, products[i]));
            } else {
                totalPricesPerVatRate[vat_rate] = getVatForRateForProduct(vat_rate, products[i]);
            }
        };


    };

    Object.keys(totalPricesPerVatRate).forEach(function(rate) {
        totalVat = totalVat + Number(getVatFromPriceAndRate(totalPricesPerVatRate[rate], rate).toFixed(2));
    });

    return Number((totalVat).toFixed(2));
}

function getVatForRateForProduct(vat_rate, product) {
    let vat_base_rate = getVatPercentageForProduct(product.id);
    let extras_price = (product.quantity * getSingleExtrasPriceFromProduct(product));
    let base_price = (product.total_price - extras_price);

    let discounts = product.discounts;

    if (vat_rate == vat_base_rate) {
        for (var dlk = 0; dlk < discounts.length; dlk++) {
            base_price = calculateDiscount(discounts[dlk], base_price, true);
        };
    } else {
        base_price = 0;
    }

    let productExtras = product.extras;
    let extras_n_price = 0;
    for (var pexl = 0; pexl < productExtras.length; pexl++) {
        let extras_nn_price = 0;
        if (!$.isEmptyObject(productExtras[pexl])) {
            if (vat_rate == getVatPercentageForExtra(productExtras[pexl])) {
                extras_nn_price = (product.quantity * Number(parseFloat(productExtras[pexl].value).toFixed(2)));
                for (var dlk = 0; dlk < discounts.length; dlk++) {
                    if (discounts[dlk].apply_on !== "product") {
                        extras_nn_price = calculateDiscount(discounts[dlk], extras_nn_price, true);
                    }
                };
            }
        }
        extras_n_price = extras_n_price + extras_nn_price;
    };

    return Number(base_price + extras_n_price);

    //(products[i].quantity * (products[i].current_price - getSingleExtrasPriceFromProduct(products[i])))

    //return product.total_price;
}

function getVatFromPriceAndRate(price, rate) {
    vat_percentage = parseInt(rate);

    product_price = Number(price);
    divider = parseInt(100 + vat_percentage);

    vat_price = ((product_price / divider) * vat_percentage);
    return Number(vat_price);
}

function getAllVatPercentagesForProducts(products) {
    let vat_percentages = [];

    for (var i = 0; i < products.length; i++) {
        vat_percentages.concat(getAllVatPercentagesForProduct(products[i]));
    };

    vat_percentages = vat_percentages.filter((item, index) => vat_percentages.indexOf(item) === index);

    return vat_percentages;
}

function getAllVatPercentagesForProduct(product) {
    let vat_percentages = [];

    vat_percentage = getVatPercentageForProduct(product.id);
    vat_percentages.push(vat_percentage);

    if (product.extras.length > 0) {
        let productExtras = product.extras;
        for (var pexob = 0; pexob < productExtras.length; pexob++) {
            if (!$.isEmptyObject(productExtras[pexob])) {
                vat_percentages.push(getVatPercentageForExtra(productExtras[pexob]))
            }
        };
    }

    vat_percentages = vat_percentages.filter((item, index) => vat_percentages.indexOf(item) === index);

    return vat_percentages;
}

function getVatPercentageForProduct(product_id) {
    selector = '.cof_pos_product_card[data-product-id='+product_id+']';

    vat_percentage = Number($(selector).attr('data-product-vat-delivery'));

    return vat_percentage;
}

function getVatPercentageForExtra(extra) {
    return Number(extra.vat.amount);
}

function getTotalDiscount(cart_id) {
    let carts = getAllCartsFromStorage();
    let discounts = getAllDiscountsForCart(cart_id);

    for (var g = 0; g < carts.length; g++) {
        if(carts[g].id == cart_id) {
            let products = carts[g].products;
            totalDiscount = 0;

            for (var i = 0; i < products.length; i++) {
                let productDiscount = 0;
                let productPrice = (products[i].quantity * (products[i].current_price - getSingleExtrasPriceFromProduct(products[i])));

                products[i].discounts = [];
                products[i].discount = 0;

                for (var k = 0; k < discounts.length; k++) {
                    if (isDiscountApplicableForProduct(discounts[k], products[i], cart_id)) {
                        productDiscount = productDiscount + calculateDiscount(discounts[k], productPrice);
                        productPrice = calculateDiscount(discounts[k], productPrice, true);

                        products[i].discounts.push(discounts[k]);
                    }
                };

                totalDiscount = totalDiscount + productDiscount;
                products[i].discount = productDiscount;
            };
            carts[g].products = products;
            localStorage.setItem('cof_carts', JSON.stringify(carts));
            break;
        }
    };





    // for (var kd = 0; kd < discounts.length; kd++) {
    //     if (discounts[kd].apply_on == "cart" && discounts[kd].type == "currency") {
    //         totalDiscount = totalDiscount + Number(parseFloat(discounts[kd].value).toFixed(2));
    //     }
    // };

    return Number((totalDiscount).toFixed(2));
}

function isDiscountApplicableForProduct(coupon, product, cart_id) {
    if (coupon.apply_on == "cart") {
        return true;
    }

    if (coupon.apply_on == "conditions") {
        let productsThatPassed = [];
        let cart = getCartFromStorage(cart_id);

        for (var cck = 0; cck < coupon.conditions.length; cck++) {
            for (var cr = 0; cr < coupon.conditions[cck].rules.length; cr++) {
                if (isCouponConditionRulePassedByCart(coupon.conditions[cck].rules[cr], cart)) {
                    productsThatPassed = productsThatPassed.concat(getProductsFromCouponConditionRuleByCart(coupon.conditions[cck].rules[cr], cart));
                }
            };
        };

        if (productsThatPassed.includes(product.id)) {
            return true;
        }

    }

    if (coupon.apply_on == "product") {
        if (coupon.apply_product == product.id) {
            return true;
        }
    }

    return false;
}

function calculateDiscount(coupon, price, applied = false) {
    let discountValue = 0;
    let discountPrice = price;

    discountValue = calculateDiscountValue(coupon, price);
    discountPrice = applyDiscount(coupon, discountPrice);

    return !applied ? discountValue : discountPrice;
}

function calculateDiscountValue(coupon, price) {
    switch (coupon.type) {
        case 'currency':
            return Number((Number(coupon.value) > price) ? price : Number(coupon.value));
            break;
        case 'percentage':
            return Number((price * (Number(coupon.value) / 100)));
            break;
        case 'gift':
            return Number(getProductDetails(coupon.apply_product).current_price);
            break;
    }
}

function applyDiscount(coupon, price) {
    return price - calculateDiscountValue(coupon, price);
}

function formatPrice(raw) {
    return '€ '+raw.toFixed(2).replace('.', ',')
}
/* END OF PRICE FUNCTIONS */








function OLDopenPaymentModal(cartId) {
    $('.cof_checkoutCashInput').val('0.00');
    $('.cof_checkoutCardInput').val('0.00');

    let carts = getAllCartsFromStorage();
    for (var i = 0; i < carts.length; i++) {
        if( carts[i].id == cartId) {
            /*let products = carts[i].products;

            let shipping = 0;*/

            let price = getTotalPrice(cartId);
            $('.cof_checkoutCashFitPayment').text(formatPrice(price));
            $('.cof_checkoutCardFitPayment').text(formatPrice(price));
            $('.cof_checkoutPendingAmount').text(formatPrice(price));
            $('#cof_finalizeOrderBtn').prop('disabled', true);
            $('#paymentModal').modal('show')
        }
    };

}

function resetPaymentModal(cartId) {
    $('.cof_checkoutCashInput').val('0.00');
    $('.cof_checkoutCardInput').val('0.00');

    let price = 0.00;
    $('.cof_checkoutCashFitPayment').text(formatPrice(price));
    $('.cof_checkoutCardFitPayment').text(formatPrice(price));
    $('.cof_checkoutPendingAmount').text(formatPrice(price));

    $('#cof_finalizeOrderBtn').prop('disabled', true);
    $('#cof_finalizeOrderBtn').text('Bestelling voltooien');

    $('#paymentModal').modal('hide');
}



function validateForm() {
    var valid = true;
    $('.legal_label').first().css('color', '#555');

    $('input[required]').each(function () {
        if ($(this).val() === '') {
            $(this).addClass('is-invalid');
            valid = false;
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    if($('input[name=legal_approval]:checked').length == 0) {
        $('.legal_label').first().css('color', 'red');
    }
    return valid
}

function getProducts() {
    var products = [];
    $('.cof_cartProductListItem').each(function() {
        var elem = $(this);

        products.push({
            product_id: elem.attr('data-product-id'),
            attributes: elem.attr('data-attribute-name') == '' ? false : elem.attr('data-attribute-name'),
            options: elem.attr('data-product-options') == '' ? false : elem.attr('data-product-options'),
            extras: elem.attr('data-product-extras') == '' ? false : elem.attr('data-product-extras'),
            name: elem.attr('data-product-name'),
            price: Number(elem.attr('data-unit-price')),
            qty: Number(elem.attr('data-quantity')),
            totprice: Number(elem.attr('data-total-price'))
        })

    });
    return products;
}



function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/[^\w ]+/g,'')
        .replace(/ +/g,'-')
        .replace('-','')
        ;
}

function resetFirstProduct()
{
    $('.cof_cartProductListItem:first').attr('data-product-id', 0);
    $('.cof_cartProductListItem:first').attr('data-quantity', 0);
    $('.cof_cartProductListItem:first').attr('data-product-name', '');
    $('.cof_cartProductListItem:first').attr('data-attribute-name', '');
    $('.cof_cartProductListItem:first').attr('data-product-options', '');
    $('.cof_cartProductListItem:first').attr('data-unit-price', 0);
    $('.cof_cartProductListItem:first').attr('data-total-price', 0);
    $('.cof_cartProductListItem:first').find('.cof_cartProductListItemOptions:not(:first)').remove();
    $('.cof_cartProductListItem:first').find('.cof_cartProductListItemOptions:first').removeClass('d-block').addClass('d-none');
}

function updateProductListItemAttributes(cart_id, selector, product_id, product_name, attribute_name, product_options_json, product_extras_json, product_subproducts_json, quantity, current_price, total_price)
{
    productListItem = $('.cof_cartTab[data-cart-id='+cart_id+']').find(''+selector+'');

    productListItem.attr('data-product-id', product_id);
    productListItem.attr('data-product-name', product_name);
    productListItem.attr('data-attribute-name', attribute_name);


    if(product_options_json.length > 0 && product_options_json !== undefined && product_options_json !== '[]') {
        prod_json = ""+product_options_json+"";
    } else {
        prod_json = '';
    }

    if(product_extras_json.length > 0 && product_extras_json !== undefined && product_extras_json !== '[]') {
        prodex_json = ""+product_extras_json+"";
    } else {
        prodex_json = '';
    }

    if(product_subproducts_json.length > 0 && product_subproducts_json !== undefined && product_subproducts_json !== '[]') {
        prodsubprod_json = ""+product_subproducts_json+"";
    } else {
        prodsubprod_json = '';
    }

    productListItem.attr('data-product-options', prod_json);
    productListItem.attr('data-product-extras', prodex_json);
    productListItem.attr('data-product-subproducts', prodsubprod_json);
    productListItem.attr('data-quantity', quantity);
    productListItem.attr('data-unit-price', current_price);
    productListItem.attr('data-total-price', total_price);
    productListItem.attr('data-unique-el', '');

    if(quantity > 1) {
        productListItem.find('.cof_cartProductListItemSubtraction').removeClass('trash');
        productListItem.find('.cof_cartProductListItemSubtraction').find('i')
                .removeClass('fa-trash')
                .addClass('fa-minus');
    }

    if(quantity == 1) {
        productListItem.find('.cof_cartProductListItemSubtraction').addClass('trash');
        productListItem.find('.cof_cartProductListItemSubtraction').find('i')
                .removeClass('fa-minus')
                .addClass('fa-trash');
    }

    full_name = product_name
                 + ((attribute_name == '' || attribute_name == undefined) ? '' : ' - ' + attribute_name);

    productListItem.find('.cof_cartProductListItemOptions:not(:first)').remove();
    productListItem.find('.cof_cartProductListItemOptions:first').removeClass('d-block').addClass('d-none');

    if(product_options_json !== '' && product_options_json !== undefined && product_options_json !== '[]') {
        product_options = JSON.parse(product_options_json);

        productListItem.find('.cof_cartProductListItemOptions:last').addClass('d-block').removeClass('d-none');

        for (var i = 0; i < product_options.length; i++) {
            if(i > 0) {
                productListItem.find('.cof_cartProductListItemOptions:first').clone().appendTo(productListItem.find('.cof_cartProductListDetails:first'));
            }

            productListItem.find('.cof_cartProductListItemOptions:last')
                           .find('.cof_cartProductListItemOptionName')
                           .text(product_options[i]['name']);
            productListItem.find('.cof_cartProductListItemOptions:last')
                           .find('.cof_cartProductListItemOptionValue')
                           .text(product_options[i]['value']);

        };
    }

    productListItem.find('.cof_cartProductListItemExtras:not(:first)').remove();
    productListItem.find('.cof_cartProductListItemExtras:first').appendTo(productListItem.find('.cof_cartProductListDetails:first'));
    productListItem.find('.cof_cartProductListItemExtras:first').removeClass('d-block').addClass('d-none');

    if(product_extras_json !== '' && product_extras_json !== undefined && product_extras_json !== '[]') {
        product_extras = JSON.parse(product_extras_json);

        productListItem.find('.cof_cartProductListItemExtras:last')
                        .addClass('d-block')
                        .removeClass('d-none');

        extra_faulty_check = false;
        g = 0;
        for (var i = 0; i < product_extras.length; i++) {
            if(!$.isEmptyObject(product_extras[i])) {
                extra_faulty_check = true;
                if(g > 0) {
                    productListItem.find('.cof_cartProductListItemExtras:first').clone().appendTo(productListItem.find('.cof_cartProductListDetails:first'));
                }

                productListItem.find('.cof_cartProductListItemExtras:last').find('.cof_cartProductListItemOptionName').text(product_extras[i]['name']);
                productListItem.find('.cof_cartProductListItemExtras:last').find('.cof_cartProductListItemOptionValue').text( formatPrice(parseFloat(product_extras[i]['value'])) );

                g++;
            }

        };

        if(!extra_faulty_check) {
            productListItem.find('.cof_cartProductListItemExtras:last').addClass('d-none').removeClass('d-block');
        }
    }

    productListItem.find('.cof_cartProductListItemSubproducts:not(:first)').remove();
    productListItem.find('.cof_cartProductListItemSubproducts:first').appendTo(productListItem.find('.cof_cartProductListDetails:first'));
    productListItem.find('.cof_cartProductListItemSubproducts:first').removeClass('d-block').addClass('d-none');

    if(product_subproducts_json !== '' && product_subproducts_json !== undefined && product_subproducts_json !== '[]') {
        product_subproducts = JSON.parse(product_subproducts_json);
        productListItem.find('.cof_cartProductListItemSubproducts:last')
                        .detach()
                        .insertAfter($(productListItem.find('.cof_cartProductListItemExtras:last')))
                        .addClass('d-block')
                        .removeClass('d-none');

        for (var i = 0; i < product_subproducts.length; i++) {
            if(i > 0) {
                productListItem.find('.cof_cartProductListItemSubproducts:first').clone().appendTo(productListItem.find('.cof_cartProductListDetails:first'));
            }
            productListItem.find('.cof_cartProductListItemSubproducts:last').find('.cof_cartProductListItemSubproductGroupItems:last ul li:not(:first)').remove();
            for(var p=0; p<product_subproducts[i]['products'].length; p++) {
                if (p > 0){
                    productListItem
                    .find('.cof_cartProductListItemSubproducts:last')
                    .find('.cof_cartProductListItemSubproductGroupItems ul li:first')
                    .clone()
                    .appendTo(productListItem.find('.cof_cartProductListItemSubproducts:last').find('.cof_cartProductListItemSubproductGroupItems ul:last'));
                }

                if (product_subproducts[i]['products'][p]['p_extra_price'] == 0) {
                    productListItem.find('.cof_cartProductListItemSubproducts:last')
                    .find('.cof_cartProductListItemSubproductGroupItems ul li:last')
                    .text(product_subproducts[i]['products'][p]['p_name']+' x '+product_subproducts[i]['products'][p]['p_qty']);
                }else{
                    productListItem.find('.cof_cartProductListItemSubproducts:last')
                    .find('.cof_cartProductListItemSubproductGroupItems ul li:last')
                    .text(product_subproducts[i]['products'][p]['p_name']+' x '+product_subproducts[i]['products'][p]['p_qty']+' (extra: '+'€ '+parseFloat(product_subproducts[i]['products'][p]['p_extra_price']*product_subproducts[i]['products'][p]['p_qty']).toFixed(2).replace('.', ',')+' )');
                }
            }
        }

    }

    productListItem.find('.cof_cartProductListItemFullName').text(full_name);
    productListItem.find('.cof_cartProductListItemQuantity:not(input)').text(quantity);
    productListItem.find('.cof_cartProductListItemQuantity:input').val(quantity);
    productListItem.find('.cof_cartProductListItemUnitPrice').text(formatPrice(parseFloat(current_price)));
    productListItem.find('.cof_cartProductListItemTotalPrice').text(formatPrice(parseFloat(total_price)));
}

function updateProductListItemQuantity(cart_id, selector, quantity, total_price)
{
    productListItem = $('.cof_cartTab[data-cart-id='+cart_id+']').find(''+selector+'');

    newquantity = parseInt(productListItem.attr('data-quantity')) + parseInt(quantity);

    if(quantity === -1 && newquantity === 0) {
        removeProductFromCart(cart_id, selector);
        return 'removed';
    }

    productListItem.attr('data-quantity', newquantity);
    productListItem.find('.cof_cartProductListItemQuantity:not(input)').text(newquantity);
    productListItem.find('.cof_cartProductListItemQuantity:input').val(newquantity);

    if(newquantity > 1) {
        productListItem.find('.cof_cartProductListItemSubtraction').removeClass('trash');
        productListItem.find('.cof_cartProductListItemSubtraction').find('i')
                .removeClass('fa-trash')
                .addClass('fa-minus');
    }

    if(quantity === -1 && newquantity === 1) {
        productListItem.find('.cof_cartProductListItemSubtraction').addClass('trash');
        productListItem.find('.cof_cartProductListItemSubtraction').find('i')
                .removeClass('fa-minus')
                .addClass('fa-trash');
    }

    new_total_price = Number((Number(productListItem.attr('data-total-price')) + Number(total_price)).toFixed(2));
    productListItem.attr('data-total-price', new_total_price);
    productListItem.find('.cof_cartProductListItemTotalPrice').text(formatPrice(parseFloat(new_total_price)));

    productListItem.attr('data-unique-el', '');

    return 'updated';
}

function resetOptionsModal()
{
    $('#attributesModalBody').addClass('d-none');
    $('.attributes_modal_item_button_group').attr('data-product-id', '');
    $('.attributes_modal_item_button:not(:first)').remove();
    $('.attributes_modal_item_button:first').removeClass('active');
    $('.attributes_modal_item_button:first').find('input').attr('id', '').prop('checked', false);
    $('.attributes_modal_item_button:first').find('input').attr('data-attribute-name', '');

    $('.options_modal_row:not(:first)').remove();
    $('.options_modal_row:first').find('.cof_options_radio_item_input:not(:first)').remove();
    $('.options_modal_row:first').find('.cof_options_select_item_input').find('option:not(:first)').remove();
    $('.options_modal_row:first').attr('data-product-option-type', '');
    $('.options_modal_row:first').attr('data-product-option-name', '');
    $('.options_modal_row:first').find('.cof_options_radio_item_input:first').find('input').attr('name', 'cof_options_radio');

    $('.extras_modal_row:not(:first)').remove();
    $('.extras_modal_row:first').find('.extras_item_name').text('cof_extra_name');
    $('.extras_modal_row:first').find('.extras_item_name').attr('for', 'cof_extra_name');
    $('.extras_modal_row:first').find('.extras_item_checkbox').attr('id', 'cof_extra_name');
    $('.extras_modal_row:first').find('.extras_item_checkbox').val('');
    $('.extras_modal_row:first').find('.extras_item_checkbox').attr('data-product-extra-item-price', '');
    $('.extras_modal_row:first').find('.extras_item_checkbox').attr('data-product-extra-item-vat-delivery', '');
    $('.extras_modal_row:first').find('.extras_item_checkbox').attr('data-product-extra-item-vat-takeout', '');
    $('.extras_modal_row:first').find('.extras_item_checkbox').attr('data-product-extra-item-vat-on-the-spot', '');

    // subproduct modal
    $('#subproductModalBody').addClass('d-none');
    $('.subproduct_group_modal_row:not(:first)').remove();
    $('.subproduct_group_modal_row:last .card:not(:first)').remove();
    $('#optionsModal #subproduct_group_total_price').text('');
    $('#optionsModal .product_qty').val('0');
    $('.subproduct_product_group_selected').text('0');
}

function setOptionsModal(product_id, product_name, current_price, quantity, total_price, product_attributes, product_options, product_extras, product_subproducts)
{
    $('.options_product_name').text(product_name);
    $('#addProductFromModalToCartButton').attr('data-product-id', product_id);
    $('#addProductFromModalToCartButton').attr('data-current-price', current_price);
    $('#addProductFromModalToCartButton').attr('data-quantity', quantity);
    $('#addProductFromModalToCartButton').attr('data-total-price', total_price);

    if(product_attributes.length > 0) {
        $('#attributesModalBody').removeClass('d-none');
        $('.attributes_modal_item_button_group').attr('data-product-id', product_id);

        for (var i = 0; i < product_attributes.length; i++) {
            if(i > 0) {
                $('.attributes_modal_item_button:first').clone().appendTo('.attributes_modal_item_button_group');
            }

            attribute_name = product_attributes[i]['name'];
            attribute_price = product_attributes[i]['price'];

            $('.attributes_modal_item_button:last').removeClass('active');
            $('.attributes_modal_item_button:last').find('input').attr('checked', false);

            // $('.attributes_modal_item_button:last').attr('data-product-attribute-name', attribute_name);
            // $('.attributes_modal_item_button:last').attr('data-product-attribute-price', attribute_price);
            $('.attributes_modal_item_button:last').find('input').attr('id', 'attributeRadio'+i);
            $('.attributes_modal_item_button:last').find('input').attr('data-attribute-name', attribute_name);
            $('.attributes_modal_item_button:last').find('input').attr('data-attribute-price', attribute_price);
            $('.attributes_modal_item_button:last').find('.attributes_modal_item_button_text').attr('id', 'attribute'+i).text(attribute_name+(attribute_price !== null ? ' ('+formatPrice(parseFloat(attribute_price))+')' : ''));

            if(i == 0) {
                $('.attributes_modal_item_button:last').addClass('active');
                $('.attributes_modal_item_button:last').find('input').attr('checked', true);
            }
        }
    }


    radio_ids = [];
    if(product_options.length > 0) {
        $('#optionsModalBody').removeClass('d-none');
        for (var i = 0; i < product_options.length; i++) {
            if(i > 0) {
                $('.options_modal_row:first').clone().appendTo('#optionsModalBody');
            }

            option_name = product_options[i]['name'];
            option_type = product_options[i]['type'];
            option_values = product_options[i]['values'].split(',');

            $('.options_modal_row:last').attr('data-product-option-type', option_type);
            $('.options_modal_row:last').attr('data-product-option-name', option_name);
            $('.options_modal_row:last').find('.options_item_name').text(option_name);

            if(option_type == 'radio') {
                $('.options_modal_row:last').find('.options_modal_item_radio').removeClass('d-none hidden');
                $('.options_modal_row:last').find('.options_modal_item_select').addClass('d-none hidden');

                $('.options_modal_row:last').find('.cof_options_radio_item_input:not(:first)').remove();

                $('.options_modal_row:last').attr('data-product-option-input-name', 'cof_options_radio_'+i);

                for (var k = 0; k < option_values.length; k++) {
                    if(k > 0) {
                        $('.options_modal_row:last').find('.cof_options_radio_item_input:first').clone().appendTo('.options_modal_row:last .cof_options_radio_item_input_group');
                    }
                    $('.options_modal_row:last')
                        .find('.cof_options_radio_item_input:last input:first').first()
                        .val(option_values[k])
                        .attr('id', 'cofOptionsRadioId'+i+'_'+k)
                        .attr('name', 'cof_options_radio_'+i)
                        .attr('checked', false);
                    $('.options_modal_row:last').find('.cof_options_radio_item_input:last').find('span').text(option_values[k]).attr('for', 'cofOptionsRadioId'+i+'_'+k);
                    $('.options_modal_row:last').find('.cof_options_radio_item_input:last').find('label').attr('for', 'cofOptionsRadioId'+i+'_'+k);

                    if(k == 0) {
                        radio_ids.push('#cofOptionsRadioId'+i+'_'+k);
                    }

                }

            }
            if(option_type == 'select') {
                $('.options_modal_row:last').find('.options_modal_item_radio').addClass('d-none hidden');
                $('.options_modal_row:last').find('.options_modal_item_select').removeClass('d-none hidden');

                $('.options_modal_row:last').find('.cof_options_select_item_input').attr('name', 'cof_options_select'+i);
                $('.options_modal_row:last').find('.cof_options_select_item_input').attr('id', 'cof_options_selectId'+i);

                $('#cof_options_selectId'+i).find('.cof_options_option_input:not(:first)').remove();
                $('.options_modal_row:last').find('.cof_options_radio_item_input:not(:first)').remove();
                $('.options_modal_row:last').find('.cof_options_radio_item_input:first').attr('name', 'cof_options_radio').attr('id', '');

                $('.options_modal_row:last').find('.cof_options_radio_item_input:first').find('label:first').attr('for', '');
                $('.options_modal_row:last').find('.cof_options_radio_item_input:first').find('input:first').attr('name', 'cof_options_radio').attr('id', '').val('');

                $('.options_modal_row:last').attr('data-product-option-input-name', 'cof_options_select'+i);

                for (var j = 0; j < option_values.length; j++) {
                    if(j > 0) {
                        $('#cof_options_selectId'+i+' .cof_options_option_input:first').first().clone().appendTo('#cof_options_selectId'+i);
                    }

                    $('#cof_options_selectId'+i).find('.cof_options_option_input:last').last().val(option_values[j]);
                    $('#cof_options_selectId'+i).find('.cof_options_option_input:last').last().text(option_values[j]);
                    $('#cof_options_selectId'+i).find('.cof_options_option_input:last').last().prop('selected', false);

                }
                $('#cof_options_selectId'+i).val($('#cof_options_selectId'+i).find('option').first().val());
            }

        };

        for (var i = 0; i < radio_ids.length; i++) {
            $(''+radio_ids[i]+'').prop('checked', true);
        };

    } else {
        $('#optionsModalBody').addClass('d-none');
    }


    if(product_extras.length > 0) {
        $('#extrasModalBody').removeClass('d-none');
        for (var i = 0; i < product_extras.length; i++) {
            if(i > 0) {
                $('.extras_modal_row:first').clone().appendTo('#extrasModalBody');
            }

            extra_name = product_extras[i]['name'];
            extra_slug = extra_name.toLowerCase().replace(/ /g,'-').replace(/[-]+/g, '-').replace(/[^\w-]+/g,'');
            extra_price = product_extras[i]['price'];
            extra_vat_delivery = product_extras[i]['vat_delivery'];
            extra_vat_takeout = product_extras[i]['vat_takeout'];
            extra_vat_on_the_spot = product_extras[i]['vat_on_the_spot'];

            //$('.extras_modal_row:last').attr('data-product-option-type', option_type);
            //$('.extras_modal_row:last').attr('data-product-option-name', option_name);
            $('.extras_modal_row:last').find('.extras_item_name')
                            .text( extra_name + ' (€ ' + parseFloat(extra_price).toFixed(2).replace('.', ',') + ')' );
            $('.extras_modal_row:last').find('.extras_item_name').attr('for', extra_slug);
            $('.extras_modal_row:last').find('.extras_item_checkbox').attr('id', extra_slug);
            $('.extras_modal_row:last').find('.extras_item_checkbox').val(extra_name);
            $('.extras_modal_row:last').find('.extras_item_checkbox').attr('data-product-extra-item-price', parseFloat(extra_price));
            $('.extras_modal_row:last').find('.extras_item_checkbox').attr('data-product-extra-item-vat-delivery', parseInt(extra_vat_delivery));
            $('.extras_modal_row:last').find('.extras_item_checkbox').attr('data-product-extra-item-vat-takeout', parseInt(extra_vat_takeout));
            $('.extras_modal_row:last').find('.extras_item_checkbox').attr('data-product-extra-item-vat-on-the-spot', parseInt(extra_vat_on_the_spot));
            $('.extras_modal_row:last').find('.extras_item_checkbox').prop('checked', false);



        };

    } else {
        $('#extrasModalBody').addClass('d-none');
    }

    if(product_subproducts.length > 0) {
        $('#subproductModalBody').removeClass('d-none');
        $('#optionsModal .modal-img').removeClass('d-none');
        product_image = $('.chuck_ofm_product_tile[data-product-id='+product_id+']').closest('.chuck_ofm_product_tile').find('.cof_productImage').attr('src');
        $('#optionsModal .modal-img img').attr( 'src', product_image);
        $('#subproductModalBody').closest('.modal-dialog').css("maxWidth", "1140px");
        $('#subproduct_group_total_price').parent().removeClass('d-none');

        for (var i = 0; i < product_subproducts.length; i++) {
            if (i > 0) {
                $('#subproductModalBody .subproduct_group_modal_row:first').clone().appendTo('#subproductModalBody');
                $('#subproductModalBody .subproduct_group_modal_row:last .card:not(:first)').remove();
            }
            group_name = product_subproducts[i]['name'];
            group_label = product_subproducts[i]['label'];
            group_max = product_subproducts[i]['max']*quantity;
            group_min = product_subproducts[i]['min']*quantity;
            group_products = product_subproducts[i]['products'];

            $('#subproductModalBody .subproduct_group_modal_row:last').find('.subproduct_product_group_label').text(group_label);
            $('#subproductModalBody .subproduct_group_modal_row:last').attr('id', 'group_'+i);
            $('#subproductModalBody .subproduct_group_modal_row#group_'+i).attr('data-product-group-name', group_name);
            $('#group_'+i+' .subproduct_product_group_max').text(group_max);

            $.each(group_products, function(index, product) {
                if(index > 0) {
                    $('#subproductModalBody .subproduct_group_modal_row:last')
                        .find('.subproduct_group_product:first')
                        .clone()
                        .appendTo('.subproduct_group_modal_row:last .subproduct_group_product_row');
                }
                let productId = $('#subproductModalBody .subproduct_group_modal_row:last').attr('id')+'_product_'+index;
                $('#subproductModalBody .subproduct_group_modal_row:last').find('.subproduct_group_product:last').attr('id', productId);
                if (product.extra_price > 0){
                    $('.subproduct_group_modal_row:last').find('.subproduct_group_product#'+productId+' .product_extra_price').removeClass('d-none');
                    $('.subproduct_group_modal_row:last').find('.subproduct_group_product#'+productId+' .product_extra_price').attr('data-extra-price', parseFloat(product.extra_price));
                    $('.subproduct_group_modal_row:last').find('.subproduct_group_product#'+productId+' .product_extra_price').text('extra € '+ parseFloat(product.extra_price).toFixed(2).replace(".", ","));
                } else {
                    $('#subproductModalBody .subproduct_group_modal_row:last').find('.subproduct_group_product#'+productId+' .product_extra_price').addClass('d-none');
                    $('#subproductModalBody .subproduct_group_modal_row:last').find('.subproduct_group_product#'+productId+' .product_extra_price').attr('data-extra-price', 0);
                }

                $('#subproductModalBody .subproduct_group_modal_row:last').find('.subproduct_group_product#'+productId).attr('data-extra-price', parseFloat(product.extra_price).toFixed(2));
                $.ajax({
                    url: "/product/"+product.id+"/json",
                    type: 'get'
                })
                .done(function(data){

                    let currentLoc = $('.cof_location_radio:checked').val();

                    $('#'+productId+' .subproduct_group_product_name').text(data.product.json['name']['{{app()->getLocale()}}']);
                    //$('.subproduct_group_product#'+productId+' img').attr('src', data.product.json['featured_image']);

                    if (data.product.json['quantity'][currentLoc] > 0){

                        $('#'+productId).attr('data-max-qty', data.product.json['quantity'][currentLoc]);
                    }else {

                        $('#'+productId).attr('data-max-qty', '-1');
                    }
                });
            });
        }

        $('#optionsModal #subproduct_group_total_price').attr('data-current-price', total_price);
        $('#optionsModal #subproduct_group_total_price').text('€ '+parseFloat(total_price).toFixed('2').replace('.', ','));

    }else {
        $('#subproductModalBody').addClass('d-none');
        $('#subproductModalBody').closest('.modal-dialog').removeAttr("style")
        $('#subproduct_group_total_price').parent().addClass('d-none');
        $('#optionsModal .modal-img').addClass('d-none');
        $('#optionsModal .modal-img img').attr( 'src', '');
    }

}

function printTicketFromCart(cart_id, order_number, order_date, order_time) {
    let cart = getCartFromStorage(cart_id);
    let items = getFormattedItemsForTicket(cart.products, cart.discounts);
    let vat = getFormattedVatItemsForTicket(cart.products);
    let location = getActiveLocationDetails(cof_pos_active_location);
    let payments = getPaymentsForCart(cart_id, cart.total);

    let job = {
        location: location,
        cart: cart,
        items: items,
        subtotal: cart.subtotal,
        discount: cart.discount,
        total: cart.total,
        vat: vat,
        payments: payments,
        date: order_date,
        time: order_time,
        customer: cart.customer_id
    };


    printJob(job);
}





function checkIfProductHasOptionsOrExtrasOrDiscounts(product) {
    if (product.options.length > 0) {
        return true;
    }

    if (product.extras.length > 0) {
        for (var pext = 0; pext < product.extras.length; pext++) {
            if (!$.isEmptyObject(product.extras[pext])) {
                return true;
            }
        };
    }

    if (product.subproducts.length > 0) {
        for (var pext = 0; pext < product.subproducts.length; pext++) {
            if (!$.isEmptyObject(product.subproducts[pext])) {
                return true;
            }
        };
    }

    if (product.discounts.length > 0) {
        return true;
    }

    return false;
}

function truncateString(str, num) {
  if (str.length <= num) {
    return str;
  }
  return str.slice(0, num) + '...';
}

function getLineSize() {
    return 48;
}


// Initialize toast
$('.toast').toast({delay:2500});



});






// let online = true;
// setInterval(function(){
//     let img = new Image();
//     img.onerror=function() {
//         online = false;
//     }
//     img.src="https://donuttello.com/img/donuttello-logo.png?rnd="+new Date().getTime();
// }, 3000);




</script>
@include('chuckcms-module-ecommerce::pos.scripts.helpers')
@include('chuckcms-module-ecommerce::pos.scripts.fullscreen')
@include('chuckcms-module-ecommerce::pos.scripts.print')
@include('chuckcms-module-ecommerce::pos.scripts.scanner')
