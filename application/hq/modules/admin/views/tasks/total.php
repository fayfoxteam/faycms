

<div class="row">
    <div class="col-12">
        <table class="list-table">
            <thead>
            <tr>
                <th>表ID</th>
                <th>表名</th>
                <th>类型</th>
                <th>当前总值</th>
                <th>地点</th>
                <th>说明</th>
                <th>最近更新时间</th>
            </tr>
            </thead>
            <tbody>
            <?php $listview->showData() ?>
            </tbody>
        </table>
        <?php $listview->showPager() ?>
    </div>
</div>