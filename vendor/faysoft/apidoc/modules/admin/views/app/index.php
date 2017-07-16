<?php
use fay\helpers\HtmlHelper;

/**
 * @var $apps array
 */
?>
<a href="javascript:" class="btn create-app-link" data-src="#create-app-dialog">新增应用</a>
<div class="dragsort-list2" id="app-list">
    <?php foreach($apps as $app){?>
        <div class="dragsort-item" data-id="<?php echo $app['id']?>">
            <a class="dragsort-rm" data-id="<?php echo $app['id']?>" href="javascript:"></a>
            <a class="dragsort-item-selector"></a>
            <div class="dragsort-item-container">
                <div class="cf">
                    <div class="col-3">
                        <strong class="mr5">名称：</strong><?php
                            echo HtmlHelper::encode($app['name'])
                        ?>
                        <div class="mt5 separate-actions"><?php
                            echo HtmlHelper::link(
                                '查看API',
                                array('apidoc/admin/api/index',
                                    array(
                                        'app_id'=>$app['id']
                                    )
                                ),
                                array(),
                                true
                            ),
                            HtmlHelper::link(
                                '新增API',
                                array('apidoc/admin/api/create',
                                    array(
                                        'app_id'=>$app['id']
                                    )
                                ),
                                array(),
                                true
                            ),
                            HtmlHelper::link(
                                '查看分类',
                                array('apidoc/admin/api-cat/index',
                                    array(
                                        'app_id'=>$app['id']
                                    )
                                ),
                                array(),
                                true
                            ),
                            HtmlHelper::link(
                                '编辑',
                                'javascript:',
                                array(
                                    'data-id'=>$app['id'],
                                    'data-src'=>'#edit-app-dialog',
                                    'class'=>'edit-app-link',
                                ),
                                true
                            ),
                            HtmlHelper::link(
                                '删除',
                                'javascript:',
                                array(
                                    'data-id'=>$app['id'],
                                    'class'=>'fc-red remove-app-link',
                                ),
                                true
                            );
                        ?></div>
                    </div>
                    <div class="col-2">
                        <strong class="mr5">登录可见：</strong><?php echo $app['need_login'] ? '<span class="fc-green">是</span>' : '否'?>
                    </div>
                    <div class="col-7">
                        <strong class="mr5">描述：</strong><?php echo $app['description'] ? HtmlHelper::encode($app['description']) : '无'?>
                    </div>
                </div>
            </div>
        </div>
    <?php }?>
</div>
<div class="hide">
    <div id="create-app-dialog" class="dialog">
        <div class="dialog-content w600">
            <h4>添加应用</h4>
            <?php echo F::form('create')->open(array('apidoc/admin/app/create'))?>
                <table class="form-table">
                    <tr>
                        <th class="adaption">名称<em class="require">*</em></th>
                        <td><?php echo F::form('create')->inputText('name', array(
                                'class'=>'form-control',
                            ))?></td>
                    </tr>
                    <tr>
                        <th class="adaption">登录可见<em class="require">*</em></th>
                        <td><?php
                            echo F::form('edit')->inputRadio('need_login', 1, array(
                                'label'=>'是',
                            ));
                            echo F::form('edit')->inputRadio('need_login', 0, array(
                                'label'=>'否',
                            ), true);
                            ?></td>
                    </tr>
                    <tr>
                        <th class="adaption">描述</th>
                        <td><?php echo F::form('create')->textarea('description', array(
                                'class'=>'form-control h60 autosize',
                            ))?></td>
                    </tr>
                    <tr>
                        <th class="adaption"></th>
                        <td><?php
                            echo F::form('create')->submitLink('添加应用', array(
                                'class'=>'btn mr10'
                            )),
                            HtmlHelper::link('取消', 'javascript:', array(
                                'class'=>'btn btn-grey fancybox-close',
                            ));
                        ?></td>
                    </tr>
                </table>
            <?php echo F::form('create')->close()?>
        </div>
    </div>
