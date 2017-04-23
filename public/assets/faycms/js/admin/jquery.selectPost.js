/**
 * 弹出文章列表，选择一篇或多篇文章
 */
;(function($){
    $.extend({
        'selectPost': function(params){
            var settings = {
                'type': 'multiple',//multiple: 多选；single: 单选
                'pageSize': '10'//单页显示文章数
            };

            $.each(params, function(i, n){
                settings[i] = n;
            });
        }
    });
})(jQuery);