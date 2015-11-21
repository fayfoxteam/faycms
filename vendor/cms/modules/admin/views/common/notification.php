<script>
<?php foreach($notification as $status => $n){
	foreach($n as $i){?>
	common.notify('<?php echo $i?>', '<?php echo $status?>');
<?php }
}?>
</script>