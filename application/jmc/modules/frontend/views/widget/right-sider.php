<?php 

// dump($data);
// dump($cats);
?>

<div class="services-sidebar">
							<h3><?php echo $data['title']?></h3>
							 <ul>
							 <?php foreach ($cats as $cat){?>
							  	<li><a href="<?php echo $this->url('cat/'.$cat['id'])?>"><?php echo $cat['title']?></a></li>
							  	
							  	<?php }?>
					 		 </ul>
					 		<?php F::widget()->load('new-articles')?>
		  </div>
		  <div class="clear"></div>