<?php
/**
 * Description of Interest
 * @property integer $id primary key
 * @property integer $project_id Project reference
 * @property integer $limit Interest limit
 * @property integer $limit Interest limit
 * @property integer $deliver_at unix timestamp 
 * @property integer $created_at unix timestamp 
 * @author Soul_man
 */
class Project_Interest extends X3_Module_Table {

    public $encoding = 'UTF-8';

    public $tableName = 'project_interest';

    public $_fields = array(
      'id'=>array('integer[10]','unsigned','primary','auto_increment'),
      'project_id'=>array('integer[10]','unsigned','index','ref'=>array('Project','id','default'=>'title')),
      'title'=>array('string[128]'),
      'notes'=>array('string[255]','default'=>'NULL'),
      'sum'=>array('integer[10]'),
      'bought'=>array('integer[10]','default'=>'0'),
      'limit'=>array('integer[10]'),
      'deliver_at'=>array('datetime'),
      'created_at'=>array('datetime')
    );
    public function fieldNames() {
        return array(
            'project_id' => 'Проект',
            'title' => 'Описание интереса',
            'notes' => 'Заметки, способ доставки',
            'sum' => 'Цена',
            'limit' => 'Ограничение на количество',
            'bought' => 'Куплено',
            'deliver_at' => 'Доставка до',
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
            $this->left = $this->limit;
        }
        if(!is_numeric($this->deliver_at))
            $this->deliver_at = (int)strtotime($this->deliver_at);
        return parent::beforeValidate();
    }
}

?>
