<script>
$(document).ready(function() {
    // Initialize onScan
    onScan.attachTo(document, {
        suffixKeyCodes: [9], // enter-key expected at the end of a scan
        reactToPaste: true,
        onScan: function(sCode, iQty) {
            cart_id = getActiveCart();
            coupon = null;

            customer_id = getCustomerByEan(sCode);

            if (customer_id == getGuestCustomer()) { //customer = guest > ean might be a coupon
                if (getCouponByEan(sCode) != false) {
                    coupon = getCouponByEan(sCode);
                    customer_id = coupon.customer_id;
                }
            }

            updateCustomerForCart(customer_id, cart_id);

            if (coupon != null && !isCouponInAnyCart(coupon)) {
                if (coupon.status == "awaiting") {
                    addScannedCouponToCart(coupon);
                    $('#couponAddedToCartToast').toast('show');
                }
            } else if (coupon != null && isCouponInAnyCart(coupon)) {
                $('#couponAlreadyInCartToast').toast('show')
            }

            $('#customerChangedToast').toast('show')
        },
        keyCodeMapper: function(oEvent) {
            return String.fromCharCode(oEvent.keyCode);
        }
    });
})
</script>
