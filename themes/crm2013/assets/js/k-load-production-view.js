/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-06-16 07:45:39
 * @version $Id$
 */
jQuery(function($) {
    var list = $('.list-progresss'),
        takLocation = document.getElementById('tak-location'),
        submitData = function(data, itemid) {
            var btn = $('#' + itemid).find('button').prop('disabled', true);
            $.ajax({
                type: "POST",
                url: postUrl,
                data: data,
                success: function(data) {
                    if (data == '') {
                        takLocation.setAttribute("href", viewUrl + '?t=' + Math.random() + '#' + itemid);
                        takLocation.click();
                    } else {
                        alert(data);
                    }
                },
                complete: function() {
                    btn.removeAttr('disabled');
                }
            });
        };
    list.each(function() {
        var t = $(this),
            progress = t.find('.progress'),
            itemid = t.attr('id'),
            nots = false;
        t.find('.btn-add,.ibtn-cancel').on('click', function() {
            nots = t.hasClass('cure-action');
            list.removeClass('cure-action');
            if (nots == false) {
                t.addClass('cure-action');
            }
        });
        t.find('.ibtn-save').on('click', function() {
            if (progress.val() == '') {
                progress.focus();
                return false;
            };
            data = {
                'itemid': itemid,
                'val': progress.val()
            };
            submitData(data, itemid);
        })
        t.find('.ibtn-ok').on('click', function() {
            if (confirm('是否确认工序已经完成,完成后工序不可再增加进度')) {
                data = {
                    'itemid': itemid,
                    'val': 'over'
                };
                submitData(data, itemid);
            }
        });
    });
    $(document).on('click', '.action-print', function() {
        var html = $(this).parents('.div-over').eq(0).html();
        printHtml(html);
    });
    $('.ibtn-print').on('click', function() {
        var html = $('#wrapper').html();
        html = html.replace(/itable/ig, 'list');
        printHtml(html);
    });
})