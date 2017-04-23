<?php
use fay\helpers\HtmlHelper;
use cms\models\tables\RolesTable;
use cms\services\user\UserRoleService;

/**
 * @var $widget \cms\widgets\tag_post_list\controllers\AdminController
 * @var $cats array
 */
?>
<div class="box">
    <div class="box-title">
        <h4>配置参数</h4>
    </div>
    <div class="box-content">
        <div class="form-field">
            <label class="title bold">分页大小</label>
            <?php echo F::form('widget')->inputText('page_size', array(
                'class'=>'form-control mw150',
            ), 10)?>
        </div>
        <div class="form-field">
            <label class="title bold">排序规则</label>
            <?php
                echo F::form('widget')->inputRadio('order', 'hand', array(
                    'wrapper'=>array(
                        'tag'=>'label',
                        'wrapper'=>'p',
                    ),
                    'after'=>'置顶+排序值+发布时间倒序（手工排序）',
                ), true);
                echo F::form('widget')->inputRadio('order', 'publish_time', array(
                    'wrapper'=>array(
                        'tag'=>'label',
                        'wrapper'=>'p',
                    ),
                    'after'=>'仅发布时间倒序（最新发布）',
                ));
                echo F::form('widget')->inputRadio('order', 'views', array(
                    'wrapper'=>array(
                        'tag'=>'label',
                        'wrapper'=>'p',
                    ),
                    'after'=>'阅读数倒序+发布时间倒序（热门文章）',
                ));
            ?>
        </div>
        <div class="form-field">
            <a href="javascript:" class="toggle" data-src="#widget-advance-setting"><i class="fa fa-caret-down mr5"></i>高级设置</a>
            <span class="fc-red">（若非开发人员，请不要修改以下配置）</span>
        </div>
        <div id="widget-advance-setting" class="<?php if(!UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN))echo 'hide';?>">
            <div class="form-field">
                <label class="title bold">标签名称字段</label>
                <?php echo F::form('widget')->inputText('tag_title_key', array(
                    'class'=>'form-control mw150',
                ), 'tag_title')?>
                <p class="fc-grey">标签名称字段名</p>
            </div>
            <div class="form-field">
                <label class="title bold">标签ID字段</label>
                <?php echo F::form('widget')->inputText('tag_id_key', array(
                    'class'=>'form-control mw150',
                ), 'tag_id')?>
                <p class="fc-grey">标签ID字段名（若同时指定名称和ID，以ID为准）</p>
            </div>
            <div class="form-field">
                <label class="title bold">页码字段</label>
                <?php echo F::form('widget')->inputText('page_key', array(
                    'class'=>'form-control mw150',
                ), 'page')?>
            </div>
            <div class="form-field">
                <label class="title bold">限定分类</label>
                <?php echo F::form('widget')->select('cat_id', HtmlHelper::getSelectOptions($cats), array(
                    'class'=>'form-control mw400',
                ))?>
                <p class="fc-grey">只查询该分类下的文章（包含子分类）</p>
            </div>
            <div class="form-field">
                <label class="title bold">发布时间格式</label>
                <?php echo F::form('widget')->inputText('date_format', array(
                    'class'=>'form-control mw150',
                ), 'pretty')?>
                <p class="fc-grey">若为空，则不显示时间；若为pretty，则会显示“1天前”这样的时间格式；<br>
                    其他格式视为PHP date函数的第一个参数</p>
            </div>
            <div class="form-field">
                <label class="title bold">文章缩略图尺寸</label>
                <?php
                echo F::form('widget')->inputText('post_thumbnail_width', array(
                    'placeholder'=>'宽度',
                    'class'=>'form-control w100 ib',
                )),
                ' x ',
                F::form('widget')->inputText('post_thumbnail_height', array(
                    'placeholder'=>'高度',
                    'class'=>'form-control w100 ib',
                ));
                ?>
                <p class="fc-grey">若留空，则返回默认尺寸缩略图。</p>
            </div>
            <div class="form-field">
                <label class="title bold">附加字段</label>
                <?php
                echo F::form('widget')->inputCheckbox('fields[]', 'category', array(
                    'label'=>'分类详情',
                ), true);
                echo F::form('widget')->inputCheckbox('fields[]', 'user', array(
                    'label'=>'作者信息',
                ));
                echo F::form('widget')->inputCheckbox('fields[]', 'files', array(
                    'label'=>'附件',
                ));
                echo F::form('widget')->inputCheckbox('fields[]', 'meta', array(
                    'label'=>'计数（评论数/阅读数/点赞数）',
                ));
                echo F::form('widget')->inputCheckbox('fields[]', 'tags', array(
                    'label'=>'标签',
                ));
                echo F::form('widget')->inputCheckbox('fields[]', 'props', array(
                    'label'=>'附加属性',
                ));
                ?>
                <p class="fc-grey">仅勾选模版中用到的字段，可以加快程序效率。</p>
            </div>
            <div class="form-field thumbnail-size-container <?php if(empty($widget->config['fields']) || !in_array('files', $widget->config['fields']))echo 'hide';?>">
                <label class="title bold">附件缩略图尺寸</label>
                <?php
                echo F::form('widget')->inputText('file_thumbnail_width', array(
                    'placeholder'=>'宽度',
                    'class'=>'form-control w100 ib',
                )),
                ' x ',
                F::form('widget')->inputText('file_thumbnail_height', array(
                    'placeholder'=>'高度',
                    'class'=>'form-control w100 ib',
                ));
                ?>
                <p class="fc-grey">若留空，则默认为100x100。</p>
            </div>
            <?php F::app()->view->renderPartial('admin/widget/_template_field')?>
            <div class="form-field">
                <label class="title bold">无内容时显示的替换文本</label>
                <?php echo F::form('widget')->textarea('empty_text', array(
                    'class'=>'form-control h90 autosize',
                ), '无相关记录！')?>
                <p class="fc-grey">可以包含html</p>
            </div>
            <div class="form-field">
                <label class="title bold">分页条模版</label>
                <p><?php
                    echo F::form('widget')->inputRadio('pager', 'system', array(
                        'label'=>'调用全局分页条',
                    ));
                    echo F::form('widget')->inputRadio('pager', 'custom', array(
                        'label'=>'小工具内自定义',
                    ));
                ?></p>
                <div id="pager-template-container" class="<?php if(!isset($widget->config['pager']) || $widget->config['pager'] == 'system')echo 'hide';?>">
                    <?php echo F::form('widget')->textarea('pager_template', array(
                        'class'=>'form-control h90 autosize',
                    ))?>
                    <p class="fc-grey mt5">
                        若模版内容符合正则<code>/^[\w_-]+(\/[\w_-]+)+$/</code>，
                        即类似<code>frontend/widget/pager</code><br />
                        则会调用当前app下符合该相对路径的view文件。<br />
                        否则视为php代码<code>eval</code>执行。若留空，会调用默认模版。
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    $('.toggle-advance').on('click', function(){
        $(".advance").toggle();
    });
    
    $('input[name="pager"]').on('click', function(){
        if($(this).val() == 'custom'){
            $('#pager-template-container').show();
        }else{
            $('#pager-template-container').hide();
        }
    });
    
    $('input[name="fields[]"][value="files"]').on('click', function(){
        if($(this).is(':checked')){
            $('.thumbnail-size-container').show();
        }else{
            $('.thumbnail-size-container').hide();
        }
    });
});
</script>