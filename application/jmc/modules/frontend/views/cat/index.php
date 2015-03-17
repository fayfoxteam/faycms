<div class="content">
    <div class="wrap">
        <div class="services">
            <div class="service-content">
                <h3><?php echo $cat['title']?></h3>
                <?php $listview->showData();?>
                
                
                <div class="clear-30"></div>
                <?php $listview->showPage()?>
            </div>
            
            <?php F::widget()->load('right-sider')?>
            
        </div>
        <div class="clear"></div>
    </div>
</div>