<script>
$(document).ready(function() {
    /* Get the documentElement (<html>) to display the page in fullscreen */
    $('body').on('click', '#pos_fullScreenToggleBtn', function (event) {
        const documentElement = document.documentElement;

        if (document.fullscreenElement != null) {
            closeFullscreen(documentElement);
        } else {
            openFullscreen(documentElement);
        }
    });

    /* View in fullscreen */
    function openFullscreen(elem) {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) { /* Safari */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE11 */
            elem.msRequestFullscreen();
        }
    }

    /* Close fullscreen */
    function closeFullscreen(elem) {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) { /* Safari */
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { /* IE11 */
            document.msExitFullscreen();
        }
    }
});
</script>
