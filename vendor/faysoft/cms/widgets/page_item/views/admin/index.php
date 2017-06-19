<?php
use cms\models\tables\RolesTable;
use cms\services\user\UserRoleService;

?>
<div class="box">
    <div class="box-title">
        <h4>配置参数</h4>
    </div>
    <div class="box-content">
        <div class="form-field">
            <label class="title bold">默认显示页面</label>
            <?php
                echo F::form('widget')->inputHidden('default_page_id', array(
                    'id'=>'page-id',
                ), 0);
                echo F::form('widget')->inputText('page_title', array(
                    'class'=>'form-control mw500',
                    'id'=>'page-title',
                ));
            ?>
            <p class="fc-grey">
                固定显示一篇文章，一般用在页面的某一块显示一些固定的描述。
            </p>
        </div>
        <div class="form-field">
            <a href="javascript:" class="toggle" data-src="#widget-advance-setting"><i class="fa fa-caret-down mr5"></i>高级设置</a>
            <span class="fc-red">（若非开发人员，请不要修改以下配置）</span>
        </div>
        <div id="widget-advance-setting" class="<?php if(!UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN))echo 'hide';?>">
            <div class="form-field">
                <label class="title bold">ID字段</label>
                <?php echo F::form('widget')->inputText('id_key', array(
                    'class'=>'form-control mw150',
                ), 'page_id')?>
                <p class="fc-grey">URL中的id字段。（此字段为URL重写后的字段，即通过<code>F::input()-&gt;get($key)</code>可以获取到）</p>
            </div>
            <div class="form-field">
                <label class="title bold">别名字段</label>
                <?php echo F::form('widget')->inputText('alias_key', array(
                    'class'=>'form-control mw150',
                ), 'page_alias')?>
                <p class="fc-grey">
                    若传入分类别名字段，会根据别名获取页面。<br>
                    若同时传入ID和分类别名， 则以ID字段为准。
                </p>
            </div>
            <div class="form-field">
                <label class="title bold">递增阅读数</label>
                <?php
                    echo F::form('widget')->inputRadio('inc_views', '1', array(
                        'label'=>'递增',
                    ));
                    echo F::form('widget')->inputRadio('inc_views', '0', array(
                        'label'=>'不递增',
                    ), true);
                ?>
                <p class="fc-grey">仅搜索此分类及其子分类下的文章，当不同分类对应不同式样时，此选项很有用。</p>
            </div>
            <?php F::app()->view->renderPartial('admin/widget/_template_field')?>
        </div>
    </div>
</div>
<script>
$(function(){
    system.getScript(system.assets('faycms/js/fayfox.autocomplete.js'), function(){
        $('#page-title').autocomplete({
            'url' : system.url('cms/admin/page/search'),
            'startSuggestLength':0,
            'onSelect':function(obj, data){
                $('#page-id').val(data.id);
            }
        });
    });
});
</script>