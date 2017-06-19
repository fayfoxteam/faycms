<?php
use cms\helpers\ListTableHelper;
use fay\helpers\HtmlHelper;
use fayfeed\models\tables\FeedsTable;

$cols = F::form('setting')->getData('cols', array());
?>
<div class="row">
    <div class="col-12">
        <?php echo F::form('search')->open(null, 'get', array(
            'class'=>'form-inline',
        ))?>
            <div class="mb5"><?php
                echo F::form('search')->select('keywords_field', array(
                    'content'=>'内容',
                    'id'=>'动态ID',
                    'user_id'=>'作者ID',
                ), array(
                    'class'=>'form-control',
                )),
                '&nbsp;',
                F::form('search')->inputText('keywords', array(
                    'class'=>'form-control w200',
                ));
            ?></div>
            <div><?php
                echo F::form('search')->select('time_field', array(
                    'publish_time'=>'发布时间',
                    'create_time'=>'创建时间',
                    'update_time'=>'更新时间',
                ), array(
                    'class'=>'form-control',
                )),
                '&nbsp;',
                F::form('search')->inputText('start_time', array(
                    'data-rule'=>'datetime',
                    'data-label'=>'开始时间',
                    'class'=>'form-control datetimepicker',
                )),
                ' - ',
                F::form('search')->inputText('end_time', array(
                    'data-rule'=>'datetime',
                    'data-label'=>'结束时间',
                    'class'=>'form-control datetimepicker',
                )),
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
                <a href="<?php echo $this->url('fayfeed/admin/feed/index')?>">全部</a>
                <span class="fc-grey">(<span id="all-feed-count">
                    <img src="<?php echo $this->assets('images/throbber.gif')?>" />
                </span>)</span>
                |
            </li>
            <li class="pending <?php if(F::app()->input->get('status') == FeedsTable::STATUS_PENDING && F::app()->input->get('deleted') != 1)echo 'sel';?>">
                <a href="<?php echo $this->url('fayfeed/admin/feed/index', array('status'=>FeedsTable::STATUS_PENDING))?>">待审核</a>
                <span class="fc-grey">(<span id="pending-feed-count">
                    <img src="<?php echo $this->assets('images/throbber.gif')?>" />
                </span>)</span>
                |
            </li>
            <li class="approved <?php if(F::app()->input->get('status') == FeedsTable::STATUS_APPROVED && F::app()->input->get('deleted') != 1)echo 'sel';?>">
                <a href="<?php echo $this->url('fayfeed/admin/feed/index', array('status'=>FeedsTable::STATUS_APPROVED))?>">通过审核</a>
                <span class="fc-grey">(<span id="approved-feed-count">
                    <img src="<?php echo $this->assets('images/throbber.gif')?>" />
                </span>)</span>
                |
            </li>
            <li class="unapproved <?php if(F::app()->input->get('status') == FeedsTable::STATUS_UNAPPROVED && F::app()->input->get('deleted') != 1)echo 'sel';?>">
                <a href="<?php echo $this->url('fayfeed/admin/feed/index', array('status'=>FeedsTable::STATUS_UNAPPROVED))?>">未通过审核</a>
                <span class="fc-grey">(<span id="unapproved-feed-count">
                    <img src="<?php echo $this->assets('images/throbber.gif')?>" />
                </span>)</span>
                |
            </li>
            <li class="draft <?php if(F::app()->input->get('status', 'intval') === FeedsTable::STATUS_DRAFT && F::app()->input->get('deleted') != 1)echo 'sel';?>">
                <a href="<?php echo $this->url('fayfeed/admin/feed/index', array('status'=>FeedsTable::STATUS_DRAFT))?>">草稿</a>
                <span class="fc-grey">(<span id="draft-feed-count"><img src="<?php echo $this->assets('images/throbber.gif')?>" /></span>)</span>
                |
            </li>
            <li class="trash <?php if(F::app()->input->get('deleted') == 1)echo 'sel';?>">
                <a href="<?php echo $this->url('fayfeed/admin/feed/index', array('deleted'=>1))?>">回收站</a>
                <span class="fc-grey">(<span id="deleted-feed-count">
                    <img src="<?php echo $this->assets('images/throbber.gif')?>" />
                </span>)</span>
            </li>
        </ul>
    </div>
