/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-05-26 11:05:24
 * @version $Id$
 */
if (typeof window['log'] == 'undefined') {
    var log = function(msg) {
        if (typeof window['console'] == 'undefined') return false;
        var len = arguments.length;
        if (len > 1) {
            for (var i = 0; i < len; i++) {
                log(arguments[i] + '\n');
            }
        } else {
            console.log(msg);
        }
    }
}
var dlog = function(obj) {
    var str = '';
    for (var el in obj) {
        str += obj[el];
    };
    alert(str);
};
/**
  * localstorage存储类
  */
var storage = {
    isLocalStorage: (window.localStorage ? true : false),
    //存值
    set: function(item, value) {
        if (this.isLocalStorage) {
            localStorage[item] = value;
        }
    },
    //取值
    get: function(item) {
        if (this.isLocalStorage) {
            return localStorage[item];
        }
    },
    //删除一个值
    del: function(item) {
        if (this.isLocalStorage) {
            localStorage.removeItem(item);
        }
    },
    //全部清除
    clear: function() {
        if (this.isLocalStorage) {
            localStorage.clear();
        }
    },
    //json存储
    json_set: function(item, value) {
        if (this.isLocalStorage) {
            localStorage[item] = JSON.stringify(value);
        }
    },
    //json取值
    json_get: function(item) {
        if (this.isLocalStorage) {
            var data = localStorage[item] ? JSON.parse(localStorage[item]) : '';
            return data;
        }
    },
    //遍历，用于测试
    display: function() {
        if (this.isLocalStorage) {
            var data = '';
            for (var i = 0; i < localStorage.length; i++) {
                key = localStorage.key(i);
                value = localStorage.getItem(key);
                data += "\nkey:" + key + " value:" + value;
            }
            return data;
        }
    }
};

    var uploadFile = function(t, pars) {
        var webuploader = $('#webuploader'),
            postUrl = webuploader.attr('data-post-url'),
            BASE_URL = webuploader.attr('src'),
            options = {
                filesize: 1024 * 1024 * 3,
                uploadError: function(file, error) {
                    var msg = file.name + '上传出错';
                    this.removeFile(file);
                    msgShow(msg, true);
                },
                uploadSuccess: function(file, result) {
                    // this.removeFile(file);
                },
                fileQueued: function(file) {
                    var self = this;
                    this.md5File(file).then(function(val) {
                        self.options.formData = {
                            md5: val,
                            size: file.szie,
                        }
                        self.upload(file);
                    });
                },
                error: function(error) {
                    var strHtml = null;
                    switch (error) {
                        case 'F_EXCEED_SIZE':
                            var file = arguments[1];
                            strHtml = file.name + '  (' + WebUploader.formatSize(file.size) + ')  超过最大限制' + WebUploader.formatSize(options.filesize);
                            break;
                        case 'Q_EXCEED_NUM_LIMIT':
                            var file = arguments[2],
                                tip = arguments[1];
                            strHtml = file.name + '  (' + WebUploader.formatSize(file.size) + ')  超过文件总数限制' + tip + '个';
                            break;
                        case 'object':
                            return false;
                    }
                    if (strHtml) {
                        msgShow(strHtml, true);
                    };
                }
            },
            BASE_URL = BASE_URL.substr(0, BASE_URL.lastIndexOf('/') + 1),
            _url = postUrl + (t.attr('data-url') ? t.attr('data-url') : '');
        //更新配置
        for (var i in pars) {
            options[i] = pars[i];
        }
        var uploader = WebUploader.create({
            // swf文件路径
            swf: BASE_URL + '/Uploader.swf',
            // 文件接收服务端。
            server: _url,
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            // pick:  '#'+t.attr('id') ,
            pick: t.get(0),
            // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
            resize: true,
            fileSingleSizeLimit: options.filesize, // {int} [可选] [默认值：undefined] 验证单个文件大小是否超出限制, 超出则不允许加入队列
            fileNumLimit: 0 //{int} [可选] [默认值：undefined] 验证文件总数量, 超出则不允许加入队列。
            // fileSizeLimit: 1024 * 1024 * 10 //{int} [可选] [默认值：undefined] 验证文件总大小是否超出限制, 超出则不允许加入队列。
        });
        // t.attr('id') ? t.attr('id') :t.get(0)
        // 当有文件被添加进队列的时候
        uploader.on('fileQueued', options.fileQueued);
        uploader.on('error', options.error);
        //上传成功以后，插入返回的编号
        uploader.on('uploadSuccess', options.uploadSuccess);
        uploader.on('uploadError', options.uploadError);
    };
