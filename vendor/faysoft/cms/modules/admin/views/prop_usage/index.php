<?php
use cms\models\tables\PropsTable;
use fay\helpers\HtmlHelper;

/**
 * @var $relation_props array
 * @var $props array
 * @var $usage_model \cms\services\prop\PropUsageInterface
 * @var $usage_type int
 * @var $usage_id int
 */
?>
<?php echo F::form()->open('cms/admin/prop-usage/edit')?>
<?php echo F::form()->inputHidden('usage_id')?>
<div class="poststuff">
    <div class="post-body">
        <div class="post-body-content">
            <div class="box">
                <div class="box-title">
                    <h4>属性列表</h4>
                </div>
                <div class="box-content">
                    <?php echo HtmlHelper::link('选取属性', 'javascript:', array(
                        'class'=>'btn',
                        'id'=>'select-prop-link',
                        'data-src'=>'#select-prop-dialog',
                    ))?>
                    <div class="dragsort-list" id="selected-prop-list">
                    <?php foreach($props as $prop){?>
                        <div class="dragsort-item prop-item" id="prop-item-<?php echo $prop['id']?>" data-id="<?php echo $prop['id']?>">
                            <a class="dragsort-rm" href="javascript:"></a>
                            <div class="dragsort-item-container">
                                <h3 class="ib"><?php echo HtmlHelper::encode($prop['title'])?></h3>
                                <?php if($prop['alias']){?>
                                <em class="fc-grey">[ <?php echo $prop['alias']?> ]</em>
                                <?php }?>
                                <div class="mt6 mb10 fc-grey">
                                    <span class="mr10 element-name" title="表单元素">
                                        <i class="fa fa-cube mr5"></i><?php echo PropsTable::$element_map[$prop['element']]?>
                                    </span>
                                    <span class="mr10 prop-required" title="是否必选">
                                        <i class="fa <?php echo $prop['required'] ? 'fa-check-square-o' : 'fa-square-o'?> mr5"></i><?php echo $prop['required'] ? '必填' : '非必填'?>
                                    </span>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="title">排序值：</label>
                                        <?php echo HtmlHelper::inputText(
                                            "sort[{$prop['id']}]",
                                            $prop['sort'],
                                            array(
                                                'class'=>'form-control ib mw200',
                                            )
                                        )?>
                                    </div>
                                    <div class="col-6">
                                        <label class="title">是否共享：</label>
                                        <?php
                                            echo HtmlHelper::inputRadio(
                                                "is_share[{$prop['id']}]",
                                                1,
                                                !!$prop['is_share'],
                                                array(
                                                    'label'=>'是',
                                                )
                                            );
                                            echo HtmlHelper::inputRadio(
                                                "is_share[{$prop['id']}]",
                                                0,
                                                !$prop['is_share'],
                                                array(
                                                    'label'=>'否',
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                    <?php foreach($relation_props as $prop){?>
                        <div class="dragsort-item bl-yellow">
                            <div class="dragsort-item-container">
                                <h3 class="ib"><?php
                                    echo HtmlHelper::encode($prop['title'])
                                ?></h3>
                                <?php if($prop['alias']){?>
                                    <em class="fc-grey">[ <?php echo $prop['alias']?> ]</em>
                                <?php }?>
                                <span class="normal">（来自：<?php
                                    echo HtmlHelper::link(
                                        $usage_model->getUsageItemTitle($prop['usage_id']),
                                        array('cms/admin/prop-usage/index', array(
                                            'usage_id'=>$prop['usage_id'],
                                            'usage_type'=>$usage_type,
                                        ))
                                    )
                                ?>的共享）</span>
                                <div class="mt6 mb10 fc-grey">
                                    <span class="mr10 element-name" title="表单元素">
                                        <i class="fa fa-cube mr5"></i><?php echo \cms\services\prop\ItemPropService::$elementMap[$prop['element']]::getName()?>
                                    </span>
                                    <span class="mr10 prop-required" title="是否必选">
                                        <i class="fa <?php echo $prop['required'] ? 'fa-check-square-o' : 'fa-square-o'?> mr5"></i><?php echo $prop['required'] ? '必填' : '非必填'?>
                                    </span>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label>排序值：</label>
                                        <?php echo $prop['sort']?>
                                    </div>
                                    <div class="col-6">
                                        <label class="title">是否共享：</label>
                                        是
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <div class="postbox-container-1 dragsort" id="side">
            <div class="box" id="box-operation">
                <div class="box-title">
                    <h3>操作</h3>
                </div>
                <div class="box-content">
                    <div>
                        <?php echo F::form()->submitLink('提交', array(
                            'class'=>'btn',
                        ))?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo F::form()->close()?>
<div class="hide">
    <div id="select-prop-dialog" class="dialog">
        <div class="dialog-content">
            <h4>属性列表</h4>
            <table class="inbox-table">
                <thead>
                <tr>
                    <th class="w240">属性名称</th>
                    <th class="w150">表单元素</th>
                    <th class="w90">操作</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div id="select-prop-list-pager" class="pager"></div>
        </div>
    </div>
</div>
<script>
var propUsage = {
    'usageType': <?php echo $usage_type?>,
    /**
     * 初始化选择属性弹窗
     */
    'dialog': function(){
        common.loadFancybox(function(){
            $('#select-prop-link').fancybox({
                'touch': false,
                'onComplete': function(){
                    propUsage.loadData();
                }
            });
        });
    },
    /**
     * 加载数据
     */
    'loadData': function(page){
        page = page || 1;

        var $selectPropDialog = $('#select-prop-dialog');
        $selectPropDialog.block({
            'zindex': 120000
        });

        $.ajax({
            'type': 'GET',
            'url': system.url('cms/admin/prop/list', {
                'page': page,
                'usage_type': propUsage.usageType
            }),
            'data': $('#select-prop-search-form').serialize(),
            'dataType': 'json',
            'cache': false,
            'success': function(resp){
                $selectPropDialog.unblock();
                
                var selectedProps = propUsage.getSelectedProps();
                
                if(resp.status){
                    var $tbody = $selectPropDialog.find('table tbody');
                    //清空原数据
                    $tbody.html('');

                    //插入新数据
                    $.each(resp.data.props, function(i, data){
                        $tbody.append([
                            '<tr id="select-prop-', data.id, '">',
                                '<td>', system.encode(data.title), '</td>',
                                '<td>', system.encode(data.element_name), '</td>',
                                (function(){
                                    if(system.inArray(data.id, selectedProps)){
                                        return '<td>已选取</td>';
                                    }
                                    return '<td><a href="javascript:" class="select-single-prop" data-id="'+data.id+'">选取</a></td>';
                                })(),
                            '<tr>'
                        ].join(''));
                    });

                    //绑定事件
                    $tbody.find('.select-single-prop').on('click', function(){
                        propUsage.onSelect($(this));
                    });

                    //分页条
                    common.showPager('select-prop-list-pager', resp.data.pager);
                }else{
                    common.alert(resp.message);
                }
            }
        });
    },
    /**
     * 获取已选中的属性id数组
     */
    'getSelectedProps': function(){
        var $selectedPropList = $('#selected-prop-list');
        var selectedPropIds = [];
        $selectedPropList.find('.prop-item').each(function(){
            if($(this).attr('data-id')){
                selectedPropIds.push($(this).attr('data-id'));
            }
        });
        
        return selectedPropIds;
    },
    /**
     * 选中属性
     */
    'onSelect': function(element){
        var prop_id = element.attr('data-id');
        element.replaceWith('已选取');
        $('#selected-prop-list').prepend([
            '<div class="dragsort-item prop-item" id="prop-item-', prop_id, '" data-id="', prop_id, '">',
                '<a class="dragsort-rm" href="javascript:"></a>',
                '<div class="dragsort-item-container">',
                    '<h3 class="ib">加载中...</h3>',
                    '<em class="fc-grey"></em>',
                    '<div class="mt6 mb10 fc-grey">',
                        '<span class="mr10 element-name" title="表单元素">',
                            '<i class="fa fa-cube mr5"></i>',
                        '</span>',
                        '<span class="mr10 prop-required" title="是否必选">',
                            '<i class="fa mr5"></i>',
                        '</span>',
                    '</div>',
                    '<div class="row">',
                        '<div class="col-6">',
                            '<label class="title">排序值：</label>',
                            '<input name="sort[', prop_id, ']" type="text" value="10000" class="form-control ib mw200 prop-sort">',
                        '</div>',
                        '<div class="col-6">',
                            '<label class="title">是否共享：</label>',
                            '<label><input name="is_share[', prop_id, ']" type="radio" value="1" checked="checked">是</label>',
                            '<label><input name="is_share[', prop_id, ']" type="radio" value="0">否</label>',
                        '</div>',
                    '</div>',
                '</div>',
            '</div>'
        ].join(''));

        $.ajax({
            'type': 'GET',
            'url': system.url('cms/admin/prop/item'),
            'data': {'id': prop_id},
            'dataType': 'json',
            'cache': false,
            'success': function (resp) {
                var data = resp.data.prop;
                var $propItem = $('#prop-item-' + prop_id);
                $propItem.find('h3').text(data.title);
                $propItem.find('.element-name i').after(data.element_name);
                $propItem.find('.prop-required i').addClass(data.required == 1 ? 'fa-check-square-o' : 'fa-square-o')
                    .after(data.required == 1 ? '必填' : '非必填');
            }
        });
    },
    /**
     * 分页条事件
     */
    'pagerEvent': function(){
        //分页事件
        $('#select-prop-list-pager').on('click', 'a.page-numbers', function(){
            var page = $(this).attr('data-page');
            if(page){
                propUsage.loadData(page);
            }
        }).on('keydown', '.pager-input', function(event){
            if(event.keyCode == 13 || event.keyCode == 108){
                propUsage.loadData($('#select-prop-list-pager').find('.pager-input').val());
                return false;
            }
        });
    },
    'init': function(){
        this.dialog();
        this.pagerEvent();
    }
};
$(function(){
    propUsage.init();
});
</script>