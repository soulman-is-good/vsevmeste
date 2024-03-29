<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Default
 *
 * @author Soul_man
 */
class Site extends X3_Module {

    public static function newInstance($class = null) {
        return parent::newInstance(__CLASS__);
    }

    public static function getInstance($class = null) {
        return parent::getInstance(__CLASS__);
    }

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

    public function route() { //Using X3_Request $url propery to parse
        return array(
            '/^sitemap\.xml$/' => 'actionMap',
            '/^download\/(.+?).html/' => array(
                'class' => 'Download',
                'argument' => '$1'
            )
        );
    }

    public function actionIndex() {
        $this->template->render('index', array('main' => true));
    }

    public function actionLimit() {
        $limit = (int) $_POST['val'];
        if ($limit <= 0 || !IS_AJAX)
            exit;
        $model = ucfirst($_POST['module']);
        $path = X3::app()->getPathFromAlias("@app:modules:$model.php");
        if (!is_file($path))
            exit;
        $model = $model . 'Limit';
        X3::app()->user->$model = $limit;
        echo 'OK';
        exit;
    }

    public function actionWeights() {
        $model = ucfirst($_POST['module']);
        $ids = explode(',', $_POST['ids']);
        if (empty($ids))
            exit;
        $tablename = X3_Module_Table::getInstance($model)->tableName;
        X3::db()->startTransaction();
        foreach ($ids as $i => $id) {
            if ($id > 0)
                X3::db()->addTransaction("UPDATE `$tablename` SET `weight`='$i' WHERE id='$id'");
        }
        X3::db()->commit();
        exit;
    }

    public function actionError() {
        $page = Page::get(array('name' => 'error404'), 1);
        if ($page == null) {
            $page = new Page;
            $page->name = 'error404';
            $page->title = 'Страница не найдена';
            $page->text = 'Страница не найдена';
            $title = "title";
            $text = "text";
            foreach (X3::app()->languages as $lang) {
                $page->{"{$title}_{$lang}"} = 'Страница не найдена';
                $page->{"{$text}_{$lang}"} = 'Страница не найдена';
            }
            $page->save();
        }
        $this->template->render('error', array('model' => $page));
    }

    public function actionRestore() {
        $error = '';
        if (isset($_GET['key'])) {
            if (NULL === ($model = User::get(array('akey' => $_GET['key']), 1)))
                throw new X3_404;
            if (isset($_POST['password'], $_POST['password_repeat'])) {
                if (empty($_POST['password'])) {
                    $error = 'Нужно ввести пароль';
                } elseif (empty($_POST['password_repeat'])) {
                    $error = 'Повторить ввод пароля';
                } elseif ($_POST['password'] !== $_POST['password_repeat']) {
                    $error = 'Пароли не совпадают';
                }
                if ($error == '') {
                    $model->password = md5($_POST['password']);
                    $model->akey = null;
                    if ($model->save())
                        $this->controller->redirect('/restore-success.phtml');
                    else
                        $error = X3_Html::errorSummary($model);
                }
            }
            $this->template->render('recover', array('error' => $error));
        }else {
            if (isset($_POST['User'])) {
                $email = X3::db()->validateSQL($_POST['User']['email']);
                $model = User::get(array('email' => $email, 'status' => '1'));
                if ($model !== NULL) {
                    $model->akey = md5(time()) . rand(100, 999);
                    $model->save();
                    $link = X3::request()->getBaseUrl() . "/restore/$model->akey";
                    Notify::sendMail('UserRestore', array('name' => $model->fullName, 'link' => $link), $model->email);
                    $this->controller->redirect('/password-restore.phtml');
                }
                else
                    $error = 'Такой e-mail не существует в базе пользователей или еще не был активирован.';
            }
            $this->template->render('restore', array('error' => $error));
        }
    }

    public function actionGo() {
        if (!isset($_GET['url']))
            throw new X3_404();
        $url = base64_decode($_GET['url']);
        header('Location: ' . $url);
        //TODO: render warning page
        exit;
    }

    public function checkKkbOrder($result, $path1, $kkb) { // Проверка существования заказа
        $logger = new Logger(X3::app()->basePath . '/application/log/kkb.log');
        if ($kkb->status == Project_Invest::STATUS_SUCCESS) {
            return true;
        }
        $req = process_check($result['PAYMENT_REFERENCE'], $result['PAYMENT_APPROVAL_CODE'], $result['ORDER_ORDER_ID'], 398, $result['ORDER_AMOUNT'], $path1);
        $host = X3::app()->kkb_host;
        try {
            $xml = null;
            $logger->log("$host/jsp/remote/checkOrdern.jsp?" . urlencode($req));
            $text = file_get_contents("$host/jsp/remote/checkOrdern.jsp?" . urlencode($req));
            if($text !== FALSE) {
                $xml = simplexml_load_string($text);
            } else {
                $logger->log("Error getting response");
                return false;
            }
            if($xml === false) {
                $logger->log("Error reading xml string!");
                $o = libxml_get_errors();
                foreach($o as $err) {
                    $logger->log($err->message);
                }
                return false;
            } else {
                $response = $xml->bank->response->attributes();
                if ($response->payment == 'true') {
                    return true;
                } else {
                    return false;
                }
            }
        }catch(Exception $e) {
            $logger->log($e->getMessage());
            return false;
        }
    }

