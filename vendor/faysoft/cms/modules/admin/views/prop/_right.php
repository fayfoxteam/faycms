<?php
/**
 * @var $listview \fay\common\ListView
 */
?>
    <table class="list-table props">
        <thead>
        <tr>
            <th>属性名称</th>
            <th class="w90">表单元素</th>
            <th class="w150">用途</th>
            <th class="w90">必选</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>属性名称</th>
            <th>表单元素</th>
            <th>用途</th>
            <th>必选</th>
        </tr>
        </tfoot>
        <tbody>
        <?php $listview->showData()?>
        </tbody>
    </table>
<?php $listview->showPager();?>