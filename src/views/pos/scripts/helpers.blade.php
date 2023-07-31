<script>
Number.prototype.padLeft = function(base,chr){
    var  len = (String(base || 10).length - String(this).length)+1;
    return len > 0? new Array(len).join(chr || '0')+this : this;
}

$(function() {
    $.fn.setMIAH();
    $(window).resize(function() {
        $.fn.setMIAH();
    });
});

$.fn.setMIAH = function() {
    let hh = $('#pos_mainWrapper .main .header').outerHeight();
    let ma = $('#pos_mainWrapper .main .menuArea').outerHeight();
    let ha = $('#pos_mainWrapper .main .handlerArea').outerHeight();
    $('#pos_mainWrapper .main .menuItemArea').css({"maxHeight": 'calc(100vh - '+(hh+ma+ha)+'px)'});
};

function popNotification(notification) {
    Swal.fire({
      icon: notification.icon,
      title: notification.title,
      text: notification.message,
      timer: 2000,
      timerProgressBar: true,
    });
}

function popHelperNotification(notification) {
    Swal.fire({
      icon: notification.icon,
      title: notification.title,
      html: notification.message
    });
}
</script>
