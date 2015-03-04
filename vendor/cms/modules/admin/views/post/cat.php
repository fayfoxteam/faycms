<?php
use fay\helpers\Html;

function showCats($cats, $dep = 0){?>
	<ul class="cat-list">
	<?php foreach($cats as $k=>$c){?>
		<li class="cat-item <?php if(!$k)echo 'first';?>">
			<div class="cat-item-container">
				<span class="fr options">
					<?php if(F::app()->checkPermission('admin/post/cat-sort')){?>
					<span class="w100 block fl">
					排序：<?php echo Html::inputText('sort[]', $c['sort'], array(
						'size'=>3,
						'maxlength'=>3,
						'data-id'=>$c['id'],
						'class'=>"edit-sort w30 cat-{$c['id']}-sort",
					))?>
					</span>
					<?php }?>
					<?php 
						echo $c['is_nav'] ? Html::link('导航:<span class="tick-circle"></span>', 'javascript:;', array(
							'class'=>F::app()->checkPermission('admin/post/cat-edit') ? 'is-nav-link' : '',
							'data-id'=>$c['id'],
							'encode'=>false,
							'title'=>F::app()->checkPermission('admin/post/cat-edit') ? '点击可改变状态' : false,
						)) : Html::link('导航:<span class="cross-circle"></span>', 'javascript:;', array(
							'class'=>F::app()->checkPermission('admin/post/cat-edit') ? 'is-nav-link' : '',
							'data-id'=>$c['id'],
							'encode'=>false,
							'title'=>F::app()->checkPermission('admin/post/cat-edit') ? '点击可改变状态' : false,
						));
						echo Html::link('分类属性', array('admin/post-prop/index', array(
							'id'=>$c['id'],
						)), array(), true);
						echo Html::link('查看该分类', array('admin/post/index', array(
							'cat_id'=>$c['id'],
						)), array(), true);
						if(F::app()->checkPermission('admin/post/cat-create')){
							echo Html::link('添加子节点', '#create-cat-dialog', array(
								'class'=>'create-cat-link',
								'data-title'=>Html::encode($c['title']),
								'data-id'=>$c['id'],
							));
						}
						if(F::app()->checkPermission('admin/post/cat-edit')){
							echo Html::link('编辑', '#edit-cat-dialog', array(
								'class'=>'edit-cat-link',
								'data-id'=>$c['id'],
							));
						}
						if(F::app()->checkPermission('admin/post/cat-remove')){
							echo Html::link('删除', array('admin/category/remove', array(
								'id'=>$c['id'],
							)), array(
								'class'=>'remove-link color-red',
							));
							echo Html::link('删除全部', array('admin/category/remove-all', array(
								'id'=>$c['id'],
							)), array(
								'class'=>'remove-link color-red',
							));
						}
					?>
				</span>
				<span class="cat-item-title cat-<?php echo $c['id']?> <?php if(empty($c['children']))
						echo 'terminal';
					else
						echo 'parent';?>">
					<?php if(empty($c['children'])){?>
						<?php echo Html::encode($c['title'])?>
					<?php }else{?>
						<strong><?php echo Html::encode($c['title'])?></strong>
					<?php }?>
					<?php if($c['alias']){?>
						<em class="color-grey">[ <?php echo $c['alias']?> ]</em>
					<?php }?>
					<?php echo Html::link('发布文章', array('admin/post/create', array(
						'cat_id'=>$c['id'],
					)), array(
						'class'=>'color-green hover-link',
						'prepend'=>'<i class="icon-pencil"></i>',
					), true)?>
				</span>
			</div>
			<?php if(!empty($c['children'])){
				showCats($c['children'], $dep + 1);
			}?>
		</li>
	<?php }?>
	</ul>
<?php }?>
<div class="col-1">
	<div class="cat-list-container">
		<?php showCats($cats)?>
	</div>
	<div class="clear"></div>
</div>

<?php $this->renderPartial('category/_common');?>