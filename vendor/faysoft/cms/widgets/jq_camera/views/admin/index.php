<?php
use fay\helpers\HtmlHelper;
use cms\services\file\FileService;
?>
<div class="drag-drop-area" id="drag-drop-area">
    <div class="drag-drop-inside">
        <p class="drag-drop-info">将文件拖拽至此</p>
        <p>或</p>
        <p class="drag-drop-buttons">
            <a class="plupload-browse-button btn btn-grey" id="plupload-browse-button">选择文件</a>
        </p>
    </div>
</div>
<div class="dragsort-list file-list">
<?php foreach($widget->config['files'] as $d){?>
    <div class="dragsort-item <?php if((!empty($d['start_time']) && \F::app()->current_time < $d['start_time'])){
        echo 'bl-yellow';
    }else if(!empty($d['end_time']) && \F::app()->current_time > $d['end_time']){
        echo 'bl-red';
    }?>">
        <?php echo HtmlHelper::inputHidden('files[]', $d['file_id'])?>
        <a class="dragsort-rm" href="javascript:;"></a>
        <a class="dragsort-item-selector"></a>
        <div class="dragsort-item-container">
            <span class="file-thumb">
            <?php
                echo HtmlHelper::link(HtmlHelper::img($d['file_id'], 2), FileService::getUrl($d['file_id']), array(
                    'class'=>'photo-thumb-link',
                    'encode'=>false,
                    'title'=>HtmlHelper::encode($d['title']),
                ));
            ?>
            </span>
            <div class="file-desc-container">
                <?php echo HtmlHelper::inputText("titles[{$d['file_id']}]", $d['title'], array(
                    'class'=>'photo-title mb5 form-control',
                    'placeholder'=>'标题',
                ))?>
                <?php echo HtmlHelper::inputText("links[{$d['file_id']}]", $d['link'], array(
                    'class'=>'photo-link mb5 form-control',
                    'placeholder'=>'链接地址',
                ))?>
                <?php echo HtmlHelper::inputText("start_time[{$d['file_id']}]", $d['start_time'] ? date('Y-m-d H:i:s', $d['start_time']) : '', array(
                    'class'=>'file-starttime datetimepicker mb5 form-control wp49 fl',
                    'placeholder'=>'生效时间',
                    'autocomplete'=>'off',
                ))?>
                <?php echo HtmlHelper::inputText("end_time[{$d['file_id']}]", $d['end_time'] ? date('Y-m-d H:i:s', $d['end_time']) : '', array(
                    'class'=>'file-endtime datetimepicker mb5 form-control wp49 fr',
                    'placeholder'=>'过期时间',
                    'autocomplete'=>'off',
                ))?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
<?php }?>
</div>
<div class="box">
    <div class="box-title">
        <h4>渲染模版</h4>
    </div>
    <div class="box-content">
        <?php echo F::form('widget')->textarea('template', array(
            'class'=>'form-control h90 autosize',
            'id'=>'code-editor',
        ))?>
        <p class="fc-grey mt5">
            若模版内容符合正则<code>/^[\w_-]+(\/[\w_-]+)+$/</code>，
            即类似<code>frontend/widget/template</code><br />
            则会调用当前application下符合该相对路径的view文件。<br />
            否则视为php代码<code>eval</code>执行。若留空，会调用默认模版。
        </p>
    </div>
</div>
<script type="text/javascript">
var jq_camera = {
    'uploadObj':null,
    'preview':function(){
        system.getCss(system.assets('js/fancybox-3.0/dist/jquery.fancybox.min.css'), function(){
            system.getScript(system.assets('js/fancybox-3.0/dist/jquery.fancybox.min.js'), function(){
                $('.photo-thumb-link').fancybox({
                    'transitionIn' : 'elastic',
                    'transitionOut' : 'elastic',
                    'type' : 'image',
                    'padding' : 0
                });
            });
        });
    },
    'files':function(){
        system.getScript(system.assets('faycms/js/admin/uploader.js'), function(){
            uploader.files({
                'browse_button': 'plupload-browse-button',
                'container': 'drag-drop-area',
                'drop_element': 'drag-drop-area',
                'cat': 'widget',
                'image_only': true,
                'file_info': ['title', 'link', 'validity']
            });
        });
    },
    'init':function(){
        this.preview();
        this.files();
    }
};
$(function(){
    jq_camera.init();
    
});
</script>