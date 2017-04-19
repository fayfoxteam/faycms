<?php
use fay\helpers\HtmlHelper;
?>
<?php echo F::form('search')->open(null, 'get', array(
    'class'=>'form-inline',
))?>
    <div class="mb5">
        <?php echo F::form('search')->select('cat_id', array(''=>'--分类--')+HtmlHelper::getSelectOptions($cats, 'id', 'title'), array(
            'class'=>'form-control',
        ))?>
        <?php /**
        //需要配置才能允许url中出现%2F，还是不要提供搜索比较好
        路由：
        <?php echo F::form()->inputText('router')?>
        */?>
        <a href="javascript:" class="btn btn-sm" id="search-form-submit">搜索</a>
    </div>
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