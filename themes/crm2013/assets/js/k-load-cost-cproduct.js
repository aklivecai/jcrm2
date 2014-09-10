jQuery(function($) {
    $(document).on('click', '.action-fold', function() {
        $(this).parents('.itable').eq(0).toggleClass('action-active');
    }).on('click', '.btn-add', function() {
        window.open('list.html?' + (new Date()).toTimeString(), 'newwindow', 'height=600, width=800, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');
    });
    var productView = new ProductLine(itemid);
    if (product) {
        productView.loads(product);
    }
    if (itemid == 0 && !iscopy) {
        productView.inits();
    }
    ko.applyBindings(productView, document.getElementById('cost-product'));
    var form = $('#form-const'),
        ifm = getIfm(),
        isok = false;
    form.find('.ibtn-ok').on('click', function(event) {
        isok = false;
        form.find('input[required]').each(function(i, elem) {
            var t = $(elem),
                v = t.val();
            if (v == '' || v == 0) {
                t.addClass('error');
                t.parents('.table-product').eq(0).removeClass('action-active');
                if (!isok) {
                    isok = t;
                };
            } else {
                t.removeClass('error');
            }
        });
        if (isok) {
            isok.focus();
            event.preventDefault();
            return false;
        }
    });
    form.attr('target', ifm.attr('name'));
});