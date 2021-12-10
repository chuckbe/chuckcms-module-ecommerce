<script>
    $(document).ready(function() {
        $('body').on('click', '#openCouponsModal', function (event) {
            event.preventDefault();
            $('#couponsModal').modal('show');
        });
    });
</script>