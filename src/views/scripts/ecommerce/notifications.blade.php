<script>
	function popNotification(type, title, message, icon) {
		h = {
            class: "iziToast-" + type || "",
            title: title || "Title",
            message: message || "toast message",
            animateInside: !1,
            position: "bottomRight",
            progressBar: !1,
            icon: icon,
            timeout: 4800,
            transitionIn: "fadeInLeft",
            transitionOut: "fadeOut",
            transitionInMobile: "fadeIn",
            transitionOutMobile: "fadeOut"
        }

        iziToast.show(h);

	}
</script>