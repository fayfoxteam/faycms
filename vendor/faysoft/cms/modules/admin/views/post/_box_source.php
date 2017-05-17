<div class="box" id="box-source" data-name="source">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>来源</h4>
    </div>
    <div class="box-content">
        <div class="form-field">
            <label for="source" class="title">来源</label>
            <?php echo F::form()->inputText('source', array(
                'id'=>'source',
                'class'=>'form-control',
            ))?>
        </div>
        <div class="form-field">
            <label for="source-link" class="title">来源链接</label>
            <?php echo F::form()->inputText('source_link', array(
                'id'=>'source-link',
                'class'=>'form-control',
            ))?>
            <p class="fc-grey mt5">不要忘了http(s)://</p>
        </div>
    </div>
</div>