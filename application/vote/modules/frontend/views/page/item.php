<?php
// dump($pages);
?>


    <div class="row">
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->url()?>">主页</a></li>
            <li><a href="javascript:;"><?php echo $pages['title']?></a></li>
            <li class="pull-right">是否登录</li>
        </ol>
    </div>
    <div class="row">
        <div class="header-title text-center">
               <h3><?php echo $pages['title']?></h3>
               <hr />
        </div>
        <p>
            <?php echo $pages['content']?>
            <div class="clear-20"></div>
        </p>
        
         
    </div>
    
   
