<?php
/**
 * @var $listview \fay\common\ListView
 */
?>
<div class="row">
    <div class="col-12">
        <table class="list-table">
            <thead>
                <tr>
                    <th class="wp30">角色</th>
                    <th>类型</th>
                    <th>描述</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>角色</th>
                    <th>类型</th>
                    <th>描述</th>
                </tr>
            </tfoot>
            <tbody>
        <?php
            $listview->showData();
        ?>
            </tbody>
        </table>
        <?php $listview->showPager();?>
    </div>
</div>