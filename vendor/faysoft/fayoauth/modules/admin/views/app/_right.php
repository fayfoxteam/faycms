<?php
/**
 * @var $listview \fay\common\ListView
 */
?>
<table class="list-table">
    <thead>
    <tr>
        <th>名称</th>
        <th>类型</th>
        <th>别名</th>
        <th class="w90">是否启用</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>名称</th>
        <th>类型</th>
        <th>别名</th>
        <th>是否启用</th>
    </tr>
    </tfoot>
    <tbody>
    <?php $listview->showData()?>
    </tbody>
</table>
<?php $listview->showPager();?>