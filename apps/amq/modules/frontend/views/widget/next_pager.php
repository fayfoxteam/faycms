<?php
use fay\helpers\HtmlHelper;

/**
 * @var $listview \fay\common\ListView
 */

//根据是否带有问号，构造出url的前面部分，用于构造页码url
if(strpos($listview->reload, '?') !== false){
    $reload = $listview->reload . '&';
}else{
    $reload = $listview->reload . '?';
}

if($listview->total_pages > 1){
    ?>
    <div class="pagination">
        <?php
        //下一页
        if($listview->current_page < $listview->total_pages){
            echo HtmlHelper::link('下页', "{$reload}{$listview->page_key}=" . ($listview->current_page + 1), array(
                'class'=>'page-numbers next',
                'title'=>'下一页',
                'encode'=>false,
            ));
        }
        ?>
    </div>
<?php }?>