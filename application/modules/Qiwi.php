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
            $comment = '';
            $result = 0;
            if(
                    (strpos($uid,"00") === 0 && NULL === ($model = User::findByPk((int)$uid))) ||
                    (strpos($uid,"00") !== 0 && NULL === ($model = Project::findByPk((int)$uid)))
              ) {
                $result = 5;
            } elseif($model->status == 0) {
                $result = 79;
                //$comment = 'Пользователь не прошел активацию';
            }
            switch ($comm) {
                case "check":
                    $this->template->layout = null;
                    header("Content-type: text/xml; charset:utf-8");
                    $this->template->render('check', array('txnid' => $txnid,'result'=>$result,'comment'=>$comment));
                    break;
                case "pay":
                    $t = new Transaction();
                    if(get_class($model) === 'User') {
                        $t->user_id = $model->id;
                        $t->project_id = NULL;
                    } else {
                        $t->user_id = NULL;
                        $t->project_id = $model->id;
                    }
                    $t->title = "Qiwi";
                    $t->created_at = time();
                    $t->sum = $sum;
                    $t->comment = $comment;
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
