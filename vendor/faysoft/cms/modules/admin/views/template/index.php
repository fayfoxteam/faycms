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
                    <th class="wp15">别名</th>
                    <th>描述</th>
                    <th class="w50">启用</th>
                    <th>类型</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>别名</th>
                    <th>描述</th>
                    <th>启用</th>
                    <th>类型</th>
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