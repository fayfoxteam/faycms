<?php
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;
use fayexam\models\tables\ExamQuestionsTable;
?>
<div class="row">
    <div class="col-12">
        <div class="detail-panel">
            <div class="bd">
                <table class="form-table col4">
                    <tbody>
                        <tr>
                            <th>试卷名称</th>
                            <td colspan=3"><?php echo HtmlHelper::encode($paper['title'])?></td>
                        </tr>
                        <tr>
                            <th>考试时间</th>
                            <td colspan=3"><?php echo DateHelper::diff($exam['start_time'], $exam['end_time']), ' ( ',
                                DateHelper::format($exam['start_time']), ' 至 ',
                                DateHelper::format($exam['end_time']), ' )'?></td>
                        </tr>
                        <tr>
                            <th>得分</th>
                            <td>
                                <em id="exam-score"><?php echo $exam['score']?></em>
                                /
                                <em id="exam-total-score"><?php echo $exam['total_score']?></em>
                            </td>
                            <th>答题方式</th>
                            <td><?php echo $exam['rand'] ? '随机题序' : '顺序答题'?></td>
                        </tr>
                        <tr>
                            <th>用户名</th>
                            <td><?php echo HtmlHelper::encode($user['username'])?></td>
                            <th>用户昵称</th>
                            <td><?php echo HtmlHelper::encode($user['nickname'])?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="exam-question-list">
            <?php foreach($exam_questions as $index => $eq){
                switch($eq['type']){
                    case ExamQuestionsTable::TYPE_SINGLE_ANSWER:
                        $this->renderPartial('_question_single_answer', array(
                            'index'=>$index,
                            'exam_question'=>$eq,
                        ));
                    break;
                    case ExamQuestionsTable::TYPE_TRUE_OR_FALSE:
                        $this->renderPartial('_question_true_or_false', array(
                            'index'=>$index,
                            'exam_question'=>$eq,
                        ));
                    break;
                    case ExamQuestionsTable::TYPE_MULTIPLE_ANSWERS:
                        $this->renderPartial('_question_multiple_answer', array(
                            'index'=>$index,
                            'exam_question'=>$eq,
                        ));
                    break;
                    case ExamQuestionsTable::TYPE_INPUT:
                        $this->renderPartial('_question_input', array(
                            'index'=>$index,
                            'exam_question'=>$eq,
                        ));
                    break;
                }
            }?>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    $('.exam-question-list').on('click', '.set-score-link', function(){
        if(!$(this).next('.set-score-panel').length){
            var id = $(this).attr('data-id');
            var score = $('#question-'+id).find('.score').text();
            $(this).after(['<span class="set-score-panel">',
                '<input type="text" class="w50" value="', score, '" />',
                '<a href="javascript:;" class="btn btn-sm set-score-submit">提交</a>',
                '<a href="javascript:;" class="btn btn-grey btn-sm set-score-cancel">取消</a>',
            '</span>'].join(''));
        }
    }).on('click', '.set-score-submit', function(){
        if($(this).parent().find('.submit-loading').length){
            return false;
        }
        var score = $(this).prev().val();
        var id = $(this).parent().prev().attr('data-id');
        var total_score = $('#question-'+id).find('.total-score').text();
        if(parseFloat(score) > parseFloat(total_score)){
            common.alert('设置得分不能高于总分');
            return false;
        }else{
            $(this).parent().append('<img src="'+system.assets('images/throbber.gif')+'" class="submit-loading" />');
            $.ajax({
                'type': 'GET',
                'url': system.url('fayexam/admin/exam/set-score'),
                'data': {
                    'id':id,
                    'score':score
                },
                'dataType': 'json',
                'cache': false,
                'success': function(resp){
                    if(resp.status){
                        var $question = $('#question-'+resp.id);
                        $question.find('.score').text(system.changeTwoDecimal(resp.score));
                        $question.find('.set-score-panel').fadeOut('normal', function(){
                            $(this).remove();
                        });
                        $('#exam-score').text(system.changeTwoDecimal(resp.exam_total_score));
                    }else{
                        common.alert(resp.message);
                    }
                }
            });
        }
    }).on('click', '.set-score-cancel', function(){
        $(this).parent().fadeOut('normal', function(){
            $(this).remove();
        });
    });
});
</script>