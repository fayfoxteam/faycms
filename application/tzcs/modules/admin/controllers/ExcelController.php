<?php
namespace tzcs\modules\admin\controllers;

use cms\library\AdminController;
use fay\common\Upload;
use fay\models\File;
use fay\core\Loader;
use fay\models\tables\Files;
use fay\core\Db;


class ExcelController extends AdminController{
    public function __construct(){
        parent::__construct();
        $this->layout->current_directory = 'excel';
    }
    
    public function index(){
        $this->layout->subtitle = '表格上传';
        $this->view->render();
    }
    
    public function show(){
        $this->layout->subtitle = '学生信息';
        $db = Db::getInstance();
        $sql = "SELECT id,idNum,realName,searchTime FROM `fay_tzcs_students`";
        $result = $db->fetchAll($sql);
        $this->view->result = $result;
        $this->view->render();
    }
    public function upload(){
        $method = $this->input->post('method');
    
        if ($method == 'database'){   
            $upload  = new Upload();
            $upload->run($_FILES['xlsfile']['tmp_name']);  
            $result = File::model()->upload('excel');
//             pr($result);
            $file_path = $result['file_path'].$result['raw_name'].'.xls';

//             echo $file_path;
            /* 引入excelphpreader类库 */
            Loader::vendor('Excel/reader');
            $excel = new \Spreadsheet_Excel_Reader();
            $excel->setOutputEncoding('UTF-8');
            
            $excel->read($file_path);
            $count = $excel->sheets[0]['numRows'];
            $realcount = $count-1;
            
            echo $excel->sheets[0]['cells'][2][8];exit();
            error_reporting(0);
            echo "<button style='width:200px;height:80px;' onclick='window.history.back();'>点击返回</button>";
            $array = $this->config->getFile('array');
     
            echo "<h1>总计".$realcount."条记录,第一条为表头不计入数据库</h1>";
            $dataData = new \stdClass();
            
            for ($i = 1; $i <= $excel->sheets[0]['numRows'];$i++){
//             for ($i = 1; $i <= 2;$i++){
                if ($i ==1){
                    continue;
                }
                for ($j = 1;$j <= $excel->sheets[0]['numCols'];$j++){
                    $dataData->data[$array[$j]] = $excel->sheets[0]['cells'][$i][$j];
                } 
               
                $theData = new \stdClass();
                $theData->realName = $dataData->data['姓名'];
                $theData->idNum = $dataData->data['学籍号'];
                $theData->data = json_encode($dataData);
                
//                 pr($theData);exit();
                $db = Db::getInstance();
                $sql = sprintf("SELECT * FROM `fay_tzcs_students` WHERE `idNum` = %d LIMIT 1",$theData->idNum);
                $result = $db->fetchRow($sql);
                if (!$result){
                    $insert = $db->insert('tzcs_students', $theData);
                    if ($insert){
                        echo $i.".".$theData->realName."...插入成功<br />";
                    }else{
                        echo $i.".".$theData->realName."...插入失败，请检查数据<br />";
                    }
                }else{
                    $update = $db->update('tzcs_students', $theData, array('idNum = ?'=> $theData->idNum));
                    if ($update){
                        echo $i.".".$theData->realName."...数据更新成功<br />";
                    }else{
                        echo $i.".".$theData->realName."...数据没有变化，不用更新！<br />";
                    }
                }
            }
            //返回
        }       
    }
    
}