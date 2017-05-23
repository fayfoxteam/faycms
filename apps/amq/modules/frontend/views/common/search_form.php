<div class="PR">
    <form method="get" action="<?php echo $this->url('search')?>" id="search-form">
        <?php echo F::form()->inputText('keywords', array(
            'class'=>'amc-search',
            'placeholder'=>'请输入关键字',
        ));?>
        <a href="javascript:$('#search-form').submit();"><img src="<?php echo $this->appAssets('images/search.png')?>" alt="" class="amc-searchfor"></a>
    </form>
</div>