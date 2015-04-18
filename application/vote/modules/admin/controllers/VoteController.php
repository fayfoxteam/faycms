<?php
namespace vote\modules\admin\controllers;

use cms\library\AdminController;
use fay\common\Upload;
use fay\models\File;
use fay\core\Loader;
use fay\models\User;
use fay\core\Db;
use fay\models\tables\Users;
use fay\core\Sql;
use fay\common\ListView;

class VoteController extends AdminController
{
    public function index()
    {
        
    }
    
    public function userUpload()
    {
        $this->layout->subtitle = '用户上传';
        $this->view->render();
    }
    
    public function upload()
    {
        $method = $this->input->post('method');
        
        if ($method == 'database')
        {
            $upload = new Upload();
            $upload->run($_FILES['xlsfile']['tmp_name']);
            $result = File::model()->upload('excel');
//             dump($result);
            $file_path = $result['file_path'].$result['raw_name'].'.xls';
            /* 引入excelphpreader类库 */
            Loader::vendor('Excel/reader');
            $excel = new \Spreadsheet_Excel_Reader();
            $excel->setOutputEncoding('UTF-8');
            
            $excel->read($file_path);
            $count = $excel->sheets[0]['numRows'];
            $realCount = $count - 1;
            
            error_reporting(0);
            echo "<button style='width:200px;height:80px;' onclick='window.history.back();'>点击返回</button>";
            $array = $this->config->getFile('array');
            
            echo "<h1>总计".$realcount."条记录,第一条为表头不计入数据库</h1>";
            $dataData = new \stdClass();
            
            $salt = 'whis';
            $user_type = $this->input->post('user_type', 'intval');
            for ($i = 1; $i <= $excel->sheets[0]['numRows']; $i++)
            {
                if ($i == 1)
                {
                    continue;
                }
                for ($j = 1; $j <= $excel->sheets[0]['numCols']; $j++)
                {
                    $excel_data[$array[$j]] = $excel->sheets[0]['cells'][$i][$j];
                }
                
                $user_data = new \stdClass();
                $user_data->username = $excel_data['学号'];
                $user_data->password = md5(md5($excel_data['密码']).$salt);
               
                $user_data->salt = $salt;
                $user_data->nickname = $excel_data['姓名'];
                $user_data->status =  3;
                $user_data->role = 1;
                $user_data->user_type = $user_type;
                $user_data->idnum = $excel_data['身份证号'];
                $user_data->grade = $excel_data['年级'];
                $user_data->class = $excel_data['班级'];
                $user_data->department = $excel_data['院系'];
                $user_data->keywords = json_encode($excel_data);
                
                $db = Db::getInstance();
                $sql = sprintf('select * from `fay_users` where `username` = %d', $user_data->username);
                $result = $db->fetchRow($sql);
                if (!$result)
                {
                    $insert = $db->insert('users', $user_data);
                    if ($insert){
                        echo $i.".".$user_data->nickname."...插入成功<br />";
                    }else{
                        echo $i.".".$user_data->nickname."...插入失败，请检查数据<br />";
                    }
                }
                else 
                {
                    $update = $db->update('users', $user_data, array('id = ?' => $result['id']));
                if ($update){
                        echo $i.".".$user_data->nickname."...数据更新成功<br />";
                    }else{
                        echo $i.".".$user_data->nickname."...数据没有变化，不用更新！<br />";
                    }
                }
            }
            
        }
    }
    
    public function detail()
    {
        $this->layout->subtitle = '投票详情';
        $condition = array(
            'role = ?' => 1,
            'status = ?' => 3,
        );
        $sql = new Sql();
        $sql->from('users')->where($condition);
        $this->view->listview = new ListView($sql);
        
        $this->view->render();
    }
}
