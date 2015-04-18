<?php 

$redis = new Redis();
$redis->connect('redis', 6379);

?>
<link rel="stylesheet" href="<?php echo $this->staticFile('css/progress.css')?>" media="all" />



<div class="container">
    <div class="clear-100"></div>
    <h1>教师评选结果</h1>
    <div class="clear-30"></div>
    
    <div class="container">
        <?php foreach ($teachers as $teacher){ 
        $teacherVote = $redis->sSize(getTeacherKey($teacher['id']));
        if ($studentCount == 0)
        {
            $percent = 0;
        }
        else
        {
            $percent = $teacherVote / $studentCount *100;
        }
        
        ?>
    <div class="skillbar clearfix " data-percent="<?php echo $percent ?>%">
    	<div class="skillbar-title" style="background: #2980b9;"><span><?php echo $teacher['title']." ".$percent."%" ?></span></div>
    	<div class="skillbar-bar" style="background: #3498db;"></div>
    	<div class="skill-bar-percent"><?php echo $teacherVote ?>票</div>
    </div> <!-- End Skill Bar -->
    <?php } ?>
    </div>
    
    
    
    
</div>


<div class="clear-100"></div>
<script>
$(document).ready(function(){
	$('.skillbar').each(function(){
		$(this).find('.skillbar-bar').animate({
			width:$(this).attr('data-percent')
		},3000);
	});
});
</script>