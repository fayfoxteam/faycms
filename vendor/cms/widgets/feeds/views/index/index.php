<?php
use fay\helpers\Date;
use fay\models\tables\Logs;
?>
<style>
.feeds li{background-color:#fafafa;margin-bottom:7px;line-height:28px;height:28px;}
.feeds li .date{margin-right:15px;color:#c1cbd0;}
.feeds li i{width:28px;line-height:28px;display:inline-block;text-align:center;}
</style>
<div class="box" data-name="<?php echo $this->__name?>">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<a class="tools toggle" title="点击以切换"></a>
		<h4>Feeds</h4>
	</div>
	<div class="box-content">
		<div class="tabbable">
			<ul class="nav-tabs">
				<li class="active"><a href="#feeds-tab-1">System Logs</a></li>
				<li><a href="#feeds-tab-2">Notification</a></li>
			</ul>
			<div class="tab-content">
				<div id="feeds-tab-1" class="tab-pane p5">
					<div id="system-log-container">
						<ul class="feeds">
						<?php foreach($logs as $l){?>
							<li>
								<i class="<?php switch($l['type']){
									case Logs::TYPE_ERROR:
										echo 'fa fa-bolt bg-red';
									break;
									case Logs::TYPE_WARMING:
										echo 'fa fa-bell-o bg-yellow';
									break;
								}?>"></i>
								<span class="fr date" title="<?php echo Date::format($l['create_time'])?>"><?php echo Date::niceShort($l['create_time'])?></span>
								<span class="desc"><?php echo $l['code']?></span>
							</li>
						<?php }?>
						</ul>
					</div>
				</div>
				<div id="feeds-tab-2" class="tab-pane p5 hide">
					此功能开发中...
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(function(){
	if($('#system-log-container').height() > 250){
		system.getScript(system.url('js/jquery.slimscroll.min.js'), function(){
			$("#system-log-container").slimScroll({
				'allowPageScroll':true,
				'height':'250px',
				'color':'#a1b2bd',
				'opacity':.3,
				'alwaysVisible':true
			});
		});
	}
});
</script>