    public function actionKkb() {
        if (NULL !== ($id = X3::request()->getRequest('id')) && NULL !== ($invest = Project_Invest::get(array('id' => $id, 'user_id' => X3::user()->id))) && (Project_Invest::STATUS_UNAPPOVED == $invest->status || Project_Invest::STATUS_WAIT == $invest->status)) {
            $dir = "vse";
            if(VV_DEBUG) {
                $dir = "paysys";
            }
            $addr = X3::app()->basePath . "/application/extensions/paysys/$dir/";
            require_once($addr . "kkb.utils.php");
            $path1 = $addr . 'config.txt';
            if (isset($_POST['response'])) {
                $logger = new Logger(X3::app()->basePath . '/application/log/kkb.log');
                $logger->log($_POST['response']);
                $result = 0;
                $result = process_response(stripslashes($_POST["response"]), $path1);
                $res = Project_Invest::getByPk(intval($result['ORDER_ORDER_ID']));
                if ($res != null) {
                    $done = false;
                    $j = 0;
                    while (FALSE === ($done = $this->checkKkbOrder($result, $path1, $res)) && $j < 3) {
                        $j++;
                        usleep(300);
                    }
                    if (!$done) {
                        $logger->log('Failed to proceed order');
                        echo -1;
                        exit;
                    } else {
                        $res->status = Project_Invest::STATUS_SUCCESS;
                        $res->pay_data = json_encode($result);
                        $res->save();
                        $per = (float)strip_tags(SysSettings::getValue('EpayComittion','string','Комиссия Epay','Общие','3.5%'));
                        $sum = $invest->amount + $invest->amount * $per / 100;
                        $t = new Transaction();
                        $t->user_id = X3::user()->id;
                        $t->project_id = $invest->project_id;
                        $t->title = "KazKom";
                        $t->created_at = time();
                        $t->sum = $sum;
                        $t->comment = '';
                        $t->save();
                        if ($res->interest_id > 0) {
                            Project_Interest::update(array('bought' => '`bought` + 1'), array('id' => $res->interest_id));
                        }
                        Project::update(array('current_sum' => '`current_sum` + ' . $res->amount), array('id' => $res->project_id));
                        
                        $url = X3::request()->getBaseUrl() . "/" . $invest->project_id()->name . "-project.html";
                        $admin_email = strip_tags(SysSettings::getValue('AdminEmail','string','Emailы Администраторов, через запятую','Общие','support@vsevmeste.kz'));
                        Notify::sendMail('User.Payed.4user', array('name' => $invest->user_id()->fullName, 'type'=>'EPay', 'title' => $invest->project_id()->title, 'url'=>$url, 'amount'=>$invest->amount), $invest->user_id()->email);
                        Notify::sendMail('User.Payed.4admin', array('name' => $invest->user_id()->fullName, 'type'=>'EPay', 'title' => $invest->project_id()->title, 'url'=>$url, 'amount'=>$invest->amount), $admin_email);
                    }
                    echo 1;
                    exit;
                } else {
                    throw new X3_404;
                }
            } else {
                $currency_id = "398"; // tenge
                $per = (float)strip_tags(SysSettings::getValue('EpayComittion','string','Комиссия Epay','Общие','3.5%'));
                $sum = $invest->amount + $invest->amount * $per / 100;
                $sign = process_request($invest->id, $currency_id, $sum, $path1);
                if (strpos(base64_decode($sign), "<") !== false) {
                    //$invest = Project_Invest::getByPk($invest->id);
                    $invest->pay_data = base64_decode($sign);
                    $invest->pay_method = Project_Invest::PAY_METHOD_EPAY;
                    $invest->status = Project_Invest::STATUS_WAIT;
                    if (!$invest->save()) {
                        $html = X3_Html::errorSummary($invest);
                        $html.=X3::db()->getErrors();
                        $html.=X3::db()->lastQuery();
                        X3::log($html);
                        throw new X3_Exception('Error updating order', 500);
                    } else {
                        $this->template->render('kkb', array('invest' => $invest, 'sign' => $sign));
                    }
                } else {
                    throw new X3_Exception($sign, 500);
                }
            }
        }
        else
            throw new X3_404;
    }