//用于动态生成网址
//$route,$params=array(),$ampersand='&'
var createUrl = function(route) {
    if (!CrmPath) {
        return false;
    }
    var ampersand = typeof arguments[2] != 'undefined' ? arguments[2] : '&',
        params = typeof arguments[1] != 'undefined' ? arguments[1] : [];
    if (!route || route == "undefined") {
        return CrmPath;
    }
    // var url = CrmPath + (CrmPath.indexOf('?')>0?'':'?');
    var url = CrmPath;
    url += route;
    url = url + (url.indexOf('?') > 0 ? '' : '?');
    if (params.length > 0) {
        url += ampersand + params.join(ampersand);
    };
    if (url.indexOf('?&') > 0) {
        url = url.replace('?&', '?');
    };
    return url;
},
    goHref = function(url) {
        window.location.href = url;
    },
    dateFormat = function(date, format) {
        if (format === undefined) {
            format = date;
            date = new Date();
        }
        var map = {
            "M": date.getMonth() + 1, //月份 
            "d": date.getDate(), //日 
            "h": date.getHours(), //小时 
            "m": date.getMinutes(), //分 
            "s": date.getSeconds(), //秒 
            "q": Math.floor((date.getMonth() + 3) / 3), //季度 
            "S": date.getMilliseconds() //毫秒 
        };
        format = format.replace(/([yMdhmsqS])+/g, function(all, t) {
            var v = map[t];
            if (v !== undefined) {
                if (all.length > 1) {
                    v = '0' + v;
                    v = v.substr(v.length - 2);
                }
                return v;
            } else if (t === 'y') {
                return (date.getFullYear() + '').substr(4 - all.length);
            }
            return all;
        });
        return format;
    },
    iAjax = function(pars) {
        var d = null,
            options = {
                type: "post",
                url: '',
                data: {},
                dataType: "json",
                success: function(result) {
                    if (result.status == 1) {
                        if(typeof result['url']!='undefined'){
                            if (result.url==true) {
                                window.location.href = window.location.href;    
                            }else{
                                window.location.href = result['url'];    
                            }                            
                        }
                    } else {
                        msgShow(result.info);
                    }
                },
                beforeSend: function() {
                    d = msgShow();
                }
            };
        for (var i in pars) {
            options[i] = pars[i];
        }
        var fun= typeof options['complete']=='function'?options['complete']:false,
         complete = function(){
            d.idone(fun);
        }
        options.complete = complete;
        $.ajax(options);
    },
    sCF = function(msg) {
        var t = $(this),
            follow = t.get(0),
            fn = arguments.length == 2 ? arguments[1] : t.attr('data-fun'),
            _url = t.attr('href'),
            d = dialog({
                align: 'right',
                skin: 'itips',
                content: '<p>' + msg + '</p>',
                button: [{
                    value: '确定',
                    callback: function() {
                        if (fn) {
                            var exc = null;
                            if (typeof fn == 'function') {
                                exc = fn;
                            }
                            if (typeof window[fn] != 'undefined') {
                                exc = window[fn];
                                // window[fn].call(window, t);
                            }
                            exc.call(window, t);
                        } else if (_url) {
                            goHref(_url);
                        }
                        return true;
                    }
                }, {
                    value: '取消',
                    autofocus: true
                }],
                quickClose: true // 点击空白处快速关闭
            });
        d.show(follow);
        // return !confirm(msg);
    },
    gotoElem = function(elem) {
        /*
        $("body,html").animate({
            scrollTop:$(elem).offset().top //让body的scrollTop等于pos的top，就实现了滚动
        },0);
    */
        $(window).scrollTop($(elem).offset().top);
    },
    trim = function(str) {
        return $.trim(str);
    },
    getTimes = function() {
        var time = new Date().getTime();
        time = parseInt(time / 1000);
        return time;
    },
    checkDecimal = function(value) {
        var decimalReg = new RegExp("^\\d+(\\.\\d+)?$");
        return decimalReg.test(value);
    },
    padLeft = function(str, lenght) { //位数不足补0，length是位数
        if (str.length >= lenght) return str;
        else return padLeft("0" + str, lenght);
    }
    //保留N位小数  
    ,
    formatFloat = function(src, pos) {
        return Math.round(src * Math.pow(10, pos)) / Math.pow(10, pos);
    },
    formatCurrency = function(value) {
        var digit = 100;
        if (typeof arguments[1] != 'undefined') {
            digit = arguments[1];
        }
        return parseFloat(Math.round(Number(value) * digit) / digit);
    },
    uniencode = function(text) {
        return text.replace(/[\u4E00-\u9FA5]/ig, function(w) {
            return escape(w).toLowerCase().replace(/%/ig, '\\');
        });
    },
    _uuid = function(len, radix) {
        var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split('');
        var uuid = [],
            i;
        radix = radix || chars.length;
        if (len) {
            for (i = 0; i < len; i++) uuid[i] = chars[0 | Math.random() * radix];
        } else {
            var r;
            uuid[8] = uuid[13] = uuid[18] = uuid[23] = '-';
            uuid[14] = '4';
            for (i = 0; i < 36; i++) {
                if (!uuid[i]) {
                    r = 0 | Math.random() * 16;
                    uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r];
                }
            }
        }
        return uuid.join('');
    },
    uuid = function() {
        var len = typeof arguments[0] != 'undefined' ? arguments[0] : 17;
        return _uuid(len, len);
    },
    trim = function(str) {
        for (var i = 0; i < str.length && str.charAt(i) == "  "; i++);
        for (var j = str.length; j > 0 && str.charAt(j - 1) == "  "; j--);
        if (i > j) return "";
        return str.substring(i, j);
    },
    _isValidatedPattern = function(value, pattern) {
        var regex = pattern;
        var match = regex.exec(value);
        return match !== null;
    },
    checkNumber = function(value) {
        return value != '' && _isValidatedPattern(value, /^\d+(\.\d+)?$/);
        // return value != '' &&( _isValidatedPattern(value, /^\d+(\.\d+)?$/)&&);
    },
    checkInteger = function(value) {
        return value != '' && _isValidatedPattern(value, /^[1-9][0-9]*$/);
    },
    checkEmail = function(value) {
        return value != '' && _isValidatedPattern(value, /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/);
    }, checkMobile = function(value) {
        return value != '' && _isValidatedPattern(value, /^0?(13[0-9]|15[012356789]|18[0236789]|14[57])[0-9]{8}$/);
    }, checkTel = function(value) {
        //评注：匹配形式如 0511-4405222 或 021-87888822
        // return value != '' && _isValidatedPattern(value, /d{3}-d{8}|d{4}-d{7}$/);
        return value != '' && _isValidatedPattern(value, /^0(([1,2]\d-\d{8})|([3-9]\d{2}-\d{7})|755-\d{8})$/);
    }, checkPostal = function(value) {
        //评注：中国邮政编码为6位数字
        return value != '' && _isValidatedPattern(value, /[1-9]d{5}(?!d)/);
    }, checkIdcard = function(value) {
        //评注：中国的身份证为15位或18位
        return value != '' && _isValidatedPattern(value, /d{15}|d{18}/);
    }, checkIp = function(value) {
        /*
        var s = '(1[0-9]{2}|[1-9]?[0-9]|2[0-4][0-9]|25[0-5])';
        var re = new RegExp('^' + s + '//.' + s + '//.' + s + '//.' + s + '$');
        return value != '' && re.exec(value);
        */
        // return value != '' && _isValidatedPattern(value, /^(1[0-9]{2}|[1-9]?[0-9]|2[0-4][0-9]|25[0-5])\.(1[0-9]{2}|[1-9]?[0-9]|2[0-4][0-9]|25[0-5])\.(1[0-9]{2}|[1-9]?[0-9]|2[0-4][0-9]|25[0-5])$/);
        return value != '' && _isValidatedPattern(value, /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/);
        // return value != '' && _isValidatedPattern(value, /^[0-9.]{1,20}$/);
    }, checkUrl = function(value) {
        return value != '' && _isValidatedPattern(value, /^https?:\/\/(.+\.)+.{2,4}(\/.*)?$/);
    }, checkPattern = function(value, pattern) {
        return value.match(pattern);
    }, _checkElem = function(elem) {
        var v = trim(elem.val()),
            strMsg = false;
        if (elem.attr('required') && v == '') {
            strMsg = '这个字段是必需填写的';
        } else {
            if (v != '') {
                var itype = elem.attr('type');
                switch (itype) {
                    case 'email':
                        if (!checkEmail(v)) {
                            strMsg = '请输入一个有效的电子邮件地址';
                        }
                        break;
                    case 'number':
                        if (elem.attr('step') == 'any') {
                            if (!checkNumber(v)) {
                                strMsg = '请输入一个有效的数值';
                            };
                        } else if (!checkInteger(v)) {
                            strMsg = '请输入一个有效的数字';
                        }
                        break;
                    case 'tel':
                        if (!checkTel(v)) {
                            strMsg = "请输入一个有效的电话号码";
                        };
                        break;
                    case 'url':
                        if (!checkUrl(v)) {
                            strMsg = '请输入一个有效的网址网站地址 <i>http://</i>开头';
                        };
                        break;
                }
                if (!strMsg) {
                    if (elem.attr('data-mobile') && !checkMobile(v)) {
                        return '请输入一个有效的手机号码';
                    } else if (elem.attr('data-phone') && !checkTel(v) && !checkMobile(v)) {
                        return "请输入正确的电话号码，或者手机号码";
                    } else if (elem.attr('data-ip') && !checkIp(v)) {
                        return "请输入一个有效的IP地址";
                    } else if (elem.attr('data-postal') && !checkPostal(v)) {
                        return "请输入一个有效的邮政编码";
                    } else if (elem.attr('data-idcard') && !checkIdcard(v)) {
                        return "请输入一个有效的身份证号码";
                    } else if (elem.attr('pattern') && !checkPattern(v)) {
                        return "请输入正确在信息";
                    }
                };
            };
        }
        return strMsg;
    },
    checkForm = function(eform) {
        var t = $(eform),
            error = null;
        t.find('input,textarea,select').each(function(index, elem) {
            var _elem = $(elem).eq(0),
                strMst = _checkElem(_elem);
            if (strMst) {
                _elem.addClass('error');
                if (error == null) {
                    error = _elem;
                }

                tipShow(strMst+_elem.attr('class'), _elem.get(0));
            } else {
                _elem.removeClass('error')
            }
        });
        t.find('span.wf_field_write[required]').each(function(index, el) {
            if($(el).find('input:checked').length==0){
                tipShow('请选择', el);
                error = el;
            }
        });
             /* iterate through array or object */
        if (error != null) {
            error.focus();
            return false;
        } else {
            return true;
        }
    },
    tipShow = function(content, elem) {
        if (typeof elem == 'string') {
            elem = document.getElementById(elem);
        };
        _align = 'right';
        if (arguments.length > 2) {
            pars = arguments[2];
            if (typeof pars['align'] != 'undefined') {
                _align = pars['align'];
            };
        };
        var d = dialog({
            skin: 'itips',
            align: _align,
            content: content,
            quickClose: true
        });
        d.show(elem);
        return d;
    }, msgShow = function(content, url) {
        var title = arguments.length > 2 ? arguments[2] : '提示';
        if (arguments.length == 0) {
            title = '操作中请稍后';
            // content = '<div class="data-loading">请等待...</div>';
        };
        var d = top.dialog({
            content: content,
            title: title,
            skin: 'msg-show',
            fixed: true,
            width: '250',
            height: '80',
            padding: 30,
            onshow: function() {
                setTimeout(function() {
                    if (url instanceof Function) {
                        d.close().remove();
                        url.call(this);
                    } else if (typeof url == 'string') {
                        window.location.href = url;
                    } else if (url) {
                        d.close().remove();
                    }
                    // 
                }, 2500);
            }
        });
        d.idone = function() {
            var parms  = arguments;
            setTimeout(function() {
                d.close();
                d.remove();
                if (parms.length>0&&typeof parms[0]=='function') {
                    parms[0].call(this);
                };
            }, 500);
        }
        d.showModal();
        return d;
    },
    ShowModal = function(url) {
        var options = arguments.length > 1 ? arguments[1] : {
            width: screen.availWidth < 1300 ? 1000 : screen.availWidth - screen.availWidth / 5,
            height: screen.availHeight - screen.availHeight / 5
        },
            dataObj = arguments.length >= 3 ? arguments[2] : {},
            title = '...',
            self = top,
            winName = typeof options.name != 'undefined' ? options.name : 'tak-' + uuid();
        if (this['attr']) {
            if (typeof options['dataFun'] == 'undefined' && this.attr('data-fun')) {
                options['dataFun'] = this.attr('data-fun');
                if (typeof window[options['dataFun']] != 'undefined') {
                    options['dataFun'] = window[options['dataFun']];
                } else {
                    options['dataFun'] = false;
                }
            }
            title = this.attr('title') ? this.attr('title') : this.text();
            if (this.attr('data-full')) {
                options.width = screen.availWidth - screen.availWidth / 18;
                options.height = screen.availHeight - screen.availHeight / 5;
                var ie = /*@cc_on!@*/ !1;
                if (ie) {
                    options.width = "860";
                }
            }
            if (this.attr('data-self') == 'win') {
                self = window;
            };
        }
        if (url.indexOf('dialog=win')==-1) {
            url+=url.indexOf('?')==-1?'?':'&';
            url+='dialog=win';
        };
        if (!options['dataFun']) {
            options['dataFun'] = function(data) {
                var url = false,
                    info = '';
                if (data['url']) {
                    url = data['url'] == true ? window.location.href : data['url'];
                }
                if (data['info']) {
                    info = data['info'];
                    msgShow(info, url);
                } else if (url) {
                    window.location.href = url;
                }
            }
        }
        dataObj.itemid = winName;
        zIndex = 2024;
        if (!self.dialogzIndex) {
            zIndex = self.dialogzIndex = 2500;
        } else {
            self.dialogzIndex += 15;
            zIndex = self.dialogzIndex;
        }
        // var data = dialog.data; // 获取对话框传递过来的数据
        var modDialog = self.dialog({
            id: winName,
            title: title,
            zIndex: zIndex,
            fixed: true,
            skin: 'mod-dialog',
            width: options.width,
            height: options.height,
            padding: 0,
            url: url,
            data: dataObj, // 给 iframe 的数据
            //quickClose: true,
            onshow: function() {

            },
            oniframeload: function() {
                // var ifr = $('iframe[name=' + winName + ']');
                // log(ifr.contents($('#content')).height(250));
                // modDialog.reset();
                // ifr.contents($('#content')).height(ifr.height());
            },
            onclose: function() {
                options['dataFun'].call(this, this.returnValue);
            }
        }).showModal();
        return modDialog;
        /*        
            l = (screen.availWidth - 10 - options.width) / 2,
            t = (screen.availHeight - 30 - options.height) / 2,
            retValue = {},
            pars = [],
         */
        // t = 25;
        if (window.showModalDialog && !/chrome/.test(navigator.userAgent.toLowerCase())) {
            pars.push("resizable:yes");
            pars.push("dialogWidth:" + options.width + 'px');
            pars.push("dialogHeight:" + options.height + 'px');
            if (!/chrome/.test(navigator.userAgent.toLowerCase())) {
                pars.push("dialogLeft:" + l + 'px');
            };
            pars.push("dialogTop:" + t + 'px');
            //传递window　为了窗口页面可以使用当前页面内容
            retValue = window.showModalDialog(url, window, pars.join(';'));
        } else {
            // for similar functionality in Opera, but it's not modal!
            pars.push("width=" + options.width);
            pars.push("height=" + options.height);
            pars.push("left=" + l);
            pars.push("top=" + t);
            var modal = window.open(url, winName, pars.join(','), null);
            if (modal) {};
            modal.dialogArguments = dataObj;
            retValue = modal;
        }
        return retValue;
    };

