<?php 
use fay\helpers\Html;
?>
<div class="row">
	<div class="col-12">
		<?php echo F::form('search')->open(null, 'get', array(
			'class'=>'form-inline',
		))?>
			<div class="mb5">
				<?php echo F::form('search')->select('user_type', array(
					''=>'--用户类型--',
					'1'=>'学生',
					'2'=>'教师',
				), array(
					'class'=>'form-control',
				));?>
				<?php echo F::form('search')->select('department', $departments, array(
					'class'=>'form-control',
				));?>
				<?php echo F::form('search')->select('class', $classes, array(
					'class'=>'form-control',
				));?>
				<?php echo F::form('search')->select('vote_active', array(
					''=>'--投票状态--',
					'0'=>'未投票',
					'1'=>'已投票',
				), array(
					'class'=>'form-control',
				));?>
				<?php echo F::form('search')->submitLink('查询', array(
					'class'=>'btn btn-sm',
				))?>
			</div>
		<?php echo F::form('search')->close()?>
	</div>
</div>
<div class="row">
    <div class="col-12">
        <table class="list-table">
            <thead>
                <tr>
                    <th>姓名</th>
                    <th>学号</th>
                    <th>院系</th>
                    <th>班级</th>
                    <th>年级</th>
                    <th>用户类型</th>
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