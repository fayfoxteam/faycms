<div id="banner">
    <?php \F::widget()->load('index-slides')?>
</div>
<div class="w1000 clearfix bg-white">
    <div class="w230 fl">
        <?php
        //直接引用widget
        \F::widget()->render('fay/category_posts', array(
            'title'=>'热门文章',
            'order'=>'views',
            'template'=>'frontend/widget/category_posts',
        ));
        //echo $this->renderPartial('common/_login_panel')?>
    </div>
    <div class="ml240">
        <div class="box category-post">
            <div class="box-title">
                <h3>友情链接</h3>
            </div>
            <div class="box-content">
                <div class="st"><div class="sl"><div class="sr"><div class="sb">
                    <div class="p16">
                        <div>
                            <ul><?php $listview->showData()?></ul>
                        </div>
                        <?php $listview->showPager();?>
                    </div>
                </div></div></div></div>
            </div>
        </div>
    </div>
</div>