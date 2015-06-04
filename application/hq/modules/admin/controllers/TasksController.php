<?php
namespace hq\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use hq\models\tables\Zbiaos;
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
//             dump($post);
//             die;
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
               $theData->type = $type;
               $theData->biao_name = $dataData->data['biao_name'];
               $theData->zongzhi = $dataData->data['zongzhi'];
               $theData->address = $dataData->data['address'];
               $theData->shuoming = $dataData->data['shuoming'];
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
                       echo $i.".".$user_data->biao_name."...数据更新成功<br />";
                   }
                   else
                   {
                       echo $i.".".$user_data->biao_name."...数据没有变化，不用更新！<br />";
                   }
               }
            }
            Response::output('success', '表格导入成功');
        }
        
        $this->view->render();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}