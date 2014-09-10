/**
 * k-load-order-updates
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-07-31 17:06:37
 * @version $Id$
 */
jQuery(function($) {
    var flowForm = $('#flow-form'),
        flowStatus = flowForm.find('#OrderFlow_status'),
        flowName = flowForm.find('#OrderFlow_name'),
        showStatus = $('#show-status'),
        setStatus = function(status, txt) {
            showStatus.html(txt);
            if (status !== '') {
                flowForm.addClass('active');
                flowStatus.val(status);
                if (status == 0) {
                    flowName.removeAttr('disabled');
                    flowName.val('');
                } else {
                    flowName.val(txt);
                    flowName.attr('disabled', true);
                }
            } else {
                flowForm.removeClass('active');
            }
        };
    window.setStatus = setStatus;
    flowForm.on('submit', function(event) {
        if (flowStatus.val() == 0 && flowName.val() == '') {
            event.preventDefault();
            alert('流程名字不能为空!');
            flowName.focus();
        };
    });
    var updatePrice = function(id, value) {
        data = ['itemid=' + id, 'value=' + value];
        $.ajax({
            type: "GET",
            url: takurls.updatePrice + '?' + data.join('&'),
            success: function(data) {
                self.location.href = takurls.updates + '?' + Math.random() + "#" + id;
            }
        });
    }
    $(document).on('dblclick', '.ajax-price', function() {
        var t = $(this),
            input = null;
        if (t.hasClass('over')) {
            return false;
        } else {
            t.addClass('over');
            input = t.find('input');
            value = t.attr('data-value');
            if (input.length == 0) {
                wap = $('<div><span class="btn btn-mini" title="取消"><i class="icon-trash"></i></span><input type="number" style="width:40%;margin:0;padding:0" min="0"/><span class="btn btn-mini btn-success" title="保存"><i class="icon-ok"></i></span></div>');
                wap.appendTo(t);
                input = wap.find('input');
                input.val(value);
                input.keyup(function() {
                    input.val(input.val().replace(/[^0-9.]/g, ''));
                }).bind("paste", function() { //CTR+V事件处理
                    input.val(input.val().replace(/[^0-9.]/g, ''));
                }).css("ime-mode", "disabled"); //CSS设置输入法不可用
                wap.find('.btn').on('click', function() {
                    var _t = $(this),
                        v = input.val();
                    if (_t.hasClass('btn-success')) {
                        if (v == '' || v == 0) {
                            input.focus();
                            return false;
                        }
                        updatePrice(t.attr('id'), v);
                    } else {
                        t.removeClass('over');
                        wap.remove();
                    }
                })
            };
        }
    })
    var mod = $('#modalStatusWap'),
        listRow = mod.find('.tak-data-rows');
    $('#list-status').on('click', ' li a', function(event) {
        event.preventDefault();
        var t = $(this);
        mod.find('h4').text(t.text());
        listRow.addClass('hide');
        if (t.text().indexOf('订单审核') >= 0) {
            listRow.eq(0).removeClass('hide');
        } else {
            listRow.eq(1).removeClass('hide');
        }
        mod.find('#status-form').attr('action', t.attr('href'));
        mod.modal({
            'show': true
        });
    });
    $('.edit-row').on('click', function(event) {
        event.preventDefault();
        var t = $(this);
        mod.find('h4').text('修改信息');
        listRow.addClass('hide');
        listRow.eq(0).removeClass('hide');
        mod.find('#status-form').attr('action', t.attr('href'));
        mod.modal({
            'show': true
        });
    })
    var objUpload = {
        runtimes: 'flash,html5,html4',
        'url': takurls.postFileUrl,
        flash_swf_url: takurls.iupload + '/Moxie.swf',
        silverlight_xap_url: takurls.iupload + '/Moxie.xap',
        browse_button: 'pickfiles',
        container: 'container',
        filters: {
            max_file_size: '10mb',
            mime_types: [{
                'title': 'Image files',
                'extensions': 'jpg,gif,png,jpeg'
            }, {
                'title': 'Zip files',
                'extensions': 'zip,rar'
            }, {
                'title': 'Doc files',
                'extensions': 'doc,docx,xls,xlsx,rtf,txt'
            }]
        }
    }, uploader = new plupload.Uploader(objUpload);
    $('#uploadfiles').click(function(e) {
        uploader.start();
        e.preventDefault();
    });
    uploader.init();
    uploader.bind('PostInit', function(up) {
        $('#filelist').html('');
    });
    uploader.bind('FilesAdded', function(up, files) {
        plupload.each(files, function(file) {
            document.getElementById('filelist').innerHTML += '<div id=\"' + file.id + '\"><a data-to=\"' + file.id + '\" href=\"javascript:;\" class=\"remove\"><i class=\"icon-trash\"></i></a>  ' + file.name + ' <b></b></div></div>';
        });
        up.refresh();
        uploader.start();
    });
    uploader.bind('UploadProgress', function(up, file) {
        $('#' + file.id + ' b').html(file.percent + '%');
    });
    uploader.bind('Error', function(up, err) {
        $('#filelist').append('<div class=\"red\">提示: ' + err.message + (err.file ? ', 文件: ' + err.file.name : '') + '</div>');
        up.refresh();
    });
    uploader.bind('FileUploaded', function(up, file, msg) {
        var elem = $('#' + file.id);
        elem.find('b').html('100%');
        if (msg && typeof msg['response'] != 'undefined') {
            var obj = $.parseJSON(msg['response']);
            if (obj && typeof obj['result'] != 'undefined') {
                elem.append('<input type=\"hidden\" name=\"files[]\" value=\"' + obj.result + '\"/>');
            }
        }
    });
    $('#filelist').on('click', ' a.remove', function(event) {
        event.preventDefault();
        var id = $(this).attr('data-to');
        uploader.removeFile(uploader.getFile(id));
        $('#' + id).remove();
    });
});