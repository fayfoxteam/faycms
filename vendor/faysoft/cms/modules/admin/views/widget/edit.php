<?php
use fay\helpers\HtmlHelper;

/**
 * @var $widget_admin fay\widget\Widget
 */
?>
<?php echo F::form('widget')->open()?>
<div class="poststuff">
    <div class="post-body">
        <div class="post-body-content"><?php
        if(method_exists($widget_admin, 'index')){
            //显示小工具配置面板
            $widget_admin->index();
        }else{?>
            <div class="box">
                <div class="box-content">该小工具无可配置项</div>
            </div>
        <?php }?></div>
        <div class="postbox-container-1">
            <div class="box">
                <div class="box-title">
                    <h4>操作</h4>
                </div>
                <div class="box-content">
                    <div><?php
                        echo F::form('widget')->submitLink('保存', array(
                            'class'=>'btn',
                        ));
                        echo HtmlHelper::link('预览', array('widget/load/'.$widget['alias']), array(
                            'class'=>'btn btn-grey ml5',
                            'target'=>'_blank',
                        ));
                    ?></div>
                    <div class="misc-pub-section mt6">
                        <strong>是否启用？</strong>
                        <?php echo HtmlHelper::inputRadio('f_widget_enabled', 1, $widget['enabled'] ? true : false, array('label'=>'是'))?>
                        <?php echo HtmlHelper::inputRadio('f_widget_enabled', 0, $widget['enabled'] ? false : true, array('label'=>'否'))?>
                        <p class="fc-grey">停用后不再显示，但会保留设置</p>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="box-title">
                    <h4>小工具信息</h4>
                </div>
                <div class="box-content">
                    <div class="form-field pb0 pt0">
                        <label class="title bold pb0">别名</label>
                        <?php echo HtmlHelper::inputText('f_widget_alias', $widget['alias'], array(
                            'class'=>'form-control',
                        ))?>
                        <p class="fc-grey mt5">
                            唯一的识别一个widget实例
                        </p>
                    </div>
                    <div class="form-field">
                        <label class="title bold pb0">描述</label>
                        <?php echo HtmlHelper::textarea('f_widget_description', $widget['description'], array(
                            'class'=>'form-control autosize',
                        ))?>
                    </div>
                    <div class="form-field pb0">
                        <label class="title bold pb0">是否ajax引入</label>
                        <?php echo HtmlHelper::inputRadio('f_widget_ajax', 1, $widget['ajax'] ? true : false, array('label'=>'是'))?>
                        <?php echo HtmlHelper::inputRadio('f_widget_ajax', 0, $widget['ajax'] ? false : true, array('label'=>'否'))?>
                    </div>
                    <div class="form-field">
                        <label class="title bold pb0">是否缓存</label>
                        <?php echo HtmlHelper::inputRadio('f_widget_cache', 1, $widget['cache'] >= 0 ? true : false, array('label'=>'是'))?>
                        <?php echo HtmlHelper::inputRadio('f_widget_cache', 0, $widget['cache'] < 0 ? true : false, array('label'=>'否'))?>
                    </div>
                    <div class="form-field <?php if($widget['cache'] < 0)echo 'hide'?>" id="cache-expire-container">
                        <label class="title bold pb0">缓存周期</label>
                        <?php echo HtmlHelper::inputText('f_widget_cache_expire', $widget['cache'] >= 0 ? $widget['cache'] : 3600, array(
                            'class'=>'form-control w100 ib',
                        ))?>
                        单位（秒）
                        <p class="fc-grey">
                            0代表不过期
                        </p>
                    </div>
                </div>
            </div>
            <?php if(file_exists($widget_admin->path . 'views/admin/sidebar.php')){
                $widget_admin->view->render('sidebar');
            }?>
        </div>
    </div>
</div>
<?php echo F::form('widget')->close()?>
<script src="<?php echo $this->assets('faycms/js/admin/widget.js')?>"></script>
<script>
$(function(){
    widget.id = '<?php echo $widget['id']?>';
    widget.init();
    
    common.filebrowserImageUploadUrl = system.url('cms/admin/file/img-upload', {'cat':'widget'});
});
</script>