<?php 
 use fay\helpers\Html;
use fay\helpers\String;
use fay\models\File;

?>
  <div class="cp_show clearfix">
        	<div class="title t1"><a href="<?php echo $this->url('cat/'.$data['top'])?>"><img src="<?php echo $this->staticFile('images/more.jpg')?>"></a><?php echo $data['title']?></div>
            <div class="picScroll">
		
		<ul>
		<?php foreach ($posts as $p){?>
			<li>
    			<?php echo Html::link(Html::img($p['thumbnail'], File::PIC_ZOOM, array(
    			    'dw' => 170,
    			    'dh' => 120,
    			)), array(str_replace('{$id}', $p['id'], $data['uri'])), array(
    			    'encode' => false,
    			    'alt'    => $p['title'],
    			    'title'  => $p['title'],
    			))?>
        			<p><?php echo Html::link(String::niceShort($p['title'], 14), array(str_replace('{$id}', $p['id'], $data['uri']))) ?></p>
    			
			</li>
		<?php }?>
		</ul>

	</div>
        </div>