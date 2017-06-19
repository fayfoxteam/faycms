<?php
use fay\helpers\HtmlHelper;

?>
<form method="post" action="" id="form" class="validform">
    <div class="row">
        <div class="col-6">
            <div class="form-field">
                <?php echo HtmlHelper::inputRadio('left[from]', 'local', true, array(
                    'label'=>'本地数据库',
                    'class'=>'left-from',
                ))?>
                <?php echo HtmlHelper::inputRadio('left[from]', 'other', false, array(
                    'label'=>'第三方数据库',
                    'class'=>'left-from',
                ))?>
            </div>
            <div id="left-db" class="hide">
                <div class="form-field">
                    <label class="title bold">Host<em class="fc-red">*</em></label>
                    <?php echo F::form()->inputText('left[host]', array(
                        'data-required'=>"Host can't be empty",
                        'class'=>'form-control mw400',
                    ))?>
                </div>
                <div class="form-field">
                    <label class="title bold">User<em class="fc-red">*</em></label>
                    <?php echo F::form()->inputText('left[user]', array(
                        'data-required'=>"User can't be empty",
                        'class'=>'form-control mw400',
                    ))?>
                </div>
                <div class="form-field">
                    <label class="title bold">Password</label>
                    <?php echo F::form()->inputText('left[password]', array(
                        'class'=>'form-control mw400',
                    ))?>
                </div>
                <div class="form-field">
                    <label class="title bold">Port</label>
                    <?php echo F::form()->inputText('left[port]', array(
                        'data-required'=>"Db Port can't be empty",
                        'class'=>'form-control mw400',
                    ), 3306)?>
                </div>
                <div class="form-field">
                    <label class="title bold">Db Name<em class="fc-red">*</em></label>
                    <?php echo F::form()->inputText('left[dbname]', array(
                        'data-required'=>"Db Name can't be empty",
                        'class'=>'form-control mw400',
                    ))?>
                </div>
                <div class="form-field">
                    <label class="title bold">Table Prefix</label>
                    <?php echo F::form()->inputText('left[prefix]', array(
                        'class'=>'form-control mw400',
                    ))?>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="form-field">
                <?php echo HtmlHelper::inputRadio('right[from]', 'local', false, array(
                    'label'=>'本地数据库',
                    'class'=>'right-from',
                ))?>
                <?php echo HtmlHelper::inputRadio('right[from]', 'other', true, array(
                    'label'=>'第三方数据库',
                    'class'=>'right-from',
                ))?>
            </div>
            <div id="right-db">
                <div class="form-field">
                    <label class="title bold">Host<em class="fc-red">*</em></label>
                    <?php echo F::form()->inputText('right[host]', array(
                        'data-required'=>"Host can't be empty",
                        'class'=>'form-control mw400',
                    ))?>
                </div>
                <div class="form-field">
                    <label class="title bold">User<em class="fc-red">*</em></label>
                    <?php echo F::form()->inputText('right[user]', array(
                        'data-required'=>"User can't be empty",
                        'class'=>'form-control mw400',
                    ))?>
                </div>
                <div class="form-field">
                    <label class="title bold">Password</label>
                    <?php echo F::form()->inputText('right[password]', array(
                        'class'=>'form-control mw400',
                    ))?>
                </div>
                <div class="form-field">
                    <label class="title bold">Port</label>
                    <?php echo F::form()->inputText('right[port]', array(
                        'data-required'=>"Db Port can't be empty",
                        'class'=>'form-control mw400',
                    ), 3306)?>
                </div>
                <div class="form-field">
                    <label class="title bold">Db Name<em class="fc-red">*</em></label>
                    <?php echo F::form()->inputText('right[dbname]', array(
                        'data-required'=>"Db Name can't be empty",
                        'class'=>'form-control mw400',
                    ))?>
                </div>
                <div class="form-field">
                    <label class="title bold">Table Prefix</label>
                    <?php echo F::form()->inputText('right[prefix]', array(
                        'class'=>'form-control mw400',
                    ))?>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="form-field">
    <a href="javascript:" class="btn" id="form-submit">Submit</a>
</div>
<script>
$(function(){
    $('.left-from').on('change', function(){
        if($(this).val() == 'local'){
            $('#left-db').find('input').each(function(){
                $(this).poshytip('hide');
            });
            $('#left-db').hide();
        }else{
            $('#left-db').show();
        }
    });
    $('.right-from').on('change', function(){
        if($(this).val() == 'local'){
            $('#right-db').find('hidden').each(function(){
                $(this).poshytip('hide');
            });
            $('#right-db').hide();
        }else{
            $('#right-db').show();
        }
    });
});
</script>