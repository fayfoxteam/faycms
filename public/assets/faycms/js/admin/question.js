var question = {
	'type':{},
	'createAnswer':function(){
		var rand = Math.random();
		$(".answer-list").append([
			'<div class="dragsort-item">',
				'<a class="dragsort-rm" href="javascript:;"></a>',
				'<a class="dragsort-item-selector"></a>',
				'<div class="dragsort-item-container mr10">',
					'<textarea class="form-control" name="selector_answers[new', rand, ']"></textarea>',
					'<label>',
					function(rand){
						if($('[name="type"]:checked').val() == question.type.single_answer){
							return '<input type="radio" name="selector_right_answers[]" value="new' + rand + '" />';
						}else if($('[name="type"]:checked').val() == question.type.multiple_answers){
							return '<input type="checkbox" name="selector_right_answers[]" value="new' + rand + '" />';
						}
					}(rand),
					'正确答案',
					'</label>',
				'</div>',
			'</div>'
		].join(''));
	},
	'setAnswerPanel':function(){
		$('[name="type"]').on('change', function(){
			if($(this).val() == question.type.multiple_answers){
				$("#selector-panel").show().siblings().hide();
				$('[name="selector_right_answers[]"]').prop('type', 'checkbox');
			}else if($(this).val() == question.type.single_answer){
				$("#selector-panel").show().siblings().hide();
				$('[name="selector_right_answers[]"]').prop('type', 'radio');
			}else if($(this).val() == question.type.true_or_false){
				$("#true-or-false-panel").show().siblings().hide();
			}else if($(this).val() == question.type.input){
				$("#input-panel").show().siblings().hide();
			}
			$("#box-answers").block();
			$("#box-answers").unblock();
		});
	},
	'event':function(){
		//添加答案
		$("#answer-container").on('click', '#create-answer-link', function(){
			question.createAnswer();
		});
	},
	'init':function(){
		this.event();
		this.setAnswerPanel();
	}
};