<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 15/6/9
 * Time: 下午9:26
 */
namespace hq\modules\frontend\controllers;

use fay\core\Sql;
use fay\helpers\Date;
use hq\library\FrontController;
use hq\models\tables\ZbiaoRecords;
use hq\models\ZbiaoRecord;

class TasksController extends FrontController
{
    public function index()
    {
        $str = '2015-6-2 00:00:00';
        $str1 =  strtotime($str) + 60*60*12;
        echo date('Y-m-d H:i:s', $str1);
    }

    public function show()
    {
        $this->layout->title = '水电详情';

        $biao_id = 1001;

        //显示第一个电表的数据
        $condition = ['biao_id = ?' => $biao_id];
        $sql = new Sql();
        $chat_data_day = $sql->from('zbiao_records', 'records', 'day_use')
            ->where($condition)
            ->order('created desc')
            ->limit(10)
            ->fetchAll();
        $chat_date_day = $sql->from('zbiao_records', 'records', 'created')
            ->where($condition)
            ->order('created desc')
            ->limit(10)
            ->fetchAll();

        $chat_data_week = $sql->select('sum(day_use)')
            ->from('zbiao_records', 'records', 'sum(day_use)')
            ->where($condition)
            ->group('week_num')
            ->order('week_num desc')
            ->fetchAll();

        $chat_date_week = $sql->from('zbiao_records', 'records', 'week_num')
            ->where($condition)
            ->group('week_num')
            ->order('week_num asc')
            ->fetchAll();

        $chat_data_month = $sql->select('sum(day_use)')
            ->from('zbiao_records', 'records', 'sum(day_use)')
            ->where($condition)
            ->group('month_num')
            ->order('month_num asc')
            ->fetchAll();

        $chat_date_month = $sql->from('zbiao_records', 'records', 'month_num')
            ->where($condition)
            ->group('month_num')
            ->order('month_num asc')
            ->fetchAll();

        $this->view->data_day = ZbiaoRecord::getChatData($chat_data_day);
        $this->view->date_day = ZbiaoRecord::getChatData($chat_date_day, true);

        $this->view->data_week = ZbiaoRecord::getChatData($chat_data_week);
        $this->view->date_week = ZbiaoRecord::getChatDataByMonth($chat_date_week, false);

        $this->view->data_month = ZbiaoRecord::getChatData($chat_data_month);
        $this->view->date_month = ZbiaoRecord::getChatDataByMonth($chat_date_month);

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
        $chat_data_day = $sql->from('zbiao_records', 'records', 'day_use')
            ->where($condition)
            ->order('created desc')
            ->limit(10)
            ->fetchAll();
        $chat_date_day = $sql->from('zbiao_records', 'records', 'created')
            ->where($condition)
            ->order('created desc')
            ->limit(10)
            ->fetchAll();
        if (!$chat_data_day)
        {
            $this->finish(['code' => -1, 'message' => '暂无数据']);
        }

        $chat_data_week = $sql->select('sum(day_use)')
            ->from('zbiao_records', 'records', 'sum(day_use)')
            ->where($condition)
            ->group('week_num')
            ->order('week_num desc')
            ->fetchAll();

        $chat_date_week = $sql->from('zbiao_records', 'records', 'week_num')
            ->where($condition)
            ->group('week_num')
            ->order('week_num asc')
            ->fetchAll();

        $chat_data_month = $sql->select('sum(day_use)')
            ->from('zbiao_records', 'records', 'sum(day_use)')
            ->where($condition)
            ->group('month_num')
            ->order('month_num asc')
            ->fetchAll();

        $chat_date_month = $sql->from('zbiao_records', 'records', 'month_num')
            ->where($condition)
            ->group('month_num')
            ->order('month_num asc')
            ->fetchAll();

        $data['data_day'] = ZbiaoRecord::getChatData($chat_data_day);
        $data['date_day'] = ZbiaoRecord::getChatData($chat_date_day, true);

        $data['data_week'] = ZbiaoRecord::getChatData($chat_data_week);
        $data['date_week'] = ZbiaoRecord::getChatDataByMonth($chat_date_week, false);

        $data['data_month'] = ZbiaoRecord::getChatData($chat_data_month);
        $data['date_month'] = ZbiaoRecord::getChatDataByMonth($chat_date_month, true);
        $data['name'] = $name;
        $data['text'] = $text;
        $data['type'] = $type;

        $this->finish($data);
    }

    public function getDataByTime()
    {
        $data['code'] = 0;

        $time = $this->input->get('time', 'intval');

        if ($time == ZbiaoRecords::TIME_DAY) {

        } elseif ($time == ZbiaoRecords::TIME_WEEK) {

        } elseif ($time == ZbiaoRecords::TIME_MONTH) {

        }


        $this->finish($data);
    }
}