<?php
/**
 * 单图上传DOM
 */
use cms\services\file\FileService;
use fay\helpers\HtmlHelper;
use fay\helpers\NumberHelper;

//默认参数
empty($field) && $field = 'thumbnail';//图片字段名称
empty($label) && $label = '缩略图';//图片字段的描述
empty($field_value) && $field_value = F::form()->getData($field, 0);//图片ID
empty($cat) && $cat = 'other';//图片分类
isset($preview_image_width) || $preview_image_width = 254;//预览图默认缩放为254宽（用于右侧sidebar）。若为0，则显示原图，若为thumbnail，则显示缩略图

//可以单独制定，一般由$label拼接出来就够用了，若设定为空，则不会显示对应链接
isset($select_text) || $select_text = "上传{$label}";
isset($remove_text) || $remove_text = "移除{$label}";

$scene = uniqid();//随机字符串，以确保id不会重复
$clean_field = str_replace(array('[', ']', ':'), '', $field);//字段名称可能包含方括号
?>
<div id="select-<?php echo $clean_field?>-container<?php echo $scene?>" class="mb10 select-container">
    <a href="javascript:" id="select-<?php echo $clean_field, $scene?>" class="btn"><?php echo $select_text?></a>
</div>
<div id="upload-<?php echo $clean_field?>-preview-container<?php echo $scene?>" class="upload-preview-container"><?php
    echo HtmlHelper::inputHidden($field, $field_value ? $field_value : 0);
    
    if($field_value){
        //若字段有值，根据字段值显示缩略图
        $preview_img = $field_value;
    }else if(!empty($default_preview_image)){
        //若字段没值，但设置了默认预览图，显示默认预览图
        $preview_img = $default_preview_image;
    }
    if(!empty($preview_img)){
        echo HtmlHelper::link(HtmlHelper::img(
            $preview_img,
            ($preview_image_width == 'thumbnail' && $preview_image_width !== 0) ? FileService::PIC_THUMBNAIL :
                ($preview_image_width == 0 ? FileService::PIC_ORIGINAL : FileService::PIC_RESIZE),
            array(
            'dw'=>$preview_image_width,
        )), NumberHelper::isInt($preview_img) ? FileService::getUrl($preview_img) : $preview_img, array(
            'encode'=>false,
            'class'=>'mask ib',
            'title'=>'点击查看原图',
            'data-fancybox'=>null,
            'data-caption'=>'',
        ));
        echo '<br>', HtmlHelper::link($remove_text, 'javascript:', array(
            'class'=>"remove-{$clean_field}-link"
        ));
    }
?></div>
<script>
$(function(){
    system.getScript(system.assets('faycms/js/admin/uploader.js'), function(){
        uploader.image({
            'cat': '<?php echo $cat?>',
            'field': '<?php echo $field?>',
            'remove_text': '<?php echo $remove_text?>',
            'scene': '<?php echo $scene?>',
            'preview_image_params': <?php echo json_encode(array(
                't'=>($preview_image_width == 'thumbnail' && $preview_image_width !== 0) ? FileService::PIC_THUMBNAIL :
                    ($preview_image_width == 0 ? FileService::PIC_ORIGINAL : FileService::PIC_RESIZE),
                'dw'=> NumberHelper::isInt($preview_image_width) ? $preview_image_width : 0,
            ))?>
        });
    });
})
</script>