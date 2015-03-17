<?php 

use fay\helpers\Html;
use fay\helpers\String;

?>	
	<!---start-wrap---->
		

			<!---start-images-slider---->
			<div class="image-slider">
						<!-- Slideshow 1 -->
					    <ul class="rslides rslides1" id="slider1" style="max-width: 2500px;">
					 
					      <li id="rslides1_s1" class="" style="float: none; position: absolute; opacity: 0; z-index: 1; display: list-item; -webkit-transition: opacity 600ms ease-in-out; transition: opacity 600ms ease-in-out;">
					      <img src="<?php echo $this->staticFile('images/slider2.png')?>" width="100%" height="420"  alt="">
					      	<div class="slider-info">
					      		<p>绍兴文理元培</p>
					      		
					      		<a href="<?php echo $this->url('page/about')?>">更多>></a>
					      	</div>
					      </li>
					      <li id="rslides1_s2" class="rslides1_on" style="float: left; position: relative; opacity: 1; z-index: 2; display: list-item; -webkit-transition: opacity 600ms ease-in-out; transition: opacity 600ms ease-in-out;">
					      <img src="<?php echo $this->staticFile('images/slider2.png')?>" width="100%" height="420" alt="">
					      	<div class="slider-info">
					      		<p>绍兴文理元培</p>
					      		
					      		<a href="<?php echo $this->url('page/about')?>">更多>></a>
					      	</div>
					      </li>
					    </ul>
						 <!-- Slideshow 2 -->
					</div>
			<!---End-images-slider---->
			<!----start-content----->
			<div class="content">

				<div class="clear"> </div>
				<div class="boxs">
					<div class="wrap">
						<div class="section group">
							<div class="grid_1_of_3 images_1_of_3">
								  <h3><?php echo Html::encode($about['title'])?></h3>
								  <?php echo String::niceShort($about['content'], 180)?>
							     <div class="button"><span><a href="<?php echo $this->url('page/'.$about['id'])?>">查看更多>></a></span></div>
							</div>
							<div class="grid_1_of_3 images_1_of_3">
								  <h3><?php echo $teacher['title'] ?></h3>
								  <?php echo $teacher['abstract']?>
							     <div class="button"><span><a href="<?php echo $this->url('page/'.$teacher['id'])?>">查看更多>></a></span></div>
							</div>
							<?php F::widget()->load('friendly-links')?>
						</div>
					</div>
					</div>
			<!----End-content----->
		</div>
		<!---End-wrap---->