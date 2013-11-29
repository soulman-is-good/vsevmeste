<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of News
 *
 * @author Soul_man
 */
class Page extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'data_page';
    public static $parent = array();
    
    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
//        'parent_id'=>array('integer[10]','unsigned','index','default'=>'NULL','ref'=>array('Page','id','default'=>'short_title','query'=>array('@condition'=>array('parent_id'=>'NULL')))),
        'title'=>array('string[255]','language'),
        'name'=>array('string[255]','unique'),
        'text'=>array('text','language'),
        'status'=>array('boolean','default'=>'1'),
        //'onmain'=>array('boolean','default'=>'0'),
        //'weight'=>array('integer[10]','default'=>'0'),
        'created_at'=>array('integer[10]','unsigned','default'=>'0'),
        //'metatitle'=>array('string','default'=>'','language'),
        //'metakeywords'=>array('string','default'=>'','language'),
        //'metadescription'=>array('string', 'default'=>'','language'),
    );

    public static function newInstance($class=__CLASS__) {
        return parent::newInstance($class);
    }
    public static function getInstance($class=__CLASS__) {
        return parent::getInstance($class);
    }
    public static function get($arr,$single=false,$class=__CLASS__) {
        return parent::get($arr,$single,$class);
    }
    public static function getByPk($pk,$class=__CLASS__) {
        return parent::getByPk($pk,$class);
    }
    public function fieldNames() {
        return array(
//            'parent_id'=>'Верхний уровень',
            'title'=>'Заголовок',
            'name'=>'Имя в URL',
            'text'=>'Содержание',
            //'weight'=>'Порядок',
            'status'=>'Видимость',
            //'onmain'=>'На главную',
            //'metatitle'=>'Metatitle',
            //'metakeywords'=>'Metakeywords',
            //'metadescription'=>'Metadescription',
        );
    }
    
    public function moduleTitle() {
        return 'Текстовые страницы';
    }

    public function cache() {
        return array(
            //'cache'=>array('actions'=>'show','role'=>'*','expire'=>'+1 day'),
            'nocache'=>array('actions'=>'*','role'=>'admin')
        );
    }
    
    public function getLink() {
        return "/page/$this->name.html";
    }

    public function actionShow(){
        $name = mysql_real_escape_string(urldecode($_GET['name']));
        $model = $this->table->select('*')->where("`name`='$name'")->asObject(true);
        if($model==null) throw new X3_404();
        $this->template->render('show',array('model'=>$model));
    }
    
    public function parentM() {
        if(!isset(self::$parent[$this->parent_id]))
            self::$parent[$this->parent_id] = Page::getByPk($this->parent_id);
        return self::$parent[$this->parent_id];
    }
    
    public function menu() {
        /*if(isset($_GET['parent_id'])){
            $current = $_GET['parent_id'];
            $html = array('<a href="/admin/page/"><b>Все</b></a>');
        }else{
            $current = -1;
            $html = array('<span><b>Все</b></span>');
        }
        if($current == 0)
            $html []= '<span>Верхний уровень</a>';
        else
            $html []= '<a href="/admin/page/parent_id/0">Верхний уровень</a>';
        if($current>0){
            $html []= "<span>$this->short_title</span>";
        }
        return implode(' &gt; ', $html);*/
    }
    
    public function getDefaultScope() {
        $query = array('@order'=>'title, created_at DESC');
        return $query;
    }
    
    public function onDelete($tables, $condition) {
        if (strpos($tables, $this->tableName) !== false) {
            $model = $this->table->select('*')->where($condition)->asObject(true);
//            X3::db()->query("UPDATE data_page SET parent_id=NULL WHERE parent_id=$model->id");
        }
        parent::onDelete($tables, $condition);
    }    

    public function beforeValidate() {
        if($this->created_at == 0)
            $this->created_at = time();
        if($this->name == ''){
            $name = new X3_String($this->title);
            $name = preg_replace("/[\s]+/", '-', $name->translit());
            $name = preg_replace("/[^a-zа-яЁ_\-]/i", '', $name);
            $this->name = strtolower($name);
        } elseif(FALSE !== ($j = strpos($this->name, 'http'))) {
            $name = explode('/', $this->name);
            $name = str_replace(".phtml","",array_pop($name));
            $this->name = str_replace(".html","",$name);
        }
//        if($this->parent_id=="" || $this->parent_id=="0") $this->parent_id = NULL;
    }
    
    public function modText() {
        $t = $this->text;
        if(X3::user()->isGuest()) {
            $uemail = $uid = '<a href="/enter.html">'.X3::translate("войти").'</a>';
        } else {
            $uid = X3::user()->id;
            $uemail = X3::user()->email;
        }
        $t = str_replace("{{USER_ID}}",$uid,$t);
        $t = str_replace("{{USER_ACCOUNT}}",$uemail,$t);
        $t = str_replace("{{PROJECT_ACCOUNT}}","".X3::user()->project_id,$t);
        $t = str_replace("{{DATETIME}}",date("d.m.Y H:i:s"),$t);
        $t = str_replace("{{DATE}}",date("d.m.Y"),$t);
        $t = str_replace("{{IP}}",$_SERVER['REMOTE_ADDR'],$t);
        $t = str_replace("{{USER_AGENT}}",$_SERVER['HTTP_USER_AGENT'],$t);
        $t = str_replace("{{HOST}}",$_SERVER['HTTP_HOST'],$t);
        return $t;
    }

}
?>
