jQuery(function($) {
    var _printHtml = function(html){
        html = html.replace(/itable/ig, 'list');
        printHtml(html);
    }
    $(document).on('click', '.action-fold', function() {
        $(this).parents('.itable').eq(0).toggleClass('action-active');
    }).on('click', '.action-print', function() {
        var html = $(this).parents('.div-over').eq(0).html();
        _printHtml(html);
    });
    $('.ibtn-print').on('click', function() {
        var html = $('#wrapper').html();
        _printHtml(html);
    });
});