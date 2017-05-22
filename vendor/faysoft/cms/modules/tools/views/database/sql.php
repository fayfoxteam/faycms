<form method="post" id="form">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-title"><h3>SQL</h3></div>
                <div class="box-content">
                    <?php echo F::form()->textarea('sql', array(
                        'class'=>'form-control h200',
                        'id'=>'code-editor',
                        'data-mode'=>'sql',
                    ));?>
                    <a href="javascript:" id="form-submit" class="btn mt5">运行</a>
                    <a href="javascript:" id="form-reset" class="btn btn-grey mt5">重置</a>
                </div>
            </div>
        </div>
        <?php if(!empty($result)){?>
        <div class="col-12">
            <div class="box">
                <div class="box-title"><h3>Result</h3></div>
                <div class="box-content">
                    <div style="min-height:200px"><?php echo $result?></div>
                </div>
            </div>
        </div>
        <?php }?>
    </div>
</form>