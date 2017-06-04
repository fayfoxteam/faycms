<?php
/**
 * @var $listview \fay\common\ListView
 */
?>
<?php echo F::form('search')->open(null, 'get', array(
    'class'=>'form-inline',
))?>
    <div class="mb5"><?php
        echo F::form('search')->inputHidden('id'),
        F::form('search')->inputText('search_keywords', array(
            'class'=>'form-control',
            'placeholder'=>'搜索关键词',
        )),
        F::form('search')->submitLink('搜索', array(
            'class'=>'btn btn-sm ml5',    
        ));
    ?></div>
<?php echo F::form('search')->close()?>
<table class="list-table props">
    <thead>
    <tr>
        <th>错误码</th>
        <th>错误描述</th>
        <th>解决方案</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>错误码</th>
        <th>错误描述</th>
        <th>解决方案</th>
    </tr>
    </tfoot>
    <tbody>
    <?php $listview->showData()?>
    </tbody>
</table>
<?php $listview->showPager();?>