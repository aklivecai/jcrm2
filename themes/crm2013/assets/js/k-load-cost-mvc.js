jQuery(function($) {
    var page_limit = 10,
        formatCurrency = function(val) {
            return formatFloat(val, 4);
        },
        fdata = function(data) {
            var result = "<table class='movie-result'><tr>",
                strs = [];
            if (data.cost_id == 0) {
                result += "<td><strong style='color:#468847'>[保存]</strong> </td> ";
            } else {
                result += "<td ><strong style='color:#f89406'>[历史]</strong> </td> ";
            }
            result +="<td>&nbsp;</td>";
            if (data.type) strs.push('品名:' + data.type);
            if (data.name) strs.push('型号:' + data.name);
            if (data.spec) strs.push('规格:' + data.spec);
            if (data.color) strs.push('颜色:' + data.color);
            if (data.expenses) strs.push('制造管理费:' + formatCurrency(data.expenses));
            if (data.price) strs.push('成本:' + formatCurrency(data.price));
            result += "<td> " + strs.join(' - ') + "</td>"
            result += "</tr></table>";
            return result;
        };
    result = {
        placeholder: "搜索",
        allowClear: true, //显示取消按钮
        minimumInputLength: 0,
        loadMorePadding: 300,
        quietMillis: 100,
        openOnEnter: true,
        selectOnBlur: true,
        dropdownCssClass: "bigdrop",
        createSearchChoice: function(term) {},
        formatResult: function(data) {
            var result = fdata(data);
            return result;
        },
        formatSelection: function(data) {
            var result = fdata(data);
            return result;
        },
        id: function(data) {
            return data.itemid;
        },
        ajax: {
            url: ajaxUrl,
            dataType: 'jsonp',
            data: function(term, page) {
                var result = {
                    q: term,
                    page_limit: page_limit
                }
                result['CostProduct_page'] = page;
                return result;
            },
            results: function(data, page) {
                var more = (page * page_limit) < data.totalItemCount;
                return {
                    results: data['data'],
                    more: more
                };
            }
        },
    };
    var Product = function() {
        var self = this;
        self.add = function() {
            var tempObj = arguments.length == 1 ? arguments[0] : new ProductLine();
            if (tempObj.totals() == 0) {
                tempObj.inits();
            };
            self.lines.push(tempObj);
        };
        self.inits = function() {
            self.add();
        };
        /*
        self.numbers = ko.computed(function() {
            var total = 0;
            jQuery.each(self.lines(), function() {
                total += this.numbers();
            });
            return formatCurrency(total);
        });
        */
        self.initElement = function(element, index, data) {
            var tbody = $('#' + index.getId('id')),
                itemid = index.itemid;
            tbody.find('.ajax-select').each(function() {
                result.formatSelection = function(data) {
                    var result = fdata(data);
                    if (data.itemid != itemid) {
                        itemid = data.itemid;
                        index.clear();
                        $.ajax({
                            type: "get",
                            url: ajaxUrl + '?id=' + data.itemid,
                            dataType: "json",
                            success: function(json) {
                                index.loads(json);
                            }
                        });
                    };
                    return result;
                };
                var s = result;
                $(this).select2(result).on('select2-removed', function(e) {
                    index.clear();
                });
            });
        };
    }
    Product.prototype = new Lines;
    var productView = new Product();
    if (products.length == 0) {
        productView.inits();
    } else {
        for (var i in products) {
            var obj = products[i],
                pro = new ProductLine(obj.itemid);
            pro.type(obj.type);
            pro.name(obj.name);
            pro.spec(obj.spec);
            pro.color(obj.color);
            pro.numbers(obj.amount);
            productView.add(pro);
        };
    }
    $('.action-fold').trigger('click').eq(0).trigger('click');
    ko.applyBindings(productView, document.getElementById('wrapper'));
    $(document).on("click", '.action-deleted', function() {
        if (confirm('是否确认删除!')) {
            productView.lines.remove(ko.dataFor(this));
        };
    });
    var form = $('#form-const'),
        ifm = getIfm(),
        isok = false;
    form.find('.ibtn-ok').on('click', function(event) {
        /*
        alert(22);
        
        form.target('submit');
        return true;
        */
    });
    form.attr('target', ifm.attr('name'));
    form.on('submit', function(event) {
        isok = false;
        form.find('input[required]').each(function(i, elem) {
            var t = $(elem),
                v = t.val();
            if (v == '' || v == 0) {
                t.addClass('error');
                t.parents('.table-product').eq(0).removeClass('action-active');
                if (!isok) {
                    isok = t;
                };
            } else {
                t.removeClass('error');
            }
        });
        if (isok) {
            isok.focus();
            event.preventDefault();
            return false;
        }
    });
});