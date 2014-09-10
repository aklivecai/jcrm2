/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-04 21:22:58
 * @version $Id$
 */
+ function($) {
    'use strict';
    var fdata = function(data) {
        var result = null;
        if (data['group']) {
            result = "<strong>" + data['group'] + "<strong>";
        } else {
            result = "<span>" + data.title + "<span>";
        }
        return result;
    },
        result = {
            placeholder: "搜索处理人",
            quietMillis: 100,
            openOnEnter: true,
            selectOnBlur: true,
            dropdownCssClass: "bigdrop",
            formatResult: fdata,
            formatSelection: fdata,
            escapeMarkup: function(m) {
                return m;
            },
            id: function(data) {
                return data.itemid;
            },
            query: function(query) {
                var results = {};
                $.each(preload_data, function() {
                    if (query.term.length == 0 || this.user_nicename.toUpperCase().indexOf(query.term.toUpperCase()) >= 0 || this.branch_name.toUpperCase().indexOf(query.term.toUpperCase()) >= 0) {
                        if (!results[this.branch]) {
                            results[this.branch] = {
                                id: this.branch,
                                group: this.branch_name,
                                children: []
                            };
                        };
                        results[this.branch].children.push({
                            itemid: this.manageid,
                            title: this.user_nicename
                        });
                    }
                });
                list = [];
                for (var i in results) {
                    list.push(results[i]);
                }
                query.callback({
                    'results': list,
                    'disabled': true,
                    'more': false,
                });
            }
        },
        checkF = function(name, id) {
            var resut = true,
                msg = null;
            if (name == '') {
                msg = '名称不能为空!';
            } else if ($('.row-title[title=' + name + ']').length > 0) {
                msg = '名称不能有重复';
            }
            if (msg) {
                resut = false;
                tipShow(msg, id);
            };
            return resut;
        },
        Step = function() {
            var self = this;
            self.old = null;
            if (arguments.length == 1 && arguments[0] != null && typeof arguments[0] == 'object') {
                self.old = arguments[0];
            } else {
                self.old = line;
            }
            Line.call(this, self.old);
            self.isNews = function() {
                return self.old.step_no == 0;
            }
            self.cancel = function() {
                self.load(self.old);
                return true;
            };
            self.isOne = function() {
                return self.old.step_no == 1;
            }
            self.save = function() {
                var key = null;
                for (var i in self.obj) {
                    key = self.obj[i];
                    self.old[key] = self[key]();
                };
            };
            self.itemid = ko.computed(function() {
                return self.step_id();
            });
            self.timeout_name = ko.computed(function() {
                var timeout = self.timeout();
                return timeout > 0 ? timeout + '天' : '';
            });
            self.getName = function(name) {
                return sprintf("tr-%s[%s]", self.itemid(), name);
            };
            self.getId = function(id) {
                var id = self.getName(id);
                return id.replace(/\[/ig, '-').replace(/\]/ig, '');
            };
            self.isChange = function() {
                var result = false,
                    key = null;
                try {
                    for (var i in self.obj) {
                        key = self.obj[i];
                        if (self.old[key] != self[key]()) {
                            result = true;
                            break;
                        }
                    };
                } catch (e) {
                    log(e);
                }
                return result;
            }
        },
        StepsLine = function(tags) {
            var self = this;
            self.selectedItem = ko.observable(null);
            self.isNews = ko.computed(function() {
                return self.selectedItem() && self.selectedItem().isNews()
            });
            Lines.call(this, Step, tags);
            self.templateToUse = function(item) {
                var temp = 'itemsTmpl';
                if (self.selectedItem() === item) {
                    temp = item.isOne() ? 'itemsTmplOne' : 'editTmpl';
                }
                return temp;
            };
            self.addI = function() {
                var s = self.add({
                    "step_id": uuid(),
                    "step_name": '',
                    "step_user": '',
                    "timeout": 0,
                    "step_no": 0,
                    "step_user_name": ''
                });
                self.selectedItem(s);
            };
            self.removeI = function(item) {
                self.lines.remove(item);
                if (item == self.selectedItem()) {
                    self.selectedItem(null);
                };
            };
            //
            self.removeObj = function(item) {
                var ele = $('#' + item.getId('del'));
                sCF.call(ele, '是否确认删 [' + item.step_name() + ']', function() {
                    var data = {
                        'itemid': item.itemid()
                    };
                    iAjax({
                        url: postUrl + '?ajax=del',
                        data: data,
                        success: function(result) {
                            if (result.status == 1) {
                                self.lines.remove(item);
                            } else {
                                msgShow(result.info);
                            }
                        }
                    });
                });
            };
            self.save = function() {
                var item = self.selectedItem(),
                    value = item.step_name();
                if (item.isChange() || self.isNews()) {
                    if (checkF(value, item.getId('step_name'))) {
                        if (!item.isOne() && (!item.step_user() || item.step_user() < 2)) {
                            tipShow('请选择负责流程的处理人', item.getId('step_user'), {
                                align: 'top'
                            });
                            return false
                        }
                        if (item.step_no() != 1) {
                            item.step_no(2);
                        };
                        var data = item.getObj(),
                            temp = ["itemid=" + item.itemid()];
                        for (var i in data) {
                            temp.push('FlowStep[' + i + ']=' + data[i]);
                        };
                        iAjax({
                            url: postUrl + '?ajax=action',
                            data: temp.join('&'),
                            success: function(result) {
                                if (result.status == 1) {
                                    if (item.itemid() != result.info) {
                                        item.step_id(result.info);
                                    }
                                    item.save();
                                    self.selectedItem(null);
                                } else {
                                    msgShow(result.info);
                                }
                            }
                        });
                    };
                } else {
                    self.selectedItem(null);
                }
            };
            self.edit = function(item) {
                var _item = self.selectedItem(),
                    msg = null;
                if (_item != null) {
                    if (_item.isNews()) {
                        tipShow('请先保存添加的流程', _item.getId('save'));
                        return false
                    };
                    _item.cancel();
                };
                self.selectedItem(item);
            };
            self.cancel = function() {
                var item = self.selectedItem();
                item.cancel();
                self.selectedItem(null);
            };
            self.indexNumber = function(index) {
                return index + 1;
            };
            //加载人员下来选择框
            self.initElement = function(element, index, datas) {
                if (index == self.selectedItem() && !index.isOne()) {
                    var wap = $('#' + index.getId('id')),
                        itemid_id = index.old.step_user;
                    wap.find('.ajax-select').each(function() {
                        var telem = $(this);
                        result.formatSelection = function(data) {
                            var _result = fdata(data);
                            if (data.itemid != itemid_id) {
                                itemid_id = data.itemid;
                                index.step_user(itemid_id);
                                index.step_user_name(data.title);
                            };
                            return _result;
                        };
                        telem.select2(result);
                        if (itemid_id) {
                            telem.select2('data', {
                                'itemid': itemid_id,
                                'title': index.step_user_name()
                            });
                        }
                    });
                }
            };
            self.getLink = function(id, typeid) {
                var type = typeid == 'f' ? 'StepFields' : 'StepCondition';
                return createUrl('workflow/production/' + type + '/' + itemid, ['itemid=' + id]);
            }
        }
    var mview = null,
        temps = [];
    for (var i = 0; i < list.length; i++) {
        temps.push(new Step(list[i]));
    };
    mview = new StepsLine(temps);
    ko.applyBindings(mview, document.getElementById('wap-steps'));
    // mview.addI();
}(jQuery);