/**
 * 弹出文章列表，选择一篇或多篇文章
 * 此插件依赖于faycms/admin/common.js, faycms/system.js, fancybox
 */
;(function($){
    $.fn.selectPost = function(params){
        params = params || {};
        var settings = {
            'pageSize': '10',//单页显示文章数
            'onSelect': function(element){},//选取文章事件，参数为被点击元素
            'onDataLoaded': function(){}//每次数据被加载都会回调此函数
        };
        settings = $.extend(settings, params);
        
        var selectPost = function(element){
            this.init();
            var _this = this;

            element.attr('data-src', '#select-post-dialog');
            common.loadFancybox(function(){
                element.fancybox({
                    'touch': false,
                    'onComplete': function(){
                        _this.loadData();
                    }
                });
            });
        };

        selectPost.prototype = {
            /**
             * 初始化html
             */
            'init': function(){
                //初始化隐藏div，不会重复初始化
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
                                            '<input name="keywords" type="text" value="" class="form-control w200" placeholder="搜索关键词" />',
                                            ' | ',
                                            '<span id="select-post-search-form-cat-container"></span>',
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
                                            '<a class="btn btn-sm" id="select-post-search-form-submit-ajax" href="javascript:" title="查询">查询</a>',
                                        '</div>',
                                    '</form>',
                                    '<table class="inbox-table">',
                                        '<thead>',
                                            '<tr>',
                                                '<th class="w70">ID</th>',
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
                    
                    //获取分类下拉框
                    this.getCatSelect();
                    //初始化一下时间选择插件
                    common.datepicker();
                    //绑定基础事件
                    this.events();
                }
            },
            /**
             * 获取文章列表
             * @param page
             */
            'loadData': function(page){
                page = page || 1;
                var $selectPostDialog = $('#select-post-dialog');
                $selectPostDialog.block({
                    'zindex': 120000
                });

                $.ajax({
                    'type': 'GET',
                    'url': system.url('cms/admin/post/list', {
                        'page': page,
                        'page_size': settings.pageSize
                    }),
                    'data': $('#select-post-search-form').serialize(),
                    'dataType': 'json',
                    'cache': false,
                    'success': function(resp){
                        $selectPostDialog.unblock();
                        if(resp.status){
                            var $tbody = $selectPostDialog.find('table tbody');
                            //清空原数据
                            $tbody.html('');

                            //插入新数据
                            $.each(resp.data.posts, function(i, data){
                                $tbody.append([
                                    '<tr>',
                                        '<td>', data.id, '</td>',
                                        '<td>', system.encode(data.title), '</td>',
                                        '<td>', system.encode(data.cat_title), '</td>',
                                        '<td><a href="javascript:" class="select-single-post" data-id="', data.id, '">选取</a></td>',
                                    '<tr>'
                                ].join(''));
                            });
                            
                            //绑定事件
                            $tbody.find('.select-single-post').on('click', function(){
                                settings.onSelect($(this));
                            });
                            
                            //分页条
                            common.showPager('select-post-list-pager', resp.data.pager);
                            
                            settings.onDataLoaded();
                        }else{
                            common.alert(resp.message);
                        }
                    }
                });
            },
            /**
             * 获取分类下拉框
             */
            'getCatSelect': function(){
                $.ajax({
                    'type': 'GET',
                    'url': system.url('cms/admin/post/get-cats'),
                    'data': {'format': 'html'},
                    'success': function(resp){
                        $('#select-post-search-form-cat-container').html(resp)
                            //插一个所有分类
                            .find('select').prepend('<option value="">--所有分类--</option>')
                            .val('');
                    }
                });
            },
            /**
             * 绑定一些基础事件，一旦dialog被初始化就不需要再重复绑定了
             */
            'events': function(){
                //分页事件
                $('#select-post-list-pager').on('click', 'a.page-numbers', function(){
                    var page = $(this).attr('data-page');
                    if(page){
                        selectPost.prototype.loadData(page);
                    }
                }).on('keydown', '.pager-input', function(event){
                    if(event.keyCode == 13 || event.keyCode == 108){
                        selectPost.prototype.loadData($('#select-post-list-pager').find('.pager-input').val());
                        return false;
                    }
                });
                
                //搜索事件
                $('#select-post-dialog').on('click', '#select-post-search-form-submit-ajax', function(){
                    selectPost.prototype.loadData();
                    return false;
                });
            }
        };
        
        return new selectPost(this, params);
    }
})(jQuery);