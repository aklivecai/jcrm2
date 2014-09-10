    var page_limit = 10,
        defaultID = 'itemid',
        defaultTitle = 'title',
        formatMange = function(data) {
            var result = "<table class='movie-result'><tr>";
            result += "<td class='movie-info'><div class='movie-title'>" + data.user_nicename + "</div></td>";
            result += "<td> (" + data.title + ")</td>"
            result += "</tr></table>";
            return result;
        }, __arrFun = {
            'Manage': {
                'func': formatMange
            }
        }, getSwitch = function(type) {
            var result = false,
                format = arguments.length >= 2 ? arguments[1] : false;
            if (typeof __arrFun[type] != 'undefined') {
                if (format) {
                    if (typeof __arrFun[type][format] != 'undefined') {
                        result = __arrFun[type][format];
                    } else if (format == 'id') {
                        result = defaultID;
                    }
                } else {
                    result = __arrFun[type];
                }
            }
            if (!result) {
                if (format == 'id') {
                    result = defaultID;
                } else {
                    result = defaultTitle;
                }
            }
            return result;
        }, mFormatSelection = function(data, type) {
            var result = '',
                arr = getSwitch(type);
            if (typeof arr['func'] != 'undefined') {
                result = arr['func'](data);
            } else {
                result = typeof arr['s1'] != 'undefined' ? data[arr['s1']] : data[defaultTitle];
                _temp = "<table class='movie-result'><tr>";
                _temp += "<td class='movie-info'><div class='movie-title'>" + result + "</div>";
                _temp += "</td></tr></table>";
                result = _temp;
            }
            return result;
        }, mFormatResult = function(data, type) {
            var result = '',
                arr = getSwitch(type);
            if (typeof arr['func'] != 'undefined') {
                result = arr['func'](data);
            } else {
                result = typeof arr['s1'] != 'undefined' ? data[arr['s1']] : data[defaultTitle];
                _temp = "<table class='movie-result'><tr>";
                _temp += "<td class='movie-info'><div class='movie-title'>" + result + "</div>";
                _temp += "</td></tr></table>";
                result = _temp;
            }
            return result;
        }, mFormatID = function(data, type) {
            var result = '',
                name = getSwitch(type, 'id');
            if (typeof data[name] != 'undefined') {
                result = data[name];
            }
            return result;
        }, loadSelects = function(elem) {
            if (elem instanceof Array) {
                for (var i = elem.length - 1; i >= 0; i--) {
                    loadSelects(elem[i]);
                };
                return true;
            }
            if (elem.length > 1) {
                elem.each(function(i, el) {
                    loadSelects(el);
                    return true;
                });
            };
            var __t = $(elem);
            if (__t.length == 0) {
                return false;
            };
            (function(t) {
                var sType = iType = t.attr('data-select');
                if (t.attr('data-get') && typeof __arrFun[t.attr('data-get')] != 'undefined') {
                    sType = t.attr('data-get');
                }
                var action = '/' + (t.attr('data-action') ? t.attr('data-action') : 'select');
                if (t.attr('data-path')) {
                    action += '?' + t.attr('data-path');
                };
                var ajaxUrl = iType + action,
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
                        escapeMarkup: function(m) {
                            return m;
                        },
                        formatResult: function(data) {
                            return mFormatResult(data, sType)
                        },
                        formatSelection: function(data) {
                            return mFormatSelection(data, sType)
                        },
                        id: function(data) {
                            return mFormatID(data, sType);
                        },
                        ajax: {
                            url: createUrl(ajaxUrl),
                            dataType: 'jsonp',
                            data: function(term, page) {
                                var result = {
                                    q: term,
                                    page_limit: page_limit
                                }, _temp = t.attr('data-selectby');
                                result[sType + '_page'] = page;
                                if (_temp) {
                                    _temp = _temp.split(':');
                                    result[_temp[1]] = $(_temp[0]).val();
                                };
                                var _not = [];
                                if (t.attr('data-not') && t.attr('data-not') != '') {
                                    _not.push(t.attr('data-not'));
                                };
                                // data-notbyel
                                if (t.attr('data-notbyel')) {
                                    var _ls = '.' + t.attr('class').split(' ')[0];
                                    var _els = $(_ls);
                                    _els.each(function(i, _el) {
                                        if ($(_el).val() != '') {
                                            _not.push($(_el).val());
                                        };
                                    });
                                }
                                if (t.attr('data-get')) {
                                    result['get'] = t.attr('data-get');
                                };
                                result['not'] = _not.join(',');
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
                        initSelection: function(element, callback) {
                            var t = $(element),
                                id = t.val(),
                                get = t.attr('data-get');
                            if (id !== "") {
                                var pars = ['id=' + id];
                                if (get != 'undefined') {
                                    pars.push('get=' + get);
                                };
                                $.ajax(createUrl(ajaxUrl, pars), {
                                    dataType: "jsonp"
                                }).done(function(data) {
                                    if (data != '' && typeof data == 'object') {
                                        callback(data.data[0]);
                                    };
                                });
                            }
                        }
                    };
                t.select2(result).trigger('select-load');
            })(__t);
        };
    var initSelect = function(dom) {
        var clientele = dom.find(".select-clientele").attr({
            'data-select': 'Clientele',
            'placeholder': '搜索客户'
        }),
            prson = dom.find(".select-prsonid").attr({
                'data-select': 'ContactpPrson',
                'placeholder': '搜索联系人',
                'data-selectby': 'input.select-clientele:clienteleid'
            }),
            manage = dom.find(".select-manageid").attr({
                'data-select': 'Manage'
            }),
            fromidS = dom.find(".select-fromid").attr({
                'data-select': 'Memeber'
            }),
            selectAjax = dom.find('.select-ajax');
        prson.on('select-load', function() {
            clientele.on("change", function(e) {
                prson.select2('val', '');
            })
        });
        $('.iselect').each(function() {
            var obj = {};
            if ($(this).attr('placeholder')) {
                obj.placeholder = $(this).attr('placeholder');
            };
            $(this).select2(obj);
        })
        loadSelects([clientele, prson, manage, fromidS, selectAjax]);
    }
    jQuery(function($) {
        initSelect($(document));
        // $('#list-grid,body').on('takLoad',function(){ /*tselect();*/});
    });