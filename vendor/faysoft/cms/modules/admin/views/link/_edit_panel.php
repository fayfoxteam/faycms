<?php
use fay\helpers\HtmlHelper;

?>
<div class="form-field">
    <label class="title bold">名称<em class="required">*</em></label>
    <?php echo F::form()->inputText('title', array(
        'class'=>'form-control mw400',
    ))?>
    <p class="description">例如：Faycms</p>
</div>
<div class="form-field">
    <label class="title bold">网址<em class="required">*</em></label>
    <?php echo F::form()->inputText('url', array(
        'class'=>'form-control mw400',
    ))?>
    <p class="description">例子：http://www.faycms.com/ —— 不要忘了 http(s)://</p>
</div>
<div class="form-field">
    <label class="title bold">描述</label>
    <?php echo F::form()->textarea('description', array(
        'class'=>'form-control mw500 h90 autosize',
    ))?>
    <p class="description">通常，当访客将鼠标光标悬停在链接表链接的上方时，它会显示出来。根据主题的不同，也可能显示在链接下方。</p>
</div>
<div class="form-field">
    <label class="title bold">打开方式</label>
    <p>
        <?php echo F::form()->inputRadio('target', '_blank', array('label'=>'_blank — 新窗口或新标签。'), true)?>
    </p>
    <p>
        <?php echo F::form()->inputRadio('target', '_top', array('label'=>'_top — 不包含框架的当前窗口或标签。'))?>
    </p>
    <p>
        <?php echo F::form()->inputRadio('target', '_none', array('label'=>'_none — 同一窗口或标签。'))?>
    </p>
    <p class="description">为您的链接选择目标框架。</p>
</div>
<div class="form-field">
    <label class="title bold">可见性</label>
    <p>
        <?php echo F::form()->inputCheckbox('visible', '0', array(
            'label'=>'将这个链接设置为不可见',
        ))?>
    </p>
    <p class="description">前台是否可见</p>
</div>
<div class="form-field">
    <label class="title bold">排序</label>
    <?php echo F::form()->inputText('sort', array(
        'class'=>'form-control mw200',
    ), 100)?>
    <p class="description"></p>
</div>
<?php if($cats){//若没有设置分类，则不显示分类下拉框（并不是所有的系统都要对友链做分类）?>
<div class="form-field">
    <label class="title bold">分类</label>
    <?php echo F::form()->select('cat_id', array('0'=>'--分类--') + HtmlHelper::getSelectOptions($cats, 'id', 'title'), array(
        'class'=>'form-control mw200',
    ));?>
    <p class="description">分类效果视主题而定，可留空</p>
</div>
<?php }?>
<div class="form-field">
    <label class="title bold">Logo</label>
    <?php $this->renderPartial('file/_upload_image', array(
        'field'=>'logo',
        'cat'=>'link',
        'label'=>'Logo',
        'preview_image_width'=>0,
    ))?>
    <p class="description">是否需要Logo视主题而定</p>
</div>