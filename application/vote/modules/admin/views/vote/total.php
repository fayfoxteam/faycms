<?php
?>
<div class="row">
    <div class="col-12">
        <table class="list-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>用户类型</th>
                    <th>院系部门</th>
                    <th>年级系科</th>
                    <th>未投票人数</th>
                </tr>
            </thead>
            <tbody>
                 <?php foreach ($total as $key => $count)
                 { ?>
                 <tr>
                     <td><?= $key + 1 ?></td>
                     <td><?= $count['user_type']==1 ? '学生' : '教师'?></td>
                     <td><?= $count['department'] ?></td>
                     <td><?= $count['class'] ?></td>
                     <td><?= $count['COUNT(id)'] ?>人</td>
                 </tr>
                 
                 <?php 
                 }
                 ?>
            </tbody>
        </table>

    </div>
</div>