<script>
    const sessionToken = "{{ Session::token() }}";

    const PosURL = {
        addToCart: '{{ route("dashboard.module.ecommerce.pos.cart.add") }}',
        getCombinations: '{{ route("dashboard.module.ecommerce.pos.combinations") }}',
        updateCartItem: '{{ route("dashboard.module.ecommerce.pos.cart.update") }}',
        removeCartItem: '{{ route("dashboard.module.ecommerce.pos.cart.remove") }}',
        scanCode: '{{ route("dashboard.module.ecommerce.pos.scanner") }}',
        initiatePayment: '{{ route("dashboard.module.ecommerce.pos.payment.initiate") }}',
        cashPayment: '{{ route("dashboard.module.ecommerce.pos.cash") }}',
        terminalPayment: '{{ route("dashboard.module.ecommerce.pos.terminal") }}',
        checkTerminalPayment: '{{ route("dashboard.module.ecommerce.pos.terminal.check") }}',
        removePayment: '{{ route("dashboard.module.ecommerce.pos.payment.remove") }}',
        cancelOrder: '{{ route("dashboard.module.ecommerce.pos.order.cancel") }}',
        finalizeOrder: '{{ route("dashboard.module.ecommerce.pos.order.finalize") }}'
    }
</script>
