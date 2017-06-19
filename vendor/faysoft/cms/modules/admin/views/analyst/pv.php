<?php
use cms\helpers\ListTableHelper;
use fay\helpers\HtmlHelper;

?>
<div class="row">
    <div class="col-12">
        <?php echo F::form('search')->open(null, 'get', array(
            'class'=>'form-inline',
        ));?>
            <div class="mb5">
                TrackId
                <?php echo F::form('search')->inputText('trackid', array(
                    'class'=>'form-control',
                ));?>
                |
                IP
                <?php echo F::form('search')->inputText('ip', array(
                    'class'=>'form-control',
                ))?>
                |
                <?php echo F::form('search')->select('site', array(''=>'--所有站点--')+HtmlHelper::getSelectOptions($sites, 'id', 'title'), array(
                    'class'=>'form-control',
                ))?>
            </div>
            <div class="mb5">
                访问时间
                <?php echo F::form('search')->inputText('start_time', array(
                    'class'=>'datetimepicker form-control',
                ));?>
                -
                <?php echo F::form('search')->inputText('end_time', array(
                    'class'=>'datetimepicker form-control',
                ));?>
                <a href="javascript:" class="btn btn-sm" id="search-form-submit">查询</a>
            </div>
        <?php echo F::form('search')->close()?>
        <ul class="subsubsub fl">
            <li class="<?php if($flag === 'today')echo 'sel';?>">
                <a href="<?php echo $this->url('cms/admin/analyst/pv', array(
                    'start_time'=>date('Y-m-d 00:00:00', $today),
                    'end_time'=>'',
                    'site'=>F::app()->input->get('site'),
                    'trackid'=>F::app()->input->get('trackid'),
                    'ip'=>F::app()->input->get('ip'),
                ))?>">今天</a>
                |
            </li>
            <li class="<?php if($flag === 'yesterday')echo 'sel';?>">
                <a href="<?php echo $this->url('cms/admin/analyst/pv', array(
                    'start_time'=>date('Y-m-d 00:00:00', $yesterday),
                    'end_time'=>date('Y-m-d 00:00:00', $today),
                    'site'=>F::app()->input->get('site'),
                    'trackid'=>F::app()->input->get('trackid'),
                    'ip'=>F::app()->input->get('ip'),
                ))?>">昨天</a>
                |
            </li>
            <li class="<?php if($flag === 'week')echo 'sel';?>">
                <a href="<?php echo $this->url('cms/admin/analyst/pv', array(
                    'start_time'=>date('Y-m-d 00:00:00', $week),
                    'end_time'=>'',
                    'site'=>F::app()->input->get('site'),
                    'trackid'=>F::app()->input->get('trackid'),
                    'ip'=>F::app()->input->get('ip'),
                ))?>">最近7天</a>
                |
            </li>
            <li class="<?php if($flag === 'month')echo 'sel';?>">
                <a href="<?php echo $this->url('cms/admin/analyst/pv', array(
                    'start_time'=>date('Y-m-d 00:00:00', $month),
                    'end_time'=>'',
                    'site'=>F::app()->input->get('site'),
                    'trackid'=>F::app()->input->get('trackid'),
                    'ip'=>F::app()->input->get('ip'),
                ))?>">最近30天</a>
            </li>
        </ul>
        <?php $listview->showPager();?>
        <table class="list-table">
            <thead>
                <tr> 
                    <th>受访页面</th>
                    <th class="w115"><?php echo ListTableHelper::getSortLink('pv', '浏览量(PV)')?></th>
                    <th class="w115"><?php echo ListTableHelper::getSortLink('uv', '访客数(UV)')?></th>
                    <th class="w50">IP数</th>
                    <th class="w115">站点</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>受访页面</th>
                    <th><?php echo ListTableHelper::getSortLink('pv', '浏览量(PV)')?></th>
                    <th><?php echo ListTableHelper::getSortLink('uv', '访客数(UV)')?></th>
                    <th>IP数</th>
                    <th>站点</th>
                </tr>
            </tfoot>
            <tbody>
            <?php $listview->showData();?>
            </tbody>
        </table>
        <?php $listview->showPager();?>
    </div>
</div>