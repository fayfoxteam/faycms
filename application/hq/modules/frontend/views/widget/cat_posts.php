<?php
use fay\helpers\Html;

function renderCats($cats, $uri, $dep = 0){
    $html = '<ul';
    $html .= $dep ? ' class="children"' : '';
    $html .= '>';
    foreach($cats as $c){
        $html .= '<li class="cat-item">';
        $html .= Html::link($c['title'], array(str_replace(array(
            '{$id}', '{$alias}',
        ), array(
            $c['id'], $c['alias'],
        ), $uri)));
        if(!empty($c['children'])){
            $html .= renderCats($c['children'], $uri, ++$dep);
        }
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}
//dump($cats);
$tab = F::session()->get('tab');
?>

<div class="gyah-minleft">
    <ul>
        <?php foreach ($cats as $c) {
            if (!$c['is_nav']) continue;
         ?>

        <li class="<?= $tab == $c['id'] ? 'li-active' : '' ?>">
            <a class="<?= $tab == $c['id'] ? 'active' : '' ?>" href="<?= $this->url('cat/'. $c['id']) ?>" ><?= $c['title'] ?></a>
        </li>
        <?php } ?>

    </ul>
</div>