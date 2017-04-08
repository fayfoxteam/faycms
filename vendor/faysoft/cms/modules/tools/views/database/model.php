<?php
use fay\helpers\HtmlHelper;
?>
<div class="row">
	<div class="col-8">
		<div class="col-2-2-body-content">
			<?php echo HtmlHelper::textarea('code', '', array(
				'style'=>'font-family:Consolas,Monaco,monospace',
				'id'=>'code',
				'class'=>'form-control autosize',
			))?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="col-4">
		<h3>Tables</h3>
		<ul class="table-list">
		<?php foreach($tables as $t){
			$t_name = preg_replace("/^{$prefix}(.*)/", '$1', array_shift($t), 1);
			if(strpos($t_name, '_') &&
				in_array(substr($t_name, 0, strpos($t_name, '_')), $apps) &&
				substr($t_name, 0, strpos($t_name, '_')) != APPLICATION){
				continue;
			}
		?>
			<li class="">
				<span class="fr">
					<?php echo HtmlHelper::link('show', array('cms/tools/database/get-model', array(
						't'=>$t_name,
					)), array(
						'class'=>'get-model',
						'data-name'=>$t_name,
					))?>
					|
					<?php echo HtmlHelper::link('download', array('cms/tools/database/download-model', array(
						't'=>$t_name,
					)))?>
					|
					<?php echo HtmlHelper::link('dd', array('cms/tools/database/dd', array(
						't'=>$t_name,
					)))?>
				</span>
				<span class="t-name pointer ellipsis w155" title="<?php echo $t_name?>"><?php echo $t_name?></span>
			</li>
		<?php }?>
		</ul>
	</div>
</div>
<script>
$(function(){
	$(".table-list").delegate('.get-model', 'click', function(){
		$(".t-name").removeClass("bold");
		$(".table-list li").removeClass("disc");
		$(this).parent().parent().addClass("disc");
		$(this).parent().siblings(".t-name").addClass("bold");
		$.ajax({
			type: "GET",
			url: system.url("tools/database/get-model"),
			data: {"t":$(this).attr('data-name')},
			cache: false,
			success: function(resp){
				$("#code").val(resp);
				autosize.update($("#code"));
			}
		});
		return false;
	});

	$(".table-list").delegate('.t-name', 'click', function(){
		$(this).parent().find(".get-model").click();
		return false;
	});

	<?php if(!empty($current_table)){?>
		$("[data-name='<?php echo $current_table?>']").click();
	<?php }else{?>
		$(".get-model").first().click();
	<?php }?>
});
</script>