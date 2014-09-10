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
                    for (var el in odata[i]) {
                        obj[el] = odata[i][el];
                    };
                    obj.product_id = obj["itemid"];
                    obj.number = 1;
                    obj.note = '';
                    temps.push(new Product(obj));
                }
            }
            if (temps.length > 0) {
                viewm.add(temps);
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
        self.obj = {};
        self.load = function(obj) {
            self.obj = obj;
            self.price(obj.price);
            self.number(obj.numbers);
            self.note(obj.note);
            self.itemid(obj.itemid);
        }
        self.eid = ko.computed(function() {
            return 'tr' + self.itemid();
        });
        self.getName = function(name) {
            return "Product[" + self.itemid() + "][" + name + "]";
        }
        if (arguments.length == 1) {
            self.load(arguments[0]);
        };
    };
    var ListViewModel = function() {
        var self = this;
        self.list = ko.observableArray([]);
        self.totals = ko.computed(function() {
            var total = 0;
            jQuery.each(self.list(), function() {
                total += this.totals();
            });
            return formatFloat(total, 4);
        });
        self.add = function(newItem) {
            var result = $.isArray(newItem) ? newItem : [newItem],
                len = result.length;
            // _obj = null;
            for (var i = 0; i < result.length; i++) {
                var _obj = result[i];
                if (addProduct(_obj.obj.product_id)) {
                    self.list.push(_obj);
                };
            };
            $(".stepContainer").css('height', '');
        };
        self.remove = function(item) {
            var product_id = item.obj.product_id;
            removeProduct(item.obj.product_id);
            self.list.remove(item);
        }
        self.templateToUse = function(item) {
            return 'itemsTmpl';
        }
    };
    var viewm = new ListViewModel();
    ko.applyBindings(viewm, document.getElementById('table-product'));
    $('#addproduct').on('click', function() {
        var wurl = createUrl("Product/window", ['type=' + $("#type").val(), "warehouse_id=" + $("#Movings_warehouse_id").val()]);
        ShowModal.call($(this), wurl, {
            width: 850,
            height: 550,
            name: 'selectProduct',
            dataFun: addData
        });
    });
    $('#create-product').on('click', function() {
        var wurl = createUrl("Product/create", ['action=window', "warehouse_id=" + $("#Movings_warehouse_id").val()]);
        ShowModal.call($(this), wurl, {
            width: 650,
            height: 450,
            name: 'createProduct',
            dataFun: addData
        });
    })
});
var getIfm = function() {
    var ifmname = "ifm" + Math.random(),
        ifm = $('<iframe src="about:blank" style="position: absolute;top:-9999;" width="2" height="1" frameborder="0" name="' + ifmname + '">');
    ifm.appendTo($(document.body));
    return ifm;
};
var wizard = $("#wizard"),
    leaveAStepCallback = function(obj) {
        var step_num = obj.attr("rel");
        return validateSteps(step_num);
    }, validateSteps = function(step) {
        var isStepValid = true;
        if (step == 1) {
            if (validateStep1() == false) {
                isStepValid = false;
                wizard.smartWizard("setError", {
                    stepnum: step,
                    iserror: true
                });
            } else {
                wizard.smartWizard("setError", {
                    stepnum: step,
                    iserror: false
                });
            }
        }
        return isStepValid;
    }, validateStep1 = function() {
        var isStepValid = false;
        if (valdata($("#step-1"))) {
            isStepValid = true;
        }
        return isStepValid;
    }, valdata = function(elem) {
        var isStepValid = true,
            message = "";;
        elem.find("[required]").each(function(i, el) {
            var t = $(el);
            if (t.val() == "") {
                t.addClass("error");
                isStepValid = false;
                message += "<li>" + $("label[for=" + t.attr("id") + "]").text() + "</li>";
            } else {
                t.removeClass("error");
            }
        });
        if (message != "") {
            message = "<ul><li>下面内内容填写不正确！</li>" + message + "</ul>";
            wizard.smartWizard("showMessage", message);
        } else {
            wizard.find(".close").trigger("click");
        }
        return isStepValid;
    };

function submitAction() {
    if (datalist == 0) {
        alert("尚未添加产品型号！");
        return false;
    }
    var message = "",
        priceMessage = "";
    $("#product-movings input[name*=number]").each(function() {
        var val = $(this).val();
        if (checkDecimal(val) && val > 0) {
            $(this).removeClass("error");
        } else {
            $(this).addClass("error");
            if (message == "") {
                message = "<li>请输入正确的数量(正数，最多四位小数)！</li>";
            }
        }
    });
    $("#product-movings input[name*=price]").each(function() {
        var val = $(this).val();
        //刘魏 入库单价不需要必填
        if (checkDecimal(val) && val >= 0) {
            $(this).removeClass("error");
        } else {
            $(this).addClass("error");
            if (priceMessage == "") {
                priceMessage = "<li>价格必须为合法数字(正数，最多四位小数)！</li>";
            }
        }
    });
    message += priceMessage;
    if (message != "") {
        message = "<ul>" + message + "</ul>";
        wizard.smartWizard("showMessage", message);
        wizard.smartWizard("setError", "1");
        return false;
    };
    var ifm = getIfm(),
        ifmname = ifm.attr("name");
    wizard.parents("form").attr("target", ifmname);
    wizard.find('.buttonFinish').addClass('buttonDisabled');
    wizard.parents("form").submit();
    ifm.on("load", function() {
        // ifm.remove();
    });
    wizard.smartWizard("setError", "0");
}
wizard.smartWizard({
    labelNext: "下一步",
    labelPrevious: "上一步",
    labelFinish: "提交",
    onFinish: submitAction,
    onLeaveStep: leaveAStepCallback,
    enableFinishButton: true
});