<div class="form-field">
    <label class="title bold">名称<em class="required">*</em></label>
    <?php echo F::form()->inputText('title', array(
        'class'=>'form-control',
        'data-ajax-param-name'=>'tag',
    ))?>
</div>
<div class="form-field">
    <label class="title bold">SEO Title</label>
    <?php echo F::form()->inputText('seo_title', array(
        'class'=>'form-control',
    ))?>
</div>
<div class="form-field">
    <label class="title bold">SEO Keywords</label>
    <?php echo F::form()->textarea('seo_keywords', array(
        'class'=>'form-control h30 autosize',
    ))?>
</div>
<div class="form-field">
    <label class="title bold">SEO Description</label>
    <?php echo F::form()->textarea('seo_description', array(
        'class'=>'form-control h60 autosize',
    ))?>
</div>