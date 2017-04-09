<?php
use fayexam\models\tables\ExamQuestionsTable;
use fay\helpers\HtmlHelper;
?>
<div class="box fl wp100">
    <div class="box-title">
        <h3><?php echo HtmlHelper::encode($paper['title'])?></h3>
    </div>
    <div class="box-content">
        <div class="st"><div class="sl"><div class="sr"><div class="sb">
            <div class="p16">
                <form method="post" id="form" action="<?php echo $this->url('user/paper/create')?>">
                    <?php echo HtmlHelper::inputHidden('paper_id', $paper['id'])?>
                    <?php echo HtmlHelper::inputHidden('hash', $hash)?>
                    <div class="paper-description"><?php echo $paper['description']?></div>
                    <div id="paper-question-list">
                    <?php foreach($paper['questions'] as $index => $eq){
                        switch($eq['type']){
                            case ExamQuestionsTable::TYPE_SINGLE_ANSWER:
                                $this->renderPartial('_question_single_answer', array(
                                    'index'=>$index,
                                    'question'=>$eq,
                                ));
                            break;
                            case ExamQuestionsTable::TYPE_TRUE_OR_FALSE:
                                $this->renderPartial('_question_true_or_false', array(
                                    'index'=>$index,
                                    'question'=>$eq,
                                ));
                            break;
                            case ExamQuestionsTable::TYPE_MULTIPLE_ANSWERS:
                                $this->renderPartial('_question_multiple_answer', array(
                                    'index'=>$index,
                                    'question'=>$eq,
                                ));
                            break;
                            case ExamQuestionsTable::TYPE_INPUT:
                                $this->renderPartial('_question_input', array(
                                    'index'=>$index,
                                    'question'=>$eq,
                                ));
                            break;
                        }
                    }?>
                    </div>
                    <div class="clearfix">
                        <a href="javascript:;" class="btn-blue" id="form-submit">交卷</a>
                    </div>
                </form>
            </div>
        </div></div></div></div>
    </div>
</div>
<script>
$(function(){
    $('#paper-question-list').on('change', '[type="radio"],[type="checkbox"]', function(){
        $(this).parent().parent().parent().parent().removeClass('error');
    });
    $('#paper-question-list').on('change', 'textarea', function(){
        if($(this).val()){
            $(this).parent().parent().removeClass('error');
        }else{
            $(this).parent().parent().addClass('error');
        }
    });
    $('#form').on('submit', function(){
        var error_number = 0;
        $('#paper-question-list .true-or-false,#paper-question-list .single-answer').each(function(){
            if(!$(this).find('[type="radio"]:checked').length){
                $(this).addClass('error');
                error_number++;
            }else{
                $(this).removeClass('error');
            }
        });
        $('#paper-question-list .multiple-answer').each(function(){
            if(!$(this).find('[type="checkbox"]:checked').length){
                $(this).addClass('error');
                error_number++;
            }else{
                $(this).removeClass('error');
            }
        });
        $('#paper-question-list .input').each(function(){
            if(!$(this).find('textarea').val()){
                $(this).addClass('error');
                error_number++;
            }else{
                $(this).removeClass('error');
            }
        });
        if(error_number){
            if(confirm('您有 '+error_number+' 题未作答，确定要提交吗？')){
                return true;
            }else{
                return false;
            }
        }
    });
});
</script>