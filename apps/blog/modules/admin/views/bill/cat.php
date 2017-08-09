<?php
use fay\helpers\HtmlHelper;

function showCats($cats, $dep = 0){?>
    <ul class="cat-list">
    <?php foreach($cats as $k=>$c){?>
        <li class="cat-item <?php if(!$k)echo 'first';?>">
            <div class="cat-item-container">
                <span class="fr options">
                    <span class="w100 block fl">
                    排序：<?php echo HtmlHelper::inputNumber('sort[]', $c['sort'], array(
                        'size'=>3,
                        'maxlength'=>3,
                        'data-id'=>$c['id'],
                        'class'=>"edit-sort w30 cat-{$c['id']}-sort",
                    ))?>
                    </span>
                    <?php echo HtmlHelper::link('添加子节点', '#create-cat-dialog', array(
                        'class'=>'create-cat-link',
                        'data-title'=>HtmlHelper::encode($c['title']),
                        'data-id'=>$c['id'],
                    ))?>
                    <?php echo HtmlHelper::link('编辑', '#edit-cat-dialog', array(
                        'class'=>'edit-cat-link',
                        'data-id'=>$c['id'],
                    ))?>
                    <?php echo HtmlHelper::link('删除', array('admin/category/remove', array(
                        'id'=>$c['id'],
                    )), array(
                        'class'=>'remove-link fc-red',
                    ))?>
                </span>
                <span class="cat-item-title cat-<?php echo $c['id']?> <?php if(empty($c['children']))
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
<div class="col-1">
    <div class="cat-list-container">
        <?php showCats($cats)?>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->renderPartial('admin/category/_common');?>