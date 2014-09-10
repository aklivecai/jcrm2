$(document).ready(function(){

    $(".faq .item .title").click(function(){
        var text = $(this).parent('.item').find('.text');
        
        if(text.is(':visible'))
            text.fadeOut();
        else
            text.fadeIn();                
    });

    $("#faqSearch").click(function(){
        var keyword = $(".faqSearchKeyword").val();
        
        if(keyword.length >= 1){
            $(".faq").find('.text').fadeOut();
            $("#faqSearchResult").html("");
            $(".faq").removeHighlight();
            
            var items = $(".faq .item:containsi('"+keyword+"')").find('.text');
            items.highlight(keyword);
            items.fadeIn();            
            $("#faqSearchResult").html("找到 "+items.length);            
            
        }else
            $("#faqSearchResult").html("<span style='color: red;'>最少 1 个字</span>");
         
    });
    
    $("#faqListController a").click(function(){
        var open = $(this).attr('href');
        $(open).find('.text').fadeIn();
    });
    
    $("#faqOpenAll").click(function(){
        $(".faq").find('.text').fadeIn();
    });
    
    $("#faqCloseAll").click(function(){
        $(".faq").find('.text').fadeOut();
    });
    
    $("#faqRemoveHighlights").click(function(){
        $(".faq").removeHighlight();
    });
    
    
    
});
