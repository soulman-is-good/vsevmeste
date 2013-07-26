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
class Project_Invest extends X3_Module_Table {

    public $encoding = 'UTF-8';

    public $tableName = 'project_invest';

    public $_fields = array(
      'id'=>array('integer[10]','unsigned','primary','auto_increment'),
      'user_id'=>array('integer[10]','unsigned','index','ref'=>array('User','id','default'=>'name')),
      'project_id'=>array('integer[10]','unsigned','index','ref'=>array('Project','id','default'=>'title')),
      'interest_id'=>array('integer[10]','unsigned','default'=>'NULL','index','ref'=>array('Project_Interest','id','default'=>'title')),
      'amount'=>array('integer[10]'),
      'created_at'=>array('datetime')
    );
    public function fieldNames() {
        return array(
            'project_id' => 'Проект',
            'user_id' => 'Пользователь',
            'interest_id' => 'Интерес',
            'amount' => 'Сумма вложения',
            'content' => 'Текст',
            'created_at' => 'Дата публикации',
        );
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
