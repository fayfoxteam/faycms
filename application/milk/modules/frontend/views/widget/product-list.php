<?php 
use fay\helpers\Html;
// dump($cats);
?>



               <aside id="categories_nav">
		    <header class="aside_title">
                        <h3><?php echo Html::encode($data['title'])?></h3>
                    </header>

		    <nav class="right_menu">
			<ul>
			<?php foreach ($cats as $key => $cat){?>
		
			    <li><a href="<?php echo $this->url('cat/'.$cat['id'])?>"><?php echo Html::encode($cat['title']) ?></a></li>
			    <?php }?>

			</ul>
		    </nav><!-- .right_menu -->
                </aside><!-- #categories_nav -->