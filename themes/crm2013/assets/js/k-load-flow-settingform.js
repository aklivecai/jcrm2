/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-03 21:12:39
 * @version $Id$
 */
+ function($) {
    'use strict';
    $(document).ready(function() {
        var sheight = $('#content').height();
        sheight -= 30 + 35 + 30;
        $('#editor').css('height', sheight + 'px');
        var formSub = $('#submit-form'),
            // dataurl = 'http://test.9juren.com/_8__ETonz/document-query-path.php',
            dataurl = formSub.attr('action'),
            ue = UE.getEditor('editor', {
                focus: true,
                retainOnlyLabelPasted: true,
            }),
            crmDesign = {
                exec: function(method) {
                    ue.execCommand(method);
                },
                preview: function() {
                    $("#tempContent").remove();
                    $("<div id='tempContent' style='display:none;'></div>").appendTo(document.body);
                    $("#tempContent").html(ue.getContent());
                    var content = $("#tempContent"),
                        template = new crmTemplate(),
                        data = null,
                        html = null;
                    content.find('.wf_field').each(function() {
                        var t = $(this),
                            otype = t.attr('otype');
                        if (!otype) {
                            return true;
                        }
                        try {
                            data = JSON.parse(t.attr('odata'));
                            html = template.getHtml(otype, data);
                            t.replaceWith(html);
                        } catch (e) {
                            log(e);
                            return false;
                        };
                    });
                    printHtml(content.html());
                }
            },
            printHtml = function(html) {
                var options = {
                    width: 860,
                    height: 650
                },
                    l = (screen.availWidth - 10 - options.width) / 2,
                    t = (screen.availHeight - 30 - options.height) / 2,
                    pars = ['width=' + options.width, 'height=' + options.height, 'left=' + l, 'top=' + t, 'scrollbars=1', 'resizable=0'],
                    htmls = ['&lt;html&gt;&lt;head&gt;&lt;meta charset="utf-8"&gt; &lt;link rel="stylesheet" type="text/css" href="' + ue.options.iframeCssUrl + '"&gt;&lt;/head&gt;&lt;body&gt;', html, '&lt;/body&gt;&lt;/html&gt;', '&lt;script&gt;  &lt;/script&gt;'],
                    newWin = window.open('about:blank', 'printf'+uuid(), pars.join(','), null);
                html = htmls.join('');
                html = html.replace(/&gt;/ig, '>');
                html = html.replace(/&lt;/ig, '<');
                newWin.document.open();
                newWin.document.write(html);
                newWin.document.close();
            };
        ue.ready(function() {
            list.removeAttr('disabled');
            var content = ue.execCommand("getlocaldata");
            if (content) {
                // ue.setContent(content);
            };
        });
        ue.addListener('sourcemodechanged', function(paren, isok) {
            if (isok) {
                list.prop('disabled', 'disabled');
            } else {
                list.removeAttr('disabled');
            }
        });
        $('#btn-close').on('click', function() {
            closeWin();
        });
        $('#btn-preview').on('click', function() {
            crmDesign.preview();
        });
        $('.btn-save').on('click', function() {
            var isclose = $(this).attr('data-value');
            iAjax({
                url: dataurl,
                data: formSub.serialize(),
                success: function(data) {
                    if (data.status == 0) {
                        msgShow(data.info);
                    } else {
                        var fun = null;
                        if (isclose) {
                            fun = function() {
                                closeWin();
                            }
                        } else {
                            fun = true;
                        }
                        msgShow('保存成功', fun);
                    }
                }
            });
        });
        $('.btn[data-fun]').on('click', function() {
            crmDesign.exec($(this).attr('data-fun'));
        });
        //检测是否已经存在有名字的控件
        ue.getFormItems = function() {
            return function(a) {
                $("#tempContent").remove();
                $("<div id='tempContent' style='display:none;'></div>").appendTo(document.body);
                $(ue.getContent()).appendTo($("#tempContent"));
                var cname = '.wf_field';
                cname += !a ? "[name]" : "[name=" + a + "]";
                var b = $(cname);
                $("#tempContent").remove();
                return b
            }
        }();
        ue.hasSameName = function() {
            return function(b) {
                return ue.getFormItems(b).length > 0;
            }
        }();
    });
}(jQuery);