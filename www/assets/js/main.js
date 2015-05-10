$(function () {
    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "500",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "slideDown",
        "hideMethod": "fadeOut"
    };

    $(document).ajaxError(function (e, r) {
        if (r.status === 403) {
            window.location.reload(true);
        } else {
            toastr.error('Sorry, chyba na straně serveru.', 'Ouch...');
        }
    });

    $.nette.init();
    $.nette.ext({
        success: function () {
            $.nette.load();
        }
    });
});
