var paper = {
	'types':{},
	'page':1,//弹窗问题列表当前页
	'getQuestions':function(page){
		if(typeof(page) == 'undefined'){
			page = 1;
		}
		paper.page = page;
		
		//搜索表单数据
		var ajax_data = $('#search-form').serializeArray();
		ajax_data = (function(data){
			var return_data = {};
			$.each(data, function(i, n){
				return_data[n.name] = n.value;
			})
			return return_data;
		}(ajax_data));
		//已选择试题
		ajax_data.selected = (function(){
			var selected = [];
			$('[name="questions[]"]').each(function(){
				selected.push($(this).val());
			});
			return selected;
		}());
		//当前页
		ajax_data.page = page;
		$.ajax({
			'type': 'GET',
			'url': system.url('admin/exam-question/get-all'),
			'data': ajax_data,
			'dataType': 'json',
			'cache': false,
			'success': function(resp){
				$('#question-dialog').unblock();
				if(resp.status){
					paper.renderQuestions(resp.data);
					common.showPager('questions-list-pager', resp.pager);
					$('#question-dialog .select-all').attr('checked', false);
				}else{
					alert(resp.message);
				}
			}
		});
	},
	'renderQuestions':function(questions){
		$('#question-dialog .inbox-table tbody').html('');
		$.each(questions, function(i, data){
			$('#question-dialog .inbox-table tbody').append(['<tr>',
  				'<td><input type="checkbox" name="select-questions[]" value="', data.id, '" /></td>',
 				'<td>', data.question, '</td>',
				'<td>', system.encode(data.cat_title), '</td>',
				'<td>', paper.types[data.type], '</td>',
				'<td>', data.score, '</td>',
			'</tr>'].join(''));
		});
		
		$.fancybox.center(true);
	},
	//遍历选中的试题
	'selectQuestions':function(){
		$('#question-dialog').block({
			'zindex':1200
		});
		
		var questions = [];
		$('input[name="select-questions[]"]:checked').each(function(){
			questions.push($(this).val());
		});
		
		paper.setQuestion(questions);
	},
	//往试题列表插入一个或多个试题
	'setQuestion':function(question_id){
		$.ajax({
			'type': 'GET',
			'url': system.url('admin/exam-question/get'),
			'data': {'id':question_id},
			'dataType': 'json',
			'cache': false,
			'success': function(resp){
				if(resp.status){
					$.each(resp.data, function(i, data){
						$('#question-list').append(['<div class="dragsort-item">',
							'<input type="hidden" name="questions[]" value="', data.id, '" />',
							'<a class="dragsort-rm" href="javascript:;"></a>',
							'<a class="dragsort-item-selector"></a>',
							'<div class="dragsort-item-container mr10">',
								'<p>', data.question, '</p>',
								'<p class="mt5">',
									'<span>', paper.types[data.type], '</span>',
									' | ',
									'<label>分值：<input type="text" name="score[]" class="w100" value="', data.score, '" /></label>',
								'</p>',
							'</div>',
						'</div>'].join(''));
					});
					paper.getQuestions(paper.page);
					paper.refreshScore();
				}else{
					alert(resp.message);
				}
			}
		});
	},
	//刷新总分
	'refreshScore':function(){
		var total_score = 0;
		$('#question-list [name="score[]"]').each(function(){
			total_score += parseFloat($(this).val());
		});
		$('#total-score').text(system.changeTwoDecimal(total_score));
	},
	'events':function(){
		system.getCss(system.url('css/jquery.fancybox-1.3.4.css'), function(){
			system.getScript(system.url('js/jquery.fancybox-1.3.4.pack.js'), function(){
				$('#select-question-link').fancybox({
					'padding':0,
					'titleShow':false,
					'centerOnScroll':true,
					'onComplete':function(o){
						$('#question-dialog').block({
							'zindex':1200
						});
						paper.getQuestions($(o).attr('data-id'));
					}
				});
			});
		});
		
		//搜索试题
		$(document).on('click', '#search-form-ajax-submit', function(){
			$('#question-dialog').block({
				'zindex':1200
			});
			paper.getQuestions();
		});
		
		//分页条
		$('#questions-list-pager').on('click', 'li a', function(){
			var page = $(this).attr('data-page');
			if(page){
				$('#question-dialog').block({
					'zindex':1200
				});
				paper.getQuestions(page);
			}
		});
		
		//插入选中的试题
		$(document).on('click', '#select-questions', function(){
			paper.selectQuestions();
		});
		
		//移除一个试题
		common.afterDragsortListItemRemove = paper.refreshScore;
		
		$('#question-list').on('change', '[name="score[]"]', function(){
			paper.refreshScore();
		});
		
		//全选
		$('#question-dialog').on('change', '.select-all', function(){
			$('#question-dialog').find('[type="checkbox"]').attr('checked', !!$(this).attr('checked'));
		});
	},
	'init':function(){
		this.events();
	}
};