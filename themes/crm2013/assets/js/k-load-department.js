/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-07-04 14:39:33
 * @version $Id$
 */
jQuery(function($) {
    if (typeof History != "undefined") {
        $(document).on("click", ".pagination a", function() {
            var url = $(this).attr("href").split("?"),
                params = $.deparam.querystring("?" + (url[1] || ""));
            delete params["ajax"];
            delete params["q"];
            window.History.pushState(null, document.title, decodeURIComponent($.param.querystring(url[0], params)));
        });
    }
    var temps = {
        worker: doT.template(document.getElementById("data-worker").innerHTML),
        viewworker: doT.template(document.getElementById("view-worker").innerHTML),
        price: doT.template(document.getElementById("data-price").innerHTML),
        viewprice: doT.template(document.getElementById("view-price").innerHTML),
    }, setView = function(data) {
            if (typeof data['actionUrl'] != 'undefined') {
                data.actionUrl = undefined;;
            }
            if (typeof data['json'] != 'undefined') {
                data.json = undefined;;
            }
            var json = JSON.stringify(data);
            data.json = json.replace(/"/g, "&quot;"),
            data.actionUrl = actionUrl + "?" + ["action=del" + data.type, "itemid=" + data.id].join("&");
            var temp = temps["view" + data.type](data);
            $("#" + data.id).replaceWith(temp);
        }, setEdit = function(pdata) {
            var data = JSON.parse(pdata),
                temp = temps[data.type](data);
            $("#" + data.id).replaceWith(temp);
            obj = $("#" + data.id);
            obj.find(".icon-ban-circle").on("click", function() {
                setView(data);
            })
            obj.find(".icon-ok").on("click", function() {
                var el = false,
                    countEq = 0,
                    post = [];
                obj.find("input[required]").removeClass("error").each(function() {
                    var t = $(this),
                        _name = t.attr("name"),
                        val = t.val();
                    if (val == "") {
                        t.addClass("error");
                        if (!el) {
                            el = t;
                        }
                    } else if (val == data[_name]) {
                        countEq++;
                    }
                    post.push("m[" + _name + "]=" + val);
                });
                if (el) {
                    el.focus();
                } else if (countEq == data.nums) {
                    setView(data);
                } else {
                    data.actionUrl = actionUrl + "?" + ["action=save" + data.type, "itemid=" + data.id].join("&");
                    setSave(data, post.join("&"));
                }
            })
        }, setSave = function(data, post) {
            $.ajax({
                type: "POST",
                url: data.actionUrl,
                data: post,
                dataType: "json",
                success: function(json) {
                    if (typeof json.error != 'undefined') {
                        notify_e(json.error);
                    } else if (typeof json.data != 'undefined') {
                        for (var i in json.data) {
                            data[i] = json.data[i];
                        }
                        setView(data);
                    }
                }
            });
        }
    $(document).on("submit", ".list-form", function(event) {
        event.preventDefault();
        var t = $(this),
            action = t.attr("to-view"),
            data = {
                "data": t.serialize(),
                "url": t.attr("action")
            };
        if (typeof History != "undefined") {
            _turl = decodeURIComponent($.param.querystring(data.url, data.data));
            window.History.pushState(null, document.title, _turl);
        }
        $.fn.yiiListView.update(action, data);
    }).on("click", ".btn-edit", function(event) {
        setEdit($(this).attr("data-json"));
    });
})