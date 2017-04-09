<div class="box" id="box-seo" data-name="seo">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>SEO优化</h4>
    </div>
    <div class="box-content">
        <div class="form-field">
            <label for="seo-title" class="title">标题（title）</label>
            <?php echo F::form()->inputText('seo_title', array(
                'id'=>'seo-title',
                'class'=>'form-control',
            ))?>
        </div>
        <div class="form-field">
            <label for="seo-keyword" class="title">关键词（keyword）</label>
            <?php echo F::form()->inputText('seo_keywords', array(
                'id'=>'seo-keywords',
                'class'=>'form-control',
            ))?>
        </div>
        <div class="form-field">
            <label for="seo-description" class="title">描述（description）</label>
            <?php echo F::form()->textarea('seo_description', array(
                'id'=>'seo-description',
                'class'=>'form-control h60',
            ))?>
        </div>
    </div>
</div>