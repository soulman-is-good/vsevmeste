<?php
/**
 * Description of Uploads
 * @property string $file_id primary key
 * @property integer $message_id message id
 * @property integer $created_at unix timestamp 
 * @author Soul_man
 */
class Project_Uploads extends X3_Module_Table {

    public $encoding = 'UTF-8';

    public $tableName = 'project_uploads';

    public $_fields = array(
      'id'=>array('integer[10]','unsigned','primary','auto_increment'),
      'title'=>array('string[64]','default'=>'NULL'),
      'video'=>array('string[1024]','default'=>'NULL'),
      'file_id'=>array('string[128]','default'=>'NULL'),
      'project_id'=>array('integer[10]','unsigned','index','ref'=>array('Project','id','default'=>'title')),
      'created_at'=>array('integer[10]','unsigned','default'=>'0')
    );
    public function fieldNames() {
        return array(
            'created_at' => 'Дата',
            'title' => 'Название',
            'video' => 'Видео',
            'file_id' => 'Изображение',
            'project_id' => 'Проект',
        );
    }
    public static function newInstance($class = __CLASS__) {
        return parent::newInstance($class);
    }

    public static function getInstance($class = __CLASS__) {
        return parent::getInstance($class);
    }
    
}

?>
