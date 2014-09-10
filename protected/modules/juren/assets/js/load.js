/**
 * Anonymous function that is immediately called
 * for making sure that we can use the $-shortcut for jQuery.
 */
function toClipboards(copy_id, txt) {
    var clip = new ZeroClipboard.Client();
    clip.setHandCursor(true);
    clip.setText(txt);
    clip.addEventListener('complete', function(client) {
        alert("复制成功!");
    });
    clip.glue(copy_id);
    if (window.iclips) {
        window.iclips.push(clip);
    };
}
jQuery(function($) {
    $('.btn-confirm').on('click', function(event) {
        event.preventDefault();
        var text = $(this).text(),
            url = 'Check?id=' + $(this).attr('data-itemid');
        dialogWin(text, url);
    });
    var dialogWin = function(title, url) {
        var width = 350,
            height = 200;
        var d = dialog({
            content: '<span class="ui-dialog-loading">加载中...</span>',
            title: title,
            fixed: true,
            width: width,
            height: height,
            padding: 2,
            button: [],
            onshow: function() {},
            onclose: function() {}
        });
        $.ajax({
            type: "get",
            url: url,
            success: function(data) {
                d.content(data);
                $('.ui-dialog-body .btn-close').on('click', function() {
                    d.close().remove();
                });
            },
            error: function() {
                alert('fail');
            }
        });
        d.showModal();
    };
    window.iclips = [];
    ZeroClipboard.setMoviePath("http://i.9juren.com/_ak/js/zeroclipboard/ZeroClipboard.swf"); //手动指定Flash地址 
    var clearCopys = function() {
        for (var i = window.iclips.length - 1; i >= 0; i--) {
            window.iclips[i].destroy();
        };
        window.iclips = [];
    }, copyInit = function() {
            if (window.iclips.length > 0) {
                $('div[style^=position]').remove();
                window.iclips = [];
            };
            var copys = $('.copy');
            if (copys.length > 0) {
                copys.each(function(i, elem) {
                    var txt = $(elem).parents('td').find('a').attr('href');
                    toClipboards($(elem).attr('id'), txt);
                })
            };
        }
    window.kloadGridview = function() {
        copyInit();
    }
    copyInit();
    $('td a[title="点击打开"]').on('click', function(event) {
        event.preventDefault();
    })
    $('.search-button').click(function() {
        $('.search-form').toggle();
        return false;
    });
    $('---.search-form form').submit(function() {
        $('#list-grid').yiiGridView('update', {
            data: $(this).serialize()
        });
        return false;
    });
    $('.logout').on('click', function(event) {
        if (!confirm('是否确认退出?')) {
            event.preventDefault();
        };
    })
    $('.add').on('click', function(evnet) {
        evnet.preventDefault();
        if ($('#mydialog').length > 0) {
            $('#mydialog').dialog("open");
        } else {
            $.ajax({
                url: "test.html"
            }).done(function() {
                $(this).addClass("done");
            });
        }
        $('#mydialog').dialog({
            'title': '修改密码',
            'autoOpen': false,
            'modal': true,
            'minWidth': '100'
        });
    })
    var listDate = $('.date');
    if (listDate.length > 0) {
        listDate.each(function(i, elem) {
            var v = $(elem).val();
            if (v == 0) {
                v = '';
            } else {
                if (v.indexOf('-') <= 0) {
                    time = v * 1000;
                    d = new Date(time);
                    v = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate() + ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
                }
                $(elem).val(v);
            };
            $(elem).on('focus', function() {
                WdatePicker();
            });
        });
    };
});