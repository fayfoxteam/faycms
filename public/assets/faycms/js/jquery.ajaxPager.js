/**
 * ajax获取下一页，调用元素为加载下一页按钮
 * 适用于复合以下条件的场景：
 * 1.本身每页都是完整的html，可以单独访问（有利于SEO优化）
 * 2.网页中包含下一页链接
 * 3.以ajax的形式加载下一页，将列表部分抠出来加到文章列表当前页后面
 */
;(function($){
    /**
     * @param nextPageSelector 下一页链接选择器
     * @param listContainerSelector 文章列表外层元素选择器
     * @param params
     * @returns {jQuery.ajaxPager}
     */
    $.fn.ajaxPager = function(nextPageSelector, listContainerSelector, params){
        params = params || {};
        var settings = {
            //开始加载下一页前执行
            'beforeLoaded': function(element){
                element.text('加载中...');
            },
            //下一页加载完成时执行
            'afterLoaded': function(element, hasMore){
                if(hasMore){
                    element.text('加载更多...');
                }else{
                    element.text('没有啦');
                }
            },
            //当没数据的时候
            'onNoData': function(element){
                element.text('没有啦');
            }
        };
        settings = $.extend(settings, params);

        var ajaxPager = function(element){
            ajaxPager.prototype.nextPageUrl = $(nextPageSelector).attr('href');
            
            var _this = this;
            element.on('click', function(){
                _this.loadNextPage($(this));
            })
        };

        ajaxPager.prototype = {
            /**
             * 正在加载中
             */
            'loading': false,
            /**
             * 下一页链接地址
             */
            'nextPageUrl': '',
            /**
             * 加载下一页
             */
            'loadNextPage': function(element){
                if(!ajaxPager.prototype.nextPageUrl){
                    //没有下一页了
                    settings.onNoData(element);
                    return false;
                }
                
                if(ajaxPager.prototype.loading){
                    //已经在加载中，避免重复发送ajax
                    return false;
                }

                ajaxPager.prototype.loading = true;
                settings.beforeLoaded(element);

                $.ajax({
                    'type': 'GET',
                    'url': ajaxPager.prototype.nextPageUrl,
                    'success': function(resp){
                        ajaxPager.prototype.loading = false;
                        $(listContainerSelector).append($(resp).find(listContainerSelector).html());
                        ajaxPager.prototype.nextPageUrl = $(resp).find(nextPageSelector).attr('href');

                        settings.afterLoaded(element, !!ajaxPager.prototype.nextPageUrl);
                    }
                });
            }
        };

        return new ajaxPager(this);
    }
})(jQuery);