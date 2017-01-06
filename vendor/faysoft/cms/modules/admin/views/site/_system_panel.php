<?php
use fay\helpers\HtmlHelper;
use fay\services\OptionService;
?>
<form id="system-form" class="site-settings-form" action="<?php echo $this->url('admin/site/set-options')?>">
	<div class="row">
		<div class="col-6">
			<div class="form-field">
				<h4>文章</h4>
			</div>
			<div class="form-field">
				<label class="title">是否启用文章审核功能</label>
				<?php
					echo HtmlHelper::inputRadio('system:post_review', 1, OptionService::get('system:post_review') != 0, array(
						'label'=>'是',
					)), HtmlHelper::inputRadio('system:post_review', 0, OptionService::get('system:post_review') == 0, array(
						'label'=>'否',
					));
				?>
			</div>
			<div class="form-field">
				<label class="title">是否启用角色文章分类权限控制</label>
				<?php
					echo HtmlHelper::inputRadio('system:post_role_cats', 1, OptionService::get('system:post_role_cats') != 0, array(
						'label'=>'是',
					)), HtmlHelper::inputRadio('system:post_role_cats', 0, OptionService::get('system:post_role_cats') == 0, array(
						'label'=>'否',
					));
				?>
			</div>
			<div class="form-field">
				<label class="title">是否仅显示通过审核的文章评论</label>
				<?php
					echo HtmlHelper::inputRadio('system:post_comment_verify', 1, OptionService::get('system:post_comment_verify') != 0, array(
						'label'=>'是',
					)), HtmlHelper::inputRadio('system:post_comment_verify', 0, OptionService::get('system:post_comment_verify') == 0, array(
						'label'=>'否',
					));
				?>
			</div>
			<div class="form-field">
				<h4>图片</h4>
			</div>
			<div class="form-field">
				<label class="title">输出图片质量</label>
				<?php echo HtmlHelper::inputText('system:image_quality', OptionService::get('system:image_quality', 75), array(
					'class'=>'form-control mw200',
				))?>
			</div>
			
		</div>
		<div class="col-6">
			<div class="form-field">
				<h4>用户</h4>
			</div>
			<div class="form-field">
				<label class="title">用户昵称必填</label>
				<?php
					echo HtmlHelper::inputRadio('system:user_nickname_required', 1, OptionService::get('system:user_nickname_required') != 0, array(
						'label'=>'是',
					)), HtmlHelper::inputRadio('system:user_nickname_required', 0, OptionService::get('system:user_nickname_required') == 0, array(
						'label'=>'否',
					));
				?>
			</div>
			<div class="form-field">
				<label class="title">用户昵称唯一</label>
				<?php
					echo HtmlHelper::inputRadio('system:user_nickname_unique', 1, OptionService::get('system:user_nickname_unique') != 0, array(
						'label'=>'是',
					)), HtmlHelper::inputRadio('system:user_nickname_unique', 0, OptionService::get('system:user_nickname_unique') == 0, array(
						'label'=>'否',
					));
				?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="form-field">
				<a href="javascript:;" id="system-form-submit" class="btn">提交保存</a>
			</div>
		</div>
	</div>
</form>