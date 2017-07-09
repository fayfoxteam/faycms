var cat = {
    'getCatUrl': system.url('cms/admin/category/get'),
    'editCat':function(){
        common.loadFancybox(function(){
            $('.edit-cat-link').fancybox({
                'onComplete': function(instance, slide){
                    $('#edit-cat-form').find('.submit-loading').remove();
                    var $editCatDialog = $('#edit-cat-dialog');
                    $editCatDialog.block({
                        'zindex': 120000
                    });
                    $.ajax({
                        type: 'GET',
                        url: cat.getCatUrl,
                        data: {'id': slide.opts.$orig.attr('data-id')},
                        dataType: 'json',
                        cache: false,
                        success: function(resp){
                            $editCatDialog.unblock();
                            if(resp.status){
                                $('#edit-cat-title').text(resp.data.cat.title);
                                $editCatDialog.find("input[name='id']").val(resp.data.cat.id);
                                $editCatDialog.find("input[name='title']").val(resp.data.cat.title);
                                $editCatDialog.find("input[name='alias']").val(resp.data.cat.alias);
                                $editCatDialog.find("input[name='alias']").attr('data-ajax-params', '{id:'+resp.data.cat.id+'}');
                                
                                if(resp.data.cat.is_nav == 1){
                                    $editCatDialog.find("input[name='is_nav']").prop('checked', 'checked');
                                }else{
                                    $editCatDialog.find("input[name='is_nav']").prop('checked', false);
                                }
                                if(resp.data.cat.file_id != 0){
                                    $editCatDialog.find('.upload-preview-container').html([
                                        '<input type="hidden" name="file_id" value="', resp.data.cat.file_id, '">',
                                        '<a href="javascript:" data-fancybox="image" data-type="image" data-src="', system.url('file/pic', {
                                            't':1,
                                            'f':resp.data.cat.file_id
                                        }), '" class="mask ib">',
                                            '<img src="', system.url('file/pic', {
                                                't':2,
                                                'f':resp.data.cat.file_id
                                            }), '" />',
                                        '</a>',
                                        '<br>',
                                        '<a href="javascript:;" class="remove-file_id-link">移除插图</a>'
                                    ].join(''));
                                }else{
                                    $editCatDialog.find('.upload-preview-container').html('');
                                }
                                $editCatDialog.find("textarea[name='description']").val(resp.data.cat.description);
                                autosize.update($editCatDialog.find("textarea[name='description']"));
                                $editCatDialog.find("input[name='sort']").val(resp.data.cat.sort);
                                $editCatDialog.find("input[name='seo_title']").val(resp.data.cat.seo_title);
                                $editCatDialog.find("input[name='seo_keywords']").val(resp.data.cat.seo_keywords);
                                $editCatDialog.find("textarea[name='seo_description']").val(resp.data.cat.seo_description);
                                $editCatDialog.find("select[name='parent']").val(resp.data.cat.parent);
                                //父节点不能被挂载到其子节点上
                                $editCatDialog.find("select[name='parent'] option").attr('disabled', false).each(function(){
                                    if(system.inArray($(this).attr("value"), resp.data.children) || $(this).attr("value") == resp.data.cat.id){
                                        $(this).attr('disabled', 'disabled');
                                    }
                                });
                            }else{
                                common.alert(resp.message);
                            }
                        }
                    });
                }
            });
        });
    },
    'createCat':function(){
        common.loadFancybox(function(){
            $('.create-cat-link').fancybox({
                'beforeLoad': function(instance, slide){
                    $('#create-cat-parent').text(slide.opts.$orig.attr('data-title'));
                    $("#create-cat-dialog").find("input[name='parent']").val(slide.opts.$orig.attr('data-id'));
                }
            });
        });
    },
    'toggleSEOInfo':function(){
        $('.toggle-seo-info').click(function(){
            $(this).parent().parent().nextAll('.toggle').toggle();
        });
    },
    'isNav':function(){
        $('.tree-container').on('click', '.is-nav-link', function(){
            var o = this;
            $(this).find('span').hide().after('<img src="'+system.assets('images/throbber.gif')+'" />');
            $.ajax({
                type: 'GET',
                url: system.url('cms/admin/category/set-is-nav'),
                data: {
                    'id':$(this).attr('data-id'),
                    'is_nav':$(this).find('span').hasClass('tick-circle') ? 0 : 1
                },
                dataType: 'json',
                cache: false,
                success: function(resp){
                    if(resp.status){
                        $(o).find('span').removeClass('tick-circle')
                            .removeClass('cross-circle')
                            .addClass(resp.data.is_nav == 1 ? 'tick-circle' : 'cross-circle')
                            .show()
                            .next('img').remove();
                    }else{
                        common.alert(resp.message);
                    }
                }
            });
        });
    },
    'events':function(){
        $('.tree-container').on('click', '.leaf-title.parent', function(){
            $li = $(this).parent().parent();
            if($li.hasClass('close')){
                $li.children('ul').slideDown(function(){
                    $li.removeClass('close');
                });
            }else{
                $li.children('ul').slideUp(function(){
                    $li.addClass('close');
                });
            }
        });
        
        $('.leaf-container').hover(function(){
            $(this).addClass('hover');
        }, function(){
            $(this).removeClass('hover');
        });
    },
    'init':function(){
        this.editCat();
        this.createCat();
        this.toggleSEOInfo();
        this.isNav();
        this.events();
    }
};