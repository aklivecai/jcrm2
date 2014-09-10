$(document).ready(function(){
    /* Check cookies */
    var takAjax = function(aar){
        var data = [];
        for (var obj in aar) {
           data.push('Setting['+obj+']='+aar[obj]);
        };
        data = data.join('&');
     $.ajax({
            type: "POST",
            url:createUrl('setting/create'),
            data:data,
            async: false,
            error: function(request) {
                console.log(request);
                // alert("系统异常!");
            },
            success: function(data) {
                if (data!='') {
                    // alert(data);
                };
            }
        });
    }         
        var wrapper = $('.wrapper');
        /*theme style*/
        var tStyle = $.cookies.get('themeSettings_style');
        if($('.wrapper').hasClass(tStyle)&&null != tStyle){
            if($('.wrapper').hasClass('fixed'))
                $(".wrapper").attr('class','').addClass('wrapper fixed');
            else
                $(".wrapper").attr('class','').addClass('wrapper');         
            
            $('.settings .styleExample').removeClass('active');
            $(".wrapper").addClass(tStyle);        
            $('.settings .styleExample[data-style="'+tStyle+'"]').addClass('active');
        }          
        /*fixed*/
        var tFixed = $.cookies.get('themeSettings_fixed');
        if(wrapper.attr('class')!='wrapper'&&null != tFixed){
            if (!wrapper.hasClass(tFixed)) {
                 wrapper.addClass('fixed');
            };           
            $(".settings input[name=settings_fixed]").attr('checked',true).parent('span').addClass('checked');
        }
        
        /*menu*/
        var tMenu = $.cookies.get('themeSettings_menu');
        if(null != tMenu){
            if(null != tMenu){                            
                $(".menu").addClass('hidden').hide();
                $(".header_menu li.list_icon").show();
                $(".content").addClass('wide');      
                $(".settings input[name=settings_menu]").attr('checked',true).parent('span').addClass('checked');
            }
        }
        /*bg*/
        var tBg = $.cookies.get('themeSettings_bg')
        , body = $('body')
        ;
        if(null != tBg || body.attr('class')!=''){
            if (!body.hasClass(tBg)) {
                body.removeAttr('class').addClass(tBg);
            };
            $('.settings .bgExample').removeClass('active');
            $('.settings .bgExample[data-style="'+tBg+'"]').addClass('active');
        }
      
    
    /* Check cookies */
    
    $(".link_themeSettings").click(function(){
        
        if($("#themeSettings").is(':visible')){
            $("#themeSettings").fadeOut(200);
            $("#themeSettings").find(".checker").hide();
        }else{
            $("#themeSettings").fadeIn(300);        
            $("#themeSettings").find(".checker").show();
        }
        
       return false;
       
    });
    
    $(".settings input[name=settings_fixed]").change(function(){
        var _value = 0;
        if($(this).is(':checked')){
            $(".wrapper").addClass('fixed');
             $.cookies.set('themeSettings_fixed','1');
             _value = 1;
        }else{
            $(".wrapper").removeClass('fixed');
            $.cookies.set('themeSettings_fixed',null);
        }
        takAjax({'item_key':'themeSettings_fixed','item_value':_value}); 
        
    });
    
    $(".settings input[name=settings_menu]").change(function(){
        var _value = 0;
        if($(this).is(':checked')){
            $(".menu").addClass('hidden').hide();
            $(".header_menu li.list_icon").show();
            $(".content").addClass('wide');
            $.cookies.set('themeSettings_menu','1');
            _value = 1;
        }else{
            $(".menu").removeClass('hidden').removeAttr('style');
            $(".header_menu li.list_icon").hide();
            $(".content").removeClass('wide');
            $("body > .modal-backdrop").remove();
            $.cookies.set('themeSettings_menu',null);
        }
        takAjax({'item_key':'themeSettings_menu','item_value':_value});
    });    
    
    $(".settings .bgExample").click(function(){
        var cls = $(this).attr('data-style');        
        
        $('body').removeAttr('class');
        $('.settings .bgExample').removeClass('active');

         takAjax({'item_key':'themeSettings_bg','item_value':cls});

        if(cls != ''){
            $('body').addClass(cls);
        }else{
            cls = null;
        }
        $.cookies.set('themeSettings_bg',cls);
        $(this).addClass('active');

         takAjax({'item_key':'themeSettings_bg','item_value':null!=cls?cls:'0'}); 
        return false;
    });

    $(".settings .styleExample").click(function(){
        var cls = $(this).attr('data-style'); 
        var _value = 0;
        if($('.wrapper').hasClass('fixed'))
            $(".wrapper").attr('class','').addClass('wrapper fixed');
        else
            $(".wrapper").attr('class','').addClass('wrapper');
            
        $('.settings .styleExample').removeClass('active');
        
        if(cls != ''){
            $(".wrapper").addClass(cls);
            $(this).addClass('active');
            $.cookies.set('themeSettings_style',cls);
            _value = cls;
        }else
            $.cookies.set('themeSettings_style',null);
        takAjax({'item_key':'themeSettings_style','item_value':_value});
        return false;
    });
    
});