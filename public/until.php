<?php
//工具帮助类

function getTeacherKey($id)
{
    return "vote.teacher.$id.verification.id";
}

function getStudentKey($id)
{
    return "vote.student.$id.verification.id";
}