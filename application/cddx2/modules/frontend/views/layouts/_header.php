<?php
use fay\helpers\Html;
use fay\models\Category;

$cats = Category::model()->getTree('__root__');
?>
<header class="g-hd">
	<div class="w1000">
		<div class="hd-search-bar">
			<form action="<?php echo $this->url('search/index')?>">
				<span><?php echo date('Y年m月d日')?></span>
				<span class="sep">|</span>
				<span><?php echo Html::inputText('q', F::app()->input->get('q', 'trim'), array(
					'placeholder'=>'请输入关键词',
				))?></span>
				<input type="submit" value="搜索" style="width:50px;height:20px;">
			</form>
		</div>
	</div>
	<nav class="g-nav">
		<div class="w1000">
			<ul><?php
				echo Html::link('网站首页', array(), array(
					'wrapper'=>'li',
				));
				foreach($cats as $c){
					if(!$c['is_nav']) continue;
					echo Html::link($c['title'], array('cat/'.$c['id']), array(
						'wrapper'=>'li',
					));
				}
			?></ul>
		</div>
	</nav>
</header>