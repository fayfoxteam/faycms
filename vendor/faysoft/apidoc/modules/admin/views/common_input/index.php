<?php
use apidoc\models\tables\ApidocInputsTable;
use fay\helpers\HtmlHelper;

/**
 * @var $common_inputs array
 */
?>
<a href="javascript:" class="btn create-common-input-link" data-src="#create-common-input-dialog">新增公共请求参数</a>
<div class="dragsort-list2" id="common-input-list">
    <?php foreach($common_inputs as $common_input){?>
        <div class="dragsort-item" data-id="<?php echo $common_input['id']?>">
            <a class="dragsort-rm" data-id="<?php echo $common_input['id']?>" href="javascript:"></a>
            <a class="dragsort-item-selector"></a>
            <div class="dragsort-item-container">
                <div class="cf">
                    <div class="col-3">
                        <strong class="mr5">名称：</strong><?php
                            echo HtmlHelper::encode($common_input['name'])
                        ?><em>（<?php echo HtmlHelper::encode(ApidocInputsTable::getType($common_input['type']))?>）</em>
                        <div class="mt5">
                            <a href="javascript:" class="edit-common-input-link" data-src="#edit-common-input-dialog" data-id="<?php echo $common_input['id']?>">编辑</a>
                            |
                            <a class="fc-red remove-common-input-link" data-id="<?php echo $common_input['id']?>" href="javascript:">删除</a>
                        </div>
                    </div>
                    <div class="col-2">
                        <strong class="mr5">必选：</strong><?php echo $common_input['required'] ? '<span class="fc-green">是</span>' : '否'?>
                    </div>
                    <div class="col-7">
                        <strong class="mr5">描述：</strong><?php echo $common_input['description'] ? HtmlHelper::encode($common_input['description']) : '无'?>
                    </div>
                </div>
            </div>
        </div>
    <?php }?>
