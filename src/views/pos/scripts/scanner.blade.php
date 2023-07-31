<script>
$(document).ready(function() {
    // Initialize onScan
    onScan.attachTo(document, {
        suffixKeyCodes: [9], // enter-key expected at the end of a scan
        reactToPaste: true,
        onScan: function(sCode, iQty) {
            scanCode(sCode).done(function (data) {
                if (data.status == "success" && data.type == "cart") {
                    $('#pos_cart').replaceWith(data.cart);
                }

                if (data.status == "success" && data.type == "combinations") {
                    let combinationsModal = $('#combinationsModal');

                    combinationsModal.modal('show');

                    $('#addCombinationToCart').data('id', data.id);

                    combinationsModal.find('.modal-body').html(data.body);
                }

                if (data.status == "error") {
                    popNotification(data.notification);
                }
            });

            // cart_id = getActiveCart();
            // coupon = null;

            // customer_id = getCustomerByEan(sCode);

            // if (customer_id == getGuestCustomer()) { //customer = guest > ean might be a coupon
            //     if (getCouponByEan(sCode) != false) {
            //         coupon = getCouponByEan(sCode);
            //         customer_id = coupon.customer_id;
            //     }
            // }

            // updateCustomerForCart(customer_id, cart_id);

            // if (coupon != null && !isCouponInAnyCart(coupon)) {
            //     if (coupon.status == "awaiting") {
            //         addScannedCouponToCart(coupon);
            //         $('#couponAddedToCartToast').toast('show');
            //     }
            // } else if (coupon != null && isCouponInAnyCart(coupon)) {
            //     $('#couponAlreadyInCartToast').toast('show')
            // }

            // $('#customerChangedToast').toast('show')
        },
        keyCodeMapper: function(oEvent) {
            return String.fromCharCode(oEvent.keyCode);
        }
    });

    function scanCode(code) {
        return $.ajax({
            method: 'POST',
            url: PosURL.scanCode,
            data: {
                code: code,
                _token: sessionToken
            }
        });
    }
})
</script>
