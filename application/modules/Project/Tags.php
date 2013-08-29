<?php
/**
 * Project - Tags many to many
 * @property integer $id primary key
 * @property integer $project_id project id
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
      'tag_id'=>array('integer[10]','unsigned','index','ref'=>array('Tags','id','default'=>'tag')),
    );
    public function fieldNames() {
        return array(
            'project_id' => 'Проект',
            'tag_id' => 'Тег',
        );
    }
    
    public static function assign($project_id, $tag_id) {
        if(NULL === ($model = self::get(array('tag_id'=>$tag_id,'project_id'=>$project_id)))) {
            $model = new self;
            $model->project_id = $project_id;
            $model->tag_id = $tag_id;
            $model->save();
        }
        return $model;
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
        $this->tag = mb_strtolower($this->tag,X3::app()->encoding);
        $name = new X3_String($this->tag);
        $name = preg_replace("/[\s]+/", '-', $name->translit());
        $name = preg_replace("/[^a-zа-яЁ_\-]/i", '', $name);
        $this->name = mb_strtolower($name,X3::app()->encoding);
        return parent::beforeValidate();
    }
}

?>
