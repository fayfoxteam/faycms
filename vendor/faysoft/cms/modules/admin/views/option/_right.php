<table class="list-table options">
    <thead>
        <tr>
            <th>键</th>
            <th>值</th>
            <th width="82">系统</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>键</th>
            <th>值</th>
            <th>系统</th>
        </tr>
    </tfoot>
    <tbody>
<?php
    $listview->showData();
?>
    </tbody>
</table>
<?php $listview->showPager();?>