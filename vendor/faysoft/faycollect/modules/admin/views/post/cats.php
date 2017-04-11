<?php
use fay\helpers\HtmlHelper;

/**
 * @var $cats array
 */
echo F::form()->select('cat_id', HtmlHelper::getSelectOptions($cats));