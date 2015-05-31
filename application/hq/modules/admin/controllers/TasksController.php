<?php
namespace hq\modules\admin\controllers;

use cms\library\AdminController;

class TasksController extends AdminController
{
    public function index()
    {
        echo 'tasks/index';
    }

    public function show()
    {
        $this->layout->subtitle = '水电详情';
        $this->view->render();
    }

    public function getData()
    {
        $data['code'] = 0;
        $type = $this->input->post('type', 'intval');
        $tree_id = $this->input->post('treeId', 'intval');
        $name = $this->input->post('name');
        $text = $this->input->post('text');

        $data['data'] = [$tree_id, 21.9, 9.5, 21.5, 18.2,30.0, 36.9, 9.5, 14.5, 8.2];
        $data['name'] = $name;
        $data['text'] = $text;

        $this->finish($data);

    }

    public function input()
    {
        $this->layout->subtitle = '数据录入';
        $this->view->render();
    }
}