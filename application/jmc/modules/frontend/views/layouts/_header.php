<?php
use fay\models\Category;
use fay\helpers\Html;
use fay\core\Session;


?>
			<!---start-header---->
			<div class="header">

					<div class="main-header">
						<div class="wrap">
							<div class="logo">
								<a href="<?php echo $this->url()?>"><img src="<?php echo $this->staticFile('images/logo.gif')?>" title="logo" width="430" height="75" /></a>
							</div>
						
							</div>
							<div class="clear"> </div>
						</div>
					</div>
					<div class="clear"> </div>
					<div class="top-nav">
						<div class="wrap">
							<ul>
								<li class="<?php echo $page==1 ? active : '' ?>"><a href="<?php echo $this->url()?>">首页</a></li>
								<li><a href="<?php echo $this->url('page/about')?>">课程介绍</a></li>
								<li><a href="<?php echo $this->url('page/teacher')?>">教师队伍</a></li>
								<?php 
								    $cats = Category::model()->getTree('_system_post');
								    foreach ($cats as $cat)
								    {
								        if (!$cat['is_nav'])continue;
								        echo "<li class='";
								  
							             echo "'>";
								        echo  Html::link($cat['title'], array('cat/'.$cat['id']), array(
								            'class'=>'L',
								            'title' => false,
								        ));
								        if(!empty($cat['children'])){
								            echo '<div class="livs" style="display:none;"><ul>';
								            foreach($cat['children'] as $c){
								                if(!$c['is_nav'])continue;
								                echo '<li>', Html::link($c['title'], array('cat/'.$c['id']), array(
								                    'title'=>false,
								                )), '</li>';
								            }
								            echo '</ul></div>';
								        }
								    }
								    echo '</li>';
								?>
							   
								<li><a href="<?php echo $this->url('page/contact')?>">联系我们</a></li>
								<div class="clear"> </div>
							</ul>
						</div>
					</div>
			</div>
			<!---End-header---->