    /**
     * Pay method QIWI
     * @throws X3_Exception
     * @throws X3_404
     */
    public function actionQiwi() {
        if (NULL !== ($id = X3::request()->getRequest('id')) && NULL !== ($invest = Project_Invest::get(array('id' => $id, 'user_id' => X3::user()->id))) && (Project_Invest::STATUS_UNAPPOVED == $invest->status || Project_Invest::STATUS_WAIT == $invest->status)) {
            if(isset($_POST['qiwi'])) {
                $url = "/" . $invest->project_id()->name . "-project.html";
                $this->controller->redirect($url);
            } else {
                $invest->pay_method = Project_Invest::PAY_METHOD_QIWI;
                $invest->save();
                $this->template->render('qiwi', array('invest' => $invest));
            }
        } else {
            throw new X3_404;
        }
    }

    public function actionWallet() {
        if (NULL !== ($id = X3::request()->getRequest('id')) && NULL !== ($invest = Project_Invest::get(array('id' => $id, 'user_id' => X3::user()->id))) && (Project_Invest::STATUS_UNAPPOVED == $invest->status || Project_Invest::STATUS_WAIT == $invest->status)) {
            $per = (float)strip_tags(SysSettings::getValue('WalletComittion','string','Комиссия с личного кошелька','Общие','0%'));
            $sum = $invest->amount + $invest->amount * $per / 100;
            if(isset($_POST['wallet'])) {
                $user = User::getByPk(X3::user()->id);
                $invest->pay_method = Project_Invest::PAY_METHOD_WALLET;
                $invest->pay_data = json_encode(array('user_id' => X3::user()->id, 'ip' => $_SERVER['REMOTE_ADDR']));
                if($user->money >= $sum) {
                    $invest->status = Project_Invest::STATUS_SUCCESS;
                    if (!$invest->save()) {
                        $html = X3_Html::errorSummary($invest);
                        $html.=X3::db()->getErrors();
                        $html.=X3::db()->lastQuery();
                        X3::log($html);
                        throw new X3_Exception('Error updating order', 500);
                    } else {
                        if ($invest->interest_id > 0) {
                            Project_Interest::update(array('bought' => '`bought` + 1'), array('id' => $invest->interest_id));
                        }
                        User::update(array('money' => '`money` - ' . $sum), array('id' => X3::user()->id));
                        Project::update(array('current_sum' => '`current_sum` + ' . $invest->amount), array('id' => $invest->project_id));
                        $url = X3::request()->getBaseUrl() . "/" . $invest->project_id()->name . "-project.html";
                        $admin_email = strip_tags(SysSettings::getValue('AdminEmail','string','Emailы Администраторов, через запятую','Общие','support@vsevmeste.kz'));
                        Notify::sendMail('User.Payed.4user', array('name' => $invest->user_id()->fullName, 'type'=>'личный счет', 'title' => $invest->project_id()->title, 'url'=>$url, 'amount'=>$invest->amount), $invest->user_id()->email);
                        Notify::sendMail('User.Payed.4admin', array('name' => $invest->user_id()->fullName, 'type'=>'личный счет', 'title' => $invest->project_id()->title, 'url'=>$url, 'amount'=>$invest->amount), $admin_email);
                        $this->redirect(X3::request()->getBaseUrl() . "/" . $invest->project_id()->name . "-project/investments.html");
                    }
                } else {
                    $invest->status = Project_Invest::STATUS_ERROR;
                    if (!$invest->save()) {
                        $html = X3_Html::errorSummary($invest);
                        $html.=X3::db()->getErrors();
                        $html.=X3::db()->lastQuery();
                        X3::log($html);
                        throw new X3_Exception('Error updating order', 500);
                    } else {
                        $this->redirect(X3::request()->getBaseUrl() . "/" . $invest->project_id()->name . "-project.html");
                    }
                }
            } else {
                $this->template->render('wallet', array('invest' => $invest));
            }
        } else {
            throw new X3_404;
        }
    }

    public function actionUpdate() {
        if (!isset($_POST['attr']) || !X3::user()->isAdmin() || !IS_AJAX)
            throw new X3_404();
        $attr = $_POST['attr'];
        $val = isset($_POST['value']) ? $_POST['value'] : null;
        $module = false;
        $q = '';
        if (preg_match("/\((.+)?\)/", $attr, $m) > 0) {
            $q = $m[1];
            $module = ucfirst(strtok(str_replace($m[0], "", $attr), '.'));
            $attr = substr($attr, strpos($attr, '.') + 1);
        }
        else
            throw new X3_404();
        if (strpos($q, '{') !== false)
            $q = json_decode($q);
        if (false === $module || !class_exists($module) || (!is_array($q) && ($model = X3_Module_Table::getByPk($q, $module)) == null) || (is_array($q) && ($model = X3_Module_Table::get($q, 1, $module)) === null))
            throw new X3_404();
        if (!is_array($q)) {
            $pk = X3_Module_Table::getInstance($module)->getTable()->getPK();
            $q = array($pk => $q);
        }
        if (($attr == 'title' || $attr == 'text') && $val == '') {
            $attr = 'status';
            $val = '0';
        }
        X3_Module_Table::update(array($attr => $val), $q, $module);
        $model->afterSave();
        exit;
    }

}

?>
