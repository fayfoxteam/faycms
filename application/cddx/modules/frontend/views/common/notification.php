<?php foreach($notification as $status => $n){
    $content = implode('<br />', $n);?>
    <script>
    $(function(){
        setTimeout(function(){
            $.fancybox(
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
            );
        }, 1);
    });
    </script>
<?php }?>