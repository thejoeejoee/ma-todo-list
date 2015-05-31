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
        } else if (r.statusText != "canceled") {
            toastr.error('Sorry, chyba na straně serveru.', 'Ouch...');
        }
    }).ajaxSuccess(function (e, r) {
        $.nette.load();
        init();
    });
    var init = function () {
        var $items = $('#items');
        initSortable($items.get(0));
        initFinishing($items);
    };
    init();
    $.nette.init();
});

var initSortable = function (items) {
    return Sortable.create(items, {
        animation: 250,
        handle: ".handle",
        ghostClass: 'active',
        sortable: "li.item.sortable",
        onUpdate: function (evt) {
            var $item = jQuery(evt.item);
            var url = $item.data('insert').replace('__replace__', $item.index());
            $.nette.ajax({
                url: url
            });
        }
    });
};

var initFinishing = function ($items) {
    $items.off('click').on('click', 'button.finish', function (evt, el) {
        var $this = $(this);
        var $item = $this.closest('li.item');
        $item.slideUp(500);
        var $btn = $('<button type="button" class="btn btn-danger btn-undo">Vrátit zpět potvrzení!</button>');
        toastr.info($btn, {hideDuration: 10000, extendedTimeOut: 50000});
        $.nette.ajax({url: $item.data('finish')});

        $btn.click(function () {
            $.nette.ajax({url: $item.data('undo')});
            toastr.warning('Akce zrušena.');
            $item.slideDown(500);
            $item.parent().append($item);
        });
    });
};
