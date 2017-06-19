<?php
use fay\helpers\HtmlHelper;

?>
<form id="batch-form" method="post" action="<?php echo $this->url('cms/admin/notification/batch')?>" class="form-inline">
    <div class="row">
        <div class="col-5"><?php
            echo HtmlHelper::select('', array(
                ''=>'批量操作',
                'set-read'=>'标记为已读',
                'set-unread'=>'标记为未读',
                'delete'=>'删除',
            ), '', array(
                'class'=>'form-control',
                'id'=>'batch-action',
            ));
            echo HtmlHelper::link('提交', 'javascript:;', array(
                'id'=>'batch-form-submit',
                'class'=>'btn btn-sm ml5',
            ));
        ?></div>
        <div class="col-7">
            <?php $listview->showPager()?>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="list-table posts">
                <thead>
                    <tr>
                        <th class="w20 pl11"><label><input type="checkbox" class="batch-ids-all" /></label></th>
                        <th>消息</th>
                        <th>分类</th>
                        <th>来自</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="pl11"><label><input type="checkbox" class="batch-ids-all" /></label></th>
                        <th>消息</th>
                        <th>分类</th>
                        <th>来自</th>
                    </tr>
                </tfoot>
                <tbody><?php $listview->showData();?></tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-7 fr">
            <?php $listview->showPager()?>
        </div>
        <div class="col-5"><?php
            echo HtmlHelper::select('', array(
                ''=>'批量操作',
                'set-read'=>'标记为已读',
                'set-unread'=>'标记为未读',
                'delete'=>'删除',
            ), '', array(
                'class'=>'form-control',
                'id'=>'batch-action-2',
            ));
            echo HtmlHelper::link('提交', 'javascript:;', array(
                'id'=>'batch-form-submit-2',
                'class'=>'btn btn-sm ml5',
            ));
        ?></div>
    </div>
</form>