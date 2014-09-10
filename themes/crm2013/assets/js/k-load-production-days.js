/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-06-20 11:10:16
 * @version $Id$
 */
jQuery(function($) {
    var wap = $('#wap-production');
    wap.on('click', '.check-pro', function() {
        var t = $(this),
            p1 = t.parent().find('.d-content'),
            par = p1.find('input');
        if ($(this).prop('checked')) {
            p1.removeClass('hide');
            par.removeAttr('disabled').attr('required', 'required');
        } else {
            p1.addClass('hide');
            par.attr('disabled', 'disabled').removeAttr('required');
        }
    })
    var formSubmit = $('#form-submit'),
        result = false;
    // formSubmit.attr('target', ifm.attr('name'));
    formSubmit.on('submit', function(event) {
        result = false;
        // event.preventDefault();
        formSubmit.find('input[required]').removeClass('error').each(function() {
            var t = $(this)
            value = t.val();
            if (t.attr('type') == 'number') {
                // ＞0正的数字判断
                if (value.search(/^\d+[\d\.]?\d*$/) != 0) {
                    value = 0;
                } else {
                    value = parseFloat(value).toFixed(2).replace('.00', '');
                }
            };
            t.val(value);
            if (value == '' || value <= 0 || value == t.attr('placeholder')) {
                t.addClass('error');
                if (result == false) {
                    result = t;
                }
            };
        });
        if (result) {
            result.focus();
        } else {
            formSubmit.find('.list-production-process-days').removeClass('error').each(function() {
                var t = $(this);
                if (t.find("input:checked").length == 0) {
                    t.addClass('error');
                    if (result == false) {
                        result = true;
                    }
                };
            });
        }
        if (result) {
            event.preventDefault();
        };
    })
    if (tak.ihtml5.placeholder) {
        $('.placeholder').each(function() {
            var elem = $(this);
            elem.focus(function() {
                if (elem.val() == elem.attr("placeholder")) elem.val("");
            }).blur(function() {
                if (elem.val() == "") elem.val(elem.attr("placeholder"));
            });
            if (elem.val() == '') {
                elem.val(elem.attr("placeholder"));
            };
            elem.removeClass('placeholder');
        });
    };
});