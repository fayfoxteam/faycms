<?php
use fay\helpers\Html;
use fay\models\tables\Categories;

F::form('create')->setModel(Categories::model());
F::form('edit')->setModel(Categories::model());
?>
<div class="hide">
	<div id="edit-cat-dialog" class="dialog">
		<div class="dialog-content w600">
			<h4>编辑分类<em>（当前分类：<span id="edit-cat-title" class="fc-orange"></span>）</em></h4>
			<?php echo F::form('edit')->open(array('admin/category/edit'))?>
				<?php echo Html::inputHidden('id')?>
				<table class="form-table">
					<tr>
						<th class="adaption">标题<em class="required">*</em></th>
						<td><?php echo Html::inputText('title', '', array(
							'class'=>'form-control',
						))?></td>
					</tr>
					<tr>
						<th class="adaption">别名</th>
						<td>
							<?php echo Html::inputText('alias', '', array(
								'class'=>'form-control w150 ib',
							))?>
							<span class="fc-grey">若您不确定它的用途，请不要修改</span>
						</td>
					</tr>
					<tr>
						<th valign="top" class="adaption">描述</th>
						<td><?php echo Html::textarea('description', '', array(
							'class'=>'form-control h90 autosize',
						))?></td>
					</tr>
					<tr>
						<th class="adaption">排序</th>
						<td>
							<?php echo Html::inputText('sort', '1000', array(
								'class'=>'form-control w100 ib',
							))?>
							<span class="fc-grey">0-65535之间，数值越小，排序越靠前</span>
						</td>
					</tr>
					<tr>
						<th class="adaption">父节点</th>
						<td>
							<?php echo Html::select('parent', array($root=>'根节点')+Html::getSelectOptions($cats, 'id', 'title'), '', array(
								'class'=>'form-control',
							))?>
						</td>
					</tr>
					<tr>
						<th class="adaption">导航</th>
						<td>
							<?php echo Html::inputCheckbox('is_nav', '1', false, array(
								'label'=>'在导航栏显示',
							))?>
							<span class="fc-grey">（该选项实际效果视主题而定）</span>
						</td>
					</tr>
					<tr>
						<th valign="top" class="adaption">插图</th>
						<td><div id="upload-cat-pic-for-edit-container">
							<?php
							echo Html::inputHidden('file_id', '', array(
								'id'=>'cat-pic-for-edit',
							));
							echo Html::link('上传插图', 'javascript:;', array(
								'class'=>'upload-cat-pic btn btn-sm mb5',
								'id'=>'upload-cat-pic-for-edit',
							))?>
							<span class="fc-grey">（该选项实际效果视主题而定）</span>
							<div id="cat-pic-for-edit-container"></div>
						</div></td>
					</tr>
					<tr>
						<th class="adaption"><a href="javascript:;" class="toggle-seo-info" style="font-weight:normal;text-decoration:underline;">SEO信息</a></th>
						<td></td>
					</tr>
					<tr class="hide toggle">
						<th class="adaption">Title</th>
						<td><?php echo Html::inputText('seo_title', '', array(
							'class'=>'form-control',
						))?></td>
					</tr>
					<tr class="hide toggle">
						<th class="adaption">Keywords</th>
						<td><?php echo Html::inputText('seo_keywords', '', array(
							'class'=>'form-control',
						))?></td>
					</tr>
					<tr class="hide toggle">
						<th valign="top" class="adaption">Description</th>
						<td><?php echo Html::textarea('seo_description', '', array(
							'class'=>'form-control',
							'rows'=>5,
						))?></td>
					</tr>
					<tr>
						<th class="adaption"></th>
						<td>
							<a href="javascript:;" class="btn" id="edit-form-submit">编辑分类</a>
							<a href="javascript:;" class="btn btn-grey fancybox-close">取消</a>
						</td>
					</tr>
				</table>
			<?php echo F::form('edit')->close()?>
		</div>
	</div>
</div>
<div class="hide">
	<div id="create-cat-dialog" class="dialog">
		<div class="dialog-content w600">
			<h4>添加子分类<em>（父分类：<span id="create-cat-parent" class="fc-orange"></span>）</em></h4>
			<?php echo F::form('create')->open(array('admin/category/create'))?>
				<?php echo Html::inputHidden('parent')?>
				<table class="form-table">
					<tr>
						<th class="adaption">标题<em class="required">*</em></th>
						<td><?php echo Html::inputText('title', '', array(
							'class'=>'form-control',
						))?></td>
					</tr>
					<tr>
						<th class="adaption">别名</th>
						<td>
							<?php echo Html::inputText('alias', '', array(
								'class'=>'form-control w150 ib',
							))?>
							<span class="fc-grey">若您不确定它的用途，请不要修改</span>
						</td>
					</tr>
					<tr>
						<th valign="top" class="adaption">描述</th>
						<td><?php echo Html::textarea('description', '', array(
							'class'=>'form-control h90 autosize',
						))?></td>
					</tr>
					<tr>
						<th class="adaption">排序</th>
						<td>
							<?php echo Html::inputText('sort', '1000', array(
								'class'=>'form-control w100 ib',
							))?>
							<span class="fc-grey">0-65535之间，数值越小，排序越靠前</span>
						</td>
					</tr>
					<tr>
						<th class="adaption">导航</th>
						<td>
							<?php echo Html::inputCheckbox('is_nav', '1', true, array(
								'label'=>'在导航栏显示',
							))?>
							<span class="fc-grey">（该选项实际效果视主题而定）</span>
						</td>
					</tr>
					<tr>
						<th valign="top" class="adaption">插图</th>
						<td><div id="upload-cat-pic-for-create-container">
							<?php
							echo Html::inputHidden('file_id', '', array(
								'id'=>'cat-pic-for-create',
							));
							echo Html::link('上传插图', 'javascript:;', array(
								'class'=>'upload-cat-pic',
								'id'=>'upload-cat-pic-for-create',
							))?>
							<span class="fc-grey">（该选项实际效果视主题而定）</span>
							<div id="cat-pic-for-create-container"></div>
						</div></td>
					</tr>
					<tr>
						<th class="adaption"><a href="javascript:;" class="toggle-seo-info" style="font-weight:normal;text-decoration:underline;">SEO信息</a></th>
						<td></td>
					</tr>
					<tr class="hide toggle">
						<th class="adaption">Title</th>
						<td><?php echo Html::inputText('seo_title', '', array(
							'class'=>'form-control',
						))?></td>
					</tr>
					<tr class="hide toggle">
						<th class="adaption">Keywords</th>
						<td><?php echo Html::inputText('seo_keywords', '', array(
							'class'=>'form-control',
						))?></td>
					</tr>
					<tr class="hide toggle">
						<th valign="top" class="adaption">Description</th>
						<td><?php echo Html::textarea('seo_description', '', array(
							'class'=>'form-control',
							'rows'=>5,
						))?></td>
					</tr>
					<tr>
						<th class="adaption"></th>
						<td>
							<a href="javascript:;" class="btn" id="create-form-submit">添加新分类</a>
							<a href="javascript:;" class="btn btn-grey fancybox-close">取消</a>
						</td>
					</tr>
				</table>
			<?php echo F::form('edit')->close()?>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->url()?>js/plupload.full.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/fayfox.editsort.js"></script>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/cat.js"></script>
<script>
$(function(){
	cat.init();
})
</script>