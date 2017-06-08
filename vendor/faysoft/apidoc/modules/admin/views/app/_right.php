<?php
/**
 * @var $listview \fay\common\ListView
 */
?>
<table class="list-table options">
    <thead>
        <tr>
            <th class="wp25">应用名称</th>
            <th>应用描述</th>
            <th width="82">登录</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>应用名称</th>
            <th>应用描述</th>
            <th>登录</th>
        </tr>
    </tfoot>
    <tbody><?php $listview->showData(); ?></tbody>
</table>
<?php $listview->showPager();?>