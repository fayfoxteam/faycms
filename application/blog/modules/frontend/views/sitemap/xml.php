<?php echo '<?xml version="1.0" encoding="UTF-8"?>';?>
<urlset>
	<url>
		<loc><?php echo $this->url()?></loc>
		<changefreq>weekly</changefreq>
		<priority>1.0</priority>
	</url>
<?php foreach($cats as $c){?>
	<url>
		<loc><?php echo $this->url('cat/'.$c['id'])?></loc>
		<changefreq>weekly</changefreq>
		<priority>0.9</priority>
	</url>
<?php }?>
<?php foreach($posts as $p){?>
	<url>
		<loc><?php echo $this->url('post/'.$p['id'])?></loc>
		<lastmod><?php echo date('Y-m-d', $p['publish_time'])?></lastmod>
		<changefreq>never</changefreq>
		<priority>0.8</priority>
	</url>
<?php }?>
</urlset>