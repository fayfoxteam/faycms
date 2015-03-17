
<?php 

// dump($links);
?>

<div class="grid_1_of_3 images_1_of_3">
								  <h3><?php echo $data['title']?></h3>
								  <ul>
								  <?php foreach ($links as $link){?>
								  	<li><a href="<?php echo $link['url']?>"><?php echo $link['title']?></a></li>
								
								  	<?php }?>
								  </ul>
							     <div class="button"><span><a href="#">Read More</a></span></div>
							</div>