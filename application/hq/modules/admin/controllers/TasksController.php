<?php
namespace hq\modules\admin\controllers;

use cms\library\AdminController;
use fay\common\ListView;
use fay\core\Sql;
use hq\models\tables\ZbiaoRecords;
use hq\models\tables\Zbiaos;
use hq\models\Zbiao;
use hq\models\ZbiaoRecord;
use fay\core\Response;
use fay\common\Upload;
use fay\models\File;
use fay\core\Loader;
use fay\core\Db;

class TasksController extends AdminController
{
    public function index()
    {
        echo 'tasks/index';
    }

    public function show()
    {
        $this->layout->subtitle = '水电详情';

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

    public function input()
    {
        $this->layout->subtitle = '数据录入';

        //判断选择录入数据类型
        if ($type_id = $this->input->get('type_id', 'intval'))
        {
            $condition = ['type = ?' => $type_id];
            $tables = Zbiaos::model()->fetchAll($condition);
            $this->view->tables = $tables;
        }

        //录入完成后进行数据的储存
        if ($post = $this->input->post())
        {
            $created = array_pop($post);
            ZbiaoRecord::model()->insertRecord($post, $created);
            Response::output('success', '信息录入成功');
        }

        $this->view->render();
    }

    public function total()
    {
        $this->layout->subtitle = '水电统计';

        $sql = new Sql();
        $sql->from('zbiaos')->order('id asc');

        if ($type_id = $this->input->get('type_id', 'intval'))
        {
            $sql->where(array('type = ?' => $type_id));
        }

        $this->view->listview = new \fay\common\ListView($sql);

        $this->view->render();
    }

    public function detail()
    {
        $biao_id = $this->input->get('id', 'intval');
        $biao = Zbiaos::model()->fetchRow(['biao_id = ?' => $biao_id]);

        $subtitle = $biao['biao_name'].'-详情';
        $this->layout->subtitle = $subtitle;


        $condition = ['biao_id = ?' => $biao_id];
        $sql = new Sql();
        $chat_data = $sql->from('zbiao_records', 'records', 'zongliang')
                         ->where($condition)
                         ->order('created desc')
                         ->limit(10)
                         ->fetchAll();

        $chat_date = $sql->from('zbiao_records', 'records', 'created')
                         ->where($condition)
                         ->order('created desc')
                         ->limit(10)
                         ->fetchAll();

        $this->view->text = $biao['biao_name'];
        $this->view->data = ZbiaoRecord::getChatData($chat_data);
        $this->view->date = ZbiaoRecord::getChatData($chat_date, true);
        $this->view->type = $biao['type'];

        //显示全部数据记录
        $query = new Sql();
        $query->from('zbiao_records', 'records')
            ->where(['biao_id = ?' => $biao_id])
            ->order('created desc');

        $this->view->listview = new ListView($query, [
            'item_view' => '_detail_item_view'
        ]);

        $this->view->render();
    }
    
    public function upload()
    {
        $this->layout->subtitle = '表数据上传';
        
        $method = $this->input->post('method');

        if ($method == 'database')
        {
            $upload = new Upload();
            $upload->run($_FILES['xlsfile']['tmp_name']);
            $result = File::model()->upload('excel');
            $file_path = $result['file_path'].$result['raw_name'].'.xls';
            
            /* 引入excelphpreader类库 */
            Loader::vendor('Excel/excel_reader2');

            $excel = new \Spreadsheet_Excel_Reader($file_path, true, 'UTF-8');
            $count = $excel->sheets[0]['numRows'];
            $realCount = $count - 1;
            
            error_reporting(0);
            echo "<button style='width:200px;height:80px;' onclick='window.history.back();'>点击返回</button>";
            echo "<h1>总计".$realCount."条记录,第一条为表头不计入数据库</h1>";
            
            $dataData = new \stdClass();
            
            $array = $this->config->getFile('array');
            $type = $this->input->post('type', 'intval');
            for ($i = 1; $i <= $excel->sheets[0]['numRows']; $i++)
            {
               if ($i == 1)
               {
                   continue;
               } 
               for ($j = 1; $j <= $excel->sheets[0]['numCols']; $j++)
               {
                   $dataData->data[$array[$j]] = $excel->sheets[0]['cells'][$i][$j];
               }
               
               $theData = new \stdClass();
               $theData->biao_id = $dataData->data['biao_id'];
               $theData->parent_id = $dataData->data['parent_id'];
               $theData->type = $dataData->data['type'];
               $theData->biao_name = $dataData->data['biao_name'];
               $theData->address = $dataData->data['address'];
               $theData->t_number = $dataData->data['t_number'];
               $theData->shuoming = $dataData->data['shuoming'];
               $theData->times = $dataData->data['times'];
               $theData->zongzhi = $dataData->data['zongzhi'];

               $theData->data = json_encode($dataData);
               $theData->created = $this->current_time;
               $theData->updated = $this->current_time;
               
               $db = Db::getInstance();
               $sql = sprintf('select * from fay_zbiaos where biao_id = %d limit 1', $theData->biao_id);
               $result = $db->fetchAll($sql);
               if (!$result)
               {
                   $insert_id = $db->insert('zbiaos', $theData);
                   if ($insert_id)
                   {
                       echo $i. '.' . $theData->biao_name. '...插入成功<br/>';
                   }
                   else
                   {
                       echo $i.".".$theData->biao_name."...插入失败，请检查数据<br />";
                   }
               }
               else
               {
                   $update = $db->update('zbiaos', $theData);
                   if ($update)
                   {
                       echo $i.".".$theData->biao_name."...数据更新成功<br />";
                   }
                   else
                   {
                       echo $i.".".$theData->biao_name."...数据没有变化，不用更新！<br />";
                   }
               }
            }
//            Response::output('success', '表格导入成功');
        }
        
        $this->view->render();
    }
    
    public function inputUpload()
    {
        $this->layout->subtitle = '数据上传';

        $method = $this->input->post('method');

        if ($method == 'database')
        {
            $upload = new Upload();
            $upload->run($_FILES['xlsfile']['tmp_name']);
            $result = File::model()->upload('excel');
            $file_path = $result['file_path'].$result['raw_name'].'.xls';

            /* 引入excelphpreader类库 */
            Loader::vendor('Excel/excel_reader2');

            $excel = new \Spreadsheet_Excel_Reader($file_path, true, 'UTF-8');
            $count = $excel->sheets[0]['numRows'];
            $realCount = $count - 1;

            error_reporting(0);
            echo "<button style='width:200px;height:80px;' onclick='window.history.back();'>点击返回</button>";
            echo "<h1>总计".$realCount."条记录,第一条为表头不计入数据库</h1>";

            $excel_data = new \stdClass();
            $db = Db::getInstance();

            $type = $this->input->post('type');

            for ($i = 1; $i <= $excel->sheets[0]['numRows']; $i++) {

                if ($i == 1) {
                    continue;
                }
                for ($j = 1; $j <= $excel->sheets[0]['numCols']; $j++) {
                    $excel_data->data[$j] = $excel->sheets[0]['cells'][$i][$j];
                }
                $insert_data = new \stdClass();

                if ($type == 1) {
                    $insert_data->biao_id = $excel_data->data[1];
                    $insert_data->parent_id = $excel_data->data[2];
                    $insert_data->zongliang = $excel_data->data[3] ? :0;
                    $insert_data->day_use = $excel_data->data[4];
                    $insert_data->week_num = $excel_data->data[5];
                    $insert_data->month_num = $excel_data->data[6];
                    $insert_data->created = strtotime($excel_data->data[7]) + 60 * 60 * 12;

                    $insert_id = $db->insert('zbiao_records', $insert_data);
                    if ($insert_id) {
                        echo $i. '.' . $insert_data->biao_id. '...插入成功<br/>';
                    } else {
                        echo $i.".".$insert_data->biao_id."...插入失败，请检查数据<br />";
                    }

                } else if ($type == 2) {
                    $insert_data->biao_id = $excel_data->data[1];
                    $insert_data->zongzhi = $excel_data->data[2];
                    $insert_data->updated = $excel_data->data[3];

                    $update_data = ['zongzhi' => $insert_data->zongzhi, 'updated' => $insert_data->updated];
                    $update_id = $db->update('zbiaos', $update_data, array('biao_id = ? '=> $insert_data->biao_id));
                    if ($update_id) {
                        echo $i.".".$insert_data->biao_id."...数据更新成功<br />";
                    } else {
                        echo $i.".".$insert_data->biao_id."...数据没有变化，不用更新！<br />";
                    }
                } else if ($type == 3) {
                    $insert_data->p_id = $excel_data->data[1];
                    $insert_data->name = $excel_data->data[2];
                    $insert_data->created = $this->current_time;
                    $insert_data->updated = $this->current_time;

                    $insert_id = $db->insert('parent_biaos', $insert_data);
                    if ($insert_id) {
                        echo $i. '.' . $insert_data->p_id. '...插入成功<br/>';
                    } else {
                        echo $i.".".$insert_data->p_id."...插入失败，请检查数据<br />";
                    }
                }
            }
        }

        $this->view->render();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}