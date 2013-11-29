<?php
/**
 * Qiwi module
 *
 * @author Soul_man
 */
class Qiwi extends X3_Module {

    public function filter() {
        return array(
            'allow' => array(
                '*' => array('index', 'error', 'restore', 'kkb','wallet','qiwi'),
                'admin' => array('index')
            ),
            'deny' => array(
                '*' => array('*'),
            ),
            'handle' => 'redirect:/user/login.html'
        );
    }

    public function err401() {
        header("HTTP/1.0 401 Authorization Required");
        echo '<h1>Доступ запрещен.</h1>';
        exit(0);
    }

    public function cache() {
        return array(
                //'cache'=>array('actions'=>'map','role'=>'*','expire'=>'+1 day','filename'=>'sitemap.xml','directory'=>X3::app()->basePath),
                //'nocache'=>array('actions'=>'*','role'=>'admin')
        );
    }

    public function actionIndex() {
        if($_SERVER['REMOTE_ADDR'] === X3::app()->qiwi_ip && isset($_GET['command'],$_GET['txn_id'],$_GET['account'],$_GET['sum'])) {
            list($comm,$txnid,$uid,$sum) = array($_GET['command'],$_GET['txn_id'],$_GET['account'],$_GET['sum']);
            $isemail = false;
            $comment = '';
            $result = 0;
            if(
                    ($isemail && NULL === ($model = User::get(array('email'=>$uid),1))) ||
                    (!$isemail && NULL === ($model = Invest::get(array('hash'=>$uid),1)))
              ) {
                $result = 5;
            } elseif($model->status == 0) {
                $result = 79;
                //$comment = 'Пользователь не прошел активацию';
            }
            switch ($comm) {
                case "check":
                    if($model !== NULL && $model instanceof Invest && $sum < $model->amount) {
                        $result = 241;
                        $comment = "Нужно внести хотябы $model->amount тенге";
                    }
                    $this->template->layout = null;
                    header("Content-type: text/xml; charset:utf-8");
                    $this->template->render('check', array('txnid' => $txnid,'result'=>$result,'comment'=>$comment));
                    break;
                case "pay":
                    if($model instanceof Invest) {
                        $model->pay_method = Project_Invest::PAY_METHOD_WALLET;
                        $model->status = Project_Invest::STATUS_SUCCESS;
                        $model->pay_data = json_encode(array('user_id' => X3::user()->id, 'ip' => $_SERVER['REMOTE_ADDR']));
                        if (!$model->save()) {
                            $html = X3_Html::errorSummary($model);
                            $html.=X3::db()->getErrors();
                            $html.=X3::db()->lastQuery();
                            X3::log($html);
                            $result = 1;
                        } else {
                            if ($model->interest_id > 0) {
                                Project_Interest::update(array('bought' => '`bought` + 1'), array('id' => $model->interest_id));
                            }
                            $url = X3::request()->getBaseUrl() . "/" . $model->project_id()->name . "-project.html";
                            $admin_email = strip_tags(SysSettings::getValue('AdminEmail','string','Emailы Администраторов, через запятую','Общие','support@vsevmeste.kz'));
                            Notify::sendMail('User.Payed.4user', array('name' => $model->user_id()->fullName, 'type'=>'Qiwi', 'title' => $model->project_id()->title, 'url'=>$url, 'amount'=>$model->amount), $model->user_id()->email);
                            Notify::sendMail('User.Payed.4admin', array('name' => $model->user_id()->fullName, 'type'=>'Qiwi', 'title' => $model->project_id()->title, 'url'=>$url, 'amount'=>$model->amount), $admin_email);
                            $per = (float)strip_tags(SysSettings::getValue('QiwiComittion','string','Комиссия с Qiwi','Общие','1%'));
                            $delta = $sum - $model->amount;
                            if($delta > 0) {
                                User::update(array('money' => '`money` + ' . $delta), array('id' => X3::user()->id));
                            }
                            Project::update(array('current_sum' => '`current_sum` + ' . $model->amount), array('id' => $model->project_id));
                        }
                    } else {
                        $url = X3::request()->getBaseUrl() . "/user/$model->id/";
                        User::update(array('money' => '`money` + ' . $sum), array('id' => $model->id));
                        Notify::sendMail('User.Funds.4user', array('name' => $model->fullName, 'amount'=>$sum,'url'=>$url), $model->email);
                        Notify::sendMail('User.Funds.4admin', array('name' => $model->fullName, 'amount'=>$sum,'url'=>$url), $admin_email);
                    }
                    
                    $t = new Transaction();
                    if(get_class($model) === 'User') {
                        $t->user_id = $model->id;
                        $t->project_id = NULL;
                    } else {
                        $t->user_id = $model->user_id;
                        $t->project_id = $model->project_id;
                    }
                    $t->title = "Qiwi";
                    $t->created_at = time();
                    $t->sum = $sum;
                    $t->comment = $comment;
                    $t->status = 0;
                    $t->save();
                    
                    
                    header("Content-type: text/xml; charset:utf-8");
                    $this->template->layout = null;
                    
                    $this->template->render('pay', array('txnid' => $txnid,'result'=>$result,'comment'=>$comment,'sum'=>$sum,'pid'=>$t->id));
                    break;
                default:
                    throw new X3_404();
                    break;
            }
        } else {
            throw new X3_404();
        }
    }
}

?>
