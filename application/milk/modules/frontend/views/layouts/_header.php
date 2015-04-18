<?php 
 use fay\models\Option;
 ?> 
  <header>
        <div class="container_12">
            <div class="grid_3">
                <hgroup>
                    <h1 id="site_logo"><a href="<?php echo $this->url()?>" title=""><img src="<?php echo $this->staticFile('img/miaosi.png')?>" width="155" height="66" alt="Tea and milk"></a></h1>
                  
                </hgroup>
            </div><!-- .grid_3 -->

            <div class="grid_9">
                <div class="top_header">
                    <div class="welcome">
                       <?php F::widget()->load('index-message')?>
                    </div><!-- .welcome -->
                    
                    <div class="btns">
                        <ul>
                        	<li><a href="javascript:;" onclick="setHomepage()">设为首页</a></li>
                        	<li><a href="javascript:;" onclick="AddFavorite('<?php echo $this->url()?>', '<?php echo Option::get('sitename')?>')">加入收藏</a></li>
                        </ul>
                    </div>
                            

                    </form><!-- .search -->
                </div><!-- .top_header -->

                <nav class="primary">
                    <ul>
                        <li class="<?php if ($section == 'index'){echo 'curent';}?>"><a href="<?php echo $this->url()?>">网站首页</a></li>
                        <li class="<?php if ($section == 'about'){echo 'curent';}?>"><a href="<?php echo $this->url('page/about')?>">公司介绍</a></li>
                        <li class="<?php if ($section == 'train'){echo 'curent';}?>"><a href="<?php echo $this->url('page/train')?>">技术培训</a></li>
<!--                         <li class="parent"> -->
<!--                             <a href="catalog_grid.html">Spray</a> -->
<!--                             <ul class="sub"> -->
<!--                                 <li><a href="catalog_grid.html">For home</a></li> -->
<!--                                 <li><a href="catalog_grid.html">For Garden</a></li> -->
<!--                                 <li><a href="catalog_grid.html">For Car</a></li> -->
<!--                                 <li><a href="catalog_grid.html">Other spray</a></li> -->
<!--                             </ul> -->
<!--                         </li> -->
                        <li class="<?php if ($section == 'employ'){echo 'curent';}?>"><a href="<?php echo $this->url('page/employ')?>">人才招聘</a></li>
                        <li class="<?php if ($section == 'contact'){echo 'curent';}?>"><a href="<?php echo $this->url('page/contact')?>">联系我们</a></li>
                
                    </ul>
                </nav><!-- .primary -->
            </div><!-- .grid_9 -->
        </div>
    </header>