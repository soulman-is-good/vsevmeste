<?php
/**
 * Description of Interest
 * @property integer $id primary key
 * @property integer $project_id Project reference
 * @property integer $user_id Interest limit
 * @property integer $content Interest limit
 * @property integer $created_at unix timestamp 
 * @author Soul_man
 */
class Project_Partner extends X3_Module_Table {

    public $encoding = 'UTF-8';

    public $tableName = 'project_partner';

    public $_fields = array(
      'id'=>array('integer[10]','unsigned','primary','auto_increment'),
      'project_id'=>array('integer[10]','unsigned','index','ref'=>array('Project','id','default'=>'title')),
      'user_id'=>array('integer[10]','unsigned','index','ref'=>array('User','id','default'=>'name')),
      'confirmation'=>array('string[36]','default'=>'NULL'),
      'status'=>array('boolean','default'=>'0'),
      'created_at'=>array('datetime')
    );
    public function fieldNames() {
        return array(
            'project_id' => 'Проект',
            'user_id' => 'Пользователь',
            'confirmation' => 'Код подтверждения',
            'status' => 'Подтвержден',
            'created_at' => 'Дата публикации',
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
        return parent::beforeValidate();
    }
}

?>
