<?php
/**
 * @var string $alias
 * @var array $config
 * @var fay\widgets\category_posts\controllers\IndexController $widget
 */
?>
<div class="col-md-4">
    <?php $widget->view->render('template', $this->getViewData())?>
</div>