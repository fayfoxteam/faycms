<?php
use fay\helpers\HtmlHelper;
if($listview->total_pages > 1){
?>
<div class="pagination">
    <?php 
    //上一页
    if($listview->current_page == 2){
        echo HtmlHelper::link('&lt;', $listview->reload, array(
            'class'=>'page-numbers prev',
            'title'=>'上一页',
            'encode'=>false,
        ));
    }else if($listview->current_page > 2){
        echo HtmlHelper::link('&lt;', $listview->reload . '?page=' . ($listview->current_page - 1), array(
            'class'=>'page-numbers prev',
            'title'=>'上一页',
            'encode'=>false,
        ));
    }
    
    //首页
    if($listview->current_page > ($listview->adjacent + 1)) {
        echo HtmlHelper::link(1, $listview->reload, array(
            'class'=>'page-numbers',
        ));
    }
    
    //点点点
    if($listview->current_page > ($listview->adjacent + 2)) {
        echo '<span class="page-numbers dots">&hellip;</span>';
    }
    
    //页码
    $pmin = $listview->current_page > $listview->adjacent ? $listview->current_page - $listview->adjacent : 1;
    $pmax = $listview->current_page < $listview->total_pages - $listview->adjacent ? $listview->current_page + $listview->adjacent : $listview->total_pages;
    for($i=$pmin; $i<=$pmax; $i++){
        if($i == $listview->current_page){
            echo '<span class="page-numbers crt">', $i, '</span>';
        }else if($i == 1){
            echo HtmlHelper::link(1, $listview->reload, array(
                'class'=>'page-numbers',
            ));
        }else{
            echo HtmlHelper::link($i, $listview->reload . '?page='.$i, array(
                'class'=>'page-numbers',
            ));
        }
    }
    
    //点点点
    // interval
    if($listview->current_page < ($listview->total_pages - $listview->adjacent - 1)) {
        echo '<span class="page-numbers dots">&hellip;</span>';
    }
    
    //末页
    if($listview->current_page < $listview->total_pages - $listview->adjacent) {
        echo HtmlHelper::link($listview->total_pages, $listview->reload . '?page=' . $listview->total_pages, array(
            'class'=>'page-numbers',
        ));
    }
    
    //下一页
    if($listview->current_page < $listview->total_pages){
        echo HtmlHelper::link('&gt;', $listview->reload . '?page=' . ($listview->current_page + 1), array(
            'class'=>'page-numbers next',
            'title'=>'下一页',
            'encode'=>false,
        ));
    }
    ?>
</div>
<?php }?>