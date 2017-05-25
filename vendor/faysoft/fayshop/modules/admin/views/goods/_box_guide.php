<div class="box" id="box-guide" data-name="guide">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>导购</h4>
    </div>
    <div class="box-content">
        <div class="misc-pub-section b0">
            <strong>排序</strong>
            <?php echo F::form()->inputText('sort', array(
                'class'=>'form-control w90 ib',
            ), 10000)?>
            <span class="fc-grey">数字越小越靠前</span>
        </div>
    </div>
</div>