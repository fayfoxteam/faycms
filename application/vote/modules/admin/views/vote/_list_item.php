<?php

?>
<tr>
    <td><?php echo $data['nickname']; ?></td>
    <td><?php echo $data['username']; ?></td>
    <td><?php echo $data['department'] ?></td>
    <td><?php echo $data['class'] ?></td>
    <td><?php echo $data['grade'] ?></td>
    <td><?php echo $data['user_type']==1 ? '学生' : '教师' ?></td>
    <td><?php echo $data['vote_active']==0 ? '未投票' : '已投票'; ?></td>
</tr>