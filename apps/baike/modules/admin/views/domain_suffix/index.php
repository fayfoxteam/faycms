<?php
use fay\helpers\HtmlHelper;

/**
 * @var $domain_suffixes array
 */
?>
<a href="javascript:" class="btn create-domain-suffix-link" data-src="#create-domain-suffix-dialog">新增域名后缀</a>
<div class="dragsort-list2" id="domain-suffix-list">
    <?php foreach($domain_suffixes as $domain_suffix){?>
        <div class="dragsort-item" data-id="<?php echo $domain_suffix['id']?>">
            <a class="dragsort-rm" data-id="<?php echo $domain_suffix['id']?>" href="javascript:"></a>
            <a class="dragsort-item-selector"></a>
            <div class="dragsort-item-container">
                <div class="cf">
                    <div class="col-4">
                        <strong class="mr10">域名后缀</strong><?php echo HtmlHelper::encode($domain_suffix['suffix'])?>
                        <div class="mt5">
                            <a href="javascript:" class="edit-domain-suffix-link" data-src="#edit-domain-suffix-dialog" data-id="<?php echo $domain_suffix['id']?>">编辑</a>
                            |
                            <a class="fc-red remove-domain-suffix-link" data-id="<?php echo $domain_suffix['id']?>" href="javascript:">删除</a>
                        </div>
                    </div>
                    <div class="col-8">
                        <strong class="mr10">描述</strong><?php echo $domain_suffix['description'] ? HtmlHelper::encode($domain_suffix['description']) : '无'?>
                    </div>
                </div>
            </div>
        </div>
    <?php }?>
</div>
<div class="hide">
    <div id="create-domain-suffix-dialog" class="dialog">
        <div class="dialog-content w600">
            <h4>创建域名后缀</h4>
            <?php echo F::form('create')->open(array('baike/admin/domain-suffix/create'))?>
            <table class="form-table">
                <tr>
                    <th class="adaption">后缀<em class="required">*</em></th>
                    <td><?php echo HtmlHelper::inputText('suffix', '', array(
                            'class'=>'form-control',
                        ))?></td>
                </tr>
                <tr>
                    <th valign="top" class="adaption">描述</th>
                    <td><?php echo HtmlHelper::textarea('description', '', array(
                        'class'=>'form-control autosize',
                        'rows'=>3,
                    ))?></td>
                </tr>
                <tr>
                    <th class="adaption"></th>
                    <td>
                        <?php echo F::form('create')->submitLink('创建域名后缀', array(
                            'class'=>'btn'
                        ))?>
                        <a href="javascript:" class="btn btn-grey fancybox-close">取消</a>
                    </td>
                </tr>
            </table>
            <?php echo F::form('create')->close()?>
        </div>
    </div>
</div>
<div class="hide">
    <div id="edit-domain-suffix-dialog" class="dialog">
        <div class="dialog-content w600">
            <h4>编辑域名后缀</h4>
            <?php echo F::form('edit')->open(array('baike/admin/domain-suffix/edit'))?>
            <input type="hidden" name="id" id="edit-domain-suffix-id">
            <table class="form-table">
                <tr>
                    <th class="adaption">后缀<em class="required">*</em></th>
                    <td><?php echo HtmlHelper::inputText('suffix', '', array(
                        'class'=>'form-control',
                        'id'=>'edit-domain-suffix-suffix',
                    ))?></td>
                </tr>
                <tr>
                    <th valign="top" class="adaption">描述</th>
                    <td><?php echo HtmlHelper::textarea('description', '', array(
                        'class'=>'form-control autosize',
                        'rows'=>3,
                        'id'=>'edit-domain-suffix-description',
                    ))?></td>
                </tr>
                <tr>
                    <th class="adaption"></th>
                    <td>
                        <?php echo F::form('edit')->submitLink('编辑域名后缀', array(
                            'class'=>'btn'
                        ))?>
                        <a href="javascript:" class="btn btn-grey fancybox-close">取消</a>
                    </td>
                </tr>
            </table>
            <?php echo F::form('edit')->close()?>
        </div>
    </div>
</div>
<script>
var domainSuffix = {
    'dragsort': function(){
        var $domainSuffixList = $('#domain-suffix-list');
        system.getScript(system.assets('js/jquery.dragsort-0.5.2.js'), function(){
            if(!$domainSuffixList.find('.dragsort-item').length){
                //如果本来是空的，要先插一个元素进去，否则后加入的元素无法拖拽，应该是dragsort的bug
                $domainSuffixList.append('<div class="dragsort-item hide remove-after-init"></div>');
            }
            $domainSuffixList.dragsort({
                'itemSelector': 'div.dragsort-item',
                'dragSelector': '.dragsort-item-selector',
                'placeHolderTemplate': '<div class="dragsort-item holder"></div>',
                'dragEnd': function(){
                    //保存排序
                    var sort = [];
                    $domainSuffixList.find('.dragsort-item').each(function(){
                        sort.push($(this).attr('data-id'));
                    });

                    $.ajax({
                        type: 'POST',
                        url: system.url('baike/admin/domain-suffix/sort'),
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
            $domainSuffixList.find('.remove-after-init').remove();
        });
    },
    'createDomainSuffix': function(){
        common.loadFancybox(function(){
            $('.create-domain-suffix-link').fancybox();
        });
    },
    'editDomainSuffix': function(){
        common.loadFancybox(function(){
            $('.edit-domain-suffix-link').fancybox({
                'onComplete': function(instance, slide){
                    var $editDomainSuffixDialog = $('#edit-domain-suffix-dialog');
                    $editDomainSuffixDialog.block({
                        'zindex': 120000
                    });
                    $.ajax({
                        type: 'GET',
                        url: system.url('baike/admin/domain-suffix/get'),
                        data: {'id': slide.opts.$orig.attr('data-id')},
                        dataType: 'json',
                        cache: false,
                        success: function(resp){
                            $editDomainSuffixDialog.unblock();
                            if(resp.status){
                                var domainSuffix = resp.data.domain_suffix;
                                $editDomainSuffixDialog.find('#edit-domain-suffix-id').val(domainSuffix.id);
                                $editDomainSuffixDialog.find('#edit-domain-suffix-suffix').val(domainSuffix.suffix);
                                $editDomainSuffixDialog.find('#edit-domain-suffix-description').val(domainSuffix.description);
                            }else{
                                common.alert(resp.message);
                            }
                        }
                    });
                }
            });
        });
    },
    'removeDomainSuffix': function(){
        var $domainSuffixList = $('#domain-suffix-list');
        //删除
        $domainSuffixList.on('click', '.dragsort-rm,.remove-domain-suffix-link', function(){
            if(confirm('确定要删除此域名后缀吗？删除后无法恢复，但可以重新添加。')){
                var _this = $(this);
                $.ajax({
                    'type': 'GET',
                    'url': system.url('baike/admin/domain-suffix/remove'),
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
        this.createDomainSuffix();
        this.editDomainSuffix();
        this.removeDomainSuffix();
    }
};
$(function(){
    domainSuffix.init();
});
</script>