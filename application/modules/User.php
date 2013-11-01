<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of News
 *
 * @author Soul_man
 */
class User extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'data_user';
    public static $balance = null;
    public $_fields = array(
        'id' => array('integer[10]', 'unsigned', 'primary', 'auto_increment'),
        'image' => array('file', 'default' => 'NULL', 'allowed' => array('jpg', 'gif', 'png', 'jpeg'), 'max_size' => 10240),
        'city_id' => array('integer[10]', 'unsigned', 'default'=>'NULL', 'index', 'ref'=>array('City','id','default'=>'title')),
        'name' => array('string[255]', 'default' => ''),
        'surname' => array('string[255]', 'default' => ''),
        'debitcard' => array('string[20]', 'default' => 'NULL'),
        'company_name'=>array('string[32]','default'=>'NULL'),
        'company_bin'=>array('string[32]','default'=>'NULL'),
        'email' => array('email', 'unique'), //as login
        'contact_email' => array('email', 'default'=>'NULL'),
        'contact_phone' => array('string[32]', 'default'=>'NULL'),
        'password' => array('string[5|50]', 'password'),
        'role' => array('string[255]', 'default' => 'user'),
        'akey' => array('string[255]', 'default' => 'NULL'),
        'bankname' => array('string[64]', 'default' => 'NULL'),
        'address' => array('string[1024]', 'default' => 'NULL'),
        'about' => array('string[1024]', 'default' => 'NULL'),
        'links' => array('string[2048]', 'default' => 'NULL'),
        'date_of_birth' => array('integer[11]', 'default' => '0'),
        'lastbeen_at' => array('datetime', 'default' => '0'),
        'created_at' => array('datetime', 'default' => '0'),
        'ispartner' => array('boolean', 'unsigned', 'default' => '0'),
        'status' => array('integer[1]', 'unsigned', 'default' => '0'),
        //unused
        'password_old' => array('string[6|50]', 'password', 'default' => '', 'unused'),
        'password_new' => array('string[6|50]', 'password', 'default' => '', 'unused'),
        'password_repeat' => array('string[50]', 'password', 'default' => '', 'unused'),
        'captcha' => array('string[255]', 'default' => '', 'unused'),
        'iagree' => array('boolean', 'default' => '0', 'unused'),
    );

    public function moduleTitle() {
        return 'Пользователи';
    }
    
    public function onValidate($attr, $pass) {
        $pass = false;
        if (isset($this->_fields[$attr]) && in_array('xss', $this->_fields[$attr]) && trim($this->$name) != '') {
            
        }
        if ($attr == 'debitcard' && trim($this->$attr) != '') {
            if($this->bankname == '') {
                $this->addError('bankname', X3::translate('Укажите название банка.'));
            }
        }
        if ($attr == 'contact_phone' && trim($this->$attr) != '') {
            if(preg_match("/[0-9\-\s]+/", "", trim($this->$attr))==0) {
                $this->addError($attr, X3::translate('Не корректно указан номер телефона.'));
            }
        }
        if ($attr == 'phone' && trim($this->$attr) != '') {
            $id = $this->id;
            $phone = preg_replace("/^\+7/", "", trim($this->$attr));
            $phone = trim(preg_replace("/[\(\) ]/", "", trim($phone)));
            $phone = array(substr($phone, 0, 3), substr($phone, 3, 3), substr($phone, 6, 2), substr($phone, 8, 2));
            //var_dump($phone);die;
            if (preg_match("/^[0-9]{3} [0-9]{3}.{0,1}[0-9]{2}.{0,1}[0-9]{2}$/", $this->$attr) == false) {
                $this->addError($attr, X3::translate('Не корректно указан номер телефона.'));
            } else if (X3::db()->count("SELECT id FROM data_user u WHERE u.phone REGEXP '{$phone[0]} {$phone[1]}.{0,1}{$phone[2]}.{0,1}{$phone[3]}' AND u.id<>'$id'") > 0) {
                $this->addError($attr, X3::translate('Такой номер телефона уже используется.'));
            }
        }
    }

    public function fieldNames() {
        $known = X3::translate('Я ознакомлен(а) и соглас(ен/на) с [правилами] сайта');
        if (preg_match("/\[(.+)\]/", $known, $m) > 0) {
            $known = str_replace($m[0], '<a href="/page/rules.html" target="_blank">' . $m[1] . '</a>', $known);
        }
        return array(
            'iagree' => $known,
            'image' => X3::translate('Аватарка'),
            'name' => X3::translate('Имя'),
            'surname' => X3::translate('Фамилия'),
            'duty' => X3::translate('Должность'),
            'email' => 'E-mail',
            'contact_email' => 'Контактный E-mail',
            'contact_phone' => 'Контактный телефон',
            'links' => 'Ссылки в соц.сетях',
            'about' => 'Биография',
            'bankname' => 'Банк',
            'password' => X3::translate('Пароль'),
            'password_old' => X3::translate('Старый пароль'),
            'password_new' => X3::translate('Новый пароль'),
            'password_repeat' => X3::translate('Повторите новый пароль'),
            'gender' => X3::translate('Пол'),
            'role' => X3::translate('Роль'),
            'lastbeen_at' => X3::translate('Последнее посещение'),
            'date_of_birth' => X3::translate('Дата рождения'),
            'ispartner' => X3::translate('Партнер'),
        );
    }

    public function filter() {
        return array(
//            'allow' => array(
//                '*' => array('login', 'logout', 'deny', 'add', 'rank'),
//                'user' => array('index', 'edit', 'logout', 'password', 'list'),
//                'ksk' => array('index', 'edit', 'logout', 'password', 'list', 'send', 'block', 'unblock'),
//                'admin' => array('index', 'edit', 'admins', 'logout', 'password', 'delete', 'list', 'block', 'send', 'block', 'unblock')
//            ),
//            'deny' => array(
//                '*' => array('*')
//            ),
//            'handle' => 'redirect:/user/login.html'
        );
    }

    public function getFilled() {
        return !empty($this->name) && !empty($this->surname) && !empty($this->debitcard) && !empty($this->city_id);
    }
    
    public function beforeAction() {
        return true;
    }
    
    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    private function get_gravatar($email, $s = 80, $d = 'wavatar', $r = 'g', $img = false, $atts = array()) {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val)
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    public function getAvatar($size = '100x100') {
        if ($this->image == '' || $this->image == null || !is_file('uploads/User/' . $this->image)) {
            if ($size)
                list($w, $h) = explode('x', $size);
            else
                $w = 100;
            return $this->get_gravatar($this->email, $w);
        }
        if ($size)
            return '/uploads/User/' . $size . '/' . $this->image;
        return '/uploads/User/' . $this->image;
    }

    public function isOnline() {
        $online = null;
        if (X3::app()->hasComponent('mongo') && X3::mongo() != null) {
            $online = X3::mongo()->query(array('online:findOne' => array('user_id' => $this->id)));
        }
        return !is_null($online);
    }

    public function actionIndex() {
        if (isset($_GET['id']))
            $id = (int) $_GET['id'];
        else
            $id = X3::user()->id;
        $user = User::getByPk($id);
        $this->template->render('index', array('user' => $user));
    }

    public function actionInvested() {
        if (isset($_GET['id']))
            $id = (int) $_GET['id'];
        else
            $id = X3::user()->id;
        $user = User::getByPk($id);
        $q = array('@condition'=>array('user_id'=>$id,'status'=>'1'));
//        if(X3::user()->id !== $user->id)
//            $q['@condition']['status'] = 1;
        $count = Project_Invest::num_rows($q);
        $paginator = new Paginator('User/Invested',$count);
        $q['@limit'] = $paginator->limit;
        $q['@offset'] = $paginator->offset;
        $projects = Project_Invest::get($q);
        $this->template->render('invested', array('user' => $user,'models'=>$projects,'paginator'=>$paginator));
    }

    public function actionMessages() {
        $id = X3::user()->id;
        $user = User::getByPk($id);
        $q = array('@condition'=>array('to_user_id'=>$id),'@order'=>'created_at DESC');
        $count = User_Message::num_rows($q);
        $paginator = new Paginator('User/Messages',$count);
        $q['@limit'] = $paginator->limit;
        $q['@offset'] = $paginator->offset;
        $projects = User_Message::get($q);
        X3::clientScript()->registerScriptFile('/js/user.messages.js',  X3_ClientScript::POS_END);
        $this->template->render('messages', array('user' => $user,'models'=>$projects,'paginator'=>$paginator));
    }

    public function actionInvestments() {
        if (isset($_GET['id']))
            $id = (int) $_GET['id'];
        else
            $id = X3::user()->id;
        $user = User::getByPk($id);
        $q = array(
            '@join'=>'INNER JOIN `project` `p` ON `p`.`id`=`project_invest`.`project_id`',
            '@condition'=>array('p.user_id'=>$id,'project_invest.status'=>'1')
        );
//        if(X3::user()->id !== $user->id)
//            $q['@condition']['status'] = 1;
        $count = Project_Invest::num_rows($q);
        $paginator = new Paginator('User/Investments',$count);
        $q['@limit'] = $paginator->limit;
        $q['@offset'] = $paginator->offset;
        $projects = Project_Invest::get($q);
        $this->template->render('investments', array('user' => $user,'models'=>$projects,'paginator'=>$paginator));
    }

    public function actionProjects() {
        if (isset($_GET['id']))
            $id = (int) $_GET['id'];
        else
            $id = X3::user()->id;
        $user = User::getByPk($id);
        $q = array('@condition'=>array('user_id'=>$id));
        if(X3::user()->id !== $user->id)
            $q['@condition']['status'] = 1;
        $count = Project::num_rows($q);
        $paginator = new Paginator('User/Projects',$count);
        $q['@limit'] = $paginator->limit;
        $q['@offset'] = $paginator->offset;
        $q['@with'] = array('user_id','city_id');
        $projects = Project::get($q);
        $this->template->render('projects', array('user' => $user,'models'=>$projects,'paginator'=>$paginator));
    }

    /**
     * renders user list
     */
    public function actionList() {
        $type = 'user';
        $id = X3::user()->id;
        if (isset($_GET['type']))
            $type = $_GET['type'];
        if (X3::user()->isAdmin()) {
            $query = array(
                '@condition' => array('role' => $type, 'id' => array('<>' => $id)),
            );
            $count = User::num_rows($query);
            $models = User::get($query);
        } elseif (X3::user()->isKsk()) {
            $query = array(
                '@condition' => array('role' => 'user', 'id' => array('IN' => "(SELECT a1.user_id FROM user_address a1, user_address a2 
WHERE a2.user_id=$id AND a1.user_id<>a2.user_id AND `a2`.`city_id` = a1.city_id AND `a2`.`region_id` = a1.region_id AND `a2`.`house` = a1.house AND a2.status=1)")),
                '@group' => 'id'
            );
            $count = User::num_rows($query);
            $models = User::get($query);
        } elseif (X3::user()->isUser()) {
            $query = array(
                '@condition' => array('role' => $type, 'id' => array('IN' => "(SELECT a1.user_id FROM user_address a1, user_address a2 
WHERE a2.user_id=$id AND a1.user_id<>a2.user_id AND `a2`.`city_id` = a1.city_id AND `a2`.`region_id` = a1.region_id AND `a2`.`house` = a1.house AND a1.status=1)")),
                '@group' => 'id'
            );
            $count = User::num_rows($query);
            echo X3::db()->getErrors();
            $models = User::get($query);
        }
        $this->template->render('users', array('models' => $models, 'count' => $count, 'type' => $type));
    }

    public function actionEdit() {
        $id = X3::user()->id;
        $model = User::getByPk($id);
        if(isset($_POST['User'])){
            $data = $_POST['User'];
            $model->getTable()->acquire($data);
            if(is_array($model->links)) {
                $model->links = implode("\n", $model->links);
            }
            $u = new Upload($model,'image');
            if($u->message=='' && !$u->source){
                $u->save();
            }
            if(trim($model->name) == '') 
                $model->addError ('name', 'Необходимо ввести ваше имя');
            if(trim($model->surname) == '') 
                $model->addError ('surname', 'Необходимо ввести вашу фамилию');
            if(trim($model->debitcard) == '') 
                $model->addError ('debitcard', 'Необходимо ввести номер вашей банковской карты');
            if(NULL === City::findByPk($model->city_id)) 
                $model->addError ('city_id', 'Выберите город из списка');
            if (isset($_POST['Change'])) {
                $data = $_POST['Change'];
                if ($data['password_old'] != '' && $data['password_new'] != '' && $data['password_repeat'] != '') {
                    if (md5($data['password_old']) != $model->password)
                        $model->addError('password_old', X3::translate('Пароли не совпадают'));
                    if ($data['password_new'] != $data['password_repeat'])
                        $model->addError('password_repeat', X3::translate('Пароли не совпадают'));
                    if (!$model->getTable()->hasErrors()) {
                        $model->password = md5($data['password_new']);
                    }
                } elseif ($data['password_old'] != '' || $data['password_new'] != '' || $data['password_repeat'] != '') {
                    if ($data['password_old'] == '')
                        $model->addError('password_old', X3::translate('Поле `{attribute}` не должно быть пустым', array('{attribute}' => $model->fieldName('password_old'))));
                    if ($data['password_new'] == '')
                        $model->addError('password_new', X3::translate('Поле `{attribute}` не должно быть пустым', array('{attribute}' => $model->fieldName('password_new'))));
                    if ($data['password_repeat'] == '')
                        $model->addError('password_repeat', X3::translate('Поле `{attribute}` не должно быть пустым', array('{attribute}' => $model->fieldName('password_repeat'))));
                }
            }
            if($model->save()){
                $this->redirect("/user/$model->id/");
            }
        }
        X3::app()->datapicker = true;
        X3::clientScript()->registerScriptFile('/js/jqueryui.ru.js',  X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/step3.js?1',  X3_ClientScript::POS_END);

        $this->template->render('edit', array('model'=>$model));
    }

    public function actionBlock() {
        if (X3::user()->isUser() || !isset($_GET['id']))
            $this->redirect('/');
        $id = (int) $_GET['id'];
        $q = array('id' => $id);
        if (X3::user()->isKsk())
            $q['role'] = 'user';
        User::update(array('status' => '2'), $q);
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function actionUnblock() {
        if (X3::user()->isUser() || !isset($_GET['id']))
            $this->redirect('/');
        $id = (int) $_GET['id'];
        $q = array('id' => $id);
        if (X3::user()->isKsk())
            $q['role'] = 'user';
        User::update(array('status' => '1'), $q);
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function actionRank() {
        if (X3::user()->isUser() && isset($_GET['id']) && ($id = (int) $_GET['id']) > 0 && User::isMyKsk($id)) {
            $rank = (int) $_GET['mark'];
            echo User_Rank::add($id, $rank);
            exit;
        }
        throw new X3_404();
    }

    public function actionAdmins() {
        $count = User::num_rows(array('role' => 'admin', 'status' => array('>' => '0')));
        $models = User::get(array('role' => 'admin', 'status' => array('>' => '0')));
        $this->template->render('admins', array('count' => $count, 'models' => $models));
    }

    public function actionDelete() {
        if (!X3::user()->isAdmin() || !isset($_GET['id']))
            $this->redirect('/');
        $id = (int) $_GET['id'];
        User::deleteByPk($id);
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function actionLogin() {
        if (!X3::user()->isGuest())
            $this->redirect('/');
        $error = false;
        $u = array('email' => '', 'password' => '');
        $user = new User;
        $address = new User_Address;
        if (isset($_POST['captcha'])) {
            $pass = true;
            if (md5(strtolower($_POST['captcha'])) != X3::user()->captcha['text']) {
                $user->addError('captcha', X3::translate('Неверный код с картинки'));
                $pass = false;
            }
            $user->getTable()->acquire($_POST['User']);
            $user->role = 'user';
            $user->status = 0;
            if ($user->password != $user->password_repeat) {
                $user->addError('password_repeat', X3::translate('Пароли не совпадают'));
            }
            $user->password = md5($user->password);
            if ($user->save()) {
                $link = "http://vsevmeste.kz/user/add/key/".base64_encode($user->akey . "|" . $user->id);
                Notify::sendMail('User.Registered', array('link' => $link), $user->email);
                $this->redirect('/registration-succeeded.phtml');
            }
        }
        if (!isset($_POST['captcha']) && isset($_POST['User'])) {
            $u = array_extend($u, $_POST['User']);
            $u['email'] = mysql_real_escape_string($u['email']);
            $u['password'] = mysql_real_escape_string($u['password']);
            $userI = new UserIdentity($u['email'], $u['password']);
            $error = $userI->login();
            $user->email = $u['email'];
            if (!is_string($error)) {
                User_Stat::add();
                $this->refresh();
            }
        }
        $this->template->render('login', array('error' => $error, 'user' => $user, 'address' => $address));
    }

    public function actionLogout() {
        if (X3::app()->user->logout()) {
            $this->controller->redirect('/');
        }
    }

    /**
     * Add user logic
     * @throws X3_404
     */
    public function actionSend() {
        if (IS_AJAX && isset($_POST['email'])) {
            $email = $_POST['email'];
            $type = isset($_POST['type']) && $_POST['type'] == 'ksk' ? 'ksk' : 'user';
            $user = new User();
            $user->password = $email . "password";
            $user->role = $type;
            $user->email = $email;
            $user->status = 0;
            $type = ucfirst($type);
            if (!$user->save()) {
                $errs = $user->getTable()->getErrors();
                if (isset($errs['email']))
                    echo json_encode(array('status' => 'error', 'message' => $errs['email'][0]));
                else
                    echo json_encode(array('status' => 'error', 'message' => 'Возникла неизвестная ошибка. Обратитесь к Администратору'));
                exit;
            }

            $link = base64_encode($user->akey . "|" . X3::user()->id);
            if (TRUE === ($msg = Notify::sendMail('welcome' . $type, array('link' => $link), $email)))
                echo json_encode(array('status' => 'ok', 'message' => X3::translate('Письмо успешно отправлено')));
            else
                echo json_encode(array('status' => 'error', 'message' => $msg));
            exit;
        }
        throw new X3_404();
    }

    public function actionDeny() {
        if (!isset($_GET['key']))
            throw new X3_404();
        $key = base64_decode($_GET['key']);
        $key = explode('|', $key);
        User::delete(array('akey' => $key[0]));
        $this->redirect('/');
    }

    public function actionAdd() {
        if (!isset($_GET['key']))
            throw new X3_404();
        $key = base64_decode($_GET['key']);
        $key = explode('|', $key);
        if (NULL === ($user = User::get(array('akey' => $key[0],'id'=>$key[1]), 1)))
            throw new X3_404();
        $user->status = 1;
        $user->akey = null;
        $user->save();
        $this->redirect('/activated.phtml');
    }

    public function beforeValidate() {
        if ($this->getTable()->getIsNewRecord()) {
            $this->created_at = time();
            $this->akey = md5(time() . rand(10, 99)) . rand(10, 99);
        }
        if(!is_numeric($this->date_of_birth))
            $this->date_of_birth = strtotime ($this->date_of_birth);
    }

    public function afterValidate() {
    }

    public function afterSave($bNew = false) {
        if (!$this->getTable()->getIsNewRecord() && X3::app()->user->id == $this->id) {
            if (!is_null($this->name))
                X3::app()->user->fullname = $this->fullname;
            if (!is_null($this->role))
                X3::app()->user->role = $this->role;
            if (!is_null($this->email))
                X3::app()->user->email = $this->email;
        }
        if($this->getTable()->getIsNewRecord()){
            @mkdir("uploads/User/Files{$this->id}",0777,true);
        }
        return TRUE;
    }

    public function getFullname() {
        return X3_Html::encode(($this->name != '' || $this->surname != '') ? ($this->name . " " . $this->surname) : $this->email);
    }

    public function onDelete($tables, $condition) {
        if (strpos($tables, $this->tableName) !== false) {
            $model = $this->table->select('*')->where($condition)->asObject(true);
            Uploads::cleanUp($model, $model->image);
            Project_Event::delete(array('user_id'=>$model->id));
            Project::delete(array('user_id'=>$model->id));
        }
        parent::onDelete($tables, $condition);
    }

}

?>
