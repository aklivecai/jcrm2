/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-06-11 17:59:46
 * @version $Id$
 */
jQuery(function($) {
    var wap = $('#wap-production');
    /*工序*/
    var MoLine = function(obj) {
        var self = this;
        self.obj = obj;
        self.itemid = obj.itemid;
        self.line = ko.observable();
        self.line.subscribe(function(value) {
            if (typeof value == 'object') {
                // 车间设置
                if (value.typeid < 0) {
                    document.getElementById('btn-workshop').click();
                };
            }
        });
        self.getWName = function() {
            return sprintf("M[%s][workshop]", self.itemid);
        }
        self.getPName = function(pid) {
            return sprintf("M[%s][process][%s]", self.itemid, pid);
        }
        self.getPId = function(id) {
            return sprintf("process-%s-%s", self.itemid, id);
        }
        self.name = '';
        self.numbers = obj.numbers;
        (function(_obj) {
            var data = [];
            _obj.type != '' && data.push(_obj.type);
            _obj.name != '' && data.push(_obj.name);            
            _obj.spec != '' && data.push(_obj.spec);
            _obj.color != '' && data.push(_obj.color);
            self.name = data.join(' , ');
        })(obj);
    };
    var ListViewModel = function() {
        var self = this;
        self.lines = ko.observableArray();
        self.add = function(obj) {
            self.lines.push(obj);
        }
        self.initInput = function() {
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
        }
    }
    //添加车间设置功能
    workshops.push({
        typeid: -1,
        typename: '-车间设置-',
        process: []
    });
    var mView = new ListViewModel();
    if (tags.length > 0) {
        for (var i = 0; i < tags.length; i++) {
            var obj = new MoLine(tags[i]);
            mView.add(obj);
        };
    };
    ko.applyBindings(mView, document.getElementById('wap-production'));
    wap.on('click', '.check-pro', function() {
        var t = $(this),
            par = t.parent().find('.days');
        if ($(this).prop('checked')) {
            par.removeAttr('disabled').attr('required', 'required').show();
        } else {
            par.attr('disabled', 'disabled').removeAttr('required').hide();
        }
    })
    var formSubmit = $('#form-submit'),
        ifm = getIfm(),
        result = false;
    // formSubmit.attr('target', ifm.attr('name'));
    formSubmit.on('submit', function(event) {
        result = false;
        // event.preventDefault();
        if (true || tak.ihtml5.required) {
            formSubmit.find('select,input[required]').removeClass('error').each(function() {
                var t = $(this)
                value = t.val();
                if (t.get(0).tagName.toLowerCase() != 'input') {
                    if (t.find("option:selected").text() != '选择车间') {
                        value = t.find("option:selected").text();
                    }
                } else {
                    // ＞0正的数字判断
                    if (value.search(/^\d+[\d\.]?\d*$/) != 0) {
                        value = 0;
                    } else {
                        value = parseFloat(value).toFixed(2).replace('.00', '');
                    }
                    t.val(value);
                }
                if (value == '' || value <= 0) {
                    t.addClass('error');
                    if (result == false) {
                        result = t;
                    }
                };
            });
        } else {
            formSubmit.find('input[required][value=0]').addClass('error');
        }
        if (result) {
            result.focus();
        } else {
            formSubmit.find('.list-production-process').removeClass('error').each(function() {
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
});