<?php 


?>


<div class="row">
    <div class="col-8">
        <table class="list-table">
            <thead>
                <tr>
                    <th>姓名</th>
                    <th>学号</th>
                    <th>班级</th>
                    <th>是否已经投票</th>
                </tr>
            </thead>
            <tbody>
                <?php $listview->showData() ?>
            </tbody>
        </table>
        <?php $listview->showPager() ?>
    </div>
</div>