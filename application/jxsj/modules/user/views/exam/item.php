<?php
use fay\helpers\DateHelper;
use fay\helpers\HtmlHelper;
use fayexam\models\tables\ExamQuestionsTable;
?>
<div class="box fl wp100">
    <div class="box-title">
        <h3>我的考卷</h3>
    </div>
    <div class="box-content">
        <div class="st"><div class="sl"><div class="sr"><div class="sb">
            <div class="p16">
                <div class="paper-description">
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th>试卷名称</th>
                                <td><?php echo HtmlHelper::encode($paper['title'])?></td>
                            </tr>
                            <tr>
                                <th>考试时间</th>
                                <td><?php echo DateHelper::diff($exam['start_time'], $exam['end_time']), ' ( ',
                                    DateHelper::format($exam['start_time']), ' 至 ',
                                    DateHelper::format($exam['end_time']), ' )'?></td>
                            </tr>
                            <tr>
                                <th>得分</th>
                                <td><?php echo $exam['score'], ' / ', $exam['total_score']?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="paper-description"><?php echo $paper['description']?></div>
                <div id="paper-question-list">
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
        </div></div></div></div>
    </div>
</div>