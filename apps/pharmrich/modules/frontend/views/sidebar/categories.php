<?php
namespace pharmrich\modules\frontend\views\sidebar;

use fay\helpers\HtmlHelper;

/**
 * @var $widget
 * @var $cats array
 */

if(!function_exists('pharmrich\modules\frontend\views\sidebar\renderCats')){
    function renderCats($cats, $dep = 0){
        $html = '<ul';
        $html .= $dep ? ' class="children"' : '';
        $html .= '>';
        foreach($cats as $c){
            $html .= '<li class="cat-item">';
            $html .= HtmlHelper::link($c['title'], $c['link']);
            if(!empty($c['children'])){
                $html .= renderCats($c['children'], ++$dep);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
}
?>
<div class="widget categories">
    <h3><?php echo $widget->config['title']?></h3>
    <?php echo renderCats($cats)?>
</div>