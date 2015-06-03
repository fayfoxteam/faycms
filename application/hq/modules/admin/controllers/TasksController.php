<?php
namespace hq\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use hq\models\tables\Zbiaos;
use hq\models\ZbiaoRecord;
use fay\core\Response;

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

        if ($post = $this->input->post())
        {
           ZbiaoRecord::model()->insertRecord($post);
           Response::output('success', '信息录入成功');
        }
        
        $tables = Zbiaos::model()->fetchAll();
        
        $this->view->tables = $tables;
        
        $this->view->render();
    }

    public function total()
    {
        $this->layout->subtitle = '水电统计';

        $condition = [
            'type' => Zbiaos::TYPE_ELECTRICITY,
        ];
        $sql = new Sql();
        $sql->from('zbiaos')->where($condition);

        $sql->order('created desc');

        $this->view->listview = new \fay\common\ListView($sql);

        $this->view->render();
    }
}