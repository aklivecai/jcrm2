+jQuery(function($) {
    var initPlaceholder = function(elem) {
        if (!elem.attr('placeholder-over')) {
            elem.focus(function() {
                if (elem.val() == elem.attr("placeholder")) elem.val("");
            }).blur(function() {
                if (elem.val() == "") elem.val(elem.attr("placeholder"));
            });
            if (elem.val() == '') {
                elem.val(elem.attr("placeholder"));
            };
            elem.attr('placeholder-over', true);
            var pform = elem.parents('form');
            if (!pform.attr('placeholder-over')) {
                pform.on('submit', function() {
                    pform.find('[placeholder]').each(function() {
                        var input = $(this);
                        if (input.val() == input.attr('placeholder')) {
                            input.val('');
                        }
                    })
                });
                pform.attr('placeholder-over', true);
            };
        };
    }
    var init = function(elem) {
        elem.on('focus', '[placeholder]', function() {
            initPlaceholder($(this));
        }).on('blur', '[placeholder]', function() {
            initPlaceholder($(this));
        });
        elem.find('[placeholder]').blur();
    }
    init($(document));
    $(document.body).on('ajax-load', function() {
        init($(this));
    });
});