<?php
use hq\models\tables\Zbiaos;
?>

<div class="row">
    <div class="col-12">
        <?php echo F::form('search')->open(null, 'get', array('class' => 'form-inline')); ?>
        请选择需要查看的类型:
        <select name="type_id" class="form-control">
            <option value="" >--分类--</option>
            <option value="<?= Zbiaos::TYPE_ELECTRICITY ?>" <?= isset($_GET['type_id']) && $_GET['type_id'] == Zbiaos::TYPE_ELECTRICITY ? 'selected' : '' ?> >电表</option>
            <option value="<?= Zbiaos::TYPE_WATER ?>" <?= isset($_GET['type_id']) && $_GET['type_id'] == Zbiaos::TYPE_WATER ? 'selected' : '' ?>>水表</option>
        </select>
        <?php echo F::form('search')->submitLink('确认', array(
            'class'=>'btn btn-sm',
        ))?>
        <?php echo F::form()->close(); ?>
    </div>
</div>
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