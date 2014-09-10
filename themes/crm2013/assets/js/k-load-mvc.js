/**
 /k/GitHub/CRM/themes/crm2013/assets/js/k-load-mvc.js
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-04 21:17:01
 * @version $Id$
 */
var Line = function(list) {
    var self = this;
    self.obj = [];
    self.init = function(list) {
        var isNum = null,
            numeric = null,
            v = null;
        for (var i in list) {
            v = list[i];
            isNum = false;
            numeric = 0;
            self.obj.push(i);
            if (v == null) {
                v = '';
            } else if ((typeof v['num'] != 'undefined')) {
                isNum = true;
                numeric = v['num'];
                v = '';
            } else if (v != '' && (typeof v == 'number' || v.search(/^[\+\-]?\d*$/) == 0)) {
                isNum = true;
            }
            self[i] = ko.observable(v);
            // log(v);
            if (isNum) {
                self[i].extend({
                    numeric: numeric,
                    rateLimit: 800
                });
            };
        };
    };
    self.getObj = function() {
        var el = arguments.length == 1 ? arguments[0] : self.obj,
            result = {}, key = null;
        for (var i in el) {
            key = el[i];
            result[key] = self[key]();
        };
        return result;
    };
    self.load = function(obj) {
        for (var i in obj) {
            var fun = self[i],
                val = obj[i] == 'null' ? '' : obj[i];
            if (fun !== undefined) {
                fun.call(this, val);
            };
        }
    };
    if (arguments.length == 1 && typeof arguments[0] == 'object') {
        self.init(arguments[0]);
    };
},
    Lines = function(line, tags) {
        var self = this;
        self.lines = ko.observableArray(tags ? tags : []);
        self.line = line;
        self.add = function() {
            var temp = null;
            if (arguments.length == 1 && typeof arguments[0] == 'object') {
                temp = arguments[0];
            };
            temp = new self.line(temp);
            self.lines.push(temp);
            return temp;
        };
        self.clear = function() {
            self.lines.removeAll();
        };
        self.remove = function() {
            self.lines.remove(this);
        };
        self.getLines = function() {
            var s = self.lines();
            if (s.length > 0) {
                if (typeof s[0]['getObj'] == 'function') {
                    var _s = [];
                    for (var i = 0; i < s.length; i++) {
                        _s.push(s[i].getObj());
                    };
                    s = _s;
                };
            };
            return s;
        }
        self.getObj = function() {
            var el = arguments.length == 1 ? arguments[0] : self.obj,
                result = {};
            for (var i in el) {
                result[el[i]] = self[el[i]]();
            };
            result.dataItems = self.getLines();
            return result;
        };
    };
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