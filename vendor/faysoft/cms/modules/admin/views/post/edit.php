<?php
use cms\helpers\LinkHelper;
use cms\models\tables\PostsTable;
use cms\models\tables\RolesTable;
use cms\services\OptionService;
use cms\services\post\PostCategoryService;
use cms\services\user\UserRoleService;
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;

/**
 * @var $post array
 */
$enabled_boxes = F::form('setting')->getData('enabled_boxes');
$boxes_cp = $enabled_boxes;//复制一份出来，因为后面会不停的被unset
?>
<?php echo F::form()->open()?>
<div class="poststuff">
    <div class="post-body">
        <div class="post-body-content">
            <div class="mb30"><?php echo F::form()->inputText('title', array(
                'id'=>'title',
                'class'=>'form-control bigtxt',
                'placeholder'=>'在此键入标题',
            ));?></div>
            <div class="mb30"><?php $this->renderPartial('_content')?></div>
        </div>
        <div class="postbox-container-1 dragsort" id="side">
            <div class="box operation" id="box-operation">
                <div class="box-title">
                    <h3>操作</h3>
                </div>
                <div class="box-content">
                    <div>
                        <?php
                            echo F::form()->submitLink('更新', array(
                                'class'=>'btn',
                            ));
                            if($post['status'] == PostsTable::STATUS_PUBLISHED){
                                //已发布的文章，展示一个查看链接
                                echo HtmlHelper::link('查看', LinkHelper::getPostLink($post), array(
                                    'class'=>'btn btn-grey ml5',
                                    'target'=>'_blank',
                                ));
                            }    
                        ?>
                    </div>
                    <div class="misc-pub-section mt6">
                        <strong>状态：</strong>
                        <?php
                            $options = array(PostsTable::STATUS_DRAFT=>'草稿');
                            $current_status = F::form()->getData('status');
                            if(F::app()->post_review){
                                //开启审核，显示待审核选项
                                $options[PostsTable::STATUS_PENDING] = '待审核';
                                if($current_status == PostsTable::STATUS_REVIEWED || F::app()->checkPermission('cms/admin/post/review')){
                                    //若当前文章状态为“通过审核”或者有审核权限，显示“通过审核”选项
                                    $options[PostsTable::STATUS_REVIEWED] = '通过审核';
                                }
                            }
                            if(!F::app()->post_review || $current_status == PostsTable::STATUS_PUBLISHED || F::app()->checkPermission('cms/admin/post/publish')){
                                //未开启审核，或当前文章状态为“已发布”，或者有发布权限，显示“已发布”选项
                                $options[PostsTable::STATUS_PUBLISHED] = '已发布';
                            }
                            echo HtmlHelper::select('status', $options, F::form()->getData('status'), array(
                                'class'=>'form-control mw100 ib',
                                'id'=>'edit-status-selector'
                            ));
                        ?>
                    </div>
                    <div class="misc-pub-section">
                        <strong>是否置顶？</strong>
                        <?php echo F::form()->inputRadio('is_top', 1, array('label'=>'是'))?>
                        <?php echo F::form()->inputRadio('is_top', 0, array('label'=>'否'), true)?>
                    </div>
                    <div class="misc-pub-section">
                        <strong>创建时间：</strong>
                        <?php echo HtmlHelper::tag('abbr', array(
                            'class'=>'time',
                            'title'=>DateHelper::format($post['create_time']),
                        ), DateHelper::niceShort($post['create_time']))?>
                    </div>
                    <div class="misc-pub-section">
                        <strong>更新时间：</strong>
                        <?php echo HtmlHelper::tag('abbr', array(
                            'class'=>'time',
                            'title'=>DateHelper::format($post['update_time']),
                        ), DateHelper::niceShort($post['update_time']))?>
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
            foreach($boxes_cp as $box){
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
    post.postId = <?php echo $post['id']?>;
    <?php if(!UserRoleService::service()->is(RolesTable::ITEM_SUPER_ADMIN) && OptionService::get('system:post_role_cats')){?>
        post.roleCats = <?php echo json_encode(PostCategoryService::service()->getAllowedCatIds())?>;
    <?php }?>
    post.init();
});
</script>