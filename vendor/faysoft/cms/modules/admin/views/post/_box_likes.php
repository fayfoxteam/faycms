<div class="box" id="box-likes" data-name="likes">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>点赞数</h4>
    </div>
    <div class="box-content">
        <?php echo F::form()->inputText('likes', array(
            'class'=>'form-control mw150',
        ), 0)?>
        <?php if(F::form()->getData('real_likes') !== null){?>
        <p class="fc-grey mt5">设定初始值，会按实际获赞增减。</p>
        <p class="misc-pub-section mt6 pl0">
            <span>真实点赞数：</span>
            <?php echo F::form()->getData('real_likes')?>
        </p>
        <?php }?>
    </div>
</div>