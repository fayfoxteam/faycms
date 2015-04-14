<?php
namespace vote\modules\frontend\controllers;

use vote\library\FrontendController;
use fay\models\Post;
use fay\models\Page;
use fay\models\User;
use fay\models\tables\Users;
use fay\core\Db;
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
        $user_all = Users::model()->fetchAll();
        $this->view->teachers = Post::model()->getByCatAlias('list');
        
        $this->view->studentCount = count($user_all);
        $this->view->render();
    }
    
    
    

}