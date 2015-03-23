<?php 
use fay\models\Setting;
use fay\helpers\Html;
use fay\models\tables\Users;
use fay\helpers\String;
use fay\helpers\SqlHelper;
use fay\helpers\Backtrace;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo $this->getCss()?>

<!--[if (!IE)|(gte IE 8)]><!-->
<link type="text/css" rel="stylesheet" href="<?php echo $this->url()?>css/admin/style-metro.css" />
<!--<![endif]-->

<!--[if lt IE 8]>
<link type="text/css" rel="stylesheet" href="<?php echo $this->url()?>css/admin/style.css" />
<![endif]-->

<!--[if IE 6]>
<link type="text/css" rel="stylesheet" href="<?php echo $this->url()?>css/admin/ie6.css" />
<![endif]-->
<script type="text/javascript" src="<?php echo $this->url()?>js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/system.min.js"></script>
<script>
system.base_url = '<?php echo $this->url()?>';
system.user_id = '<?php echo F::app()->session->get('id', 0)?>';
</script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/fayfox.block.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/common.min.js"></script>
<title><?php echo $subtitle?> | Fayfox后台</title>
</head>
<body class="<?php $admin_body_class = Setting::model()->get('admin_body_class');echo $admin_body_class['class']?>">
<div class="wrapper">
	<div class="adminbar">
		<ul class="adminbar-left">
			<li class="toggle-hover"><a href="<?php echo $this->url()?>" class="item" target="_blank"><i class="fa fa-home"></i>站点首页</a></li>
			<li class="toggle-hover"><a href="<?php echo $this->url('admin/index/index')?>" class="item"><i class="fa fa-dashboard"></i>控制台</a></li>
			<?php if(F::app()->session->get('role') == Users::ROLE_SUPERADMIN){?>
			<li class="toggle-hover"><a href="<?php echo $this->url('tools')?>" class="item"><i class="fa fa-wrench"></i>Tools</a></li>
			<?php }?>
		</ul>
	</div>
	<div class="menuback"></div>
	<div class="menuwrap">
		<?php include '_admin_left.php';?>
	</div>
	<div class="ffcontent" id="ffcontent">
		<div class="ffbody">
			<div class="ffbody-content">
				<div class="screen-meta">
				<?php if(isset($_help)){?>
					<div class="hide" id="ffhelp-content"><?php $this->renderPartial($_help);?></div>
				<?php }?>
				<?php if(isset($_setting)){?>
					<div class="hide" id="ffsetting-content"><?php $this->renderPartial($_setting);?></div>
				<?php }?>
				</div>
				<div class="screen-meta-links">
				<?php if(isset($_help)){?>
					<div class="ffhelp-link-wrap">
						<a href="#ffhelp-content" class="ffhelp-link">帮助</a>
					</div>
				<?php }?>
				<?php if(isset($_setting)){?>
					<div class="ffsetting-link-wrap">
						<a href="#ffsetting-content" class="ffsetting-link">设置</a>
					</div>
				<?php }?>
				</div>
				<h2 class="sub-title">
					<?php echo isset($subtitle) ? $subtitle : '';?>
					<?php if(isset($sublink)){
						$htmlOptions = isset($sublink['htmlOptions']) ? $sublink['htmlOptions'] : array();
						if(isset($htmlOptions['class'])){
							$htmlOptions['class'] .= ' sub-link';
						}else{
							$htmlOptions['class'] = ' sub-link';
						}

						echo Html::link($sublink['text'], $sublink['uri'], $htmlOptions);
					}?>
				</h2>
				<div class="notification-wrap">
					<?php echo F::app()->flash->get();?>
				</div>
				<div class="notification-wrap-js"></div>
				<?php echo $content?>
			</div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<script>
//系统消息提示
$(function(){
	<?php if($js_rules = F::form()->getJsRules()){?>
	common.validformParams = {
		'rules':<?php echo json_encode($js_rules)?>,
		'labels':<?php echo json_encode(F::form()->getLabels())?>
	};
	<?php }?>
	common.init();
});

</script>
<img src="<?php echo $this->url()?>images/throbber.gif" class="hide" />
<img src="<?php echo $this->url()?>images/ajax-loading.gif" class="hide" />
</body>
</html>