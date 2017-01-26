<?php
/**
 * @var $this \fay\core\View
 * @var $group array
 */
$this->appendCss($this->appStatic('css/group.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<div class="swiper-slide" id="group-32">
			<div class="layer brand"><img src="<?php echo $this->appStatic('images/group/brand.png')?>"></div>
			<div class="layer subtitle">
				<span class="title">盟誓</span>
				<span>第三式</span>
			</div>
			<div class="layer left-bottom"><img src="<?php echo $this->appStatic('images/group/lb.png')?>"></div>
			<div class="layer group-name"><h1><?php echo $group['name']?></h1></div>
			<div class="layer form">
			<?php echo F::form()->open()?>
				<fieldset>
					<label for="vows" id="label-vows">内定誓词</label>
					<div>
						<select id="vows" class="form-control"></select>
						<a href="javascript:;" class="btn-2" id="select-vow">选定</a>
					</div>
				</fieldset>
				<fieldset>
					<label for="vow" id="label-vow">内定誓词</label>
					<textarea name="vow" id="vow" class="form-control"></textarea>
				</fieldset>
				<fieldset>
					<label for="words" id="label-words">我想对兄弟说</label>
					<textarea name="words" id="words" class="form-control"></textarea>
					<p class="description">限200字</p>
				</fieldset>
				<fieldset>
					<label for="secrecy_period" id="label-secrecy-period">解密期</label>
					<div>
						<span class="btn-3">一年后</span>
					</div>
				</fieldset>
			<?php echo F::form()->close()?>
			</div>
			<div class="layer actions"><?php echo F::form()->submitLink('盟誓', array(
					'class'=>'btn btn-1',
				))?></div>
		</div>
		<div class="swiper-slide" id="group-31">
			<div class="layer brand"><img src="<?php echo $this->appStatic('images/group/brand.png')?>"></div>
			<div class="layer" id="step">
				<span class="number">第三式</span>
				<span class="title">盟誓</span>
			</div>
			<div class="layer guangong"><img src="<?php echo $this->appStatic('images/group/guangong.png')?>"></div>
		</div>
	</div>
</div>
<script>
$(function(){
	var vow = {
		/**
		 * 获取系统内置誓词
		 */
		'getVows': function(){
			$.ajax({
				'type': 'GET',
				'url': system.url('api/vow/list'),
				'dataType': 'json',
				'cache': false,
				'success': function(resp){
					if(resp.status){
						$.each(resp.data, function(i, d){
							$('#vows').append('<option>'+d+'</option>');
						});
					}else{
						common.toast(resp.message, 'error');
					}
				}
			});
		},
		/**
		 * 选择系统誓词
		 */
		'selectVow': function(){
			$('#select-vow').on('click', function(){
				$('#vow').val($('#vows').val());
			});
		},
		'init': function(){
			this.getVows();
			this.selectVow();
		}
	};
	
	vow.init();
})
</script>
<script type="text/javascript" src="<?php echo $this->appStatic('js/group.js')?>"></script>
<script>group.init();</script>