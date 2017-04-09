<?php
namespace cms\widgets\categories\views\index;

use fay\helpers\HtmlHelper;

/**
 * @var $widget \cms\widgets\categories\controllers\IndexController
 * @var $cats array
 */

if(!function_exists('cms\widgets\categories\views\index\renderCats')){
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
<div class="widget widget-categories" id="widget-<?php echo HtmlHelper::encode($widget->alias)?>">
    <div class="widget-title">
        <h3><?php echo HtmlHelper::encode($widget->config['title'])?></h3>
    </div>
    <div class="widget-content">
        <?php echo renderCats($cats)?>
    </div>
</div>