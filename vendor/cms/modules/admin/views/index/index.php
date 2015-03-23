<?php
use fay\helpers\RequestHelper;

$boxes_cp = $enabled_boxes;?>
<div class="col-2-1">
	<div class="col-left">
		<div class="col-content dragsort" id="dashboard-left">
		<?php 
			$browser = RequestHelper::getBrowser();
			if($browser[0] == 'msie' && ($browser[1] == '6.0' || $browser[1] == '7.0')){
		?>
			<div class="box">
				<div class="box-title">
					<a class="tools toggle" title="点击以切换"></a>
					<h4>您正在使用很古老的浏览器</h4>
				</div>
				<div class="box-content">
					<img src="<?php echo $this->url()?>images/ie.png" class="fr" style="margin:0 0 10px 10px;" />
					<p>
						似乎您正在使用的 
						<a href="http://www.microsoft.com/windows/internet-explorer/">Internet Explorer</a>
						版本严重过时。使用过时的浏览器会降低您计算机的安全性。同时，为了获得最佳体验，请升级您的浏览器。
						<br /><br />
						升级您的
						<a href="http://windows.microsoft.com/zh-cn/windows/upgrade-your-browser">Internet Explorer</a>，或了解一下
						<a href="http://browsehappy.com/?locale=zh_CN">先进的浏览器</a>的有关信息。
					</p>
					<div class="clear"></div>
				</div>
			</div>
		<?php }?>
		<?php 
			if(isset($_settings['dashboard-left'])){
				foreach($_settings['dashboard-left'] as $box){
					foreach($boxes_cp as $k =>$v){
						if($box == $v){
							$ajax = in_array($box, F::app()->ajax_boxes) ? true : false;
							F::app()->widget->render($box, array(), $ajax);
							unset($boxes_cp[$k]);
							break;
						}
					}
				}
			}
		?>
		</div>
	</div>
	<div class="col-right">
		<div class="col-content dragsort" id="dashboard-right">
			<?php 
			if(isset($_settings['dashboard-right'])){
				foreach($_settings['dashboard-right'] as $box){
					foreach($boxes_cp as $k =>$v){
						if($box == $v){
							$ajax = in_array($box, F::app()->ajax_boxes) ? true : false;
							F::app()->widget->render($box, array(), $ajax);
							unset($boxes_cp[$k]);
							break;
						}
					}
				}
			}
			
			foreach($boxes_cp as $box){
				$ajax = in_array($box, F::app()->ajax_boxes) ? true : false;
				F::app()->widget->render($box, array(), $ajax);
			}
			?>
		</div>
	</div>
	<div class="clear"></div>
</div>
<script>
common.dragsortKey = 'admin_dashboard_box_sort';
</script>