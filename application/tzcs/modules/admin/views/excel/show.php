<div class="col-1">

        <table class="list-table">
            <thead>
                <tr class="alternate">
                    <th>Id</th>
                    <th>学生姓名</th>
                    <th>学生学号</th>
                    <th>最后查询时间</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $value){?>
                    <tr>
                        <td><?php echo $value['id']?></td>
                        <td><?php echo $value['realName']?></td>
                        <td><?php echo $value['idNum']?></td>
                        <td><?php echo $value['searchTime']?></td>
                       
                    </tr>
                <?php }?>
            </tbody>
        </table>

</div>