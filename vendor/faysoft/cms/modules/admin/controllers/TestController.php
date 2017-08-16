<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use cms\models\tables\PostsTable;
use cms\services\file\ImageTextService;
use fay\core\Db;
use fay\core\Validator;
use fay\helpers\HtmlHelper;
//use fay\log\Logger;
use Monolog\Handler\FilterHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class TestController extends AdminController{
    public function valid(){
        $v = new Validator();
        $v->skip_all_on_error = false;
        $v->setLabels(array(
            'email'=>'邮箱',
            'i'=>'Int',
            'f'=>'Float',
        ));
        pr($v->check(array(
            array('email', 'email'),
            array('m', 'mobile'),
            array('u', 'url'),
            array('zh', 'chinese'),
            array('d', 'datetime'),
            array(array('e', 'email'), 'email'),
            array('r', 'required', array('enableEmpty'=>false)),
            array('i', 'int', array('max'=>10, 'min'=>8, 'too_big'=>'太大了测试')),
            array('f', 'float', array('length'=>5, 'decimal'=>2, 'max'=>88.88, 'min'=>-10000)),
            array('s', 'string', array('max'=>10, 'format'=>'/\d+/')),
            array('unique', 'unique', array('table'=>'users', 'field'=>'username', 'except'=>'id')),
            array('exist', 'exist', array('table'=>'users', 'field'=>'username')),
            array('r', 'range', array('range'=>array('a', 'bb', 'ccc'), 'not'=>true)),
            array('c', 'compare', array('compare_attribute'=>'id', 'operator'=>'==', 'message'=>'{$attribute}值不对')),
        )));
    }
    
    public function jsvalid(){
        return $this->view->render();
    }
    
    public function jsvalidForms(){
        return $this->view->render();
    }
    
    public function phpvalid(){
        $rules = array(
            array('username', 'string', array('min'=>2, 'max'=>5, 'format'=>'alias')),
            array('username', 'required'),
            array('role', 'range', array('range'=>array('2', '3'))),
            array('status', 'int', array('min'=>1, 'max'=>5)),
            array('status', 'required'),
            array('refer', 'string', array('min'=>2, 'max'=>5)),
            array('cat_id', 'int', array('min'=>2, 'max'=>4)),
            array('cat_id', 'range', array('range'=>array('2', '3'))),
            array('username', 'unique', array('table'=>'users', 'field'=>'username', 'ajax'=>array('cms/api/user/is-username-not-exist'))),
            array('datetime', 'datetime', array('int'=>true)),
        );
        
        if($this->input->post()){
            $valid = $this->form()->setData($this->input->post())
                ->setRules($rules)
                ->setFilters(array('datetime'=>'strtotime'))
                ->check();
//             $valid = $this->form()->setModel(UsersTable::model())
//                 ->setData($this->input->post())
//                 ->check(true);
            if($valid === true){
                pr($this->input->post());
            }else{
                //FlashService::set(pr($valid, true, true));
                dump($this->form()->getErrors());
            }
        }
        
        return $this->view->render();
    }
    
    public function tag(){
//         echo HtmlHelper::tag('a', array(
//             'href'=>'http://www.baidu.com',
//             'before'=>array(
//                 'tag'=>'em',
//                 'text'=>'*',
//                 'class'=>'fc-red',
//             ),
//             'append'=>'---',
//             'prepend'=>array(
//                 'tag'=>'time',
//                 'text'=>'2014-01-06',
//                 'after'=>array(
//                     'tag'=>'br',
//                 )
//             ),
//             'wrapper'=>array(
//                 'tag'=>'div',
//                 'wrapper'=>'div',
//                 'class'=>'inner-div',
//             )
//         ), array(
//             array(
//                 'tag'=>'span',
//                 'text'=>'链接',
//             ),
//             array(
//                 'tag'=>'span',
//                 'text'=>'链接2',
//             ),
//         ));
//         echo "\r\n\r\n\r\n";
        
        echo HtmlHelper::link('链接', array('cms/admin/user/index'), array(
            'prepend'=>'-->'
        ));
        echo "\r\n<br>\r\n<br>\r\n";
        echo HtmlHelper::tag('a', array(
            'href'=>'javascript',
            'prepend'=>'{prepend}',
            'before'=>'{before}',
        ), 'tag生成的链接');
        echo "\r\n<br>\r\n<br>\r\n";
        
        
        
        
        
        
        /**
         * 生成完整表单
         */
//         echo HtmlHelper::tag('form', array(
//             'method'=>'post',
//         ), array(
//             array(
//                 'tag'=>'fieldset',
//                 'class'=>'form-field',
//                 'text'=>array(
//                     array(
//                         'tag'=>'label',
//                         'class'=>'title',
//                         'text'=>'名称',
//                         'append'=>array(
//                             'tag'=>'em',
//                             'class'=>'fc-red',
//                             'text'=>'*',
//                         )
//                     ),
//                     array(
//                         'tag'=>'input',
//                         'type'=>'text',
//                         'name'=>'title',
//                         'class'=>'w300',
//                     ),
//                     array(
//                         'tag'=>'p',
//                         'text'=>'例如：百度',
//                         'class'=>'description',
//                     )
//                 )
//             ),
//             array(
//                 'tag'=>'fieldset',
//                 'class'=>'form-field',
//                 'text'=>array(
//                     array(
//                         'tag'=>'label',
//                         'class'=>'title',
//                         'text'=>'打开方式',
//                     ),
//                     array(
//                         'tag'=>'p',
//                         'text'=>array(
//                             'tag'=>'input',
//                             'type'=>'radio',
//                             'name'=>'target',
//                             'value'=>'_blank',
//                             'checked'=>'checked',
//                             'text'=>'_blank — 新窗口或新标签。',
//                             'wrapper'=>array(
//                                 'tag'=>'label',
//                             )
//                         ),
//                     ),
//                     array(
//                         'tag'=>'p',
//                         'text'=>array(
//                             'tag'=>'input',
//                             'type'=>'radio',
//                             'name'=>'target',
//                             'value'=>'_top',
//                             'text'=>'_top — 不包含框架的当前窗口或标签。',
//                             'wrapper'=>array(
//                                 'tag'=>'label',
//                             )
//                         ),
//                     ),
//                     array(
//                         'tag'=>'p',
//                         'text'=>array(
//                             'tag'=>'input',
//                             'type'=>'radio',
//                             'name'=>'target',
//                             'value'=>'_none',
//                             'text'=>'_none — 同一窗口或标签。',
//                             'wrapper'=>array(
//                                 'tag'=>'label',
//                             )
//                         ),
//                     ),
//                     array(
//                         'tag'=>'p',
//                         'text'=>'为您的链接选择目标框架。',
//                         'class'=>'description',
//                     )
//                 )
//             ),
//         ));
    }
    
    public function debug(){
        $this->layout_template = null;
        return $this->view->render();
    }
    
    public function in(){
        //$ids = array(10086,20000,130001,200133,349985,858372,1139822,2993814,3482713,3898234);
        $ids = array(/* 10086,20000,130001,200133,349985,858372,1139822,2993814,3482713,3898234, */
            30000,30001,30002,30003,30004,30005,30006,30007,30008,30009);
        $start = microtime(true);
        $posts = \cms\models\tables\PostsTableTable::model()->fetchAll('id IN ('.implode(',', $ids).')');
        //\fay\core\Db::getInstance()->fetchAll('SELECT id,title FROM posts_0 WHERE id IN ('.implode(',', $ids).')');
        $in_cost = microtime(true) - $start;
        echo 1000 * $in_cost, '<br>';
        unset($posts);
        
        $start = microtime(true);
        foreach($ids as $id){
            \cms\models\tables\PostsTableTable::model()->find($id);
            //\fay\core\Db::getInstance()->fetchRow('SELECT id,title FROM posts_0 WHERE id = '.$id);
        }
        $simple_cost = microtime(true) - $start;
        echo 1000 * $simple_cost, '<br>';
        echo '相差：', 1000 * ($simple_cost - $in_cost), 'ms';
    }
    
    public function cache(){
        //Memcache
        echo '设置缓存a，永不过期';
        dump(\F::cache()->set('a', 'b', 100, 'memcache'));
        echo '读取缓存a';
        dump(\F::cache()->get('a', 'memcache'));
        
        echo '设置缓存c，过期时间3秒';
        dump(\F::cache()->set('c', 'b', 3, 'memcache'));
        echo '获取缓存c';
        dump(\F::cache()->get('c', 'memcache'));
        
        echo '批量设置缓存d, f';
        dump(\F::cache()->mset(array(
            'd'=>'e',
            'f'=>'g',
        ), 0, 'memcache'));
        echo '批量获取缓存d, f';
        dump(\F::cache()->mget(array('d', 'f'), 'memcache'));
        echo '删除缓存c';
        dump(\F::cache()->delete('c', 'memcache'));
        echo '删除缓存f';
        dump(\F::cache()->delete('f', 'memcache'));
        echo '批量获取缓存a, c, d, f, g';
        dump(\F::cache()->mget(array('a', 'c', 'd', 'f', 'g'), 'memcache'));
        
//         echo '清空缓存';
//         dump(\F::cache()->flush(null, 'memcache'));
//         echo '批量获取缓存a, c, d, f, g';
//         dump(\F::cache()->mget(array('a', 'c', 'd', 'f', 'g'), 'memcache'));
    }
    
    /**
     * 随即更新多条数据
     */
    public function update(){
        $rand = array();
        for($i = 0; $i < 1000; $i++){
            $rand[] = mt_rand(1, 600000);
        }
        
        $start_time = microtime(true);
        foreach($rand as $r){
            PostsTable::model()->update(array(
                'update_time'=>time(),
            ), $r);
        }
        
        dump($rand);
        echo microtime(true) - $start_time;
        //dump(PostsTable::model()->db->getSqlLogs());
    }
    
    /**
     * 日志测试
     */
    public function log(){
        //这行不注释则是语法错误
        //$this->a();
        //throw new ErrorException('这是一个自定义的错误异常');
        //throw new HttpException('这是一个404异常');
        //throw new HttpException('这是一个500异常', 500);
        \F::logger()->log('haha', Logger::LEVEL_ERROR);
        \F::logger()->log('hehe', Logger::LEVEL_INFO);
        \F::logger()->flush();
    }
    
    /**
     * 批量执行SQL测试
     */
    public function db(){
        $db = Db::getInstance();
        $sql = '-- 页面
INSERT INTO `faycms_pages` (title, alias) VALUES (\'关于我们\', \'about\');

--　基础分类
INSERT INTO `faycms_categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES (\'1000\', \'师资力量\', \'teacher\', \'1\', \'1\', \'1\');
INSERT INTO `faycms_categories` (`id`, `title`, `alias`, `parent`, `is_nav`, `is_system`) VALUES (\'1001\', \'学生作品\', \'works\', \'1\', \'1\', \'1\');';
        
//         $sql = ';UPDATE faycms_categories SET alias = \'about\' WHERE id = 1';
        $db->exec($sql, true);
        
        dump($db->getSqlLogs());
    }
    
    public function postCats(){
        set_time_limit(0);
        $p = 0;
        $cats = array();
        for($c = 10000; $c <= 10100; $c++){
            $cats[] = $c;
        }
        
        for($i = 0; $i < 10000; $i++){
            $data = array();
            for($j = 0; $j < 1000; $j++){
                $p++;
                $rand_keys = array_rand($cats, mt_rand(2, 5));
                foreach($rand_keys as $k){
                    $data[] = array(
                        'post_id'=>$p,
                        'cat_id'=>$cats[$k],
                    );
                }
            }
            //dump($data);die;
            \cms\models\tables\PostsCategoriesTableTable::model()->bulkInsert($data);
        }
    }
    
    public function diff(){
        $oldHtml = '<div class="left">
<div class="article article_16" id="artibody">
<p>　　中国经济网北京4月11日综合报道 据闪电新闻客户端消息，4月11日，山东省第十二届人民代表大会常务委员会第28次会议在济南举行。会议经过表决，决定接受姜异康同志因工作变动辞去山东省人大常委会主任职务的请求。</p>

<p>　　会议表决通过了《山东省人民代表大会常务委员会关于召开山东省第十二届人民代表大会第七次会议的决定》，决定省十二届人大七次会议于4月下旬在济南召开。</p>

<p>　　<strong>姜异康简历</strong>&nbsp;</p>

<div class="img_wrapper"><img alt="123" src="http://71.fayfox.com/uploads/blog/2017/04/RcvDA.jpg" /></div>

<p align="justify">　　姜异康，男，汉族，1953年1月生，山东招远人，中南工业大学行政管理专业毕业，大学文化，工学硕士，1970年12月加入中国共产党，1969年12月参加工作。</p>

<p align="justify">　　1969年12月入伍，战士、副班长。1974年2月起先后在山东电影机械厂、济南市委宣传部、研究室、办公室工作，历任市委办公室科长、副县级秘书。</p>

<p align="justify">　　1985年12月任中央办公厅秘书局文电处、收发处干部、副处长；</p>

<p align="justify">　　1988年4月任中央办公厅秘书局副局长；</p>


<p align="justify">　　1990年10月任中央直属机关事务管理局常务副局长；</p>

<p align="justify">　　1993年5月任中央直属机关事务管理局局长；</p>

<p align="justify">　　1995年7月任中央办公厅副主任兼中央直属机关事务管理局局长；</p>

<p align="justify">　　1997年4月任中央办公厅副主任兼中央直属机关事务管理局局长，中央精神文明建设指导委员会委员、办公室副主任；</p>

<p align="justify">　　2000年10月任中央办公厅副主任，中央精神文明建设指导委员会委员、办公室副主任；</p>

<p align="justify">　　2002年10月任中共重庆市委副书记，中央精神文明建设指导委员会委员、办公室副主任；</p>

<p align="justify">　　2002年12月任中共重庆市委副书记；</p>

<p align="justify">　　2006年6月任国家行政学院党委书记、副院长；</p>

<p align="justify">　　2008年3月-2009.02任中共山东省委书记，省委党校校长；（2008年6月，国务院免去姜异康的国家行政学院副院长职务。）</p>

<p align="justify">　　2009.02，山东省委书记（至2017.03）、省人大常委会主任（至2017.04）、省委党校校长。</p>

<p align="justify">　　2010年7月，在29日下午召开的中国共产党山东省军区七届一次全会上，姜异康当选为省军区党委第一书记。</p>

<p align="justify">　　中共十六大、十七大、十八大代表；中共十六届中央候补委员、十七届、十八届中央委员；第十届全国人大代表（重庆）、第十一届全国人大代表（山东）；第九届全国政协委员。</p>

<p class="article-editor">责任编辑：张迪</p>
</div>

<div id="navStopHere" style="position:relative;">&nbsp;</div>

<div class="article-info clearfix">
<div class="article-keywords" data-sudaclick="art_keywords"><span>文章关键词：</span> <a href="http://tags.news.sina.com.cn/姜异康" target="_blank">姜异康</a> <a href="http://tags.news.sina.com.cn/人大常委会主任" target="_blank">人大常委会主任</a> <a href="http://tags.news.sina.com.cn/人事" target="_blank">人事</a></div>
</div>
</div>
';
        $newHtml = '<div class="left">
<div class="article article_16" id="artibody">
<p>　　中国经济网北京4月11日综合报道 据闪电新闻客户端消息，4月11日，山东省第十二届人民代表大会常务委员会第28次会议在济南举行。会议经过表决，决定接受姜异康同志因工作变动辞去山东省人大常委会主任职务的请求。</p>

<p>　　会议表决通过了《山东省人民代表大会常务委员会关于召开山东省第十二届人民代表大会第七次会议的决定》，决定省十二届人大七次会议于4月下旬在济南召开。</p>

<p>　　<strong>姜异康简历</strong>&nbsp;</p>

<div class="img_wrapper"><img src="http://71.fayfox.com/uploads/blog/2017/04/RcvDA.jpg" alt="123" /></div>

<p align="justify">　　姜异康，男，汉族，1953年1月生，山东招远人，中南工业大学行政管理专业毕业，大学文化，工学硕士，1970年12月加入中国共产党，1969年12月参加工作。</p>

<p align="justify">　　1969年12月入伍，战士、副班长。1974年2月起先后在山东电影机械厂、济南市委宣传部、研究室、办公室工作，历任市委办公室科长、副县级秘书。</p>

<p align="justify">　　1985年12月任中央办公厅秘书局文电处、收发处干部、副处长；</p>

<p align="justify">　　1988年4月任中央办公厅秘书局副局长；</p>

<p align="justify">　　1990年10月任中央直属机关事务管理局常务副局长；</p>

<p align="justify">　　1993年5月任中央直属机关事务管理局局长；</p>

<p align="justify">　　1995年7月任中央办公厅副主任兼中央直属机关事务管理局局长；</p>

<p align="justify">　　1997年4月任中央办公厅副主任兼中央直属机关事务管理局局长，中央精神文明建设指导委员会委员、办公室副主任；</p>

<p align="justify">　　2000年10月任中央办公厅副主任，中央精神文明建设指导委员会委员、办公室副主任；</p>

<p align="justify">　　2002年10月任中共重庆市委副书记，中央精神文明建设指导委员会委员、办公室副主任；</p>

<p align="justify">　　2002年12月任中共重庆市委副书记；</p>

<p align="justify">　　2006年6月任国家行政学院党委书记、副院长；</p>

<p align="justify">　　2008年3月-2009.02任中共山东省委书记，省委党校校长；（2008年6月，国务院免去姜异康的国家行政学院副院长职务。）</p>

<p align="justify">　　2009.02，山东省委书记（至2017.03）、省人大常委会主任（至2017.04）、省委党校校长。</p>

<p align="justify">　　2010年7月，在29日下午召开的中国共产党山东省军区七届一次全会上，姜异康当选为省军区党委第一书记。</p>

<p align="justify">　　中共十六大、十七大、十八大代表；中共十六届中央候补委员、十七届、十八届中央委员；第十届全国人大代表（重庆）、第十一届全国人大代表（山东）；第九届全国政协委员。</p>

<p class="article-editor">责任编辑：张迪</p>
</div>

<div id="navStopHere" style="position:relative;">&nbsp;</div>

<div class="article-info clearfix">
<div class="article-keywords" data-sudaclick="art_keywords"><span>文章关键词：</span> <a href="http://tags.news.sina.com.cn/姜异康" target="_blank">姜异康</a> <a href="http://tags.news.sina.com.cn/人大常委会主任" target="_blank">人大常委会主任</a> <a href="http://tags.news.sina.com.cn/人事" target="_blank">人事</a></div>
</div>
</div>
';
        $htmlDiff = new \Caxy\HtmlDiff\HtmlDiff($oldHtml, $newHtml);
        $content = $htmlDiff->build();
        echo $content;
    }
    
    public function img(){
        $image = new ImageTextService();
        $image->loadFromSize(300, 500, true)
            //->fill()
            ->write(
                '我爱北京天安门天安门前国旗升伟大领袖毛主席带领我们向前进',
                15,
                '#FF0000',
                BASEPATH . 'assets/fonts/msyh.ttf',
                array('center', 'top'),
                array('top'=>100),
                1.3,
                0,
                150
            )
            ->merge(10371, '0, 0, 100, 0', array('center', 'bottom'), 100)
            ->output('image/png');
        
        
        
//        $image = new ImageService(10371);
//        $image
//            //->resize(100, 200)
//            //->flipHorizontal()
//            //->rotate(30)
//            //->crop(100, 100, 200, 1500)
//            //->cutHorizontal(2000)
//            //->cutVertical(200)
//            //->addBorder(array('r'=>100, 'g'=>100, 'b'=>100), 10)
//            //->fillByImage(10371)
//            //->scalesc(2)
//            //->circle(200)
//            ->merge(10371, '0, 0, 0, 0', array('center', 'center'), 60)
//            ->output('image/png')
//        ;
    }
    
    public function imgText(){
        $image = new ImageTextService(10000);
        $image
            //->resize(50, 40)
            ->write(
                //'我爱北京天安门，天安门前国旗升，伟大领袖毛主席，带领我们向前进',
                '我爱北京天安门',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>255, 'g'=>0, 'b'=>0),
                array('center', 'top'),
                array('top'=>1)
            )
            ->write(
                '天安门前国旗升',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>255, 'g'=>0, 'b'=>0),
                array('left', 'center'),
                array(),
                1.3,
                0,
                2
            )
            ->write(
                '啊',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>255, 'g'=>0, 'b'=>0),
                array('left', 'center'),
                array('left'=>10),
                1.3,
                0,
                2
            )
            ->write(
                '啊啊',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>255, 'g'=>0, 'b'=>0),
                array('left', 'center'),
                array('left'=>30),
                1.3,
                0,
                2
            )
            ->write(
                '啊啊啊',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>255, 'g'=>0, 'b'=>0),
                array('left', 'center'),
                array('left'=>50),
                1.3,
                0,
                2
            )
            ->write(
                '啊啊啊啊',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>255, 'g'=>0, 'b'=>0),
                array('left', 'center'),
                array('left'=>70),
                1.3,
                0,
                2
            )
            ->write(
                '啊啊啊啊啊',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>255, 'g'=>0, 'b'=>0),
                array('left', 'center'),
                array('left'=>90),
                1.3,
                0,
                2
            )
            ->write(
                '伟大领袖毛主席',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>255, 'g'=>0, 'b'=>0),
                array('right', 'center'),
                array(),
                1.3,
                0,
                2
            )
            ->write(
                '带领我们向前进',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>255, 'g'=>0, 'b'=>0),
                array('center', 'bottom')
            )
            
            ->write(
                //'我爱北京天安门，天安门前国旗升，伟大领袖毛主席，带领我们向前进',
                '我爱北京天安门',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>0, 'g'=>255, 'b'=>0),
                array('center', 'top'),
                array(),
                1.3,
                0,
                65
            )
            ->write(
                '天安门前国旗升',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>0, 'g'=>255, 'b'=>0),
                array('left', 'center'),
                array(),
                1.3,
                0,
                65
            )
            ->write(
                '伟大领袖毛主席',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>0, 'g'=>255, 'b'=>0),
                array('right', 'center'),
                array(),
                1.3,
                0,
                65
            )
            ->write(
                '带领我们向前进',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>0, 'g'=>255, 'b'=>0),
                array('center', 'bottom'),
                array(),
                1.3,
                0,
                65
            )
            
            ->write(
                '爱我中华',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>0, 'g'=>0, 'b'=>0),
                array('center', 'center'),
                array(),
                1.3,
                0,
                40
            )

            ->write(
            //'我爱北京天安门，天安门前国旗升，伟大领袖毛主席，带领我们向前进',
                '我爱北京天安门',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>0, 'g'=>0, 'b'=>255),
                array('left', 'top'),
                array(),
                1.3,
                0,
                65
            )
            ->write(
                '天安门前国旗升',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>0, 'g'=>0, 'b'=>255),
                array('right', 'top'),
                array(),
                1.3,
                0,
                65
            )
            ->write(
                '伟大领袖毛主席',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>0, 'g'=>0, 'b'=>255),
                array('right', 'bottom'),
                array(),
                1.3,
                0,
                65
            )
            ->write(
                '带领我们向前进',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>0, 'g'=>0, 'b'=>255),
                array('left', 'bottom'),
                array(),
                1.3,
                0,
                65
            )

            ->write(
            //'我爱北京天安门，天安门前国旗升，伟大领袖毛主席，带领我们向前进',
                '我爱北京天安门',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>100, 'g'=>100, 'b'=>100),
                array('left', 'top'),
                array(
                    'top'=>50,
                    'right'=>100,
                    'bottom'=>50,
                    'left'=>100
                )
            )
            ->write(
                '天安门前国旗升',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>100, 'g'=>100, 'b'=>100),
                array('right', 'top'),
                array(
                    'top'=>50,
                    'right'=>100,
                    'bottom'=>50,
                    'left'=>100
                )
            )
            ->write(
                '伟大领袖毛主席',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>100, 'g'=>100, 'b'=>100),
                array('right', 'bottom'),
                array(
                    'top'=>50,
                    'right'=>100,
                    'bottom'=>50,
                    'left'=>100
                )
            )
            ->write(
                '带领我们向前进',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>100, 'g'=>100, 'b'=>100),
                array('left', 'bottom'),
                array(
                    'top'=>50,
                    'right'=>100,
                    'bottom'=>50,
                    'left'=>100
                )
            )

            ->write(
            //'我爱北京天安门，天安门前国旗升，伟大领袖毛主席，带领我们向前进',
                '我爱北京天安门',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>200, 'g'=>200, 'b'=>200),
                array('left', 'top'),
                array(
                    'top'=>110,
                    'right'=>130,
                    'bottom'=>110,
                    'left'=>130
                ),
                1,
                2,
                65
            )
            ->write(
                '天安门前国旗升',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>200, 'g'=>200, 'b'=>200),
                array('right', 'top'),
                array(
                    'top'=>110,
                    'right'=>130,
                    'bottom'=>110,
                    'left'=>130
                ),
                1,
                2,
                65
            )
            ->write(
                '伟大领袖毛主席',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>200, 'g'=>200, 'b'=>200),
                array('right', 'bottom'),
                array(
                    'top'=>110,
                    'right'=>130,
                    'bottom'=>110,
                    'left'=>130
                ),
                1,
                2,
                65
            )
            ->write(
                '带领我们向前进',
                BASEPATH . 'assets/fonts/msyh.ttf',
                15,
                array('r'=>200, 'g'=>200, 'b'=>200),
                array('left', 'bottom'),
                array(
                    'top'=>110,
                    'right'=>130,
                    'bottom'=>110,
                    'left'=>130
                ),
                1,
                2,
                65
            )
            ->output()
        ;
    }
    
    public function logger(){
        \F::logger('first')->info('这是info
        第二行', ['a'=>'b']);
    }
}