<?php
use fay\helpers\Html;
use fay\models\Option;
use fay\models\tables\Users;

function showCats($cats, $dep = 0, $open_dep = 2){?>
	<ul class="tree">
	<?php foreach($cats as $k=>$c){?>
		<li class="leaf-container <?php if(!$k)echo 'first';?> <?php echo 'dep-'.$dep?> <?php if($dep + 2 > $open_dep) echo 'close'?>">
			<div class="leaf">
				<span class="fr options">
					<?php if(F::app()->checkPermission('admin/post/cat-sort')){?>
					<span class="w115 block fl">
					排序：<?php echo Html::inputText('sort[]', $c['sort'], array(
						'data-id'=>$c['id'],
						'class'=>"form-control w50 edit-sort cat-{$c['id']}-sort",
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
								'class'=>'remove-link fc-red',
							));
							echo Html::link('删除全部', array('admin/category/remove-all', array(
								'id'=>$c['id'],
							)), array(
								'class'=>'remove-link fc-red',
							));
						}
					?>
				</span>
				<span class="leaf-title cat-<?php echo $c['id']?> <?php if(empty($c['children']))
						echo 'terminal';
					else
						echo 'parent';?>">
					<?php if(empty($c['children'])){?>
						<?php echo Html::encode($c['title'])?>
					<?php }else{?>
						<strong><?php echo Html::encode($c['title'])?></strong>
					<?php }?>
					<?php if($c['alias']){?>
						<em class="fc-grey hidden-not-lg">[ <?php echo $c['alias']?> ]</em>
					<?php }?>
					<?php if(F::session()->get('role') == Users::ROLE_SUPERADMIN || !Option::get('system.role_cats') || in_array($c['id'], F::session()->get('role_cats'))){
						echo Html::link('发布文章', array('admin/post/create', array(
							'cat_id'=>$c['id'],
						)), array(
							'class'=>'fc-green hover-link',
							'prepend'=>'<i class="fa fa-pencil"></i>',
						), true);
					}?>
				</span>
			</div>
			<?php if(!empty($c['children'])){
				showCats($c['children'], $dep + 1, $open_dep);
			}?>
		</li>
	<?php }?>
	</ul>
<?php }?>
<div class="row">
	<div class="col-12">
		<div class="form-inline tree-container">
			<?php showCats($cats, 0, F::form('setting')->getData('default_dep', 2))?>
		</div>
	</div>
</div>
<?php $this->renderPartial('category/_common');?>