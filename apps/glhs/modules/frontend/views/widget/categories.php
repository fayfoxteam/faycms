<?php
use fay\helpers\HtmlHelper;

function renderCats($cats, $uri, $dep = 0){
    $html = '<ul';
    $html .= $dep ? ' class="children"' : '';
    $html .= '>';
    foreach($cats as $c){
        $html .= '<li class="cat-item">';
        $html .= HtmlHelper::link($c['title'], array(str_replace(array(
            '{$id}', '{$alias}',
        ), array(
            $c['id'], $c['alias'],
        ), $uri)));
        if(!empty($c['children'])){
            $html .= $this->renderCats($c['children'], $uri, ++$dep);
        }
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}
?>
<aside class="widget widget-categories" id="widget-<?php echo HtmlHelper::encode($alias)?>">
    <h2>分类目录</h2>
    <?php echo renderCats($cats, $data['uri'])?>
</aside>