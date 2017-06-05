<div class="box" id="box-views" data-name="views">
    <div class="box-title">
        <a class="tools remove" title="隐藏"></a>
        <h4>阅读数</h4>
    </div>
    <div class="box-content">
        <?php echo F::form()->inputText('views', array(
            'class'=>'form-control mw150',
        ), 0)?>
        <p class="fc-grey mt5">设定初始值，会按实际PV递增。</p>
        <?php if(F::form()->getData('real_views') !== null){?>
        <p class="misc-pub-section mt6 pl0">
            <span>真实阅读数：</span>
            <?php echo F::form()->getData('real_views')?>
        </p>
        <?php }?>
    </div>
</div>