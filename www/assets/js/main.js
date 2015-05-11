jQuery(function ($) {
    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "250",
        "hideDuration": "250",
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

var initSortable = function () {
    var sort = Sortable.create(items, {
        animation: 250, // ms, animation speed moving items when sorting, `0` — without animation,
        handle: ".handle",
        sortable: "li.sortable",
        onUpdate: function (evt/**Event*/) {
            var $item = jQuery(evt.item);
            $item.data('order', $item.index());
            console.log(evt.newIndex);
        }
    });
};
