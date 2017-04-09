<?php 
namespace cms\helpers;

use cms\models\tables\PostCommentsTable;

class PostCommentHelper{
    /**
     * 获取文章评论状态
     * @param int $status 评论状态码
     * @param int $delete 是否删除
     * @param bool $coloring 是否着色（带上html标签）
     */
    public static function getStatus($status, $delete, $coloring = true){
        if($delete == 1){
            if($coloring)
                return '<span class="fc-red">回收站</span>';
            else
                return '回收站';
        }
        switch ($status) {
            case PostCommentsTable::STATUS_APPROVED:
                return '通过审核';
                break;
            case PostCommentsTable::STATUS_UNAPPROVED:
                if($coloring)
                    return '<span class="fc-blue">未通过审核</span>';
                else
                    return '未通过审核';
                break;
            case PostCommentsTable::STATUS_PENDING:
                if($coloring)
                    return '<span class="fc-orange">待审核</span>';
                else
                    return '待审核';
                break;
        }
    }
}