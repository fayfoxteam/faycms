<?php 
use fay\helpers\HtmlHelper;

function showCats($cats, $dep = 0){?>
    <ul class="tree">
    <?php foreach($cats as $k=>$c){?>
        <li class="leaf-container <?php if(!$k)echo 'first';?>">
            <div class="leaf">
                <span class="fr options">
                    <span class="w115 block fl">
                    排序：<?php echo HtmlHelper::inputText('sort[]', $c['sort'], array(
                        'size'=>3,
                        'maxlength'=>3,
                        'data-id'=>$c['id'],
                        'class'=>"form-control w50 edit-sort cat-{$c['id']}-sort",
                    ))?>
                    </span>
                    <?php if(F::app()->checkPermission('fayexam/admin/question/cat-create')){
                        echo HtmlHelper::link('添加子节点', '#create-cat-dialog', array(
                            'class'=>'create-cat-link',
                            'data-title'=>HtmlHelper::encode($c['title']),
                            'data-id'=>$c['id'],
                        ));
                    }
                    if(F::app()->checkPermission('fayexam/admin/question/cat-edit')){
                        echo HtmlHelper::link('编辑', '#edit-cat-dialog', array(
                            'class'=>'edit-cat-link',
                            'data-id'=>$c['id'],
                        ));
                    }
                    if(F::app()->checkPermission('fayexam/admin/question/cat-remove')){
                        echo HtmlHelper::link('删除', array('cms/admin/category/remove', array(
                            'id'=>$c['id'],
                        )), array(
                            'class'=>'remove-link fc-red',
                        ));
                    }?>
                </span>
                <span class="leaf-title cat-<?php echo $c['id']?> <?php if(empty($c['children']))
                        echo 'terminal';
                    else
                        echo 'parent';?>">
                    <?php if(empty($c['children'])){?>
                        <?php echo HtmlHelper::encode($c['title'])?>
                    <?php }else{?>
                        <strong><?php echo HtmlHelper::encode($c['title'])?></strong>
                    <?php }?>
                    <?php if($c['alias']){?>
                        <em class="fc-grey">[ <?php echo $c['alias']?> ]</em>
                    <?php }?>
                </span>
            </div>
            <?php if(!empty($c['children'])){
                showCats($c['children'], $dep + 1);
            }?>
        </li>
    <?php }?>
    </ul>
<?php }?>
<div class="row">
    <div class="col-12">
        <div class="form-inline tree-container">
            <?php showCats($cats)?>
        </div>
    </div>
</div>
<?php $this->renderPartial('cms/admin/category/_common');?>