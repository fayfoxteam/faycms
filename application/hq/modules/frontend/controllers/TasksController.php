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
use hq\models\tables\Zbiaos;
use hq\models\ZbiaoRecord;

class TasksController extends FrontController
{
    public function index()
    {

    }

    public function show()
    {
        $this->layout->title = '水电详情';

        $start_time = $this->input->get('start_time', null, '');
        $end_time = $this->input->get('end_time', null, '');
        $biao_id = $this->input->get('hidden_biao_id', 'intval', 1001);
        
        $biao_name = Zbiaos::model()->fetchRow(['biao_id =?' => $biao_id]);

        //显示第一个电表的数据
        $condition = ['biao_id = ?' => $biao_id];
        $sql = new Sql();
        $sql->from('zbiao_records', 'records', 'day_use')->where($condition);
        if ($start_time) {
            $sql->where(['records.created >= ?' => strtotime($start_time)]);
        }
        if ($end_time) {
            $sql->where(['records.created <= ?' => strtotime($end_time)]);
        }
        if (!$start_time && !$end_time) {
            $sql->limit(10);
        }
        $chat_data_day = $sql->order('created desc')->fetchAll();

        $sql->from('zbiao_records', 'records', 'created')->where($condition);
        if ($start_time) {
            $sql->where(['records.created > ?' => strtotime($start_time)]);
        }
        if ($end_time) {
            $sql->where(['records.created < ?' => strtotime($end_time)]);
        }
        if (!$start_time && !$end_time) {
            $sql->limit(10);
        }
        $chat_date_day = $sql->order('created desc')->fetchAll();

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
            ->order('month_num desc')
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

        $this->view->biao_name = $biao_name['biao_name'];

        $this->session->set('start_time', $start_time);
        $this->session->set('end_time', $end_time);

        $this->view->render();
    }

    public function getData()
    {
        $data['code'] = 0;
        $type = $this->input->post('type', 'intval');
        $tree_id = $this->input->post('treeId', 'intval');
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
            ->order('month_num desc')
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
        $data['text'] = $text;
        $data['type'] = $type;

        $this->finish($data);
    }

    public function getDataByPid()
    {
        $data['code'] = 0;
        $type = $this->input->post('type', 'intval');
        $pid = $this->input->post('pid', 'intval');
        $text = $this->input->post('text');

        $condition = ['parent_id = ?' => $pid];
        $sql = new Sql();
        $chat_data_day = $sql->from('zbiao_records', 'records', 'sum(day_use)')
            ->where($condition)
            ->group('created')
            ->order('created desc')
            ->limit(10)
            ->fetchAll();

        $chat_date_day = $sql->from('zbiao_records', 'records', 'created')
            ->where($condition)
            ->distinct(true)
            ->order('created desc')
            ->limit(10)
            ->fetchAll();

        $chat_data_week = $sql->from('zbiao_records', 'records', 'sum(day_use)')
            ->where($condition)
            ->group('week_num')
            ->order('week_num desc')
            ->fetchAll();

        $chat_date_week = $sql->from('zbiao_records', 'records', 'week_num')
            ->where($condition)
            ->group('week_num')
            ->order('week_num asc')
            ->fetchAll();


        $data['data_day'] = ZbiaoRecord::getChatData($chat_data_day);
        $data['date_day'] = ZbiaoRecord::getChatData($chat_date_day, true);

        $data['data_week'] = ZbiaoRecord::getChatData($chat_data_week);
        $data['date_week'] = ZbiaoRecord::getChatDataByMonth($chat_date_week, false);

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

        $data['data_month'] = ZbiaoRecord::getChatData($chat_data_month);
        $data['date_month'] = ZbiaoRecord::getChatDataByMonth($chat_date_month, true);

        $data['text'] = $text;
        $data['type'] = $type;

        $this->finish($data);
    }

    /**
     * 根据时间来进行查询具体数据，后期可能做excel导出
     */
    public function showListByDate()
    {
        $biaos = Zbiaos::model()->fetchAll([], 'biao_id, biao_name');

        $start_time = $this->input->get('start_time', 'strtotime', '');
        $end_time = $this->input->get('end_time', 'strtotime', '');
        $biao_id = $this->input->get('biao_id', 'intval', 1001);

        $condition = ['biao_id = ?' => $biao_id];

        $this->view->biao_info = Zbiaos::model()->fetchRow($condition, 'type, biao_name');

        $sql = new Sql();
        $sql->from('zbiao_records', 'records', 'day_use')->where($condition);
        if ($start_time) {
            $sql->where(['records.created >= ?' => $start_time]);
        }
        if ($end_time) {
            $sql->where(['records.created <= ?' => $end_time]);
        }
        if (!$start_time && !$end_time) {
            $sql->limit(10);
        }
        $chat_data_day = $sql->order('created desc')->fetchAll();

        $sql->from('zbiao_records', 'records', 'created')->where($condition);
        if ($start_time) {
            $sql->where(['records.created > ?' => $start_time]);
        }
        if ($end_time) {
            $sql->where(['records.created < ?' => $end_time]);
        }
        if (!$start_time && !$end_time) {
            $sql->limit(10);
        }
        $chat_date_day = $sql->order('created desc')->fetchAll();

        $this->view->data_day = ZbiaoRecord::getChatData($chat_data_day);
        $this->view->date_day = ZbiaoRecord::getChatData($chat_date_day, true);

        $biao_arr = [
            '' => '--水电表--',
        ];
        foreach ($biaos as $key => $b) {
            $biao_arr[$b['biao_id']] = $b['biao_name'];
        }

        $this->view->biao_arr = $biao_arr;

        $this->session->set('start_time', date('Y-m-d', $start_time));
        $this->session->set('end_time', date('Y-m-d', $end_time));

        $this->view->render('show-list-by-date');
    }

}