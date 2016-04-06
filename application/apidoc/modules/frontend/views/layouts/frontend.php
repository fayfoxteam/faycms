<?php 
use fay\helpers\Html;
use fay\models\Option;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link type="image/x-icon" href="<?php echo $this->url()?>favicon.ico" rel="shortcut icon" />

<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/font-awesome.min.css')?>" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->appStatic('css/style.css')?>" >
<?php echo $this->getCss()?>

<script type="text/javascript" src="<?php echo $this->assets('js/jquery-1.8.3.min.js')?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/system.min.js')?>"></script>
<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo $this->assets('js/html5.js')?>"></script>
<![endif]-->
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo F::session()->get('user.id', 0)?>';
</script>
<script type="text/javascript" src="<?php echo $this->appStatic('js/common.js')?>"></script>
<title><?php echo empty($subtitle) ? '' : $subtitle . ' | '?><?php echo Option::get('site:sitename')?></title>
</head>
<body id="faycms">
<div class="wrapper">
	<?php include '_sidebar_menu.php'?>
	<div class="container main-content">
		<nav class="user-info-navbar">
			<ul class="user-info-menu fl">
				<li><a href="javascript:;" class="toggle-sidebar"><i class="fa fa-bars"></i></a></li>
				<?php
					foreach(F::app()->_top_nav as $nav){
						if(isset($nav['roles'])){
							is_array($nav['roles']) || $nav['roles'] = array($nav['roles']);
							if(!array_intersect(F::session()->get('user.roles'), $nav['roles'])){
								continue;
							}
						}
						echo Html::link('', array($nav['router']), array(
							'target'=>isset($nav['target']) ? $nav['target'] : false,
							'title'=>$nav['label'],
							'prepend'=>'<i class="'.$nav['icon'].'"></i>',
							'wrapper'=>array(
								'tag'=>'li',
								'class'=>'hover-line',
							)
						));
					}
				?>
			</ul>
		</nav>
		<div class="page-title">
			<div class="title-env">
				<h1 class="title"><?php
					echo isset($subtitle) ? $subtitle : '无标题';
					if(isset($sublink)){
						$html_options = isset($sublink['html_options']) ? $sublink['html_options'] : array();
						$html_options['prepend'] = '<i class="fa fa-link"></i>';
						if(isset($html_options['class'])){
							$html_options['class'] .= ' quick-link';
						}else{
							$html_options['class'] = 'quick-link';
						}
						echo Html::link($sublink['text'], $sublink['uri'], $html_options, true);
					}?></h1>
			</div>
		</div>
		<?php echo $content?>
	</div>
</div>
<script>
$(function(){
	common.init();
});
</script>
</body>
</html>