<script src="https://kit.fontawesome.com/e23a04b30b.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('body').on('click', '#openCouponsModal', function (event) {
            event.preventDefault();
            $('#couponsModal').modal('show');
        });
    });
</script>