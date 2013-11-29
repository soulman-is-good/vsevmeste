<?php
/**
 * City
 *
 * @author Soul_man
 * 
 * @property string $id primary
 * @property string $hash index
 * @property string $user_id default NULL
 * @property string $project_id default NULL
 * @property string $title
 * @property string $comment default NULL
 * @property string $sum
 * @property string $created_at
 * @property string $status default=1
 */
class Transaction extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'transaction';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'hash'=>array('string[64]','index'),
        'user_id' => array('integer[10]', 'unsigned', 'default'=>'NULL', 'index', 'ref' => array('User', 'id', 'default' => "name")),
        'project_id' => array('integer[10]', 'unsigned', 'default'=>'NULL', 'index', 'ref' => array('Project', 'id', 'default' => "title")),
        'title'=>array('string[8]'),
        'comment'=>array('string[32]', 'default'=>'NULL'),
        'sum'=>array('decimal[13,3]'),
        'created_at'=>array('datetime'),
        'status'=>array('boolean','default'=>'1'),
    );

    public static function newInstance($class=__CLASS__) {
        return parent::newInstance($class);
    }
    public static function getInstance($class=__CLASS__) {
        return parent::getInstance($class);
    }
    public static function get($arr,$single=false,$class=__CLASS__,$asArray=false) {
        return parent::get($arr,$single,$class,$asArray);
    }
    public static function getByPk($pk,$class=__CLASS__) {
        return parent::getByPk($pk,$class);
    }
    public function fieldNames() {
        return array(
            //'parent_id'=>'Находится в:',
            'title'=>'Тип проводки',
            'sum'=>'Сумма',
            'user_id'=>'Инициатор',
            'project_id'=>'Проект',
            'comment'=>'Комментарий',
            'created_at'=>'Дата проводки',
        );
    }
    
    public function moduleTitle() {
        return 'Транзакции';
    }

    public function cache() {
        return array(
            //'cache'=>array('actions'=>'show','role'=>'*','expire'=>'+1 day'),
            'nocache'=>array('actions'=>'*','role'=>'admin')
        );
    }
    
    public function beforeValidate() {
        if($this->getTable()->getIsNewRecord()) {
            $this->created_at = time();
        }
    }

}
?>
