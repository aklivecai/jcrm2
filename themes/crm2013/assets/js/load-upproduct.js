/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-05-26 09:55:02
 * @version $Id$
 */
jQuery(function($) {
    /*产品编号数组*/
    var datalist = []
    /**
     * 检测是否存在产品
     * @param  {int} id 产品编号
     * @return {bool}
     */
        ,
        checkeProduct = function(id) {
            return datalist.join('|').indexOf(id) >= 0;
        }
        /**
         * 插入产品编号
         * @param {int} id 产品编号
         * @return {bool} 是否成功
         */
        , addProduct = function(id) {
            var result = !checkeProduct(id);
            if (result) {
                datalist.push(id);
            }
            return result;
        }
        /**
         * 移除产品编号
         * @param  {int} $id 产品编号
         * @return {bool}     是否删除成功
         */
        , removeProduct = function(id) {
            var result = checkeProduct(id);
            if (result) {
                result = false;
                for (var i = datalist.length - 1; i >= 0; i--) {
                    if (datalist[i] == id) {
                        datalist.splice(i, 1);
                        result = true;
                        break;
                    }
                };
            };
            return result;
        }
        /**
         * 检测表单,非空,和非零
         * @param  {string} id
         * @return {boole}   是否成功
         */
        ,
        checkF = function(id) {
            var result = true;
            $('#' + id).find('input[required]').each(function() {
                var t = $(this)
                _v = t.val();
                if (_v = '' || (_v == 0 && t.attr('type') == 'number')) {
                    t.addClass('error');
                    if (result == true) {
                        result = false;
                        t.focus();
                    };
                } else {
                    t.removeClass('error');
                }
            });
            return result == true;
        },
        /**
         * 添加新数据
         * @param {object} odata 传递过来的产品参数
         */
        addData = function(odata) {
            var temps = [],
                obj = null,
                len = odata.length;
            for (i = 0; i < len; i++) {
                if (!checkeProduct(odata[i]["itemid"])) {
                    obj = {};
                    //window.open 拆散对象，ＩＥ中窗口传递过来的数据，窗口已经关闭，会出错，无法引用到对象,被调用的对象已与其客户端断开连接
                    for (var el in odata[i]) {
                        obj[el] = odata[i][el];
                    };
                    obj.product_id = obj["itemid"];
                    // obj.price = 0;
                    obj.number = 1;
                    obj.note = '';
                    temps.push(new Product(obj));
                }
            }
            if (temps.length > 0) {
                viewm.add(temps);
                var scroll_offset = $("#edits-name").offset(); //得到pos这个div层的offset，包含两个值，top和left
                $("body").animate({
                    scrollTop: scroll_offset.top //让body的scrollTop等于pos的top，就实现了滚动
                }, 0);
            };
            return true;
        };
    window.datalist = datalist;
    window.checkeProduct = checkeProduct;
    window.addData = addData;
    window.removeProduct = removeProduct;

    function Product() {
        var self = this;
        self.itemid = ko.observable();
        self.price = ko.observable(0).extend({
            numeric: 4,
            rateLimit: 500
        });
        self.number = ko.observable(1).extend({
            numeric: 4,
            rateLimit: 500
        });
        self.note = ko.observable();
        /*总价*/
        self.totals = ko.computed(function() {
            var total = 0;
            number = formatFloat(self.number(), 4),
            price = formatFloat(self.price(), 4);
            total += formatFloat(number * price, 4);
            return formatFloat(total, 4);
        });
        self.isNews = function() {
            return self.obj.product_id == self.itemid();
        }
        self.obj = {};
        self.load = function(obj) {
            self.obj = obj;
            self.price(obj.price);
            self.number(obj.numbers);
            self.note(obj.note);
            self.itemid(obj.itemid);
        }
        self.cancel = function() {
            self.price(self.obj.price);
            self.number(self.obj.numbers);
            self.note(self.obj.note);
        }
        self.save = function() {
            self.obj.price = self.price();
            self.obj.number = self.number();
            self.obj.note = self.note();
        }
        self.eid = ko.computed(function() {
            return 'tr' + self.itemid();
        });
        if (arguments.length == 1) {
            self.load(arguments[0]);
        };
    }
    var ListViewModel = function(initialData) {
        var self = this;
        self.list = ko.observableArray(initialData);
        self.editlist = ko.observableArray();
        self.pageSize = ko.observable(15);
        self.pageIndex = ko.observable(0);
        self.selectedItem = ko.observable(null);
        self.saveUrl = saveUrl;
        self.deleteUrl = deleteUrl;
        self.totals = ko.computed(function() {
            var total = 0;
            jQuery.each(self.list(), function() {
                total += this.totals();
            });
            return formatFloat(total, 4);
        });
        self.edit = function(item) {
            var _item = self.selectedItem();
            if (_item != null) {
                _item.cancel();
            };
            self.selectedItem(item);
        };
        self.cancel = function(item) {
            if (item.isNews()) {
                if (removeProduct(item.obj.product_id)) {
                    self.editlist.remove(item);
                }
            } else {
                var item = self.selectedItem();
                item.cancel();
                self.selectedItem(null);
            }
        };
        self.add = function(newItem) {
            // var newItem = arguments.length == 1 ? arguments[0] : new Product();
            // self.list.unshift(newItem);
            var result = $.isArray(newItem) ? newItem : [newItem],
                len = result.length,
                _obj = null;
            for (var i = 0; i < result.length; i++) {
                _obj = result[i];
                if (addProduct(_obj.obj.product_id)) {
                    self.editlist.push(_obj);
                    // self.selectedItem(newItem);
                    // self.moveToPage(self.maxPageIndex());
                };
            };
        };
        self.remove = function(item) {
            if (confirm('是否确认删除?')) {
                var product_id = item.obj.product_id;
                // 
                if (item.isNews()) {;;
                    removeProduct(item.obj.product_id);
                    self.editlist.remove(item);
                } else {
                    $.get(self.deleteUrl, "itemid=" + item.itemid()).complete(function(result) {
                        self.list.remove(item);
                        removeProduct(item.obj.product_id);
                        if (self.pageIndex() > self.maxPageIndex()) {
                            self.moveToPage(self.maxPageIndex());
                        }
                    });
                }
            }
        };
        var _keys = ['price', 'number', 'note'];
        self.save = function(item) {
            // return false;
            // var item = self.selectedItem();
            var isnew = item.isNews();
            if (checkF(item.eid())) {
                var data = [],
                    isok = true;
                data.push("m[itemid]=" + item.itemid());
                data.push("m[price]=" + item.price());
                data.push("m[numbers]=" + item.number());
                data.push("m[note]=" + item.note());
                data.push("m[product_id]=" + item.obj.product_id);
                // log(data.join('&'));
                $.post(self.saveUrl, data.join('&'), function(result) {
                    if (result == '') {;
                        self.selectedItem(null);
                    } else if (Number(result) == result) {
                        if (item.itemid() != result) {
                            item.itemid(result);
                            item.save();
                            self.list.push(item);
                            self.editlist.remove(item);
                            self.moveToPage(self.maxPageIndex());
                        } else {
                            // self.selectedItem(null);
                        }
                    } else {
                        isok = false;
                    }
                    if (isok) {
                        // item.save();
                        // self.selectedItem(null);
                    };
                });
            };
        };
        self.templateToUse = function(item) {
            return self.selectedItem() === item ? 'editTmpl' : 'itemsTmpl';
        };
        self.pagedList = ko.dependentObservable(function() {
            var size = self.pageSize();
            var start = self.pageIndex() * size;
            return self.list.slice(start, start + size);
        });
        self.maxPageIndex = ko.dependentObservable(function() {
            return Math.ceil(self.list().length / self.pageSize()) - 1;
        });
        self.previousPage = function() {
            if (self.pageIndex() > 0) {
                self.moveToPage(self.pageIndex() - 1);
            }
        };
        self.nextPage = function() {
            if (self.pageIndex() < self.maxPageIndex()) {
                self.moveToPage(self.pageIndex() + 1);
            }
        };
        self.allPages = ko.dependentObservable(function() {
            var pages = [];
            for (i = 0; i <= self.maxPageIndex(); i++) {
                pages.push({
                    pageNumber: (i + 1)
                });
            }
            return pages;
        });
        self.moveToPage = function(index) {
            self.pageIndex(index);
            //跳转回头部
            gotoElem('#table-upproduct');
        };
        self.indexNumber = function(index) {
            return self.pageSize() * self.pageIndex() + index + 1;
        }
    };
    var temps = [],
        temp = null;
    for (var i = 0; i < tags.length; i++) {
        if (addProduct(tags[i]['product_id'])) {
            temps.push(new Product(tags[i]));
        }
    };
    var viewm = new ListViewModel(temps);
    ko.applyBindings(viewm, document.getElementById('table-upproduct'));
    $('#addproduct').on('click', function() {
        var wurl = createUrl("Product/window", ["warehouse_id=" + $("#Movings_warehouse_id").val()]);
        ShowModal.call($(this),wurl, {
            width: 800,
            height: 650,
            name: 'selectProduct',
            dataFun: addData
        });
    });
})