  <header>
        <div class="container_12">
            <div class="grid_3">
                <hgroup>
                    <h1 id="site_logo"><a href="<?php echo $this->url()?>" title=""><img src="<?php echo $this->staticFile('img/logo.png')?>" alt="Tea and milk"></a></h1>
                    <h2 id="site_description">Tea and milk</h2>
                </hgroup>
            </div><!-- .grid_3 -->

            <div class="grid_9">
                <div class="top_header">
                    <div class="welcome">
                        免费送奶茶，不要问我为什么，有钱，任性！<span style="color: red;">店址：绍兴市城南大道900号。</span>
                    </div><!-- .welcome -->

              
                    <form class="search">
                        <input type="submit" class="search_button" value="">
                        <input type="text" name="search" class="search_form" value="" placeholder="Search entire store here...">
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