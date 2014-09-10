/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-06-06 08:35:31
 * @version $Id$
 */
jQuery(function($) {
    window.popupCate = function(data) {
        $('input[name=vendor_id_display]').val(data.text);
        $('.sourceField').val(data.id);

    }
    window.popups = {};
    var getPopups = function(elem) {
        var data = {}, name = $(elem).attr('name');
        if (typeof window.popups[name] === 'undefined') {
            data.val = $(elem);
            var parent = $(elem).parent();
            data.txt = parent.find('input[name=vendor_id_display]');
            data.mod = parent.find('input[name=popupReferenceModule]');
            data.parent = parent;
            window.popups[name] = data;
        } else {
            data = window.popups[name];
        }
        return data;
    }
    $(document).on('click', '.relatedPopup,.clearReferenceSelection', function() {
        var t = $(this),
            data = getPopups(t.parent().find('.sourceField'));
        if (t.hasClass('relatedPopup')) {
            url = createUrl('Category/admin', ['m=' + data.mod.val(), 'action=select']);
            wurl = url;
            if (data.val.val() > 0) {
                wurl += '&id=' + data.val.val();
            };
            ShowModal.call(top, wurl, {
                width: 350,
                height: 450,
                name: 'relatedPopup',
                dataFun:popupCate
            });
        } else if (t.hasClass('clearReferenceSelection')) {
            data.txt.val(data.txt.attr('placeholder'));
            data.val.val('');
        }
    }).on('click', '.clearReferenceSelection', function() {
        var data = getPopups($(this).parent().find('.sourceField'));
    }).on('click', '.createPopup', function() {
        var data = getPopups($(this).parent().find('.sourceField'));
        wurl = createUrl('Category/create', ['m=' + data.mod.val(), 'action=select']);
            ShowModal.call(top, wurl, {
                width: 350,
                height: 450,
                name: $(this).attr('title'),
                dataFun:popupCate
            });
    })
});