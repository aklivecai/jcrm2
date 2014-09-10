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
        self.workshops = ko.observable();
        self.workshops.subscribe(function(oldValue) {
            if (typeof oldValue == 'string') {
                // 车间设置
                if (oldValue >= 0) {
                    mWorkshops.lines()[oldValue].products.remove(self);
                }
            }
        }, null, "beforeChange");
        self.workshops.subscribe(function(value) {
            if (typeof value == 'string' || typeof value == 'number') {
                // 车间设置
                if (value < 0) {
                    document.getElementById('btn-workshop').click();
                } else {
                    mWorkshops.lines()[value].products.push(self);
                }
            }
        });
        self.getName = function() {
            return sprintf("M[%s]", self.itemid);
        }
        self.name = '';
        (function(_obj) {
            var data = [];
            _obj.type != '' && data.push(_obj.type);
            _obj.name != '' && data.push(_obj.name);
            _obj.spec != '' && data.push(_obj.spec);
            _obj.color != '' && data.push(_obj.color);
            data.push('数量:' + _obj.numbers);
            self.name = data.join(' , ');
            //修改时候，已经存在车间了
            if (typeof _obj['workshop_id'] != 'undefined' && typeof worksTags[_obj['workshop_id']] != 'undefined') {
                self.workshops(worksTags[_obj['workshop_id']]);
            };
        })(obj);
    };
    var ListViewModel = function() {
        var self = this;
        self.lines = ko.observableArray();
        self.add = function(obj) {
            self.lines.push(obj);
        }
    }
    var WorkshopsLine = function() {
        var self = this;
        self.id = null;
        self.name = null;
        self.products = ko.observableArray();
        self.process = ko.observableArray();
        self.isShow = ko.computed(function() {
            return self.products().length > 0;
        });
        /*
        self.getPWName = function(id) {
            return sprintf("M[%s][workshop]", id, self.id);
        }
        self.getPName = function(pid) {
            return sprintf("M[%s][process][%s]", self.id, pid);
        }
        self.getPId = function(id) {
            return sprintf("process-%s-%s", self.id, id);
        }
        */
        self.load = function(obj) {
            self.id = obj.typeid;
            self.name = obj.typename;
            if (typeof obj.process != 'undefined' && obj.process.length > 0) {
                self.process = ko.observableArray(obj.process);
            }
            if (typeof obj.products != 'undefined' && obj.products.length > 0) {
                self.products = ko.observableArray(obj.products);
            };
        }
        if (typeof arguments[0] != 'undefined') {
            self.load(arguments[0]);
        }
    }, WorkshopsModel = function() {
            var self = this;
            self.lines = ko.observableArray();
            self.add = function(line) {
                self.lines.push(line);
            }
            self.isprintf = ko.observable(false);
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
    var mView = new ListViewModel(),
        mWorkshops = new WorkshopsModel(),
        worksTags = {},
        workshopsSelect = [];
    for (var key in workshops) {
        worksTags[workshops[key].typeid] = key;
        workshopsSelect.push({
            'id': key,
            'name': workshops[key].typename
        });
        mWorkshops.add(new WorkshopsLine(workshops[key]));
    }
    //添加车间设置功能
    workshopsSelect.push({
        id: -1,
        name: '-车间设置-'
    });
    window.workshopsSelect = workshopsSelect;
    if (tags.length > 0) {
        for (var i = 0; i < tags.length; i++) {
            var obj = new MoLine(tags[i]);
            mView.add(obj);
        };
    };
    ko.applyBindings(mView, document.getElementById('init-production'));
    ko.applyBindings(mWorkshops, document.getElementById('init-workshops'));
    var formSubmit = $('#form-submit'),
        result = false;
    formSubmit.on('submit', function(event) {
        result = false;
        // event.preventDefault();
        if (tak.ihtml5.required) {
            formSubmit.find('select').removeClass('error').each(function() {
                var t = $(this);
                value = t.find("option:selected").text();
                if (value == '' || value <= 0) {
                    t.addClass('error');
                    if (result == false) {
                        result = t;
                    }
                };
            });
        }
        if (result) {
            result.focus();
            event.preventDefault();
        };
    })
    $('#print-produciotn').on('click', function() {
        mWorkshops.isprintf(true);
        var html = $('#print-table').html(),
            _html = "<style>.list-production-process li {float: left;width: 110px;margin: 2px;padding: 2px 5px;border: 1px solid #AAA;}.list-production-process strong{display:block;text-align:center;}.hr1,.hr2{display: inline-block;border-bottom: 1px solid #000;}.hr2{width: 30px;}.hr1{width: 70px;}</style>";
        html = html.replace(/zebra/ig, 'list');
        printHtml(_html + html);
    });
});