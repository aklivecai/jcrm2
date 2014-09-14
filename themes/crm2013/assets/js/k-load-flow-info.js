/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-08 10:52:47
 * @version $Id$
 * 开始的流程相关信息,字段解析,附件管理
 *
 * 附件管理:
 *     添加完附件
 *     页面移动到附件信息列表
 *     当前自己传的文件只要没提交,都可以删除,后期只能管理员删除
 *     删除完附件以后就没有表格了
 *     申请的时候不需要流程编号,因为没有生成,提交后系统找到文件编号,id为0的并修改为当前流程编号
 *     进度上传的时候已经有流程编号了
 *     提交后,后台可以判断文件编号对应的流程编号是否一致
 *
 */
+jQuery(function($) {
    'use strict';
    if ($('#add-file').length > 0) {
        var upload = uploadFile($('#add-file'), {
            uploadSuccess: function(file, dataJson) {
                if (dataJson.status == 1) {
                    var data = dataJson.info;
                    data.del = true;
                    mview.addI(data);
                } else {
                    msgShow(dataJson.info, true);
                }
            }
        });
    };
    var FilesList = function(list) {
        var self = this;
        Lines.call(this, function(age) {
            return age;
        }, list);
        self.getDown = function(id) {
            return createUrl('TakFile/Download/' + id);
        };
        self.addI = function(obj) {
            self.add.call(self, obj);
            gotoElem($('#wap-files'));
            storage.json_set('files', self.lines());
        }
        self.removeI = function() {
            var s = this;
            sCF.call($('#' + s.itemid), '确定删除此附件吗？', function() {
                self.remove.call(s);
                storage.json_set('files', self.lines());
            });
        }
    };
    var mview = null,
        temps = [],
        fiels = attachs.length > 0 ? attachs : []; // storage.json_get('files');
    if (!(fiels instanceof Array)) {
        fiels = [];
    }
    for (var i = 0; i < fiels.length; i++) {
        fiels[i].del = false;
        // fiels[i].del = true;
        temps.push(fiels[i]);
    };
    mview = new FilesList(temps);
    ko.applyBindings(mview, document.getElementById('wap-files'));
    (function() {
        /**
         *自定义表单操作
         */
        var content = $("#form-content"),
            template = new crmTemplate(),
            data = null,
            html = null,
            obj = null,
            elems = [];
        for (var i = 0; i < files_data.length; i++) {
            obj = files_data[i];
            var t = $('#' + obj.odata.id);
            //找到字段的时候才开始
            if (t.length > 0) {
                try {
                    html = template.getHtml(obj.otype, obj.odata);
                    elems.push({
                        'e': t,
                        'h': html
                    });
                } catch (e) {
                    log(obj.odata);
                    log(e);
                };
            };
        };
        for (var i = 0; i < elems.length; i++) {
            elems[i]['e'].replaceWith(elems[i]['h']);
        };
        content.removeClass('load-data');
        if (typeof showData != 'undefined') {
            template.show(content);
        };
    })();
    var isaction = -1,
        strmssage = null,
        listBtn = $('.ibtn-submit');
    if (listBtn.length > 0) {
        listBtn.on('click', function() {
            isaction = $(this).attr('data-val');
            $('#itype').val(isaction);
            if (isaction == '0') {
                strmssage = '退回成功!.';
            } else if (isaction == '-1') {
                strmssage = '重新申请成功.';
            } else {
                strmssage = '审批成功.';
            }
        });
    } else {
        strmssage = '申请成功.';
    }
    var eform = $('#e-form');
    eform.on('submit', function(event) {
        event.preventDefault();
        if (msgShow) {};
        if (checkForm(eform)) {
            var list = eform.find('button[type=submit]').prop('disabled', 'disabled').addClass('btn-loading');
            iAjax({
                url: eform.attr('action'),
                data: eform.serialize(),
                success: function(result) {
                    if (result.status == 1) {
                        closeWin({
                            url: result.info,
                            info: strmssage
                        });
                    } else {
                        msgShow(result.info, true);
                    }
                },
                complete: function() {
                    list.removeAttr('disabled').removeClass('btn-loading');
                }
            });
        }
    });
});