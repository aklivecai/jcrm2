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
    var uploadFile = function(t, pars) {
        var webuploader = $('#webuploader'),
            postUrl = webuploader.attr('data-post-url'),
            BASE_URL = webuploader.attr('src'),
            options = {
                filesize: 1024 * 1024,
                uploadError: function(file, error) {
                    var msg = file.name + '上传出错';
                    msgShow(msg, true);
                },
                uploadSuccess: function(file, result) {
                    this.removeFile(file);
                },
                fileQueued: function(file) {
                    var self = this;
                    this.md5File(file).then(function(val) {
                        self.options.formData = {
                            md5: val,
                            size: file.szie,
                        }
                        self.upload(file);
                    });
                },
                error: function(error) {
                    var strHtml = null;
                    switch (error) {
                        case 'F_EXCEED_SIZE':
                            var file = arguments[1];
                            strHtml = file.name + '  (' + WebUploader.formatSize(file.size) + ')  超过最大限制' + WebUploader.formatSize(options.filesize);
                            break;
                        case 'Q_EXCEED_NUM_LIMIT':
                            var file = arguments[2],
                                tip = arguments[1];
                            strHtml = file.name + '  (' + WebUploader.formatSize(file.size) + ')  超过文件总数限制' + tip + '个';
                            break;
                        case 'object':
                            return false;
                    }
                    if (strHtml) {
                        msgShow(strHtml, true);
                    };
                }
            },
            BASE_URL = BASE_URL.substr(0, BASE_URL.lastIndexOf('/') + 1),
            _url = postUrl + (t.attr('data-url') ? t.attr('data-url') : '');
        //更新配置
        for (var i in pars) {
            options[i] = pars[i];
        }
        var uploader = WebUploader.create({
            // swf文件路径
            swf: BASE_URL + '/Uploader.swf',
            // 文件接收服务端。
            server: _url,
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            // pick:  '#'+t.attr('id') ,
            pick: t.get(0),
            // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
            resize: true,
            fileSingleSizeLimit: options.filesize, // {int} [可选] [默认值：undefined] 验证单个文件大小是否超出限制, 超出则不允许加入队列
            fileNumLimit: 0 //{int} [可选] [默认值：undefined] 验证文件总数量, 超出则不允许加入队列。
            // fileSizeLimit: 1024 * 1024 * 10 //{int} [可选] [默认值：undefined] 验证文件总大小是否超出限制, 超出则不允许加入队列。
        });
        // t.attr('id') ? t.attr('id') :t.get(0)
        // 当有文件被添加进队列的时候
        uploader.on('fileQueued', options.fileQueued);
        uploader.on('error', options.error);
        //上传成功以后，插入返回的编号
        uploader.on('uploadSuccess', options.uploadSuccess);
        uploader.on('uploadError', options.uploadError);
    };
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
    }),
        FilesList = function(list) {
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
            }
            self.removeI = function() {
                var s = this;
                sCF.call($('#' + s.itemid), '确定删除此附件吗？', function() {
                    self.remove.call(s);
                });
            }
        };
    var mview = null,
        temps = [];
    for (var i = 0; i < 1; i++) {
        temps.push({
            "itemid": "32073b3cNh9dt1BwsFAwMKAgIHBQMGVAAPV1MbFxgDBwRSC1ZXBFYHShBO",
            "manageid": "6370fa16Ieh1YrAgUJVF1UCgBXUwMPUAFSUQJJQBhVAAYIUFJQVAUAHBNJ",
            "time": "2014-09-08 16:40:46",
            "name": "I5Bdjt.png",
            "user": "Tak chen",
            'del': true
        });
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
        content.removeClass('load-data')
    })();
    var eform = $('#e-form');
    eform.on('submit', function(event) {
        event.preventDefault();
        if (checkForm(eform)) {
            var list = eform.find('button[type=submit]').prop('disabled', 'disabled').addClass('btn-loading');
            iAjax({
                url: eform.attr('action'),
                data: eform.serialize(),
                success: function(result) {
                    if (result.status == 1) {} else {
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