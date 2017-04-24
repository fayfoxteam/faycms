/**
 * 弹出文章列表，选择一篇或多篇文章
 * 此插件依赖于faycms/admin/common.js
 */
;(function($){
    $.fn.selectPost = function(params){
        var selectPost = function(element, params){
            params = params || {};
            var settings = {
                'pageSize': '10'//单页显示文章数
            };

            $.each(params, function(i, n){
                settings[i] = n;
            });

            //初始化隐藏div
            if(!$('#select-post-dialog').length){
                $('body').append([
                    '<div class="hide">',
                        '<div id="select-post-dialog" class="dialog">',
                            '<div class="dialog-content w800">',
                                '<form id="select-post-search-form" method="get" class="form-inline">',
                                    '<div class="mb5">',
                                        '<select name="keywords_field" class="form-control">',
                                            '<option value="title">文章标题</option>',
                                            '<option value="id">文章ID</option>',
                                            '<option value="user_id">作者ID</option>',
                                        '</select>',
                                        ' ',
                                        '<input name="keywords" type="text" value="" class="form-control w200" placeholder="搜索标题或文章ID" />',
                                        ' | ',
                                        '<select name="cat_id" class="form-control">',
                                        '</select>',
                                    '</div>',
                                    '<div class="mb5">',
                                        '<select name="time_field" class="form-control">',
                                            '<option value="publish_time">发布时间</option>',
                                            '<option value="create_time">创建时间</option>',
                                            '<option value="update_time">更新时间</option>',
                                        '</select>',
                                        ' ',
                                        '<input name="start_time" type="text" value="" data-rule="datetime" data-label="开始时间" class="form-control datetimepicker" />',
                                        ' - ',
                                        '<input name="end_time" type="text" value="" data-rule="datetime" data-label="结束时间" class="form-control datetimepicker" />',
                                        ' ',
                                        '<a class="btn btn-sm" id="final-form-submit" href="javascript:" title="查询">查询</a>',
                                    '</div>',
                                '</form>',
                                '<table class="inbox-table">',
                                    '<thead>',
                                        '<tr>',
                                        '<th>标题</th>',
                                        '<th>分类</th>',
                                        '<th>操作</th>',
                                        '</tr>',
                                    '</thead>',
                                    '<tbody></tbody>',
                                '</table>',
                                '<div id="select-post-list-pager" class="pager"></div>',
                            '</div>',
                        '</div>',
                    '</div>'
                ].join(''));
            }

            element.attr('data-src', '#select-post-dialog');
            common.loadFancybox(function(){
                element.fancybox({
                    'onComplete': function(instance, slide){
                        
                    }
                });
            });
        };

        selectPost.prototype = {
            'init': function(){
                
            }
        };
        
        return new selectPost(this, params);
    }
})(jQuery);