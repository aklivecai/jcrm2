/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-06-11 17:59:46
 * @version $Id$
 */
jQuery(function($) {
    var wap = $('#wamp-wage-workshop');
    var selectList = {
        'product': {
            name: '输入产品',
            ajax: createUrl('Order/ProductSelect'),
            formatResult: function(data) {
                var result = [],
                    name = data.name;
                if (data.itemid == -1) {
                    name += " [  新 ] ";
                };
                result.push("<p class='row-prodcut'>");
                result.push("产品:" + name);
                result.push(",型号:" + data.model);
                result.push(",颜色:" + data.color);
                result.push(",规格:" + data.standard);
                result.push('<br />');
                result.push("工单号:" + data.serialid);
                result.push(",下单日期:" + data.add_time);
                result.push('<br />');
                result.push("客户:" + data.company);
                result.push("</p>");
                return result.join('');
            },
            formatId: function(data) {
                return data.name;
            },
            formatSelection: function(data) {
                index = this.element.data('uid');
                list.getLine(index).load(data);
                return data.name;
            },
            formatSearching: function() {
                return '可以输入产品名字,工单号,客户名称进行模糊搜索';
            }
        }
    },
        initSelect = function(elem) {
            var t = $(elem),
                obj = selectList[t.attr('data-action')],
                page_limit = 20;
            result = {
                formatSearching: obj.formatSearching,
                formatInputTooShort: obj.formatSearching,
                initSelection: function(element, callback) {
                    if (element.val() == 'c') {
                        element.val('');
                    };
                },
                placeholder: obj.name,
                width: '100%',
                // allowClear: true, //显示取消按钮
                minimumInputLength: 1,
                loadMorePadding: 300,
                quietMillis: 100,
                openOnEnter: false,
                selectOnBlur: false,
                dropdownCssClass: "bigdrop",
                createSearchChoice: function(term) {},
                escapeMarkup: function(m) {
                    return m;
                },
                formatResult: obj.formatResult,
                formatSelection: obj.formatSelection,
                id: obj.formatId,
                ajax: {
                    url: obj.ajax,
                    dataType: 'jsonp',
                    data: function(term, page) {
                        var result = {
                            q: term,
                            page_limit: page_limit,
                            page: page
                        }
                        return result;
                    },
                    results: function(data, page) {
                        var more = (page * page_limit) < data.totalItemCount;
                        return {
                            results: data['data'],
                            more: more
                        };
                    }
                }
            };
            t.select2(result).trigger('select-load').on("select2-open", function() {
                $('#select2-drop').addClass('wage-create-select');
            });
        };
    ko.extenders.numeric = function(target, precision) {
        var result = ko.computed({
            read: target,
            write: function(newValue) {
                var current = target(),
                    roundingMultiplier = Math.pow(10, precision),
                    newValueAsNum = isNaN(newValue) ? 0 : parseFloat(+newValue),
                    valueToWrite = Math.round(newValueAsNum * roundingMultiplier) / roundingMultiplier;
                if (valueToWrite !== current) {
                    target(valueToWrite);
                } else {
                    if (newValue !== current) {
                        target.notifySubscribers(valueToWrite);
                    }
                }
            }
        }).extend({
            notify: 'always'
        });
        result(target());
        return result;
    };
    var EditViewModel = function() {
        var self = this;
        self.uid = index = uuid();
        self.lines = ko.observableArray();
        self.amount = ko.observable(1).extend({
            numeric: 4,
            rateLimit: 500
        });
        self.price = ko.observable(0).extend({
            numeric: 4,
            rateLimit: 500
        });
        self.note = ko.observable();
        /*总价*/
        self.sum = ko.computed(function() {
            var total = 0,
                number = formatFloat(self.amount(), 4),
                price = formatFloat(self.price(), 4);
            total = formatFloat(number * price, 4);
            return formatFloat(total, 4);
        });
        self.product = ko.observable();
        self.serialid = ko.observable();
        self.order_time = ko.observable();
        self.company = ko.observable();
        self.model = ko.observable();
        self.standard = ko.observable();
        self.color = ko.observable();
        self.unit = ko.observable();
        self.note = ko.observable();
        self.amount = ko.observable();
         self.numbers = ko.observable();
        self.load = function(data) {
            self.product(data.name);
            self.serialid(data.serialid);
            self.order_time(data.add_time);
            self.company(data.company);
            self.model(data.model);
            self.standard(data.standard);
            self.color(data.color);
            self.unit(data.unit);
            self.note(data.note);
            self.amount(data.amount);
            self.price(data.price);
            if (data.company) {
                list.company(data.company);
            };
        }
        self.getName = function(name) {
            return "M[" + self.uid + "][" + name + "]";
        }
    }
    var List = function() {
        var self = this,
            count = 0;
        self.company = ko.observable();
        self.lines = ko.observableArray();
        self.isSubmit = ko.computed(function() {
            return self.totals > 0;
        });
        self.add = function() {
            self.lines.push(new EditViewModel());
        }
        self.remove = function(item) {
            self.lines.remove(item);
        }
        self.getLine = function(uid) {
            var result = null;
            jQuery.each(self.lines(), function() {
                if (this.uid == uid) {
                    result = this;
                    return false;
                };
            });
            return result;
        }
        self.totals = ko.computed(function() {
            var total = 0;
            jQuery.each(self.lines(), function() {
                total += this.sum();
            });
            return formatFloat(total, 4);
        });
        self.init = function(element, index, data) {
            if (element instanceof HTMLTableRowElement) {
                $('.data-select').each(function(index, elem) {
                    var t = $(elem).data('uid', data.uid).removeClass('data-select');
                    t.val('')
                    initSelect(t);
                })
                $(' .type-date').each(function(i, elem) {
                    var t = $(elem).removeClass('type-date').addClass('type-date-w');
                    if (t == 0) {
                        t.val('');
                    };
                    t.on('focus', function() {
                        WdatePicker({
                            maxDate: '%y-%M-{%d+0}'
                        });
                    });
                })
            }
        }
    }
    var list = new List();
    ko.applyBindings(list, document.getElementById('delivery'));
    list.add();
    var wageForm = $('#delivery-form');
    wageForm.on('submit', function(event) {
        var error = false;
        if (list.lines().length == 0) {
            alert('没有需要打印的送货单!\n请选择需要送货的产品');
            list.add();
            error = true;
        } else {
            wageForm.find('input[required],input[data-action]').each(function(i, elem) {
                var el = $(elem),
                    _td = el.attr('data-action') ? el.parent() : el;
                if (el.val() == '' || (el.attr('type') == 'number' && el.val() <= 0)) {
                    _td.addClass('error');
                    error = true;
                } else {
                    _td.removeClass('error');
                }
            });
        }
        if (error) {
            event.preventDefault();
        };
    });
});