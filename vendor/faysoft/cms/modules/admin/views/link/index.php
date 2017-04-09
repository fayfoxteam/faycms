<?php
use cms\helpers\ListTableHelper;
use fay\helpers\HtmlHelper;

/**
 * @var $listview \fay\common\ListView
 */
?>
<div class="row">
    <div class="col-12">
        <?php echo F::form('search')->open(null, 'get', array(
            'class'=>'form-inline',
        ))?>
            <div class="mb5">
                标题：<?php echo F::form('search')->inputText('title', array(
                    'class'=>'form-control w200',
                ));?>
                |
                <?php echo F::form('search')->select('cat_id', array(
                    ''=>'--分类--',
                ) + HtmlHelper::getSelectOptions($cats, 'id', 'title'), array(
                    'class'=>'form-control',
                ))?>
                <?php echo F::form('search')->submitLink('查询', array(
                    'class'=>'btn btn-sm',
                ))?>
            </div>
        <?php echo F::form('search')->close()?>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="list-table form-inline">
            <thead>
                <tr>
                    <th>标题</th>
                    <th>URL</th>
                    <th class="wp10">可见性</th>
                    <th>分类</th>
                    <th class="w90"><?php echo ListTableHelper::getSortLink('sort', '排序')?></th>
                    <th><?php echo ListTableHelper::getSortLink('update_time', '更新时间')?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>标题</th>
                    <th>URL</th>
                    <th>可见性</th>
                    <th>分类</th>
                    <th><?php echo ListTableHelper::getSortLink('sort', '排序')?></th>
                    <th><?php echo ListTableHelper::getSortLink('update_time', '更新时间')?></th>
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
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/admin/fayfox.editsort.js')?>"></script>
<script>
$(".edit-sort").feditsort({
    'url':system.url("admin/link/sort")
});
</script>