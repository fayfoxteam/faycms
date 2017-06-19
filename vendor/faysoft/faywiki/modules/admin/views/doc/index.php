<?php
use cms\helpers\ListTableHelper;
use cms\models\tables\PostsTable;
use fay\helpers\HtmlHelper;

/**
 * @var $listview \fay\common\ListView
 */

$cols = F::form('setting')->getData('cols', array());
?>
<div class="row">
    <div class="col-12">
        <?php echo F::form('search')->open(null, 'get', array(
            'class'=>'form-inline',
        ))?>
            <div class="mb5"><?php
                echo F::form('search')->select('keywords_field', array(
                    'title'=>'文档标题',
                    'id'=>'文档ID',
                    'user_id'=>'作者ID',
                ), array(
                    'class'=>'form-control',
                )),
                '&nbsp;',
                F::form('search')->inputText('keywords', array(
                    'class'=>'form-control w200',
                )),
                ' | ',
                F::form('search')->select('cat_id', array(
                    ''=>'--分类--',
                ) + HtmlHelper::getSelectOptions($cats, 'id', 'title'), array(
                    'class'=>'form-control',
                ));
                echo F::form('search')->inputCheckbox('with_child', 1, array(
                    'label'=>'子分类',
                    'title'=>'符合所选分类子分类的文档也将被搜出',
                ));
            ?></div>
            <div><?php
                echo F::form('search')->select('time_field', array(
                    'create_time'=>'创建时间',
                    'update_time'=>'更新时间',
                ), array(
                    'class'=>'form-control',
                )),
                '&nbsp;',
                F::form('search')->inputText('start_time', array(
                    'data-rule'=>'datetime',
                    'data-label'=>'开始时间',
                    'placeholder'=>'开始时间',
                    'class'=>'form-control datetimepicker',
                )),
                ' - ',
                F::form('search')->inputText('end_time', array(
                    'data-rule'=>'datetime',
                    'data-label'=>'结束时间',
                    'placeholder'=>'结束时间',
                    'class'=>'form-control datetimepicker',
                )),
                '&nbsp;',
                F::form('search')->submitLink('查询', array(
                    'class'=>'btn btn-sm',
                ))?>
            </div>
        <?php echo F::form('search')->close()?>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <ul class="subsubsub fl">
            <li class="all <?php if(F::app()->input->get('status') === null && F::app()->input->get('deleted') === null)echo 'sel';?>">
                <a href="<?php echo $this->url('faywiki/admin/doc/index')?>">全部</a>
                <span class="fc-grey">(<span id="all-post-count">
                    <img src="<?php echo $this->assets('images/throbber.gif')?>" />
                </span>)</span>
                |
            </li>
            <li class="publish <?php if(F::app()->input->get('status') == PostsTable::STATUS_PUBLISHED && F::app()->input->get('deleted') != 1)echo 'sel';?>">
                <a href="<?php echo $this->url('faywiki/admin/doc/index', array('status'=>PostsTable::STATUS_PUBLISHED))?>">已发布</a>
                <span class="fc-grey">(<span id="published-post-count">
                    <img src="<?php echo $this->assets('images/throbber.gif')?>" />
                </span>)</span>
                |
            </li>
            <?php if(F::app()->post_review){//仅开启审核时显示?>
            <li class="publish <?php if(F::app()->input->get('status') == PostsTable::STATUS_PENDING && F::app()->input->get('deleted') != 1)echo 'sel';?>">
                <a href="<?php echo $this->url('faywiki/admin/doc/index', array('status'=>PostsTable::STATUS_PENDING))?>">待审核</a>
                <span class="fc-grey">(<span id="pending-post-count">
                    <img src="<?php echo $this->assets('images/throbber.gif')?>" />
                </span>)</span>
                |
            </li>
            <li class="publish <?php if(F::app()->input->get('status') == PostsTable::STATUS_REVIEWED && F::app()->input->get('deleted') != 1)echo 'sel';?>">
                <a href="<?php echo $this->url('faywiki/admin/doc/index', array('status'=>PostsTable::STATUS_REVIEWED))?>">通过审核</a>
                <span class="fc-grey">(<span id="reviewed-post-count">
                    <img src="<?php echo $this->assets('images/throbber.gif')?>" />
                </span>)</span>
                |
            </li>
            <?php }?>
            <li class="draft <?php if(F::app()->input->get('status', 'intval') === PostsTable::STATUS_DRAFT && F::app()->input->get('deleted') != 1)echo 'sel';?>">
                <a href="<?php echo $this->url('faywiki/admin/doc/index', array('status'=>PostsTable::STATUS_DRAFT))?>">草稿</a>
                <span class="fc-grey">(<span id="draft-post-count"><img src="<?php echo $this->assets('images/throbber.gif')?>" /></span>)</span>
                |
            </li>
            <li class="trash <?php if(F::app()->input->get('deleted') == 1)echo 'sel';?>">
                <a href="<?php echo $this->url('faywiki/admin/doc/index', array('deleted'=>1))?>">回收站</a>
                <span class="fc-grey">(<span id="deleted-post-count">
                    <img src="<?php echo $this->assets('images/throbber.gif')?>" />
                </span>)</span>
            </li>
        </ul>
    </div>