</div>
<div class="hide">
    <div id="create-common-input-dialog" class="dialog">
        <div class="dialog-content w600">
            <h4>添加公共请求参数</h4>
            <?php echo F::form('create')->open(array('apidoc/admin/common-input/create'))?>
                <table class="form-table">
                    <tr>
                        <th class="adaption">名称<em class="required">*</em></th>
                        <td><?php echo F::form('create')->inputText('name', array(
                                'class'=>'form-control',
                            ))?></td>
                    </tr>
                    <tr>
                        <th class="adaption">类型<em class="required">*</em></th>
                        <td><?php echo F::form('create')->select('type', ApidocInputsTable::getTypes(), array(
                                'class'=>'form-control w150 ib',
                            ), ApidocInputsTable::TYPE_STRING)?></td>
                    </tr>
                    <tr>
                        <th class="adaption">是否必须<em class="required">*</em></th>
                        <td><?php
                            echo F::form('edit')->inputRadio('required', 1, array(
                                'label'=>'是',
                            ));
                            echo F::form('edit')->inputRadio('required', 0, array(
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
                        <th class="adaption">示例值</th>
                        <td><?php echo F::form('create')->textarea('sample', array(
                                'class'=>'form-control h60 autosize',
                            ))?></td>
                    </tr>
                    <tr>
                        <th class="adaption">自从</th>
                        <td><?php echo F::form('create')->inputText('since', array(
                                'class'=>'form-control w150 ib',
                            ))?></td>
                    </tr>
                    <tr>
                        <th class="adaption"></th>
                        <td><?php
                            echo F::form('create')->submitLink('添加公共请求参数', array(
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
    <div id="edit-common-input-dialog" class="dialog">
        <div class="dialog-content w600">
            <h4>编辑公共请求参数</h4>
            <?php echo F::form('edit')->open(array('apidoc/admin/common-input/edit'))?>
            <input type="hidden" name="id" id="edit-common-input-id">
            <table class="form-table">
                <tr>
                    <th class="adaption">名称<em class="required">*</em></th>
                    <td><?php echo F::form('edit')->inputText('name', array(
                        'class'=>'form-control',
                    ))?></td>
                </tr>
                <tr>
                    <th class="adaption">类型<em class="required">*</em></th>
                    <td><?php echo F::form('edit')->select('type', ApidocInputsTable::getTypes(), array(
                        'class'=>'form-control w150 ib',
                    ), ApidocInputsTable::TYPE_STRING)?></td>
                </tr>
                <tr>
                    <th class="adaption">是否必须<em class="required">*</em></th>
                    <td><?php
                        echo F::form('edit')->inputRadio('required', 1, array(
                            'label'=>'是',
                        ));
                        echo F::form('edit')->inputRadio('required', 0, array(
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
                    <th class="adaption">示例值</th>
                    <td><?php echo F::form('edit')->textarea('sample', array(
                        'class'=>'form-control h60 autosize',
                    ))?></td>
                </tr>
                <tr>
                    <th class="adaption">自从</th>
                    <td><?php echo F::form('edit')->inputText('since', array(
                        'class'=>'form-control w150 ib',
                    ))?></td>
                </tr>
                <tr>
                    <th class="adaption"></th>
                    <td><?php
                        echo F::form('edit')->submitLink('编辑公共请求参数', array(
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
var commonInput = {
    'dragsort': function(){
        var $commonInputList = $('#common-input-list');
        system.getScript(system.assets('js/jquery.dragsort-0.5.2.js'), function(){
            if(!$commonInputList.find('.dragsort-item').length){
                //如果本来是空的，要先插一个元素进去，否则后加入的元素无法拖拽，应该是dragsort的bug
                $commonInputList.append('<div class="dragsort-item hide remove-after-init"></div>');
            }
            $commonInputList.dragsort({
                'itemSelector': 'div.dragsort-item',
                'dragSelector': '.dragsort-item-selector',
                'placeHolderTemplate': '<div class="dragsort-item holder"></div>',
                'dragEnd': function(){
                    //保存排序
                    var sort = [];
                    $commonInputList.find('.dragsort-item').each(function(){
                        sort.push($(this).attr('data-id'));
                    });

                    $.ajax({
                        type: 'POST',
                        url: system.url('apidoc/admin/common-input/sort'),
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
            $commonInputList.find('.remove-after-init').remove();
        });
    },
    'createCommonInput': function(){
        common.loadFancybox(function(){
            $('.create-common-input-link').fancybox();
        });
    },
    'editCommonInput': function(){
        common.loadFancybox(function(){
            $('.edit-common-input-link').fancybox({
                'onComplete': function(instance, slide){
                    var $editCommonInputDialog = $('#edit-common-input-dialog');
                    $editCommonInputDialog.block({
                        'zindex': 120000
                    });
                    $.ajax({
                        type: 'GET',
                        url: system.url('apidoc/admin/common-input/get'),
                        data: {'id': slide.opts.$orig.attr('data-id')},
                        dataType: 'json',
                        cache: false,
                        success: function(resp){
                            $editCommonInputDialog.unblock();
                            if(resp.status){
                                var commonInput = resp.data.common_input;
                                $editCommonInputDialog.find('[name="id"]').val(commonInput.id);
                                $editCommonInputDialog.find('[name="name"]').val(commonInput.name);
                                $editCommonInputDialog.find('[name="type"]').val(commonInput.type);
                                $editCommonInputDialog.find('[name="sample"]').val(commonInput.sample);
                                $editCommonInputDialog.find('[name="description"]').val(commonInput.description);
                                $editCommonInputDialog.find('[name="since"]').val(commonInput.since);
                                $editCommonInputDialog.find('[name="required"][value="'+commonInput.required+'"]').prop('checked', 'checked');
                            }else{
                                common.alert(resp.message);
                            }
                        }
                    });
                }
            });
        });
    },
    'removeCommonInput': function(){
        var $commonInputList = $('#common-input-list');
        //删除
        $commonInputList.on('click', '.dragsort-rm,.remove-common-input-link', function(){
            if(confirm('确定要删除此公共请求参数吗？删除后无法恢复，但可以重新添加。')){
                var _this = $(this);
                $.ajax({
                    'type': 'GET',
                    'url': system.url('apidoc/admin/common-input/remove'),
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
        this.createCommonInput();
        this.editCommonInput();
        this.removeCommonInput();
    }
};
$(function(){
    commonInput.init();
});
</script>