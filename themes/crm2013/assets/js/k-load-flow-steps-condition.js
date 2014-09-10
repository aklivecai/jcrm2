/**
 *
 * @authors aklivecai (aklivecai@gmail.com)
 * @date    2014-09-04 21:22:58
 * @version $Id$
 */
+ function($) {
    'use strict';
    var BetterListModel = function(list, types, files) {
        var self = this;
        self.files = ko.observableArray(files);
        self.types = ko.observableArray(types);
        self.allItems = ko.observableArray(list); // Initial items
        self.selectedItems = ko.observableArray(); // Initial selection
        self.type = ko.observable(true);
        self.file = ko.observable(false);
        self.val = ko.observable('');
        self.addItem = function() {
            var str = sprintf('[%s]  %s  %s', self.file().value, self.type().value, self.val());
            //<em><span class="cnoa_color_blue">%s</span><span class="cnoa_color_green">%s</span><span class="cnoa_color_red">%s</span></em>
            if (self.val() != "") {
                // Prevent blanks and duplicates
                var temp = [];
                temp.push('StepCondition[field_id]=' + self.file().id);
                temp.push('StepCondition[type]=' + self.type().id);
                temp.push('StepCondition[value]=' + self.val());
                iAjax({
                    url: postUrl + '&ajax=add',
                    data: temp.join('&'),
                    success: function(result) {
                        if (result.status == 1) {
                            self.allItems.push({
                                id: result.info,
                                value: str
                            });
                            self.val("");
                        } else {
                            msgShow(result.info);
                        }
                    }
                });
            } else {
                self.val("");
            }
        };
        self.isAdd = ko.computed(function() {
            return self.type() && self.file() && self.val().length > 0;
        });
        self.removeSelected = function() {
            // self.allItems.removeAll();
            var list = self.selectedItems(),
                temp = [];
            $.each(list, function() {
                temp.push('ids[]=' + this.id);
            });
            iAjax({
                url: postUrl + '&ajax=del',
                data: temp.join('&'),
                success: function(result) {
                    if (result.status == 1) {
                        self.allItems.removeAll(list);
                        self.selectedItems([]); // Clear selection
                    } else {
                        msgShow(result.info);
                    }
                }
            });
        };
    };
    ko.applyBindings(new BetterListModel(msg.list, msg.types, msg.files), document.getElementById('wap-condition'));
}(jQuery);