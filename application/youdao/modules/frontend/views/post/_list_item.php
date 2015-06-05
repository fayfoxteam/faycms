<li>
	<span class="time"><?php echo date('Y-m-d', $data['publish_time'])?></span>
	<a href="<?php echo $this->url('post/'.$data['id'])?>"><?php echo $data['title']?></a>
</li>