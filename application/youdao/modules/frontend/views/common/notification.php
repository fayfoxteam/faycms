<?php foreach($notification as $status => $n){
	$content = implode('<br />', $n);?>
	<script>
	$(function(){
		system.getCss(system.url('css/jquery.fancybox-1.3.4.css'), function(){
			system.getScript(system.url('js/jquery.fancybox-1.3.4.pack.js'), function(){
				setTimeout($.fancybox(
					'<p class="notification-modal <?php echo $status?>"><?php echo $content?></p>',
					{
						"hideOnOverlayClick":false,
						"hideOnContentClick":true,
						"showCloseButton":false,
						"centerOnScroll":true,
						"overlayShow":false,
						"autoDimensions": false,
						"padding": 0,
						"height":"auto",
						"width":"auto"
					}
				), 100);
			});
		});
	});
	</script>
<?php }?>