<?php
use fay\helpers\ArrayHelper;
use fay\helpers\HtmlHelper;
use cms\helpers\ListTableHelper;
use guangong\models\tables\GuangongUserExtraTable;

$cols = F::form('setting')->getData('cols');
$data = $listview->getData();
$user_extra = ArrayHelper::column(GuangongUserExtraTable::model()->fetchAll(array(
    'user_id IN (?)'=> ArrayHelper::column($data, 'id'),
)), null, 'user_id');
?>
<div class="row">
    <div class="col-12">
        <?php echo F::form('search')->open(null, 'get', array(
            'class'=>'form-inline',
        ))?>
            <div class="mb5">
                <?php echo F::form('search')->select('time_field', array(
                    'reg_time' =>'注册时间',
                    'last_login_time' =>'最后登陆时间',
                    'last_time_online' =>'最后活跃时间',
                ), array(
                    'class'=>'form-control',
                ))?>
                <?php echo F::form('search')->inputText('start_time', array(
                    'data-rule'=>'datetime',
                    'class'=>'datetimepicker form-control',
                ));?>
                -
                <?php echo F::form('search')->inputText('end_time', array(
                    'data-rule'=>'datetime',
                    'class'=>'datetimepicker form-control',
                ));?>
            </div>
            <div class="mb5">
                <?php echo F::form('search')->select('keywords_field', array(
                    'username'=>'登陆名',
                    'nickname'=>'昵称',
                    'mobile'=>'手机号',
                    'email'=>'邮箱',
                    'id'=>'用户ID',
                ), array(
                    'class'=>'form-control',
                ))?>
                <?php echo F::form('search')->inputText('keywords', array('class'=>'form-control w200'))?>
                |
                <?php echo F::form('search')->select('role', array(''=>'--角色--')+HtmlHelper::getSelectOptions($roles), array(
                    'class'=>'form-control',
                ));?>
                <?php echo F::form('search')->submitLink('查询', array(
                    'class'=>'btn btn-sm',
                ))?>
            </div>
        <?php echo F::form('search')->close()?>
        <?php $listview->showPager();?>
        <table class="list-table">
            <thead>
                <tr>
                    <th class="w50">头像</th>
                    <th>昵称</th>
                    <th>手机</th>
                    <th>缴纳军费</th>
                    <th>参军日期</th>
                    <th><?php echo ListTableHelper::getSortLink('reg_time', '注册时间')?></th>
                    <th><?php echo ListTableHelper::getSortLink('last_login_time', '最后登陆时间')?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="w50">头像</th>
                    <th>昵称</th>
                    <th>手机</th>
                    <th>缴纳军费</th>
                    <th>参军日期</th>
                    <th><?php echo ListTableHelper::getSortLink('reg_time', '注册时间')?></th>
                    <th><?php echo ListTableHelper::getSortLink('last_login_time', '最后登陆时间')?></th>
                </tr>
            </tfoot>
            <tbody><?php
                $listview->showData(array(
                    'cols'=>$cols,
                    'user_extra'=>$user_extra,
                ));
            ?></tbody>
        </table>
        <?php $listview->showPager();?>
    </div>
</div>