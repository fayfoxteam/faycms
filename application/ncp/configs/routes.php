<?php
return array(
    '/^product\/(\d+)-(\d+)-(\d+)-(\d+)$/'=>'product/index/area_id/$1/cat_id/$2/month/$3/page/$4',
    '/^product\/(\d+)$/'=>'product/item/id/$1',
    '/^travel\/(\d+)-(\d+)-(\d+)-(\d+)$/'=>'travel/index/area_id/$1/cat_id/$2/month/$3/page/$4',
    '/^travel\/(\d+)$/'=>'travel/item/id/$1',
    '/^food\/(\d+)-(\d+)-(\d+)-(\d+)$/'=>'food/index/area_id/$1/cat_id/$2/month/$3/page/$4',
    '/^food\/(\d+)$/'=>'food/item/id/$1',
    '/^special\/(\d+)$/'=>'special/item/id/$1',
    '/^news\/(\d+)$/'=>'news/item/id/$1',
);