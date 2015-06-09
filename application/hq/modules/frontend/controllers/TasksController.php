<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 15/6/9
 * Time: 下午9:26
 */
namespace hq\modules\frontend\controllers;

use fay\core\Sql;
use hq\library\FrontController;
use hq\models\tables\ZbiaoRecords;
use hq\models\ZbiaoRecord;

class TasksController extends FrontController
{
    public function show()
    {
        $this->layout->title = '水电详情';

        //        显示第一个电表的数据
        $condition = ['biao_id = ?' => 1001];
        $sql = new Sql();
        $chat_data = $sql->from('zbiao_records', 'records', 'day_use')
            ->where($condition)
            ->order('created asc')
            ->limit(10)
            ->fetchAll();
        $chat_date = $sql->from('zbiao_records', 'records', 'created')
            ->where($condition)
            ->order('created asc')
            ->limit(10)
            ->fetchAll();

        $this->view->data = ZbiaoRecord::getChatData($chat_data);
        $this->view->date = ZbiaoRecord::getChatData($chat_date, true);

        $this->view->render();
    }

    public function getData()
    {
        $data['code'] = 0;
        $type = $this->input->post('type', 'intval');
        $tree_id = $this->input->post('treeId', 'intval');
        $name = $this->input->post('name');
        $text = $this->input->post('text');

        $condition = ['biao_id = ?' => $tree_id];
        $sql = new Sql();
        $chat_data = $sql->from('zbiao_records', 'records', 'day_use')
            ->where($condition)
            ->order('created asc')
            ->limit(10)
            ->fetchAll();
        $chat_date = $sql->from('zbiao_records', 'records', 'created')
            ->where($condition)
            ->order('created asc')
            ->limit(10)
            ->fetchAll();
        if (!$chat_data)
        {
            $this->finish(['code' => -1, 'message' => '暂无数据']);
        }
        $data['data'] = ZbiaoRecord::getChatData($chat_data);
        $data['date'] = ZbiaoRecord::getChatData($chat_date, true);
        $data['name'] = $name;
        $data['text'] = $text;
        $data['type'] = $type;

        $this->finish($data);
    }
}