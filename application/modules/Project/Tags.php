<?php
/**
 * Project tags
 * @property integer $id primary key
 * @property integer $project_id Project reference
 * @property integer $user_id user - tag creator
 * @property integer $name tag url name
 * @property integer $tag tag title
 * @property integer $created_at unix timestamp 
 * @author Soul_man
 */
class Project_Tags extends X3_Module_Table {

    public $encoding = 'UTF-8';

    public $tableName = 'project_tags';

    public $_fields = array(
      'id'=>array('integer[10]','unsigned','primary','auto_increment'),
      'project_id'=>array('integer[10]','unsigned','index','ref'=>array('Project','id','default'=>'title')),
      'user_id'=>array('integer[10]','unsigned','index','ref'=>array('User','id','default'=>'name')),
      'name'=>array('string[255]','index'),
      'tag'=>array('string[255]'),
      'status'=>array('boolean','default'=>'0'),
      'created_at'=>array('datetime')
    );
    public function fieldNames() {
        return array(
            'project_id' => 'Проект',
            'user_id' => 'Создатель',
            'tag' => 'Тег',
            'status' => 'Включен',
            'created_at' => 'Дата создания',
        );
    }
    
    public function actionDelete(){
        if(IS_AJAX && FALSE !== ($id = X3::request()->getRequest('id'))){
            $model = self::get(array('id'=>$id),1);
            if($model!== NULL){
                self::deleteByPk($id);
            }
        }
        throw new X3_404;
    }
    
    public static function newInstance($class = __CLASS__) {
        return parent::newInstance($class);
    }

    public static function getInstance($class = __CLASS__) {
        return parent::getInstance($class);
    }
    public function beforeValidate() {
        if($this->getTable()->getIsNewRecord()) {
            $this->created_at = time();
        }
        $name = new X3_String($this->tag);
        $name = preg_replace("/[\s]+/", '-', $name->translit());
        $name = preg_replace("/[^a-zа-яЁ_\-]/i", '', $name);
        $this->name = strtolower($name);
        return parent::beforeValidate();
    }
}

?>
