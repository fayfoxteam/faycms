<?php
use fay\models\tables\Users;

$rules = array(
	array('username', 'string', array('min'=>2, 'max'=>5, 'format'=>'alias')),
	array('username', 'required'),
	array('role', 'range', array('range'=>array('2', '3'))),
	array('status', 'int', array('max'=>1)),
	array('status', 'required'),
	array('refer', 'string', array('min'=>2, 'max'=>5)),
	array('cat_id', 'int', array('min'=>2, 'max'=>4)),
	array('cat_id', 'range', array('range'=>array('2', '3'))),
	array('username', 'ajax', array('url'=>array('tools/user/is-username-not-exist'))),
);

$js_rules = F::form()->getJsRules($rules);
$labels = array(
	'username'=>'用户名',
);
?>
<div class="col-2-1">
	<div class="col-left">
		<h3>Form 1</h3>
		<form id="test-form" class="form-1-3" action="<?php echo $this->url('tools/input/get')?>">
			<h3>输入框(username)</h3>
			<input type="text" name="username" />
			<h3>单选框(role)</h3>
			<label><input type="radio" name="role" value="1" />1</label>
			<label><input type="radio" name="role" value="2" />2</label>
			<h3>复选框(status)</h3>
			<label><input type="checkbox" name="status" value="1" />1</label>
			<label><input type="checkbox" name="status" value="2" />2</label>
			<h3>文本域(refer)</h3>
			<textarea name="refer"></textarea>
			<h3>下拉框(block)</h3>
			<select name="block">
				<option value="0">否</option>
				<option value="1">是</option>
			</select>
			<h3>下拉多选框(cat_id)</h3>
			<select name="cat_id" multiple="multiple">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
			</select>
			
			<div><input type="submit" /></div>
		</form>
		<h3>Form 3</h3>
		<form id="test-form3" class="form-1-3">
			<h3>输入框(username)</h3>
			<input type="text" name="username" />
			<h3>单选框(role)</h3>
			<label><input type="radio" name="role" value="1" />1</label>
			<label><input type="radio" name="role" value="2" />2</label>
			<h3>复选框(status)</h3>
			<label><input type="checkbox" name="status" value="1" />1</label>
			<label><input type="checkbox" name="status" value="2" />2</label>
			<h3>文本域(refer)</h3>
			<textarea name="refer"></textarea>
			<h3>下拉框(block)</h3>
			<select name="block">
				<option value="0">否</option>
				<option value="1">是</option>
			</select>
			<h3>下拉多选框(cat_id)</h3>
			<select name="cat_id" multiple="multiple">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
			</select>
			
			<div><input type="submit" /></div>
		</form>
	</div>
	<div class="col-right">
		<h3>Form 2</h3>
		<form id="test-form2">
			<h3>输入框(username2)</h3>
			<input type="text" name="username" data-required="1" data-rule="string" data-params="{min:2,max:5}" data-ajax="<?php echo $this->url('tools/user/is-username-exist')?>" />
			<h3>单选框(role2)</h3>
			<label><input type="radio" name="role2" value="1" />1</label>
			<label><input type="radio" name="role2" value="2" />2</label>
			<h3>复选框(status2)</h3>
			<label><input type="checkbox" name="status2" value="1" />1</label>
			<label><input type="checkbox" name="status2" value="2" />2</label>
			<h3>文本域(refer2)</h3>
			<textarea name="refer2"></textarea>
			<h3>下拉框(block2)</h3>
			<select name="block2">
				<option value="0">否</option>
				<option value="1">是</option>
			</select>
			<h3>下拉多选框(cat_id2)</h3>
			<select name="cat_id2" multiple="multiple">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
			</select>
			
			<div><input type="submit" /></div>
		</form>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/fayfox.validform.js"></script>
<script>
var demo, demo2;
$(function(){
	demo = $('.form-1-3').validform(<?php echo json_encode($js_rules)?>, <?php echo json_encode($labels)?>, {
		'showAllError':true,
		'beforeSubmit':function(){
			//return false;
		},
		'onError':function(obj, msg, rule){
			console.log(msg);
		},
		'ajaxSubmit':true,
		'afterAjaxSubmit':function(form, resp){
			console.log(resp);
		}
	});
	demo.addRule('username', {'required':true, 'validators':[{
		'name':'string',
		'params':{'min':2}
	}]});
	demo.addLabels({'username2':'asdf'});

	demo2 = $('#test-form2').validform();
});
</script>