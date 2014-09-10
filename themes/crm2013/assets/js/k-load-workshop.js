/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-06-11 17:59:46
 * @version $Id$
 */
jQuery(function($) {
    var wap = $('#wamp-workshop'),
        idworkshop = wap.find('#name-workshop'),
        messages = {
            '1': {
                'id': 'id-workshop',
                'name': '车间'
            },
            '2': {
                'id': 'id-process',
                'name': '工序'
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
    /*工序*/
    var ProcessLine = function(_name, itemid) {
        var self = this;
        self.oldname = _name;
        self.name = ko.observable(self.oldname);
        self.itemid = ko.observable(itemid);
        self.cancel = function() {
            self.name(self.oldname);
        };
        self.save = function() {
            self.oldname = self.name();
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
        self.selectedItem = ko.observable(null);
        self.templateToUse = function(item) {
            return self.selectedItem() === item ? 'editTmpl' : 'itemsTmpl';
        };
        self.remove = function(item) {
            if (confirm('是否确认删除工序 [' + item.name() + ']')) {
                var data = {
                    'typeid': self.workshop_id(),
                    'id': item.itemid()
                };
                $.get(saveUrl + 'DelProcess', data, function(result) {
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
                var elem = getP().find('.name-process'),
                    value = elem.val();
                if (!checkProcess(value, getP())) {
                    var data = {
                        'id': self.workshop_id(),
                        'name': value
                    };
                    $.get(saveUrl + 'upProcess', data, function(result) {
                        if (typeof result == 'object') {
                            self.lines.push(new ProcessLine(result.name, result.itemid));
                            elem.val('');
                        } else if (typeof result == 'string') {
                            alert(result);
                        } else {
                            alert('操作异常！');
                        }
                    }, 'json');
                };
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
                $.get(saveUrl + 'upProcess', data, function(result) {
                    if (typeof result == 'string' && result != '') {
                        alert(result);
                    } else {
                        item.name(value);
                        item.save();
                        self.selectedItem(null);
                    }
                }, 'json');
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
                if (true || !checkWorkshop(value)) {
                    var data = {
                        'name': value
                    };
                    $.get(saveUrl + 'upWorkshop', data, function(result) {
                        if (typeof result == 'string' && result != '') {
                            alert(result);
                        } else {
                            self.lines.push(new Process(result.itemid, result.name));
                            idworkshop.val('');
                        }
                    }, 'json');
                }
            }
        };
    }
    var mView = new ListViewModel();
    if (tags.length > 0) {
        for (var i = 0; i < tags.length; i++) {
            var d = tags[i],
                process = new Process(d.typeid, d.typename);
            if (typeof d.process == 'object') {
                for (var key in d.process) {
                    var obj = d.process[key];
                    process.add(new ProcessLine(obj['typename'], obj['typeid']));
                };
            }
            mView.add(process);
        };
    };
    ko.applyBindings(mView, document.getElementById('wamp-workshop'));
});