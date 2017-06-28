<?php
/**
 * @var $this \fay\core\View
 * @var $last_post array
 * @var $cat_last_post array
 * @var $cat_map array
 * @var $posts array
 * @var $config array
 */
?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'?>

<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<url>
    <loc><?php echo $this->url()?></loc>
    <lastmod><?php
        if($config['index:lastmod'] == 'last_post'){
            echo date('c', $last_post['update_time']);
        }else{
            echo date('c');
        }
    ?></lastmod>
<?php if($config['index:changefreq']){?>
    <changefreq><?php echo $config['index:changefreq']?></changefreq>
<?php }?>
<?php if($config['index:priority']){?>
    <priority><?php echo $config['index:priority']?></priority>
<?php }?>
</url>
<?php
/**
 * 分类页
 */
?>
<?php foreach($cat_last_post as $cat){?>
<url>
    <loc><?php echo \cms\helpers\LinkHelper::generateCatLink($cat_map[$cat['cat_id']])?></loc>
    <lastmod><?php
        if($config['cat:lastmod'] == 'last_post'){
            echo date('c', $cat['lastmod']);
        }else{
            echo date('c');
        }
        ?></lastmod>
<?php if($config['cat:changefreq']){?>
    <changefreq><?php echo $config['cat:changefreq']?></changefreq>
<?php }?>
<?php if($config['cat:priority']){?>
    <priority><?php echo $config['cat:priority']?></priority>
<?php }?>
</url>
<?php }?>
<?php
/**
 * 文章详情页
 */
?>
<?php foreach($posts as $post){?>
<url>
    <loc><?php echo \cms\helpers\LinkHelper::generatePostLink($post)?></loc>
    <lastmod><?php
        if($config['post:lastmod'] == 'update_time'){
            echo date('c', $post['update_time']);
        }else{
            echo date('c');
        }
        ?></lastmod>
<?php if($config['post:changefreq']){?>
    <changefreq><?php echo $config['post:changefreq']?></changefreq>
<?php }?>
<?php if($config['post:priority']){?>
    <priority><?php echo $config['post:priority']?></priority>
<?php }?>
</url>
<?php }?>
</urlset>