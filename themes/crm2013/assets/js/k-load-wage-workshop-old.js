/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-06-11 17:59:46
 * @version $Id$
 */
jQuery(function($) {
    var wap = $('#wamp-wage-workshop'),
        idworkshop = wap.find('#name-workshop'),
        messages = {
            '1': {
                'id': 'id-workshop',
                'name': '车间'
            },
            '2': {
                'id': 'id-process',
                'name': '工序'
            },
            '3': {
                'id': 'id-worker',
                'name': '工人'
            }
        };
    _checkValue = function(type, name, _part) {
        var result = false,
            part = _part ? _part : wap,
            msg = messages[type];
        if (result = (name == '')) {
            alert(msg['name'] + '名字不能为空！');
        } else {
            part.find('.' + msg.id).each(function() {
                if (result = $(this).val() == name) {
                    return false
                };
            });
            if (result) {
                alert(msg['name'] + ' [' + name + '] 已经存在！');
            };
        }
        return result;
    }, checkProcess = function(name) {
        var part = arguments.length == 2 ? arguments[1] : false,
            result = _checkValue(2, name, part);
        return result;
    }, checkWorkshop = function(name) {
        var result = _checkValue(1, name);
        return result;
    }, checkWorker = function(name) {
        var part = arguments.length == 2 ? arguments[1] : false,
            result = _checkValue(3, name, part);
        return result;
    }, initDragsort = function(el, func) {
        var elem = $(el),
            parent = elem.parent('tr');
        elem.dragsort({
            dragSelector: "div",
            dragBetween: true,
            dragEnd: saveOrder,
            placeHolderTemplate: "<li class='place-holder'><div></div></li>"
        });

        function saveOrder() {
            var data = elem.find(".id-process").map(function() {
                return 'm[' + $(this).attr('itemid') + ']=' + $(this).val();
            }).get();
            elem.find(".edit-process").map(function() {
                data.push('m[' + $(this).attr('itemid') + ']=' + $(this).attr('name'));
            }).get();
            func.call(this, data);
        };
    };
    /* 工人 */
    var WageLine = function(name, itemid) {
        var self = this;
        self.oldname = name;
        self.name = ko.observable(name);
        self.itemid = ko.observable(itemid);
        self.cancel = function() {
            self.name(self.oldname);
        };
        self.save = function() {
            self.oldname = self.name();
        };
    }
    /*工序*/
    var ProcessLine = function(_name, itemid, price) {
        var self = this;
        self.oldname = _name;
        self.oldprice = price;
        self.name = ko.observable(self.oldname);
        self.itemid = ko.observable(itemid);
        self.price = ko.observable(price);
        self.cancel = function() {
            self.name(self.oldname);
            self.price(self.oldprice);
        };
        self.save = function() {
            self.oldname = self.name();
            self.oldprice = self.price();
        };
    };
    var Process = function(id, name) {
        var self = this,
            objTr = null,
            getP = function() {
                if (objTr == null || arguments.length == 0) {
                    objTr = $('#' + self.workshop_id());
                };
                return objTr;
            };
        self.workshop_id = ko.observable(id);
        self.name = ko.observable(name);
        self.lines = ko.observableArray();
        self.wages = ko.observableArray();
        self.removeWage = function(item) {
            self.wages.remove(item);
        }
        self.addWage = function() {
            var item;
            if (arguments.length == 1) {
                item = arguments[0];
                self.wages.push(item);
            } else {
                var elem = getP().find('.name-worker'),
                    value = elem.val();
                if (!checkWorker(value, getP())) {
                    item = new WageLine(value, uuid());
                    self.wages.push(item);
                    elem.val('');
                }
            }
        }
        self.selectedItem = ko.observable(null);
        self.selectedWage = ko.observable(null);
        self.templateToUse = function(item) {
            return self.selectedItem() === item ? 'editTmpl' : 'itemsTmpl';
        };
        self.templateToWage = function(item) {
            return self.selectedWage() === item ? 'editWage' : 'itemsWage';
        };
        self.editWage = function(item) {
            var _item = self.selectedWage();
            if (_item != null) {
                _item.cancel();
            };
            self.selectedWage(item);
        };
        self.cancelWage = function() {
            var item = self.selectedWage();
            item.cancel();
            self.selectedWage(null);
        };
        self.saveWage = function() {
            var item = self.selectedWage(),
                // value = getP().find('.row-edit input').val()
                value = item.name();
            if (!checkWorker(value, getP())) {
                if (value == item.oldname) {
                    self.selectedWage(null);
                    return true;
                } else {
                    var data = {
                        'name': value
                    };
                    item.save();
                }
                self.selectedWage(null);
            }
        }
        self.remove = function(item) {
            if (confirm('是否确认删除工序 [' + item.name() + ']')) {
                var data = {
                    'typeid': self.workshop_id(),
                    'id': item.itemid()
                };
                self.lines.remove(item);
            };
        };
        self.add = function() {
            if (arguments.length == 1) {
                self.lines.push(arguments[0]);
            } else {
                var elem = getP().find('.name-process'),
                    value = elem.val(),
                    price = getP().find('.name-price'),
                    vprice = price.val();
                if (!checkProcess(value, getP())) {
                    if (vprice == '') {
                        price.val('0');
                        vprice = 0;
                    };
                    self.lines.push(new ProcessLine(value, uuid(), vprice));
                    elem.val('');
                }
            }
        };
        self.save = function() {
            var item = self.selectedItem(),
                // value = getP().find('.row-edit input').val()
                value = item.name();
            if (!checkProcess(value, getP())) {
                if (value == item.oldname) {
                    self.selectedItem(null);
                    return true;
                };
                var data = {
                    'id': self.workshop_id(),
                    'name': value,
                    'itemid': item.itemid()
                };
                item.save();
                self.selectedItem(null);
            }
        };
        self.edit = function(item) {
            var _item = self.selectedItem();
            if (_item != null) {
                _item.cancel();
            };
            self.selectedItem(item);
        };
        self.cancel = function() {
            var item = self.selectedItem();
            item.cancel();
            self.selectedItem(null);
        };
        var orders = function(data) {
            $.post(saveUrl + 'orderWorkshop/' + self.workshop_id(), data.join('&'), function(result) {
                if (typeof result == 'string' && result != '') {
                    alert(result);
                } else if (typeof result == 'object') {
                    self.selectedItem(null);
                    self.lines.removeAll();
                    for (var i in result) {
                        var obj = result[i];
                        self.add(new ProcessLine(obj['typename'], obj['typeid']));
                    };
                } else {}
            });
        };
        self.init = function() {
            var list = getP().find('ul');
            if (!list.attr('data-init')) {
                initDragsort(list, orders);
                list.attr('data-init', true);
            };
        };
    }
    var ListViewModel = function() {
        var self = this;
        self.lines = ko.observableArray();
        self.remove = function(item) {
            if (typeof item == 'undefined') {
                return false
            };
            if (confirm('是否确认删除车间 [' + item.name() + ']')) {
                var data = {
                    'id': item.workshop_id()
                };
                self.lines.remove(item);
                $.get(saveUrl + 'DelWorkshop', data, function(result) {
                    if (typeof result == 'string') {
                        alert(result);
                    } else {
                        self.lines.remove(item);
                    }
                })
            };
        };
        self.add = function() {
            if (arguments.length == 1) {
                self.lines.push(arguments[0]);
            } else {
                var value = idworkshop.val();
                if (!checkWorkshop(value)) {
                    self.lines.push(new Process(uuid(), value));
                    idworkshop.val('');
                }
            }
        };
    }
    var mView = new ListViewModel();
    if (tags.length > 0) {
        var obj, d;
        for (var i = 0; i < tags.length; i++) {
            d = tags[i],
            process = new Process(d.typeid, d.typename);
            if (typeof d.process == 'object') {
                for (var key in d.process) {
                    obj = d.process[key];
                    process.add(new ProcessLine(obj['typename'], obj['typeid'], 10));
                };
            }
            if (typeof d.workers == 'object') {
                for (var key in d.workers) {
                    obj = d.workers[key];
                    process.addWage(new WageLine(obj['name'], obj['itemid']));
                }
            }
            mView.add(process);
        };
    };
    ko.applyBindings(mView, document.getElementById('wamp-workshop'));
});