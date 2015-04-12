<?php
namespace vote\modules\frontend\controllers;

use vote\library\FrontendController;
use fay\models\Post;
use fay\models\Page;
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
                }
            }
        }
        else 
        {
            $this->finish(array('code' => 2, 'message' => '您已经投过了，请不要重复投'));
        }
        $this->finish($data);
    }
    
    public function test()
    {
//         dump($_SESSION);
//         echo $this->session->get('id');
        $redis = $this->redis();
        $redis->sAdd('name', '22');
        $redis->sAdd('name', '33');
//         $redis->set('name', 'wwhis', 10);
//         $redis->incr('name');
    }
    

}