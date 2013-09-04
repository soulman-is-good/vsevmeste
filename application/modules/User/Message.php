<?php
/**
 * Forum
 *
 * @author Soul_man
 */
class User_Message extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'user_message';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'from_user_id'=>array('integer[10]','unsigned','index','ref'=>array('User','id','default'=>"name")),
        'to_user_id'=>array('integer[10]','unsigned','index','ref'=>array('User','id','default'=>"name")),
        'title'=>array('string[255]'),
        'text'=>array('content'),
        'status'=>array('boolean','default'=>'0'),
        'created_at'=>array('datetime'),
        'updated_at'=>array('datetime','default'=>'NULL'),
    );
    
    public function __construct($action = null) {
        parent::__construct($action);
    }

    public function fieldNames() {
        return array(
            'from_user_id'=>'Автор',
            'to_user_id'=>'Получатель',
            'title'=>X3::translate('Тема'),
            'text'=>X3::translate('Текст'),
            'created_at'=>X3::translate('Дата отправки'),
            'updated_at'=>X3::translate('Дата прочтения'),
            'status'=>'Прочитано',
        );
    }
    
    public function moduleTitle() {
        return 'Сообщения';
    }
    
    public function actionIndex() {
        $id = X3::user()->id;
        $this->template->render('@views:user:report.php', array('models' => $models, 'count' => $count, 'paginator' => $paginator,'from'=>$from));
    }

    public function actionDelete(){
        if(!X3::user()->isAdmin()){
            $id = X3::user()->id;
            if(isset($_GET['id']) && (int)$_GET['id']>0){
                self::delete(array('user_id'=>$id,'id'=>(int)$_GET['id']));
            }
        }else {
            if(isset($_GET['id']) && (int)$_GET['id']>0)
                self::deleteByPk((int)$_GET['id']);
        }
        $this->redirect('/reports/');
    }

    public function actionRead() {
        $id = (int)$_GET['id'];
        $uid = X3::user()->id;
        $vote = self::get(array('id'=>$id,'to_user_id'=>$uid));
        if($vote == NULL){
            if(IS_AJAX)
                exit;
            $this->redirect($_SERVER['HTTP_REFERER']);
        }
        self::update(array('status'=>'1'),array('id'=>$id,'to_user_id'=>$uid));
        if(IS_AJAX)
            exit;
        $this->redirect($_SERVER['HTTP_REFERER']);
    }
    public function getDefaultScope() {
        return array(
            '@order'=>'updated_at DESC, created_at DESC, status'
        );
    }

    public function beforeValidate() {
        if($this->getTable()->getIsNewRecord()){
            $this->created_at = time();
            $this->status = 0;
        }
    }
    public function afterSave($bNew = false) {
        if(X3::app()->module->controller->id == 'admin' && !$this->hasErrors()) {
            X3::app()->module->redirect('/admin/list/module/User_Message/toid/'.$this->to_user_id.'.html');
        }
    }
    
    public function onDelete($tables, $condition) {
        if (strpos($tables, $this->tableName) !== false) {
            $model = $this->table->select('*')->where($condition)->asObject(true);
        }
        parent::onDelete($tables, $condition);
    }
}
?>
