<?php
use fay\helpers\HtmlHelper;

/**
 * @var $listview \fay\common\ListView
 */
?>
<?php echo F::form('search')->open(null, 'get', array(
    'class'=>'form-inline',
))?>
    <div class="mb5"><?php
        echo F::form('search')->inputHidden('id'),
        F::form('search')->inputText('search_router', array(
            'class'=>'form-control',
            'placeholder'=>'路由',
        )),
        F::form('search')->select('cat_id', array(''=>'--分类--')+HtmlHelper::getSelectOptions($cats, 'id', 'title'), array(
            'class'=>'form-control ml5',
        )),
        F::form('search')->submitLink('搜索', array(
            'class'=>'btn btn-sm ml5',
        ));
    ?></div>
<?php echo F::form('search')->close()?>
<table class="list-table">
    <thead>
        <tr>
            <th>描述</th>
            <th>路由</th>
            <th>父级路由</th>
            <th class="wp10">公共</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>描述</th>
            <th>路由</th>
            <th>父级路由</th>
            <th>公共</th>
        </tr>
    </tfoot>
    <tbody>
<?php
    $listview->showData();
?>
    </tbody>
</table>
<?php $listview->showPager();?>