</div>
<div class="hide">
    <div id="edit-app-dialog" class="dialog">
        <div class="dialog-content w600">
            <h4>编辑应用</h4>
            <?php echo F::form('edit')->open(array('apidoc/admin/app/edit'))?>
            <input type="hidden" name="id" id="edit-app-id">
            <table class="form-table">
                <tr>
                    <th class="adaption">名称<em class="require">*</em></th>
                    <td><?php echo F::form('edit')->inputText('name', array(
                        'class'=>'form-control',
                    ))?></td>
                </tr>
                <tr>
                    <th class="adaption">登录可见<em class="require">*</em></th>
                    <td><?php
                        echo F::form('edit')->inputRadio('need_login', 1, array(
                            'label'=>'是',
                        ));
                        echo F::form('edit')->inputRadio('need_login', 0, array(
                            'label'=>'否',
                        ), true);
                    ?></td>
                </tr>
                <tr>
                    <th class="adaption">描述</th>
                    <td><?php echo F::form('edit')->textarea('description', array(
                            'class'=>'form-control h60 autosize',
                        ))?></td>
                </tr>
                <tr>
                    <th class="adaption"></th>
                    <td><?php
                        echo F::form('edit')->submitLink('编辑应用', array(
                            'class'=>'btn mr10'
                        )),
                        HtmlHelper::link('取消', 'javascript:', array(
                            'class'=>'btn btn-grey fancybox-close',
                        ));
                    ?></td>
                </tr>
            </table>
            <?php echo F::form('edit')->close()?>
        </div>
    </div>
</div>
<script>
var app = {
    'dragsort': function(){
        var $appList = $('#app-list');
        system.getScript(system.assets('js/jquery.dragsort-0.5.2.js'), function(){
            if(!$appList.find('.dragsort-item').length){
                //如果本来是空的，要先插一个元素进去，否则后加入的元素无法拖拽，应该是dragsort的bug
                $appList.append('<div class="dragsort-item hide remove-after-init"></div>');
            }
            $appList.dragsort({
                'itemSelector': 'div.dragsort-item',
                'dragSelector': '.dragsort-item-selector',
                'placeHolderTemplate': '<div class="dragsort-item holder"></div>',
                'dragEnd': function(){
                    //保存排序
                    var sort = [];
                    $appList.find('.dragsort-item').each(function(){
                        sort.push($(this).attr('data-id'));
                    });

                    $.ajax({
                        type: 'POST',
                        url: system.url('apidoc/admin/app/sort'),
                        data: {'sort[]': sort},
                        dataType: 'json',
                        cache: false,
                        success: function(resp){
                            if(resp.status){
                                common.notify(resp.message, 'success')
                            }else{
                                common.alert(resp.message);
                            }
                        }
                    });
                }
            });
            //删掉之前加入的隐藏元素
            $appList.find('.remove-after-init').remove();
        });
    },
    'createApp': function(){
        common.loadFancybox(function(){
            $('.create-app-link').fancybox();
        });
    },
    'editApp': function(){
        common.loadFancybox(function(){
            $('.edit-app-link').fancybox({
                'onComplete': function(instance, slide){
                    var $editAppDialog = $('#edit-app-dialog');
                    $editAppDialog.block({
                        'zindex': 120000
                    });
                    $.ajax({
                        type: 'GET',
                        url: system.url('apidoc/admin/app/get'),
                        data: {'id': slide.opts.$orig.attr('data-id')},
                        dataType: 'json',
                        cache: false,
                        success: function(resp){
                            $editAppDialog.unblock();
                            if(resp.status){
                                var app = resp.data.app;
                                $editAppDialog.find('[name="id"]').val(app.id);
                                $editAppDialog.find('[name="name"]').val(app.name);
                                $editAppDialog.find('[name="description"]').val(app.description);
                                $editAppDialog.find('[name="need_login"][value="'+app.need_login+'"]').prop('checked', 'checked');
                            }else{
                                common.alert(resp.message);
                            }
                        }
                    });
                }
            });
        });
    },
    'removeApp': function(){
        var $appList = $('#app-list');
        //删除
        $appList.on('click', '.dragsort-rm,.remove-app-link', function(){
            if(confirm('确定要删除此应用吗？删除后无法恢复，但可以重新添加。')){
                var _this = $(this);
                $.ajax({
                    'type': 'GET',
                    'url': system.url('apidoc/admin/app/remove'),
                    'data': {'id': $(this).attr('data-id')},
                    'dataType': 'json',
                    'cache': false,
                    'success': function(resp){
                        if(resp.status){
                            _this.parents('.dragsort-item').fadeOut('fast', function(){
                                //移除指定项
                                $(this).remove();
                            });
                        }else{
                            common.alert(resp.message);
                        }
                    }
                });
            }
        });
    },
    'init': function(){
        this.dragsort();
        this.createApp();
        this.editApp();
        this.removeApp();
    }
};
$(function(){
    app.init();
});
</script>