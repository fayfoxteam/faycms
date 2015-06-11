<div class="col-2-2-body">
    <div class="col-2-2-content">
        <div class="box">
            <div class="box-title">
                <h4>表格上传</h4>
            </div>
            <div class="box-content">
                <form action="<?php echo $this->url('admin/tasks/inputupload')?>" method="post" enctype="multipart/form-data">
                    <h1>xls文件导入数据库</h1>
                    <label>注意：导入的格式只支持xls（xlsx请自行转换），新的数据会覆盖旧的数据</label><br /><br />
                    <input type="file" name="xlsfile" id="" />
                    <input type="hidden" name="method" value="database"/>
                    <select name="type" id="type">
                        <option value="1">导入</option>
                        <option value="2">更新</option>
                    </select>
                    <input type="submit" value="导入" />
                </form>
            </div>
        </div>
    </div>
</div>