</div>
<form method="post" action="<?php echo $this->url('fayfeed/admin/feed/batch')?>" id="batch-form" class="form-inline">
    <div class="row">
        <div class="col-5"><?php
            if(F::app()->input->get('deleted')){
                echo HtmlHelper::select('', array(
                    ''=>'批量操作',
                    'undelete'=>F::app()->checkPermission('fayfeed/admin/feed/undelete') ? '还原' : false,
                    'remove'=>F::app()->checkPermission('fayfeed/admin/feed/remove') ? '永久删除' : false,
                ), '', array(
                    'class'=>'form-control',
                    'id'=>'batch-action',
                ));
            }else{
                echo HtmlHelper::select('', array(
                    ''=>'批量操作',
                    'set-draft'=>F::app()->checkPermission('fayfeed/admin/feed/edit') ? '标记为草稿' : false,
                    'set-pending'=>F::app()->checkPermission('fayfeed/admin/feed/approve') ? '标记为待审核' : false,
                    'set-approved'=>F::app()->checkPermission('fayfeed/admin/feed/approve') ? '通过审核' : false,
                    'set-unapproved'=>F::app()->checkPermission('fayfeed/admin/feed/approve') ? '未通过审核' : false,
                    'delete'=>F::app()->checkPermission('fayfeed/admin/feed/delete') ? '移入回收站' : false,
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
                        <th class="w70">动态ID</th>
                        <?php }?>
                        <th>内容</th>
                        <?php if(in_array('tags', $cols)){?>
                        <th>标签</th>
                        <?php }?>
                        <?php if(in_array('status', $cols)){?>
                        <th class="w90">状态</th>
                        <?php }?>
                        <?php if(in_array('user', $cols)){?>
                        <th>作者</th>
                        <?php }?>
                        <?php if(in_array('comments', $cols)){?>
                        <th class="w90"><?php echo ListTableHelper::getSortLink('comments', '评论数')?></th>
                        <?php }?>
                        <?php if(in_array('real_comments', $cols)){?>
                        <th class="w100"><?php echo ListTableHelper::getSortLink('real_comments', '真实评论')?></th>
                        <?php }?>
                        <?php if(in_array('likes', $cols)){?>
                        <th class="w90"><?php echo ListTableHelper::getSortLink('likes', '点赞数')?></th>
                        <?php }?>
                        <?php if(in_array('real_likes', $cols)){?>
                        <th class="w100"><?php echo ListTableHelper::getSortLink('real_likes', '真实点赞')?></th>
                        <?php }?>
                        <?php if(in_array('publish_time', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('publish_time', '发布时间')?></th>
                        <?php }?>
                        <?php if(in_array('update_time', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('update_time', '更新时间')?></th>
                        <?php }?>
                        <?php if(in_array('create_time', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('create_time', '创建时间')?></th>
                        <?php }?>
                        <?php if(in_array('sort', $cols)){?>
                        <th class="w90"><?php echo ListTableHelper::getSortLink('sort', '排序')?></th>
                        <?php }?>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th><label><input type="checkbox" class="batch-ids-all" /></label></th>
                        <?php if(in_array('id', $cols)){?>
                        <th>动态ID</th>
                        <?php }?>
                        <th>内容</th>
                        <?php if(in_array('tags', $cols)){?>
                        <th>标签</th>
                        <?php }?>
                        <?php if(in_array('status', $cols)){?>
                        <th>状态</th>
                        <?php }?>
                        <?php if(in_array('user', $cols)){?>
                        <th>作者</th>
                        <?php }?>
                        <?php if(in_array('comments', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('comments', '评论数')?></th>
                        <?php }?>
                        <?php if(in_array('real_comments', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('real_comments', '真实评论')?></th>
                        <?php }?>
                        <?php if(in_array('likes', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('likes', '点赞数')?></th>
                        <?php }?>
                        <?php if(in_array('real_likes', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('real_likes', '真实点赞')?></th>
                        <?php }?>
                        <?php if(in_array('publish_time', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('publish_time', '发布时间')?></th>
                        <?php }?>
                        <?php if(in_array('update_time', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('update_time', '更新时间')?></th>
                        <?php }?>
                        <?php if(in_array('create_time', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('create_time', '创建时间')?></th>
                        <?php }?>
                        <?php if(in_array('sort', $cols)){?>
                        <th><?php echo ListTableHelper::getSortLink('sort', '排序')?></th>
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
                    'undelete'=>F::app()->checkPermission('fayfeed/admin/feed/undelete') ? '还原' : false,
                    'remove'=>F::app()->checkPermission('fayfeed/admin/feed/remove') ? '永久删除' : false,
                ), '', array(
                    'class'=>'form-control',
                    'id'=>'batch-action-2',
                ));
            }else{
                echo HtmlHelper::select('', array(
                    ''=>'批量操作',
                    'set-draft'=>F::app()->checkPermission('fayfeed/admin/feed/edit') ? '标记为草稿' : false,
                    'set-pending'=>F::app()->checkPermission('fayfeed/admin/feed/approve') ? '标记为待审核' : false,
                    'set-approved'=>F::app()->checkPermission('fayfeed/admin/feed/approve') ? '通过审核' : false,
                    'set-unapproved'=>F::app()->checkPermission('fayfeed/admin/feed/approve') ? '未通过审核' : false,
                    'delete'=>F::app()->checkPermission('fayfeed/admin/feed/delete') ? '移入回收站' : false,
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
<script>
$(function(){
    //显示各状态动态数
    $.ajax({
        'type': 'GET',
        'url': system.url('fayfeed/admin/feed/get-counts'),
        'dataType': 'json',
        'cache': false,
        'success': function(resp){
            $('#all-feed-count').text(resp.data.all);
            $('#draft-feed-count').text(resp.data.draft);
            $('#deleted-feed-count').text(resp.data.deleted);
            $('#pending-feed-count').text(resp.data.pending);
            $('#approved-feed-count').text(resp.data.approved);
            $('#unapproved-feed-count').text(resp.data.unapproved);
        }
    });
});
</script>