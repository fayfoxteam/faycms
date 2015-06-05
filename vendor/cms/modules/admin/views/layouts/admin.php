<?php 
use fay\helpers\Html;
use fay\models\Option;
use fay\models\File;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link type="image/x-icon" href="<?php echo $this->url()?>favicon.ico" rel="shortcut icon" />

<link type="text/css" rel="stylesheet" href="<?php echo $this->url()?>css/font-awesome.min.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->url()?>css/admin/style-responsive.css" />
<?php echo $this->getCss()?>

<script type="text/javascript" src="<?php echo $this->url()?>js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/system.min.js"></script>
<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo $this->url()?>js/html5.js"></script>
<![endif]-->
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo F::app()->session->get('id', 0)?>';
</script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/fayfox.block.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/common.min.js"></script>
<title><?php echo $subtitle?> | <?php echo Option::get('site.sitename')?>后台</title>
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
						if(isset($nav['role'])){
							if(is_array($nav['role']) && !in_array(F::app()->session->get('role'), $nav['role'])){
								continue;
							}else if(F::app()->session->get('role') != $nav['role']){
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
			<ul class="user-info-menu fr">
				<li class="dropdown-container hover-line message" id="faycms-message">
					<?php echo Html::link('', '#faycms-messages-container', array(
						'class'=>'dropdown',
						'prepend'=>array(
							'tag'=>'i',
							'class'=>'fa fa-bell-o',
							'text'=>'',
						),
						'append'=>array(
							'tag'=>'span',
							'class'=>'badge badge-green hide',
							'text'=>'0',
						)
					))?>
					<ul class="dropdown-menu" id="faycms-messages-container">
						<li>
							<ul id="faycms-messages">
								<li class="faycms-message-item"><span class="faycms-message-container">
									<span class="ellipsis" title="">暂无未读信息</span>
								</span></li>
							</ul>
						</li>
						<li class="last"><?php
							echo Html::link('查看全部', array('admin/notification/my'), array(
								'target'=>'_blank',
							));
						?></li>
					</ul>
				</li>
				<li class="dropdown-container user-profile">
					<a href="#user-profile-menu" class="dropdown"><?php 
						echo Html::img(F::session()->get('avatar'), File::PIC_THUMBNAIL, array(
							'class'=>'circle',
							'width'=>28,
							'spare'=>'avatar',
						))
					?><span>您好，<?php echo F::session()->get('username')?><i class="fa fa-angle-down"></i></span></a>
					<ul class="dropdown-menu" id="user-profile-menu">
						<li><?php
							echo Html::link('我的个人信息', array('admin/profile/index'), array(
								'prepend'=>array(
									'tag'=>'i',
									'class'=>'fa fa-user',
									'text'=>'',
								),
							));
						?></li>
						<li class="last"><?php
							echo Html::link('退出', array('admin/login/logout'), array(
								'prepend'=>array(
									'tag'=>'i',
									'class'=>'fa fa-lock',
									'text'=>'',
								),
							));
						?></li>
					</ul>
				</li>
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

						echo Html::link($sublink['text'], $sublink['uri'], $html_options);
					}?></h1>
			</div>
			<div class="operate-env">
				<div class="screen-meta-links">
					<?php if(isset($_setting_panel)){
						echo Html::link('', '#faycms-setting-content', array(
							'class'=>'fa fa-cog fa-2x faycms-setting-link',
                            'title'=>'设置',
						));
						echo Html::tag('div', array(
							'id'=>'faycms-setting-content',
                            'class'=>'dialog-content',
							'wrapper'=>array(
								'tag'=>'div',
								'class'=>'dialog hide',
							),
                            'prepend'=>'<h4>设置</h4>',
						), $this->renderPartial($_setting_panel, array(), -1, true));
					}?>
				</div>
			</div>
		</div>
		<?php echo F::app()->flash->get();?>
		<?php echo $content?>
	</div>
</div>
<script>
$(function(){
	//系统消息提示
	common.headerNotification();
	setInterval(common.headerNotification, 30000);
	
	<?php
		$forms = F::forms();
		foreach($forms as $k=>$f){?>
			common.validformParams.forms['<?php echo $k?>'] = {
				'rules':<?php echo json_encode($f->getJsRules())?>,
				'labels':<?php echo json_encode($f->getLabels())?>,
				'model':'<?php echo $f->getJsModel()?>',
				'scene':'<?php echo $f->getScene()?>'
			};
	<?php }?>
	common.init();
});
</script>
<img src="<?php echo $this->url()?>images/throbber.gif" class="hide" />
<img src="<?php echo $this->url()?>images/ajax-loading.gif" class="hide" />
<img src="<?php echo $this->url()?>images/loading.gif" class="hide" />
</body>
</html>