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

    //pay methods
    const PAY_METHOD_QIWI = 0;
    const PAY_METHOD_EPAY = 1;
    
    //status codes
    const STATUS_UNAPPOVED = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_WAIT = 2;
    const STATUS_ERROR = 5;
    
    public $encoding = 'UTF-8';

    public $tableName = 'project_invest';

    public $_fields = array(
      'id'=>array('integer[10]','unsigned','primary','auto_increment'),
      'user_id'=>array('integer[10]','unsigned','index','ref'=>array('User','id','default'=>'name')),
      'project_id'=>array('integer[10]','unsigned','index','ref'=>array('Project','id','default'=>'title')),
      'interest_id'=>array('integer[10]','unsigned','default'=>'NULL','index','ref'=>array('Project_Interest','id','default'=>'title')),
      'amount'=>array('integer[10]'),
      'address'=>array('content','default'=>'NULL'),
      'pay_method'=>array('integer[1]','default'=>'NULL'),
      'pay_data'=>array('content','default'=>'NULL'),
      'status'=>array('integer[1]','default'=>'0'),
      'created_at'=>array('datetime')
    );
    public function fieldNames() {
        return array(
            'project_id' => 'Проект',
            'user_id' => 'Пользователь',
            'interest_id' => 'Интерес',
            'amount' => 'Сумма вложения',
            'pay_method' => 'Способ оплаты (0-qiwi,1-kkb)',
            'pay_data' => 'Текст',
            'address' => 'Адрес',
            'status' => 'Статус(0 - создан, 1-успех, 2-в ожидании, 5-ошибка)',
            'created_at' => 'Дата публикации',
        );
    }
    
    public function moduleTitle() {
        return 'Вложения';
    }
    
    public function getDefaultScope() {
        $q = array(
            '@order'=>'created_at DESC'
        );
        if(isset($_GET['filter'])){
            parse_str(base64_decode($_GET['filter']),$data);
            $q['@condition'] = $data;
        }
        return $q;
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
