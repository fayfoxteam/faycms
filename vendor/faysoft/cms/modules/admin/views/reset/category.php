<div class="row">
    <div class="col-12">
        <div class="box">
            <div class="box-content">
                <div class="mb10">
                    <p>当分类表左右值索引出现异常时候，可用此方法重置左右值索引</p>
                    <p class="fc-grey">（执行时间可能较长，请耐心等待）</p>
                </div>
                <a id="reset-category-do" href="javascript:" class="btn btn-sm">重置</a>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    $('#reset-category-do').on('click', function(){
        $('body').block();
        $.ajax({
            'type': 'post',
            'cache': false,
            'success': function(resp){
                $('body').unblock();
                if(resp.status){
                    common.notify(resp.message, 'success');
                }else{
                    common.notify(resp.message, 'fail');
                }
            }
        });
    });
});
</script>