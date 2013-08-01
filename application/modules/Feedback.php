<?php
/**
 * Feedback class
 *
 * @author Soul_man
 */
class Feedback extends X3_Module_Table {

    public $encoding = 'UTF-8';

    public $tableName = 'feedback';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'name'=>array('string'),
        'email' => array('email'),
        'text'=>array('content'),
        'status'=>array('boolean','default'=>'0'),
        'created_at'=>array('datetime'),
        //unused
        'captcha' => array('string[255]', 'default' => '', 'unused'),
    );

    public function fieldNames() {
        return array(
            'name'=>'Автор',
            'email'=>'E-mail',
            'text'=>X3::translate('Содержание'),
            'status'=>'Статус',
            'created_at'=>'Дата создания',
        );
    }
            
    public function actionIndex(){
        $model = new Feedback;
        if(isset($_POST['Feedback'])){
            $data = $_POST['Feedback'];
            $model->getTable()->acquire($data);
            $model->created_at = time();
            if (X3::user()->isGuest() && md5(strtolower($_POST['captcha'])) != X3::user()->captcha['text']) {
                $model->addError('captcha', X3::translate('Неверный код с картинки'));
            }
            if($model->validate() && !$model->hasErrors()){
                $model->save();
                Notify::sendMail('Feedback',array('name'=>$model->name,'email'=>$model->email,'ip'=>$_SERVER['REMOTE_ADDR'],'text'=>nl2br($model->text),'date'=>date('d.m.Y H:i',$model->created_at)),'soulman.is.good@gmail.com',$model->email);
                $this->controller->redirect('/feedback.phtml');
            }
        }
        $this->template->render('index',array('model'=>$model,'user'=>$user));
    }
}
?>
