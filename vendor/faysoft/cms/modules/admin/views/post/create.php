<?php
use cms\models\tables\PostsTable;
use cms\models\tables\RolesTable;
use cms\services\OptionService;
use cms\services\post\PostCategoryService;
use cms\services\user\UserRoleService;

$enabled_boxes = F::form('setting')->getData('enabled_boxes');
$boxes_cp = $enabled_boxes;//复制一份出来，因为后面会不停的被unset
?>
<?php echo F::form()->open()?>
<?php echo F::form()->inputHidden('cat_id')?>
<div class="poststuff">
    <div class="post-body">
        <div class="post-body-content">
            <div class="mb30"><?php echo F::form()->inputText('title', array(
                'id'=>'title',
                'class'=>'form-control bigtxt',
                'placeholder'=>'在此键入标题',
            ))?></div>
            <div class="mb30 cf"><?php $this->renderPartial('_content')?></div>
        </div>
        <div class="postbox-container-1 dragsort" id="side">
            <div class="box operation" id="box-operation">
                <div class="box-title">
                    <h3>操作</h3>
                </div>
                <div class="box-content">
                    <div>
                        <?php echo F::form()->submitLink('提交', array(
                            'class'=>'btn',
                        ))?>
                    </div>
                    <div class="misc-pub-section mt6">
                        <strong>状态：</strong>
                        <?php
                            $options = array(PostsTable::STATUS_DRAFT=>'草稿');
                            $default = '';
                            if(F::app()->post_review){
                                //开启审核，显示待审核选项。若没有审核权限，默认为待审核
                                $options[PostsTable::STATUS_PENDING] = '待审核';
                                if(F::app()->checkPermission('cms/admin/post/review')){
                                    $options[PostsTable::STATUS_REVIEWED] = '通过审核';
                                }
                                $default = PostsTable::STATUS_PENDING;
                            }
                            if(!F::app()->post_review || F::app()->checkPermission('cms/admin/post/publish')){
                                //未开启审核，或者有审核权限，显示发布按钮，并默认为“立即发布”
                                $options[PostsTable::STATUS_PUBLISHED] = '已发布';
                                $default = PostsTable::STATUS_PUBLISHED;
                            }
                            echo F::form()->select('status', $options, array(
                                'class'=>'form-control mw100 mt5 ib',
                            ), $default);
                        ?>
                    </div>
                    <div class="misc-pub-section">
                        <strong>是否置顶？</strong>
                        <?php echo F::form()->inputRadio('is_top', 1, array('label'=>'是'))?>
                        <?php echo F::form()->inputRadio('is_top', 0, array('label'=>'否'), true)?>
                    </div>
                </div>
            </div>
            <?php
                if(isset($_box_sort_settings['side'])){
                    foreach($_box_sort_settings['side'] as $box){
                        $k = array_search($box, $boxes_cp);
                        if($k !== false){
                            if(isset(F::app()->boxes[$k]['view'])){
                                $this->renderPartial(F::app()->boxes[$k]['view'], $this->getViewData());
                            }else{
                                $this->renderPartial('_box_'.$box, $this->getViewData());
                            }
                            unset($boxes_cp[$k]);
                        }
                    }
                }
            ?>
        </div>
        <div class="postbox-container-2 dragsort" id="normal"><?php 
            if(isset($_box_sort_settings['normal'])){
                foreach($_box_sort_settings['normal'] as $box){
                    $k = array_search($box, $boxes_cp);
                    if($k !== false){
                        if(isset(F::app()->boxes[$k]['view'])){
                            $this->renderPartial(F::app()->boxes[$k]['view'], $this->getViewData());
                        }else{
                            $this->renderPartial('_box_'.$box, $this->getViewData());
                        }
                        unset($boxes_cp[$k]);
                    }
                }
            }
            
            //最后多出来的都放最后面
            foreach($boxes_cp as $k=>$box){
                if(isset(F::app()->boxes[$k]['view'])){
                    $this->renderPartial(F::app()->boxes[$k]['view'], $this->getViewData());
                }else{
                    $this->renderPartial('_box_'.$box, $this->getViewData());
                }
            }
        ?></div>
    </div>
</div>
<?php echo F::form()->close()?>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/post.js')?>"></script>
<script>
$(function(){
    common.dragsortKey = 'admin_post_box_sort';
    common.filebrowserImageUploadUrl = system.url('cms/admin/file/img-upload', {'cat':'post'});
    common.filebrowserFlashUploadUrl = system.url('cms/admin/file/upload', {'cat':'post'});
    post.boxes = <?php echo json_encode($enabled_boxes)?>;
    post.postId = <?php echo isset($post['id']) ? $post['id'] : 0 ?>;
    <?php if(!UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN) && OptionService::get('system:post_role_cats')){?>
        post.roleCats = <?php echo json_encode(PostCategoryService::service()->getAllowedCatIds())?>;
    <?php }?>
    post.init();
});
</script>