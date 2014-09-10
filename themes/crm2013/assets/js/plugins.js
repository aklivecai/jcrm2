jQuery(function($){
        /*## 颜色插件*/
        /*
        var localization = $.spectrum.localization["cn"] = {
            cancelText: "取消",
            chooseText: "选择",
            preferredFormat:'name' //格式
        };
        $.extend($.fn.spectrum.defaults, localization);
        $(".color").spectrum({
            showPaletteOnly: true,
            showPalette:true,
            palette: [
                ['black', 'white', 'blanchedalmond',
                '#FF8000', '#488026'],
                ['red', 'yellow', 'green', 'blue', 'violet']
            ]
        });
*/

        /*## 日历*/
        $.datepicker.regional['zh-CN'] = {
                closeText: '关闭',
                prevText: '<上月',
                nextText: '下月>',
                currentText: '今天',
                monthNames: ['一月','二月','三月','四月','五月','六月',
                '七月','八月','九月','十月','十一月','十二月'],
                monthNamesShort: ['一','二','三','四','五','六',
                '七','八','九','十','十一','十二'],
                dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
                dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
                dayNamesMin: ['日','一','二','三','四','五','六'],
                weekHeader: '周',
                dateFormat: 'yy-mm-dd',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: true,
                yearSuffix: '年'};
        $.datepicker.setDefaults($.datepicker.regional['zh-CN']);

    /* LEFT SIDE DATEPICKER */
    $("#menuDatepicker").datepicker();
    /* UI elements datepicker */        
    $("#Datepicker").datepicker();    
    
    /* CALENDAR */
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    var calendar = $('.fc').fullCalendar({
            header: {		
                left: 'prev,next today',
                left:  'prev,today,next',//nextYear,prevYear
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
                ,right: 'month,basicWeek,basicDay,agendaWeek,agendaDay'

            },
buttonText:{
    prevYear: '去年',
    nextYear: '明年',
    today:    '今天',
    month:    '月',
    week:     '周',
    day:      '日'
},
// 15.默认显示的视图，注意引号
defaultView:'month',
// 22.指定默认的时间格式
timeFormat:'HH(:MM)',
// 标题格式化
titleFormat:{
    month: 'MMMM yyyy',                             // September 2009
    week: "MMM d[ yyyy]{ '&#8212;'[ MMM] d yyyy}", // Sep 7 - 13 2009
    day: 'dddd, yyyy-MMM-d '                  // Tuesday, Sep 8, 2009
},      
monthNames:['一月','二月', '三月', '四月', '五月', '六月', '七月','八月', '九月', '十月', '十一月', '十二月'],     
 // 月名字的简写
monthNamesShort:['一月','二月', '三月', '四月', '五月', '六月', '七月','八月', '九月', '十月', '十一月', '十二月'],     

dayNames:['星期日', '星期一', '星期二', '星期三','星期四', '星期五', '星期六'],
// 星期名字的缩写
dayNamesShort:['日', '一', '二', '三', '四', '五', '六'],
// 31.日程默认为全天日程
allDayDefault:true,
// 43.是否可以拖拽和改变大小
editable:true,
// 44.禁止拖拽和改变大小
disableDragging:false,
disableResizing:false,
// 45.如果拖拽不成功，多久回复原状,单位是毫秒
dragRevertDuration:500,  
// 46.拖拽不透明度
dragOpacity:{
agenda:.5, //对于agenda试图
'':1.0   //其他视图
},
editable: true,
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
                    var title = prompt('行程的名字:','');
                    if (title != '' && title != null){
                            var postData = [];
                            postData.push('Events[subject]='+title);
                            postData.push('Events[start_time]='+Math.round(start.getTime() / 1000));
                            postData.push('Events[end_time]='+Math.round(end.getTime() / 1000));
                            postData.push('getItemid=1');
                         $.ajax({
                                type: "POST",
                                url:createUrl('events/create'),
                                data:postData.join('&'),
                                async: false,
                                error: function(request) {
                                    log(request);
                                    // alert("系统异常!");
                                },
                                success: function(data) {
                                    if (data!='') {
                                     var _url = createUrl('events/view',['id='+data]) 
                                    calendar.fullCalendar('renderEvent',
                                        {
                                            title: title,
                                            start: start,
                                            end: end,
                                            url: _url,
                                            allDay: allDay
                                        },
                                        true // make the event "stick"
                                    );

                                        // calendar.resetElement();
                                    };
                                }
                            });                            
                    }
                    calendar.fullCalendar('unselect');
                    window.calendar = calendar;
            }
// 当点击某一个事件时触发此操作            
,xxxxeventClick: function(calEvent, jsEvent, view) {
        var str = '';
        str+=('Event: ' + calEvent.title);
        str+=('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
        str+=('View: ' + view.name);
        // console.log(str);
        // console.log(calEvent);
        // change the border color just for fun
        $(this).css('border-color', 'red');
        $('#calendar').fullCalendar('updateEvent', event);

    }      

// 当开始读取的时候是true,当读取完成是false
,events: createUrl('events/list')   
,eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
        log(
            event.title + " was moved " +
            dayDelta + " days and " +
            minuteDelta + " minutes."
        );
        // log(this);

        if (allDay) {
            // console.log("Event is now all-day");
        }else{
            // console.log("Event has a time-of-day");
        }

        if (!confirm("是否确认移动?")) {
               revertFunc();
               return ;
                            var postData = [];
                            postData.push('Events[subject]='+title);
                            postData.push('Events[start_time]='+Math.round(start.getTime() / 1000));
                            postData.push('Events[end_time]='+Math.round(end.getTime() / 1000));
                            postData.push('getItemid=1');
                        $.ajax({
                                type: "POST",
                                url:createUrl('events/update'),
                                data:postData.join('&'),
                                async: false,
                                error: function(request) {
                                    console.log(request);
                                    // alert("系统异常!");
                                },
                                success: function(data) {
                                    if (data!='') {
                                     var _url = createUrl('events/view',['id='+data]) 
                                    calendar.fullCalendar('renderEvent',
                                        {
                                            title: title,
                                            start: start,
                                            end: end,
                                            url: _url,
                                            allDay: allDay
                                        },
                                        true // make the event "stick"
                                    );

                                        calendar.resetElement();
                                    };
                                }
                            });              
         
        }

    }
    });
 
    // CHECKBOXES AND RADIO
    $.uniform.defaults.fileButtonHtml = "浏览";
    $.uniform.defaults.fileDefaultHtml = "选择文件";    
    $(".row-form,.row-fluid,.dialog,.loginBox,.block,.block-fluid").find("input:checkbox, input:radio, input:file").not(".skip, input.ibtn").uniform();        
        
    // CUSTOM SCROLLING
        $(".scroll").mCustomScrollbar();

    // new selector case insensivity        
        $.expr[':'].containsi = function(a, i, m) {
            return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
        };        

    // TABS    
        $( ".tabs" ).tabs();        
        
        
    $('.ibtn').iButton({
         duration: 200                           // the speed of the animation
       , easing: "swing"                         // the easing animation to use
       , labelOn: "启用"                           // the text to show when toggled on
       , labelOff: "锁定"                         // the text to show when toggled off
       , resizeHandle: "auto"                    // determines if handle should be resized
       , resizeContainer: "auto"                 // determines if container should be resized
       , enableDrag: true                        // determines if we allow dragging
       , enableFx: true                          // determines if we show animation
       , allowRadioUncheck: false                // determine if a radio button should be able to
                                                 // be unchecked
       , clickOffset: 120                        // if millseconds between a mousedown & mouseup event this
                                                 // value, then considered a mouse click

       // define the class statements
       , className:         ""                   // an additional class name to attach to the main container
       , classContainer:    "ibutton-container"
       , classDisabled:     "ibutton-disabled"
       , classFocus:        "ibutton-focus"
       , classLabelOn:      "ibutton-label-on"
       , classLabelOff:     "ibutton-label-off"
       , classHandle:       "ibutton-handle"
       , classHandleMiddle: "ibutton-handle-middle"
       , classHandleRight:  "ibutton-handle-right"
       , classHandleActive: "ibutton-active-handle"
       , classPaddingLeft:  "ibutton-padding-left"
       , classPaddingRight: "ibutton-padding-right"

       // event handlers
       , init: null                              // callback that occurs when a iButton is initialized
       , change: null                            // callback that occurs when the button state is changed
       , click: null                             // callback that occurs when the button is clicked
       , disable: null                           // callback that occurs when the button is disabled/enabled
       , destroy: null            
    });
    
    // Scroll up plugin
     $.scrollUp({scrollText: '^'});
    // eof scroll up plugin   

    // 首页下 side 切换
    $(".accordion").accordion(); 
});


var notify = function(title, text){
    $.pnotify({title: title, text: text, opacity: .8, addclass: 'palert'});
}
,notify_s = function(title,text){
    $.pnotify({title: title, text: text, opacity: .8, type: 'success'});
}
, notify_i = function(title,text){
    $.pnotify({title: title, text: text, opacity: .8, type: 'info'});            
}
, notify_e = function(title,text){
    $.pnotify({title: title, text: text, opacity: .9, type: 'error'});            
}
, stack_bottomright = {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25}
, show_stack = function (title,text,type) {
    var opts = {
        title: title,
        text: text,
        addclass: "stack-bottomright",
        stack: stack_bottomright,
        opacity: .8,
        sticker: false,
         hide: true,
        type:type
        // icon: 'picon picon-network-wireless'
    };
    $.pnotify(opts);
}
;