// substr(str, start, len)
// microtime(get_as_float)
// mt_rand(min, max)
// sprintf
// strtr
/*! jcrm 2014.09.09 17:12 */
function substr(str,start,len){var i=0,allBMP=!0,es=0,el=0,se=0,ret="";str+="";var end=str.length;switch(this.php_js=this.php_js||{},this.php_js.ini=this.php_js.ini||{},this.php_js.ini["unicode.semantics"]&&this.php_js.ini["unicode.semantics"].local_value.toLowerCase()){case"on":for(i=0;i<str.length;i++)if(/[\uD800-\uDBFF]/.test(str.charAt(i))&&/[\uDC00-\uDFFF]/.test(str.charAt(i+1))){allBMP=!1;break}if(!allBMP){if(0>start)for(i=end-1,es=start+=end;i>=es;i--)/[\uDC00-\uDFFF]/.test(str.charAt(i))&&/[\uD800-\uDBFF]/.test(str.charAt(i-1))&&(start--,es--);else for(var surrogatePairs=/[\uD800-\uDBFF][\uDC00-\uDFFF]/g;null!=surrogatePairs.exec(str);){var li=surrogatePairs.lastIndex;if(!(start>li-2))break;start++}if(start>=end||0>start)return!1;if(0>len){for(i=end-1,el=end+=len;i>=el;i--)/[\uDC00-\uDFFF]/.test(str.charAt(i))&&/[\uD800-\uDBFF]/.test(str.charAt(i-1))&&(end--,el--);return start>end?!1:str.slice(start,end)}for(se=start+len,i=start;se>i;i++)ret+=str.charAt(i),/[\uD800-\uDBFF]/.test(str.charAt(i))&&/[\uDC00-\uDFFF]/.test(str.charAt(i+1))&&se++;return ret}case"off":default:return 0>start&&(start+=end),end="undefined"==typeof len?end:0>len?len+end:len+start,start>=str.length||0>start||start>end?!1:str.slice(start,end)}return void 0}function microtime(get_as_float){var now=(new Date).getTime()/1e3,s=parseInt(now,10);return get_as_float?now:Math.round(1e3*(now-s))/1e3+" "+s}function mt_rand(min,max){var argc=arguments.length;if(0===argc)min=0,max=2147483647;else{if(1===argc)throw new Error("Warning: mt_rand() expects exactly 2 parameters, 1 given");min=parseInt(min,10),max=parseInt(max,10)}return Math.floor(Math.random()*(max-min+1))+min}function sprintf(){var regex=/%%|%(\d+\$)?([-+\'#0 ]*)(\*\d+\$|\*|\d+)?(\.(\*\d+\$|\*|\d+))?([scboxXuideEfFgG])/g,a=arguments,i=0,format=a[i++],pad=function(str,len,chr,leftJustify){chr||(chr=" ");var padding=str.length>=len?"":new Array(1+len-str.length>>>0).join(chr);return leftJustify?str+padding:padding+str},justify=function(value,prefix,leftJustify,minWidth,zeroPad,customPadChar){var diff=minWidth-value.length;return diff>0&&(value=leftJustify||!zeroPad?pad(value,minWidth,customPadChar,leftJustify):value.slice(0,prefix.length)+pad("",diff,"0",!0)+value.slice(prefix.length)),value},formatBaseX=function(value,base,prefix,leftJustify,minWidth,precision,zeroPad){var number=value>>>0;return prefix=prefix&&number&&{2:"0b",8:"0",16:"0x"}[base]||"",value=prefix+pad(number.toString(base),precision||0,"0",!1),justify(value,prefix,leftJustify,minWidth,zeroPad)},formatString=function(value,leftJustify,minWidth,precision,zeroPad,customPadChar){return null!=precision&&(value=value.slice(0,precision)),justify(value,"",leftJustify,minWidth,zeroPad,customPadChar)},doFormat=function(substring,valueIndex,flags,minWidth,_,precision,type){var number,prefix,method,textTransform,value;if("%%"===substring)return"%";for(var leftJustify=!1,positivePrefix="",zeroPad=!1,prefixBaseX=!1,customPadChar=" ",flagsl=flags.length,j=0;flags&&flagsl>j;j++)switch(flags.charAt(j)){case" ":positivePrefix=" ";break;case"+":positivePrefix="+";break;case"-":leftJustify=!0;break;case"'":customPadChar=flags.charAt(j+1);break;case"0":zeroPad=!0,customPadChar="0";break;case"#":prefixBaseX=!0}if(minWidth=minWidth?"*"===minWidth?+a[i++]:"*"==minWidth.charAt(0)?+a[minWidth.slice(1,-1)]:+minWidth:0,0>minWidth&&(minWidth=-minWidth,leftJustify=!0),!isFinite(minWidth))throw new Error("sprintf: (minimum-)width must be finite");switch(precision=precision?"*"===precision?+a[i++]:"*"==precision.charAt(0)?+a[precision.slice(1,-1)]:+precision:"fFeE".indexOf(type)>-1?6:"d"===type?0:void 0,value=valueIndex?a[valueIndex.slice(0,-1)]:a[i++],type){case"s":return formatString(String(value),leftJustify,minWidth,precision,zeroPad,customPadChar);case"c":return formatString(String.fromCharCode(+value),leftJustify,minWidth,precision,zeroPad);case"b":return formatBaseX(value,2,prefixBaseX,leftJustify,minWidth,precision,zeroPad);case"o":return formatBaseX(value,8,prefixBaseX,leftJustify,minWidth,precision,zeroPad);case"x":return formatBaseX(value,16,prefixBaseX,leftJustify,minWidth,precision,zeroPad);case"X":return formatBaseX(value,16,prefixBaseX,leftJustify,minWidth,precision,zeroPad).toUpperCase();case"u":return formatBaseX(value,10,prefixBaseX,leftJustify,minWidth,precision,zeroPad);case"i":case"d":return number=+value||0,number=Math.round(number-number%1),prefix=0>number?"-":positivePrefix,value=prefix+pad(String(Math.abs(number)),precision,"0",!1),justify(value,prefix,leftJustify,minWidth,zeroPad);case"e":case"E":case"f":case"F":case"g":case"G":return number=+value,prefix=0>number?"-":positivePrefix,method=["toExponential","toFixed","toPrecision"]["efg".indexOf(type.toLowerCase())],textTransform=["toString","toUpperCase"]["eEfFgG".indexOf(type)%2],value=prefix+Math.abs(number)[method](precision),justify(value,prefix,leftJustify,minWidth,zeroPad)[textTransform]();default:return substring}};return format.replace(regex,doFormat)}function strtr(str,from,to){var fr="",i=0,j=0,lenStr=0,lenFrom=0,tmpStrictForIn=!1,fromTypeStr="",toTypeStr="",istr="",tmpFrom=[],tmpTo=[],ret="",match=!1;if("object"==typeof from){tmpStrictForIn=this.ini_set("phpjs.strictForIn",!1),from=this.krsort(from),this.ini_set("phpjs.strictForIn",tmpStrictForIn);for(fr in from)from.hasOwnProperty(fr)&&(tmpFrom.push(fr),tmpTo.push(from[fr]));from=tmpFrom,to=tmpTo}for(lenStr=str.length,lenFrom=from.length,fromTypeStr="string"==typeof from,toTypeStr="string"==typeof to,i=0;lenStr>i;i++){if(match=!1,fromTypeStr){for(istr=str.charAt(i),j=0;lenFrom>j;j++)if(istr==from.charAt(j)){match=!0;break}}else for(j=0;lenFrom>j;j++)if(str.substr(i,from[j].length)==from[j]){match=!0,i=i+from[j].length-1;break}ret+=match?toTypeStr?to.charAt(j):to[j]:str.charAt(i)}return ret}
