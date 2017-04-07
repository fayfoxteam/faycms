<?php
use fay\helpers\HtmlHelper;

function showCats($tree, $dep = 0){?>
	<ul class="tree">
	<?php foreach($tree as $k=>$node){?>
		<li class="leaf-container <?php if(!$k)echo 'first';?>">
			<div class="leaf">
				<span class="fr options">
					<span class="w115 block fl">
					排序：<?php echo HtmlHelper::inputText('sort[]', $node['sort'], array(
						'size'=>3,
						'maxlength'=>3,
						'data-id'=>$node['id'],
						'class'=>"form-control w50 edit-sort node-sort-{$node['id']}",
					))?>
					</span>
					<?php
						if(F::app()->checkPermission('cms/admin/menu/create')){
							echo HtmlHelper::link('添加子节点', '#create-cat-dialog', array(
								'class'=>'create-cat-link',
								'data-title'=>HtmlHelper::encode($node['title']),
								'data-id'=>$node['id'],
							));
						}
						if(F::app()->checkPermission('cms/admin/menu/edit')){
							echo HtmlHelper::link('编辑', '#edit-cat-dialog', array(
								'class'=>'edit-cat-link',
								'data-id'=>$node['id'],
							));
						}
						if(F::app()->checkPermission('cms/admin/menu/remove')){
							echo HtmlHelper::link('删除', array('cms/admin/menu/remove', array(
								'id'=>$node['id'],
							)), array(
								'class'=>'remove-link fc-red',
								'title'=>'删除该节点，其子节点将被挂载到其父节点',
							));
							echo HtmlHelper::link('删除全部', array('cms/admin/menu/remove-all', array(
								'id'=>$node['id'],
							)), array(
								'class'=>'remove-link fc-red',
								'title'=>'删除该节点及其所有子节点',
							));
						}
					?>
				</span>
				<span class="leaf-title node-<?php echo $node['id']?> <?php if(empty($node['children']))
						echo 'terminal';
					else
						echo 'parent';?>">
					<?php
					
						echo $node['enabled'] ? HtmlHelper::link('<span class="tick-circle"></span>', 'javascript:;', array(
							'class'=>F::app()->checkPermission('cms/admin/menu/edit') ? 'enabled-link mr5' : 'mr5',
							'data-id'=>$node['id'],
							'encode'=>false,
							'title'=>F::app()->checkPermission('cms/admin/menu/edit') ? '是否启用（点击可改变状态）' : false,
						)) : HtmlHelper::link('<span class="cross-circle"></span>', 'javascript:;', array(
							'class'=>F::app()->checkPermission('cms/admin/menu/edit') ? 'enabled-link mr5' : 'mr5',
							'data-id'=>$node['id'],
							'encode'=>false,
							'title'=>F::app()->checkPermission('cms/admin/post/cat-edit') ? '是否启用（点击可改变状态）' : false,
						));
						
						if(empty($node['children'])){
							echo HtmlHelper::encode($node['title']);
						}else{
							echo HtmlHelper::tag('strong', array(), $node['title']);
						}
					?>
					<?php if($node['alias']){?>
						<em class="fc-grey">[ <?php echo $node['alias']?> ]</em>
					<?php }?>
					<em class="fc-grey"><?php echo $node['link']?></em>
				</span>
			</div>
			<?php if(!empty($node['children'])){
				showCats($node['children'], $dep + 1);
			}?>
		</li>
	<?php }?>
	</ul>
<?php }?>
<div class="row">
	<div class="col-12">
		<div class="form-inline tree-container">
			<?php showCats($menus)?>
		</div>
	</div>
</div>
<?php include '_common.php';?>