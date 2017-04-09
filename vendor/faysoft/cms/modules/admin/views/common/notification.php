<script>
$(function(){
<?php foreach($notification as $status => $n){?>
    common.notify('<?php echo '<p>'.implode('</p><p>', $n).'</p>'?>', '<?php echo $status?>');
<?php }?>
});
</script>