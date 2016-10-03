<?php
use fay\helpers\Html;
use fay\services\Option;
?>
<form id="email-form" class="site-settings-form" action="<?php echo $this->url('admin/site/set-options')?>">
	<div class="row">
		<div class="col-12">
			<div class="form-field">
				<label class="title">是否启用<em class="required">*</em></label>
				<?php
					echo Html::inputRadio('email:enabled', '1', Option::get('email:enabled') == '1', array(
						'label'=>'是',
						'data-required'=>'required',
						'data-label'=>'是否启用',
					));
					echo Html::inputRadio('email:enabled', '0', Option::get('email:enabled') === '0', array(
						'label'=>'否',
						'data-required'=>'required',
						'data-label'=>'是否启用',
					));
				?>
				<p class="description">若不启用，则调用<code>fay\services\Email::send()</code>时直接返回true，不会真的发出邮件。</p>
			</div>
			<div class="form-field">
				<label class="title">主机名<em class="required">*</em></label>
				<?php echo Html::inputText('email:Host', Option::get('email:Host'), array(
					'class'=>'form-control mw400',
					'data-required'=>'required',
					'data-label'=>'主机名',
				))?>
				<p class="description">例如：smtp.163.com</p>
			</div>
			<div class="form-field">
				<label class="title">用户名<em class="required">*</em></label>
				<?php echo Html::inputText('email:Username', Option::get('email:Username'), array(
					'class'=>'form-control mw400',
					'data-required'=>'required',
					'data-label'=>'用户名',
				))?>
				<p class="description">例如：admin@faycms.com</p>
			</div>
			<div class="form-field">
				<label class="title">密码<em class="required">*</em></label>
				<?php echo Html::inputText('email:Password', Option::get('email:Password'), array(
					'class'=>'form-control mw400',
					'data-required'=>'required',
					'data-label'=>'密码',
				))?>
			</div>
			<div class="form-field">
				<label class="title">加密方式</label>
				<?php echo Html::select('email:SMTPSecure', array(
					''=>'无',
					'tls'=>'tls',
					'ssl'=>'ssl',
				), Option::get('email:SMTPSecure'), array(
					'class'=>'form-control mw400',
				))?>
				<p class="description">可选<code>tls</code>, <code>ssl</code>。若无需加密，则留空</p>
			</div>
			<div class="form-field">
				<label class="title">端口号<em class="required">*</em></label>
				<?php echo Html::inputText('email:Port', Option::get('email:Port'), array(
					'class'=>'form-control mw400',
					'data-required'=>'required',
					'data-label'=>'端口号',
				))?>
				<p class="description">一般是<span class="fc-orange">25</span></p>
			</div>
			<div class="form-field">
				<label class="title">显示名</label>
				<?php echo Html::inputText('email:FromName', Option::get('email:FromName'), array(
					'class'=>'form-control mw400',
				))?>
				<p class="description">你发出的所有邮件，发件人将显示此昵称</p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="form-field">
				<a href="javascript:;" id="email-form-submit" class="btn">提交保存</a>
			</div>
		</div>
	</div>
</form>