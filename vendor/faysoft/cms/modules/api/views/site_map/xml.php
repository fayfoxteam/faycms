<?php
/**
 * @var $this \fay\core\View
 * @var $last_post array
 * @var $cat_last_post array
 * @var $cat_map array
 * @var $posts array
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
    <lastmod><?php echo date('c', $last_post['update_time'])?></lastmod>
    <changefreq>always</changefreq>
</url>
<?php foreach($cat_last_post as $cat){?>
<url>
    <loc><?php echo $this->url($cat_map[$cat['cat_id']]['alias'])?>/</loc>
    <lastmod><?php echo date('c', $cat['lastmod'])?></lastmod>
    <changefreq>always</changefreq>
</url>
<?php }?>
<?php foreach($posts as $post){?>
<url>
    <loc><?php echo \cms\helpers\LinkHelper::getPostLink($post)?></loc>
    <lastmod><?php echo date('c', $post['update_time'])?></lastmod>
    <changefreq>always</changefreq>
</url>
<?php }?>
</urlset>