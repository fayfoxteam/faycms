<?php
namespace vote\modules\frontend\controllers;

use vote\library\FrontendController;
use fay\models\Post;
use fay\models\Page;
use fay\models\User;
use fay\models\tables\Users;
use fay\core\Db;
use fay\core\Sql;
class IndexController extends FrontendController
{
    
    public function index()
    {

        $this->view->introduce = Page::model()->getByAlias('introduce');
        
        $this->view->lists = Post::model()->getByCatAlias('list');
        
        $this->view->render();
    }

    public function vote()
    {
        $data['code'] = 0;
        $message = $this->input->post('user_id', 'intval');
        $user_id = $this->session->get('id');
        //调用redis
        $redis = $this->redis();
        if (!$redis->exists(getStudentKey($user_id)))
        {
            $vote_data = $this->input->post('data');
            if (!empty($vote_data) && is_array($vote_data))
            {
//                dump($vote_data);die;
                foreach ($vote_data as $key => $teacher)
                {
                    $teacher_key = getTeacherKey($teacher);
                    $redis->sAdd($teacher_key, $user_id);
                    $student_key = getStudentKey($user_id);
                    $redis->set($student_key, $user_id);
                    Users::model()->update(array('vote_active' => 1), $user_id);
                }
            }
        }
        else 
        {
            $this->finish(array('code' => 2, 'message' => '您已经投过了，请不要重复投'));
        }
        $this->finish($data);
    }
    
    //前台显示投票结结果
    public function result()
    {
        //已投票人数
        $sql = new Sql();
        $count = $sql->from('users', 'u')
                     ->where(array(
                            'vote_active = ? ' => 1,
                            'role = ?' => 1
                     ))
                     ->count();
        $this->view->teachers = Post::model()->getByCatAlias('list');
        
        $this->view->studentCount = $count;
        $this->view->render();
    }
    
    //根据姓名查询工号
    public function search()
    {
        $name = $this->input->post('name', 'trim');

        $where = array(
            'nickname = ?' => $name,
        );

        $sql = new Sql();
        $teacher =  $sql->select('username')
                        ->from('users')
                        ->where($where)
                        ->order('id')
                        ->limit(1)
                        ->fetchRow();

        if ($teacher)
        {
            $this->finish(['code' => 0, 'value' => $teacher['username']]);
        }
        else
        {
            $this->finish(['code' => 1, 'message' => '查询失败，请检查姓名是否正确！']);
        }
    }
    

}