</div>
<form method="post" action="<?php echo $this->url('faywiki/admin/doc/batch')?>" id="batch-form" class="form-inline">
    <div class="row">
        <div class="col-5"><?php
            if(F::app()->input->get('deleted')){
                echo HtmlHelper::select('', array(
                    ''=>'批量操作',
                    'undelete'=>F::app()->checkPermission('faywiki/admin/doc/undelete') ? '还原' : false,
                    'remove'=>F::app()->checkPermission('faywiki/admin/doc/remove') ? '永久删除' : false,
                ), '', array(
                    'class'=>'form-control',
                    'id'=>'batch-action',
                ));
            }else{
                echo HtmlHelper::select('', array(
                    ''=>'批量操作',
                    'set-published'=>((F::app()->post_review && F::app()->checkPermission('faywiki/admin/doc/publish')) ||
                        (!F::app()->post_review && F::app()->checkPermission('faywiki/admin/doc/edit'))) ? '标记为已发布' : false,
                    'set-draft'=>F::app()->checkPermission('faywiki/admin/doc/edit') ? '标记为草稿' : false,
                    'set-pending'=>(F::app()->post_review && F::app()->checkPermission('faywiki/admin/doc/review')) ? '标记为待审核' : false,
                    'set-reviewed'=>(F::app()->post_review && F::app()->checkPermission('faywiki/admin/doc/review')) ? '标记为通过审核' : false,
                    'delete'=>F::app()->checkPermission('faywiki/admin/doc/delete') ? '移入回收站' : false,
                ), '', array(
                    'class'=>'form-control',
                    'id'=>'batch-action',
                ));
            }
            echo HtmlHelper::link('提交', 'javascript:;', array(
                'id'=>'batch-form-submit',
                'class'=>'btn btn-sm ml5',
            ));
        ?></div>
        <div class="col-7"><?php $listview->showPager()?></div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="list-table">
                <thead>
                    <tr>
                        <th class="w20"><label><input type="checkbox" class="batch-ids-all" /></label></th>
                        <?php if(in_array('id', $cols)){?>
                        <th class="w70">文档ID</th>
                        <?php }?>
                        <?php if(in_array('thumbnail', $cols)){?>
                        <th width="62">缩略图</th>
                        <?php }?>
                        <th>标题</th>
                        <?php if(in_array('category', $cols)){?>
                        <th>分类</th>
                        <?php }?>
                        <?php if(in_array('status', $cols)){?>
                        <th class="w70">状态</th>
                        <?php }?>
                        <?php if(in_array('user', $cols)){?>
                        <th>作者</th>
                        <?php }?>
                        <?php if(in_array('views', $cols)){?>
                        <th class="w90"><?php echo ListTableHelper::getSortLink('views', '阅读数')?></th>
                        <?php }?>
                        <?php if(in_array('real_views', $cols)){?>
                        <th class="w100"><?php echo ListTableHelper::getSortLink('real_views', '真实阅读')?></th>
                        <?php }?>
                        <?php if(in_array('likes', $cols)){?>
                        <th class="w90"><?php echo ListTableHelper::getSortLink('likes', '点赞数')?></th>
                        <?php }?>
                        <?php if(in_array('real_likes', $cols)){?>
                        <th class="w100"><?php echo ListTableHelper::getSortLink('real_likes', '真实点赞')?></th>
                        <?php }?>
                        <?php if(in_array('last_view_time', $cols)){?>
                        <th class="w135"><?php echo ListTableHelper::getSortLink('last_view_time', '最后访问时间')?></th>
                        <?php }?>
                        <?php if(in_array('update_time', $cols)){?>
                        <th class="w135"><?php echo ListTableHelper::getSortLink('update_time', '更新时间')?></th>
                        <?php }?>
                        <?php if(in_array('create_time', $cols)){?>
                        <th class="w135"><?php echo ListTableHelper::getSortLink('create_time', '创建时间')?></th>
                        <?php }?>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th><label><input type="checkbox" class="batch-ids-all" /></label></th>
                        <?php if(in_array('id', $cols)){?>
                        <th>文档ID</th>
                        <?php }?>
                        <?php if(in_array('thumbnail', $cols)){?>
                        <th>缩略图</th>
                        <?php }?>
                        <th>标题</th>
                        <?php if(in_array('category', $cols)){?>
                        <th>分类</th>
                        <?php }?>
                        <?php if(in_array('status', $cols)){?>
                        <th>状态</th>
                        <?php }?>
                        <?php if(in_array('user', $cols)){?>
                        <th>作者</th>
                        <?php }?>
                        <?php if(in_array('views', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('views', '阅读数')?></th>
                        <?php }?>
                        <?php if(in_array('real_views', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('real_views', '真实阅读')?></th>
                        <?php }?>
                        <?php if(in_array('likes', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('likes', '点赞数')?></th>
                        <?php }?>
                        <?php if(in_array('real_likes', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('real_likes', '真实点赞')?></th>
                        <?php }?>
                        <?php if(in_array('last_view_time', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('last_view_time', '最后访问时间')?></th>
                        <?php }?>
                        <?php if(in_array('update_time', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('update_time', '更新时间')?></th>
                        <?php }?>
                        <?php if(in_array('create_time', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('create_time', '创建时间')?></th>
                        <?php }?>
                    </tr>
                </tfoot>
                <tbody><?php $listview->showData(array(
                    'cols'=>$cols,
                ));?></tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-7 fr"><?php $listview->showPager()?></div>
        <div class="col-5"><?php
            if(F::app()->input->get('deleted')){
                echo HtmlHelper::select('', array(
                    ''=>'批量操作',
                    'undelete'=>F::app()->checkPermission('faywiki/admin/doc/undelete') ? '还原' : false,
                    'remove'=>F::app()->checkPermission('faywiki/admin/doc/remove') ? '永久删除' : false,
                ), '', array(
                    'class'=>'form-control',
                    'id'=>'batch-action-2',
                ));
            }else{
                echo HtmlHelper::select('', array(
                    ''=>'批量操作',
                    'set-published'=>((F::app()->post_review && F::app()->checkPermission('faywiki/admin/doc/publish')) ||
                        (!F::app()->post_review && F::app()->checkPermission('faywiki/admin/doc/edit'))) ? '标记为已发布' : false,
                    'set-draft'=>F::app()->checkPermission('faywiki/admin/doc/edit') ? '标记为草稿' : false,
                    'set-pending'=>(F::app()->post_review && F::app()->checkPermission('faywiki/admin/doc/review')) ? '标记为待审核' : false,
                    'set-reviewed'=>(F::app()->post_review && F::app()->checkPermission('faywiki/admin/doc/review')) ? '标记为通过审核' : false,
                    'delete'=>F::app()->checkPermission('faywiki/admin/doc/delete') ? '移入回收站' : false,
                ), '', array(
                    'class'=>'form-control',
                    'id'=>'batch-action-2',
                ));
            }
            echo HtmlHelper::link('提交', 'javascript:;', array(
                'id'=>'batch-form-submit-2',
                'class'=>'btn btn-sm ml5',
            ));
        ?></div>
    </div>
</form>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/fayfox.editsort.js')?>"></script>
<script>
$(function(){
    //显示各状态文档数
    $.ajax({
        'type': 'GET',
        'url': system.url('faywiki/admin/doc/get-counts'),
        'dataType': 'json',
        'cache': false,
        'success': function(resp){
            $('#all-post-count').text(resp.data.all);
            $('#published-post-count').text(resp.data.published);
            $('#draft-post-count').text(resp.data.draft);
            $('#deleted-post-count').text(resp.data.deleted);
            if(resp.data.pending){
                $('#pending-post-count').text(resp.data.pending);
            }
            if(resp.data.reviewed){
                $('#reviewed-post-count').text(resp.data.reviewed);
            }
        }
    });
});
</script>