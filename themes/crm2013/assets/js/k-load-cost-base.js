/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-08-23 16:29:04
 * @version $Id$
 */
jQuery(function($) {
    $(document).on('click', '.action-fold', function() {
        $(this).parents('.itable').eq(0).toggleClass('action-active');
    }).on('click', '.btn-add', function() {
        window.open('list.html?' + (new Date()).toTimeString(), 'newwindow', 'height=600, width=800, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');
    });
    var formatCurrency = function(val){
        return formatFloat(val,4);
    }
    var initProduct = function(elem) {
        if (elem.attr('data-init')) {
            return false;
        };
        var input = $(elem),
            p = input.parent(),
            tr = p.parent().parent(),
            table = tr.parent(),
            dropdownlist = p.find('.dropdownlist'),
            span = p.find('.iselect'),
            datas = [],
            checkVale = function() {
                return input.attr('data-title') == input.val();
            },
            setProduct = function(data) {
                if (data) {
                    input.val(data.name);
                    input.attr('data-title', data.name);
                    tr.find('.product-itemid').val(data.itemid).trigger('change');
                    tr.find('.price').val(data.price).trigger('change');
                    tr.find('.unit').val(data.unit);
                    tr.find('.color').val(data.color);
                    tr.find('.spec').val(data.spec);
                } else {
                    tr.find('.product-itemid').val('').trigger('change');
                }
            },
            setValue = function(id) {
                if (id > 0 && datas.length > 0) {
                    var i = 0;
                    queryResult = Enumerable.from(datas).where(function(x) {
                        i++;
                        if (id == x.itemid) {
                            return true;
                        }
                    }).select(function(x) {
                        return x;
                    }).take(1).toObject(function(data) {
                        setProduct(data);
                        return 'data';
                    });
                }
            },
            init = function(qustr) {
                var queryResult = [],
                    html = '',
                    tdata = [];
                dropdownlist.empty().addClass('tips-loading');
                if (tags.length == 0) {
                    html = '仓库中没有产品!';
                } else if (trim(qustr) == '') {
                    html = '请输入要搜索的库存!';
                } else {
                    if (tags.length > 0) {
                        table.find('.product-itemid').each(function() {
                            tdata.push($(this).val());
                        });
                        tdata = tdata.join(',');
                        queryResult = Enumerable.from(tags).where(function(x) {
                            if (qustr == ''||qustr == '*') {
                                return true;
                            };
                            /*消除已经选择过的库存*/
                            if ((tdata == '' || tdata.indexOf(x.itemid) == -1) && (x.name.indexOf(qustr) >= 0 || (x.color && x.color.indexOf(qustr) >= 0) || (x.material && x.material.indexOf(qustr) >= 0) || (x.spec && x.spec.indexOf(qustr) >= 0))) {
                                return true;
                            }
                        }).select(function(x) {
                            return x;
                        }).toArray();
                    };
                    var htmls = [];
                    for (var i = 0; i < queryResult.length; i++) {
                        htmls.push(sprintf('<li data-id="%s">%s(%s-%s)</li>', queryResult[i].itemid, queryResult[i].name, queryResult[i].spec, queryResult[i].color));
                    };
                    if (htmls.length > 0) {
                        datas = queryResult;
                        html = '<ul>' + htmls.join('') + '</ul>';
                    } else {
                        datas = [];
                        html = '<b>库存中不存在!</b>';
                    }
                }
                dropdownlist.html(html).removeClass('tips-loading');
            };
        tr.on('change', '.product-itemid', function() {
            if ($(this).val() == '' || $(this).val() == 0) {
                tr.removeClass('active-product').find('input[readonly]').prop('readonly', false);
                input.attr('data-title', '');
            } else {
                tr.addClass('active-product');
                tr.find('.unit').prop('readonly', true);
                tr.find('.color').prop('readonly', true);
                tr.find('.spec').prop('readonly', true);
            }
        });
        input.on('keyup', function(event) {
            if (!tr.hasClass('dropdownlist-active')) {
                return true;
            };
            var t = $(this);
            p = t.parent();
            if (event.which == 13) {
                v = dropdownlist.addClass('vhide').find('.light');
                if (v.length > 0) {
                    t.val(v.text());
                };
            } else {
                qustr = t.val();
                init(qustr);
            }
        }).on('change', function() {
            if (!checkVale()) {
                setProduct(false);
            } else {}
        });
        span.on('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            if (!tr.hasClass('dropdownlist-active')) {
                if (!checkVale()) {
                    init(input.val());
                };
                $('.dropdownlist-active').removeClass('dropdownlist-active');
            } else {}
            tr.toggleClass('dropdownlist-active');
            if (tr.hasClass('dropdownlist-active')) {
                input.focus();
            }
        });
        dropdownlist.on('click', 'li', function(event) {
            var t = $(this);
            setValue(t.attr('data-id'));
            dropdownlist.find('.light').removeClass('light');
            t.addClass('light');
            span.trigger('click');
        });
        elem.attr('data-init', true);
        if (typeof arguments[1] != 'undefined') {
            input.attr('data-title', 'false');
            span.trigger('click');
        };
    }
    /*
    $('.product-id').each(function(i, el) {
        initProduct($(el));
    });
    $(document).on('click', '.xxiselect', function() {
        var t = $(this).parent();
        if (!t.attr('data-init')) {
            // $(this).unbind('click');
            initProduct(t.parent().find('.product-id'), true);
            t.attr('data-init', true);
        } else {}
        return true;
    });
*/
    var initUplaod = function(pickfiles, strcontainer, success) {
        var container = $('#' + strcontainer),
            filelist = container.find('.filelist'),
            uploader = new plupload.Uploader({
                runtimes: 'html5,flash,html4',
                browse_button: pickfiles, // you can pass in id...
                container: document.getElementById(strcontainer), // ... or DOM Element itself
                url: uploadUrl,
                flash_swf_url: CrmPath + '/js/Moxie.swf',
                multi_selection: false,
                resize: {
                    width: 1440,
                    height: 1440,
                    quality: 50
                },
                filters: {
                    max_file_size: '10mb',
                    chunk_size: '10mb',
                    mime_types: [{
                        title: "图片文件",
                        extensions: "jpg,gif,png"
                    }]
                },
                init: {
                    PostInit: function() {},
                    FilesAdded: function(up, files) {
                        plupload.each(files, function(file) {
                            filelist.html('<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>');
                        });
                        up.refresh();
                        uploader.start();
                    },
                    UploadProgress: function(up, file) {
                        $('#' + file.id).find('b').html('<span>' + file.percent + "%</span>");
                    },
                    Error: function(up, err) {
                        var str = "\nError #" + err.code + ": " + err.message;
                        alert(str);
                        up.refresh();
                    },
                    FileUploaded: function(up, file, msg) {
                        var elem = $('#' + file.id);
                        elem.find('b').html('100%');
                        if (msg && typeof msg['response'] != 'undefined') {
                            var obj = $.parseJSON(msg['response']);
                            if (obj && typeof obj['result'] != 'undefined') {
                                success.call(this, obj.result);
                            }
                        }
                    }
                }
            });
        uploader.init();
    }
    ko.extenders.numeric = function(target, precision) {
        var result = ko.computed({
            read: target,
            write: function(newValue) {
                var current = target(),
                    roundingMultiplier = Math.pow(10, precision),
                    newValueAsNum = isNaN(newValue) ? 0 : parseFloat(+newValue),
                    valueToWrite = Math.round(newValueAsNum * roundingMultiplier) / roundingMultiplier;
                if (valueToWrite < 0) {
                    valueToWrite *= -1;
                }
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
    ko.extenders.requiredx = function(target, overrideMessage) {
        target.hasError = ko.observable();
        target.validationMessage = ko.observable();

        function validate(newValue) {
            target.hasError(newValue ? false : true);
            target.validationMessage(newValue ? "" : overrideMessage || "This field is required");
        }
        validate(target());
        target.subscribe(validate);
        return target;
    };
    ko.extenders.logChange = function(target, option) {
        target.subscribe(function(newValue) {
            console.log(option + ": " + newValue);
        });
        return target;
    };
    var getNun = function() {
        var nun = arguments.length == 1 ? 1 : 0;
        return ko.observable(nun).extend({
            numeric: 4,
            rateLimit: 800
        });
    },
        Line = function(id) {
            var self = this;
            self.id = id ? id : null;
            self.id = sprintf("%s[%s]", self.id, (arguments.length > 1 && arguments[1] > 0) ? arguments[1] : uuid());
            self.init = function(id) {
                self.id = id;
            };
            self.totals = function() {
                return 0;
            };
            self.load = function(obj) {
                for (var i in obj) {
                    fun = self[i];
                    if (fun !== undefined) {
                        fun.call(this, obj[i]);
                    };
                }
            };
            self.getName = function(name) {
                return sprintf("%s[%s]", self.id, name);
            };
            self.getId = function(id) {
                var id = self.getName(id);
                return id.replace(/\[/ig, '-').replace(/\]/ig, '');
            };
        },
        Lines = function() {
            var self = this;
            self.id = null;
            self.lines = ko.observableArray();
            self.line = null;
            self.init = function(line, id) {
                self.line = line;
                self.id = id;
            };
            self.totals = ko.computed(function() {
                var total = 0;
                jQuery.each(self.lines(), function() {
                    total += this.totals();
                });
                return formatCurrency(total);
            });
            self.add = function() {
                var id = null,
                    temp = null;
                if (arguments.length == 1) {
                    if (typeof arguments[0] == 'object') {
                        temp = arguments[0]
                    } else {
                        id = arguments[0];
                    }
                };
                if (!temp) {
                    temp = new self.line(self.id, id);
                };
                self.lines.push(temp);
                return temp;
            };
            self.inits = function() {
                self.add();
            };
            self.clear = function() {
                self.lines.removeAll();
            };
            self.remove = function() {
                self.lines.remove(this);
            };
        }
        /*工序*/
        ,
        ProcessLine = function() {
            var self = this;
            Line.apply(this, arguments);
            self.name = ko.observable('');
            self.note = ko.observable('');
            self.price = getNun();
            self.totals = ko.computed(function() {
                return self.price();
            });
        },
        Process = function(product_id) {
            var self = this;
            Lines.call(this);
            self.id = sprintf("%s[process]", product_id);
            self.line = ProcessLine;
        }
        /*材料*/
        ,
        MaterialLine = function() {
            var self = this;
            Line.apply(this, arguments);
            self.product_id = ko.observable('');
            self.name = ko.observable('');
            self.price = getNun();
            self.numbers = getNun();
            self.expenses = getNun();
            self.unit = ko.observable('');
            self.color = ko.observable('');
            self.note = ko.observable('');
            self.spec = ko.observable('');
            /*总价*/
            self.totals = ko.computed(function() {
                var total = self.expenses(),
                    number = formatCurrency(self.numbers()),
                    price = formatCurrency(self.price());
                total += formatCurrency(number * price);
                return formatCurrency(total);
            });
        },
        Materia = function(type, product_id) {
            var self = this;
            self.itype = type;
            Lines.call(this);
            self.typeName = self.itype == 2 ? '辅料' : '主料';
            self.id = sprintf("%s[materia][%s]", product_id, self.itype);
            self.line = MaterialLine;
            self.initElement = function(element, index, data) {
                var t = $('#' + index.getId('id'));
                initProduct(t.find('.product-id'));
                t.find('.product-itemid').trigger('change');
            };
        },
        ProductLine = function() {
            var self = this,
                str = 'M[product][%s]',
                id = arguments.length == 1 ? arguments[0] : uuid();
            Line.call(this);
            if (id == 0) {
                str = "M[product]";
            };
            self.id = sprintf(str, id);
            self.itemid = id;
            self.type = ko.observable('');
            self.name = ko.observable('');
            self.color = ko.observable('');
            self.spec = ko.observable('');
            self.file_path = ko.observable('');
            //'http://i.9juren.com/file/upload/201406/06/09-26-29-85-4843.png'
            self.isfile = ko.computed(function() {
                return self.file_path() == '';
            });
            self.mainMaterias = new Materia(1, self.id);
            self.subMaterias = new Materia(2, self.id);
            self.process = new Process(self.id);
            self.expenses = getNun();
            self.numbers = getNun(1);
            var list = ['mainMaterias', 'subMaterias', 'process'];
            self.inits = function() {
                for (var i in list) {
                    self[list[i]].add();
                }
            };
            self.loads = function(msg) {
                if (msg[0]) {
                    self.load(msg[0]);
                };
                var typeid = 0,
                    temp = null,
                    j = null,
                    i = null;
                for (i = 1; i <= 3; i++) {
                    if (msg[i] && msg[i] instanceof Array) {
                        for (j = 0; j < msg[i].length; j++) {
                            temp = self[list[typeid]].add(msg[i][j]['itemid']);
                            temp.load(msg[i][j]);
                        };
                    };
                    typeid++;
                };
            }
            self.clear = function() {
                for (var i in list) {
                    self[list[i]].clear();
                }
            }
            /*单价*/
            self.price = ko.computed(function() {
                var total = 0;
                for (var i in list) {
                    total += self[list[i]].totals();
                };
                if (total != 0) {
                    total += self.expenses();
                };
                return formatCurrency(total);
            });
            /*总价*/
            self.totals = ko.computed(function() {
                var total = 0;
                number = formatCurrency(self.numbers()),
                price = formatCurrency(self.price());
                total += formatCurrency(number * price);
                return formatCurrency(total);
            });
            self.removePic = function(item) {
                self.setPic('');
            };
            self.setPic = function(url) {
                self.file_path(url);
            };
            self.initUpload = function() {
                var upload = initUplaod(self.getId('pickfiles'), self.getId('container'), function(src) {
                    self.setPic(src);
                });
            };
        }
        // ProcessLine.prototype = new Line;
        // MaterialLine.prototype = new Line;
        // ProductLine.prototype = new Line;
        // Process.prototype = new Lines;
        // Materia.prototype = new Lines;
    var showError = function(data) {
        if (data.length > 0) {
            var ids = 'input[name="' + data.join('"],input[name="');
            var list = $(ids).addClass('error');
            parent.gotoElem(list.eq(0));
        };
    },
        showOk = function() {}
        //公共调用部分
    window.ProductLine = ProductLine;
    window.Lines = Lines;
    window.showError = showError;
    window.showOk = showOk;
})