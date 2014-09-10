/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-04 21:22:58
 * @version $Id$
 */
+ function($) {
    'use strict';
    var FView = function(obj) {
        var self = this;
        self.id = obj.id;
        self.name = obj.name;
        self.type = obj.type;
        self.isShow = ko.observable(obj.show + '');
        self.isWrite = ko.observable(obj.write == 1);
        self.isMust = ko.observable(obj.must == 1);
        self.check = ko.observable(false);
        self.select = function() {
            var v = self.check();
            self.check(!v);
        }
        self.iswite = ko.computed(function() {
            var v = self.isMust();
            if (v) {
                self.isWrite(true);
            }
            return '';
        });
        self.ishow = ko.computed(function() {
            var v = self.isShow();
            if (v == 0) {
                self.isWrite(false);
                self.isMust(false);
            }
            return v == 1;
        });
        self.getName = function(name) {
            return sprintf("StepFields[%s][%s]", self.id, name);
        };
        self.getId = function(id) {
            var id = self.getName(id);
            return id.replace(/\[/ig, '-').replace(/\]/ig, '');
        };
    }, FilesView = function(list) {
            var self = this;
            self.lines = ko.observableArray(list);
            self.checkAll = ko.observable(false);
            self.selects = ko.computed(function() {
                var list = self.lines(),
                    temps = [];
                jQuery.each(list, function() {
                    if (this.check()) {
                        temps.push(this);
                    };
                });
                // log('list');
                return temps;
            });
            var setStatus = function(age1) {
                var strShow = null,
                    strWrite = null,
                    strMust = null;
                if (typeof age1 == 'string') {
                    strShow = age1;
                } else if (typeof age1 == 'boolean') {
                    strWrite = age1;
                } else if (typeof age1 == 'number') {
                    strMust = age1 == 1;
                }
                jQuery.each(self.selects(), function() {
                    if (strShow !== null) {
                        this.isShow(strShow);
                    };
                    if (strWrite !== null) {
                        this.isWrite(strWrite);
                        if (strWrite) {
                            this.isShow('1');
                        } else {
                            //不能输入,得取消必填
                            this.isMust(false);
                        }
                    };
                    if (strMust !== null) {
                        this.isMust(strMust);
                        if (strMust) {
                            this.isShow('1');
                        }
                    };
                });
                // log(strShow);
            };
            self.setShow = function() {
                setStatus('1');
            };
            self.setHide = function() {
                setStatus('0');
            };
            self.setWrite = function() {
                setStatus(true);
            };
            self.setNWrite = function() {
                setStatus(false);
            };
            self.setMust = function() {
                setStatus(1);
            };
            self.setNMust = function() {
                setStatus(0);
            };
        };
    var temps = [];
    for (var i = 0; i < files.length; i++) {
        temps.push(new FView(files[i]));
    };
    var mview = new FilesView(temps);
    mview.checkAll.subscribe(function(newValue) {
        jQuery.each(mview.lines(), function() {
            this.check(newValue);
        });
    });
    ko.applyBindings(mview, document.getElementById('wap-fields'));
    var modForm = $('#mod-form');
    $('.btn-save').on('click', function(event) {
        event.preventDefault();
        var isclose = $(this).attr('data-value');
        iAjax({
            url: modForm.attr('action'),
            data: modForm.serialize(),
            success: function(result) {
                msgShow(result.info, function() {
                    if (isclose) {
                        closeWin();
                    };
                });
            }
        });
    });
}(jQuery);