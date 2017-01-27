<?php
/**
 * @var $this \fay\core\View
 */
$this->appendCss($this->appStatic('css/group.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<div class="swiper-slide" id="group-1">
			<div class="layer layer-1"><img src="<?php echo $this->appStatic('images/group/1.png')?>"></div>
		</div>
		<div class="swiper-slide" id="group-2">
			<div class="layer brand"><img src="<?php echo $this->appStatic('images/group/brand.png')?>"></div>
			<div class="layer steps">
				<a class="step" href="<?php echo $this->url('group/step4')?>">
					<span class="number">第五式</span>
					<span class="title">解密</span>
				</a>
				<a class="step" href="<?php echo $this->url('group/step4')?>">
					<span class="number">第四式</span>
					<span class="title">兰谱</span>
				</a>
				<a class="step" href="javascript:;">
					<span class="number">第三式</span>
					<span class="title">盟誓</span>
				</a>
				<a class="step" href="javascript:;">
					<span class="number">第二式</span>
					<span class="title">拜帖</span>
				</a>
				<a class="step swiper-to" href="javascript:;" data-slide="2">
					<span class="number">第一式</span>
					<span class="title">称谓</span>
				</a>
			</div>
			<div class="layer explain">
				<p>使用说明：</p>
				<p>根据您结义进程，点击即可选择。</p>
			</div>
		</div>
		<div class="swiper-slide" id="group-3">
			<div class="layer brand"><img src="<?php echo $this->appStatic('images/group/brand.png')?>"></div>
			<div class="layer" id="step">
				<span class="number">第一式</span>
				<span class="title">称谓</span>
			</div>
			<div class="layer guangong"><img src="<?php echo $this->appStatic('images/group/guangong.png')?>"></div>
		</div>
		<div class="swiper-slide" id="group-4">
			<div class="layer brand"><img src="<?php echo $this->appStatic('images/group/brand.png')?>"></div>
			<div class="layer subtitle">
				<span class="title">称谓</span>
				<span>第一式</span>
			</div>
			<div class="layer left-bottom"><img src="<?php echo $this->appStatic('images/group/lb.png')?>"></div>
			<div class="layer explain">
				<p>为方便网络查询和身份识别，体现个性及涵养，请自行设计一款义结金兰称谓(雅号)。</p>
				<p>本系统最多只支持9人(含)同时义结金兰，由发起者商定并启动结义程序。</p>
			</div>
			<div class="layer form">
				<?php echo F::form()->open('api/group/create')?>
					<fieldset>
						<label>称&nbsp;谓</label>
						<div class="field-container"><?php echo F::form()->inputText('name', array(
								'class'=>'form-control',
								'placeholder'=>'仅支持中文称谓，限五字内',
							))?></div>
					</fieldset>
					<fieldset>
						<label>验证码</label>
						<div class="field-container"><?php
							echo F::form()->inputText('captcha', array(
								'class'=>'form-control short'
							)),
							F::form()->captcha(array(
								'dw'=>85,
								'dh'=>32,
								'class'=>'captcha'
							));
							?></div>
					</fieldset>
					<fieldset>
						<label>结义人数</label>
						<div class="field-container"><?php echo F::form()->inputText('count', array(
								'class'=>'form-control'
							))?></div>
					</fieldset>
				<?php echo F::form()->close()?>
			</div>
			<div class="layer actions">
				<?php echo F::form()->submitLink('提&nbsp;&nbsp;交', array(
					'class'=>'btn btn-1',
					'encode'=>false,
				))?>
			</div>
		</div>
	</div>
</div>
<script>
	common.form.afterAjaxSubmit = function(resp){
		if(resp.status){
			window.location.href = system.url('group/step2', {'group_id': resp.data.group.id});
		}else{
			common.toast(resp.message, 'error');
		}
	}
</script>
<script type="text/javascript" src="<?php echo $this->appStatic('js/group.js')?>"></script>
<script>group.init();</script>