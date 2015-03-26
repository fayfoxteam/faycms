<?php 
use fay\models\Setting;
use fay\helpers\Html;
use fay\models\Option;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link type="image/x-icon" href="<?php echo $this->url()?>favicon.ico" rel="shortcut icon" />
<?php echo $this->getCss()?>

<link type="text/css" rel="stylesheet" href="<?php echo $this->url()?>css/font-awesome.min.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->url()?>css/admin/style-metro.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $this->url()?>css/admin/style-responsive.css" />

<script type="text/javascript" src="<?php echo $this->url()?>js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/system.min.js"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo F::app()->session->get('id', 0)?>';
</script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/fayfox.block.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/common.js"></script>
<title><?php echo $subtitle?> | <?php echo Option::get('sitename')?>后台</title>
</head>
<body class="<?php $admin_body_class = Setting::model()->get('admin_body_class');echo $admin_body_class['class']?>" id="faycms">
<div class="wrapper">
	<?php include '_sidebar_menu.php'?>
	<div class="container main-content">
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
							'class'=>'fa fa-cog faycms-setting-link',
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
</body>
</html>