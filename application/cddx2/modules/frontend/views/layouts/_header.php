<?php
use fay\helpers\Html;
?>
<header class="g-hd">
	<div class="w1000">
		<div class="hd-search-bar"></div>
	</div>
	<nav class="g-nav">
		<div class="w1000">
			<ul>
				<?php foreach($menus as $m){
					echo Html::link($m['title'], $m['link'], array(
						'wrapper'=>'li',
					));
				}?>
			</ul>
		</div>
	</nav>
</header>