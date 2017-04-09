<div class="row">
    <div class="col-12">
        <?php echo F::form('search')->open(null, 'get', array(
            'class'=>'form-inline',
        ))?>
            <div class="mb5">
                <?php echo F::form('search')->select('user', array(
                    'username'=>'登录名',
                    'nickname'=>'昵称',
                ), array(
                    'class'=>'form-control',
                ))?>
                <?php echo F::form('search')->inputText('keywords', array(
                    'class'=>'form-control w200',
                ))?>
            </div>
            <div class="mb5">
                <?php echo F::form('search')->select('field', array(
                    'start_time'=>'开考时间',
                    'end_time'=>'交卷时间',
                ), array(
                    'class'=>'form-control',
                ))?>
                <?php echo F::form('search')->inputText('start_time', array(
                    'class'=>'form-control datetimepicker',
                ));?>
                -
                <?php echo F::form('search')->inputText('end_time', array(
                    'class'=>'form-control datetimepicker',
                ));?>
                <?php echo F::form('search')->submitLink('查询', array(
                    'class'=>'btn btn-sm',
                ))?>
            </div>
        <?php echo F::form('search')->close()?>
        <table class="list-table">
            <thead>
                <tr>
                    <th>试卷名称</th>
                    <th>用户</th>
                    <th class="wp15">得分</th>
                    <th class="wp15">答题时间</th>
                    <th class="wp15">开考时间</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>试卷名称</th>
                    <th>用户</th>
                    <th>得分</th>
                    <th>答题时间</th>
                    <th>开考时间</th>
                </tr>
            </tfoot>
            <tbody>
        <?php
            $listview->showData(array(
                'display_name'=>F::form('setting')->getData('display_name'),
            ));
        ?>
            </tbody>
        </table>
        <?php $listview->showPager();?>
    </div>
</div>