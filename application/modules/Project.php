<?php

/**
 * Project class
 *
 * @author Soul_man
 */
class Project extends X3_Module_Table {

    public $encoding = 'UTF-8';
    public $scenario = 'update';
    private $partners = array();
    private static $_tags = array();
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'project';
    public $_fields = array(
        'id' => array('integer[10]', 'unsigned', 'primary', 'auto_increment'),
        'user_id' => array('integer[10]', 'unsigned', 'index', 'ref' => array('User', 'id', 'default' => "name")),
        'city_id' => array('integer[10]', 'unsigned', 'index', 'ref' => array('City', 'id', 'default' => 'title')),
        'category_id' => array('integer[10]', 'unsigned', 'index', 'ref' => array('Category', 'id', 'default' => 'title')),
        'image' => array('file', 'default' => 'NULL', 'allowed' => array('jpg', 'gif', 'png', 'jpeg'), 'max_size' => 10240),
        'video' => array('string[128]', 'default' => 'NULL'),
        'links' => array('content', 'default' => 'NULL'),
        'title' => array('string[60]'),
        'name' => array('string[128]', 'unique'),
        'current_sum' => array('integer[11]', 'default' => '0'),
        'needed_sum' => array('integer[11]'),
        'short_content' => array('text[255]'),
        'full_content' => array('text'),
        'status' => array('boolean', 'default' => '0'),
        'donate' => array('boolean', 'default' => '0'),
        'clicks' => array('integer', 'default' => '0'),
        'comments' => array('integer', 'default' => '0'),
        'created_at' => array('datetime'),
        'end_at' => array('datetime')
    );

    public function fieldNames() {
        return array(
            'user_id' => X3::translate('Создатель'),
            'city_id' => X3::translate('Город'),
            'category_id' => X3::translate('Категория проекта'),
            'gallery_id' => X3::translate('Превью'),
            'title' => X3::translate('Название'),
            'name' => X3::translate('Имя в URL'),
            'full_content' => X3::translate('Описание проекта'),
            'short_content' => X3::translate('Краткое описание проекта'),
            'current_sum' => X3::translate('Вложенная сумма'),
            'needed_sum' => X3::translate('Нужная сумма'),
            'created_at' => X3::translate('Дата создания'),
            'end_at' => X3::translate('Дата окончания'),
            'status' => X3::translate('Опубликован'),
            'image' => X3::translate('Изображение'),
            'video' => X3::translate('Видео'),
            'links' => X3::translate('Ссылки на проект'),
            'donate' => X3::translate('Благотворительная акция'),
            'company_name' => X3::translate('Название компании'),
            'company_bin' => X3::translate('ИИН/БИН компании'),
        );
    }

    public function beforeAction() {
        if (X3::request()->uri[1] != 'invest')
            X3::user()->invest = null;
        return true;
    }

    public function tags() {
        if (isset(self::$_tags[$this->id]))
            return self::$_tags[$this->id];
        return self::$_tags[$this->id] = Project_Tags::get(array(
            '@condition' => array('project_id' => $this->id),
            '@join'=>'INNER JOIN tags ON tags.id = project_tags.tag_id AND tags.status=1'
        ));
    }

    public function filter() {
        return array(
            'allow' => array(
                '*' => array('*'),
            ),
            'handle' => 'redirect:/user/login.html'
        );
    }

    public function moduleTitle() {
        return 'Проекты';
    }

    public function getVideoId() {
        $vid = false;
        if (preg_match("/youtu[^\/]+\/(.+)/", $this->video, $m) > 0) {
            $vid = str_replace("watch?v=", "", $m[1]);
        }
        return $vid;
    }

    public function getPercentReal() {
        return round(100 * ($this->current_sum / $this->needed_sum));
    }

    public function getPercentDone() {
        return $this->current_sum < $this->needed_sum ? round(100 * ($this->current_sum / $this->needed_sum)) : 100;
    }

    public function getInvestmentsCount() {
        //<b>0</b> вложений
        $cnt = (int) Project_Invest::num_rows(array('project_id' => $this->id, 'status' => '1'));
        return "<b>$cnt</b> " . X3_String::create($cnt)->numeral($cnt, array("вложение", "вложения", "вложений"));
    }

    public function getTimeLeft() {
        //<b>25</b> дней осталось
        $parts = '';
        $left = $this->end_at - time();
        if ($left <= 0)
            return '<b>Закончен</b>';
        if ($left >= 31536000) {
            $y = floor($left / 31536000);
            $left -= $y * 31536000;
            $parts = "<b>$y</b> " . X3_String::create($y)->numeral($y, array('год и ', 'года и ', 'лет и '));
        }
        $d = ceil($left / 86400);
        $parts .= "<b>$d</b> " . X3_String::create($d)->numeral($d, array('день остался', 'дня осталось', 'дней осталось'));
        return $parts;
    }

    private function nostress() {
        $last = (float) X3::user()->last_query;
        if (microtime(true) - $last < 1) {
            throw new X3_Exception('Слишком частые запросы', 500);
        }
        X3::user()->last_query = microtime(true);
    }

    public function actionIndex() {
        $id = X3::user()->id;
        $q = array(
            '@condition' => array('project.status' => '1'),
            '@with' => array('user_id', 'city_id'),
            '@order' => 'created_at DESC'
        );
        $category = null;
        //Set category
        if (X3::request()->getRequest('category') !== null) {
            $category = Category::get(array('name' => X3::request()->getRequest('category')));
            if ($category == null)
                throw new X3_404();
            $q['@condition']['category_id'] = $category->id;
        }
        $sort = null;
        //Sorting by
        if (X3::request()->getRequest('sort') !== null) {
            $sort = X3::request()->getRequest('sort');
            switch ($sort) {
                case 'popular':
                    $q['@condition']['clicks'] = array('@@' => "clicks > (SELECT SUM(clicks)/COUNT(id) FROM project WHERE status=1 AND end_at>" . time() . ")");
                    $q['@order'] = 'clicks DESC';
                    break;
                case 'weekly':
                    $time = time() - 604800;
                    $q['@condition']['created_at'] = array('>' => "$time");
                    break;
                case 'ending':
                    $time = time() + 604800;
                    $q['@order'] = 'end_at DESC';
                    $q['@condition']['end_at'] = array('@@' => "end_at<$time AND end_at>" . time());
                    break;
                case 'cheap':
                    $q['@order'] = 'needed_sum ASC';
                    break;
                case 'almost':
                    $q['@condition']['needed_sum'] = array('@@' => '`needed_sum` < `current_sum` + 10001 AND `needed_sum`>10000');
                    break;
                default:
                    $this->redirect('/projects/');
            }
        }
        if (X3::request()->getRequest('tag') !== null) {
            //I prefer one select and one join instead of two joins
            $tag = Tags::get(array('tag'=>urldecode(X3::request()->getRequest('tag'))),1);
            $q['@join'] = "INNER JOIN project_tags pt ON pt.project_id=project.id AND pt.tag_id=$tag->id";
        }
        $count = self::num_rows($q);
        $paginator = new Paginator(__CLASS__, $count);
        $q['@limit'] = $paginator->limit;
        $q['@offset'] = $paginator->offset;
        $models = self::get($q);
        $cats = Category::get();
        $tags = X3::db()->query("SELECT t.id, t.tag, COUNT(p.id) cnt FROM tags t INNER JOIN project_tags pt ON pt.tag_id=t.id INNER JOIN project p ON p.id=pt.project_id WHERE p.status=1 GROUP BY t.id ORDER BY cnt DESC, tag LIMIT 7");
        if($tags === false) {
            die(X3::db()->getErrors());
        }
        $this->template->render('index', array('models' => $models, 'count' => $count, 'paginator' => $paginator, 'cats' => $cats, 'category' => $category, 'sort' => $sort, 'tags' => $tags));
    }

    public function actionPartner() {
        if (X3::user()->isGuest())
            throw new X3_404();
        $id = X3::user()->id;
        if (null != ($code = X3::request()->getRequest('code'))) {
            if (NULL === ($model = Project_Partner::get(array('@condition' => array('confirmation' => $code), '@with' => 'project_id'), 1)) || Project_Partner::num_rows(array('status' => '1', 'project_id' => $model->project_id)) > 0) {
                throw new X3_404();
            }
            $model->status = 1;
            $model->confirmation = NULL;
            $model->save();
            $back = "/" . $model->project_id()->name . "-project/";
            $this->redirect($back);
        }
        $pid = (int) X3::request()->getRequest('id');
        $this->nostress();
        if (NULL === ($model = Project::getByPk($pid)) || $model->partner() !== NULL)
            throw new X3_404();
        $a = X3::db()->fetch("SELECT UUID() AS uuid");
        $part = new Project_Partner;
        $part->user_id = $id;
        $part->project_id = $pid;
        $part->confirmation = $a['uuid']; //md5(time().rand(100,999)).rand(100,999);
        $part->status = 0;
        if ($part->save()) {
            $partner = User::getByPk($id);
            $user = User::getByPk($model->user_id);
            $link = X3::request()->baseUrl . "/partner/confirm/$part->confirmation.html";
            $partner_link = X3::request()->baseUrl . "/user/$partner->id/";
            Notify::sendMail('PartnerConfirm', array('link' => $link, 'partner' => $partner->fullName, 'name' => $user->fullName, 'partner_link' => $partner_link), $user->email);
            $this->redirect($_SERVER['HTTP_REFERER']);
        } else {
            echo X3_Html::errorSummary($part);
            exit;
        }
    }

    public function actionCity() {
        $id = X3::user()->id;
        $cid = (int) X3::request()->getRequest('id');
        $this->nostress();
        $q = array(
            '@condition' => array('project.status' => '1', 'project.city_id' => $cid),
            '@with' => array('user_id', 'city_id'),
            '@order' => 'created_at DESC'
        );

        $count = self::num_rows($q);
        $paginator = new Paginator(__CLASS__, $count);
        $q['@limit'] = $paginator->limit;
        $q['@offset'] = $paginator->offset;
        $models = self::get($q);
        $this->template->render('search', array('models' => $models, 'count' => $count, 'paginator' => $paginator));
    }

    public function actionSearch() {
        $id = X3::user()->id;
        $this->nostress();
        $q = array(
            '@condition' => array('project.status' => '1'),
            '@with' => array('user_id', 'city_id'),
            '@join' => "LEFT JOIN project_tags pt ON pt.project_id=project.id LEFT JOIN tags tt ON tt.id=pt.tag_id",
            '@order' => 'created_at DESC'
        );
        if (isset($_GET['q'])) {
            $w = X3_Html::encode($_GET['q']);
            X3::user()->psearch = $w;
        }
        if (X3::user()->psearch != '') {
            $w = X3::user()->psearch;
            $w = X3::db()->validateSQL($w);
            $q['@condition'][] = array(array('project.title' => array('LIKE' => "'%$w%'")), array('project.full_content' => array('LIKE' => "'%$w%'")), 
                array('tt.tag' => array('LIKE' => "'%$w%'")));
        }
        $count = self::num_rows($q);
        $paginator = new Paginator(__CLASS__, $count);
        $q['@limit'] = $paginator->limit;
        $q['@offset'] = $paginator->offset;
        $models = self::get($q);
        $this->template->render('search', array('models' => $models, 'count' => $count, 'paginator' => $paginator));
    }

    public function actionShow() {

        if (($id = X3::request()->getRequest('name')) !== NULL && NULL !== ($model = self::get(array('@condition' => array('project.name' => $id), '@with' => array('user_id', 'city_id')), 1))) {
            if (!isset($_COOKIE["clicked$model->id"])) {
                $model->scenario = 'click';
                $model->clicks += 1;
                if ($model->save()) {
                    setcookie("clicked$model->id", '1', time() + 864000);
                }
            }
            $interests = Project_Interest::get(array('@condition' => array('bought' => array('<' => '`limit`'), 'project_id' => $model->id), '@order' => 'created_at DESC'));
            X3::clientScript()->registerScriptFile('//yandex.st/share/share.js', X3_ClientScript::POS_END);
            if (X3::user()->id === $model->user_id) {
                X3::clientScript()->registerScriptFile('/js/ckeditor.4/ckeditor.js?2223', X3_ClientScript::POS_END);
                X3::clientScript()->registerCssFile('/js/sfbrowser/css/sfbrowser.min.css', 'screen');
                X3::clientScript()->registerCssFile('/js/sfbrowser/plugins/filetree/css/filetree.css');
                X3::clientScript()->registerCssFile('/js/sfbrowser/plugins/filetree/css/screen.min.css', 'screen');
                X3::clientScript()->registerScriptFile('/js/sfbrowser/SWFObject.min.js', X3_ClientScript::POS_END);
                X3::clientScript()->registerScriptFile('/js/sfbrowser/jquery.tinysort.min.js', X3_ClientScript::POS_END);
                X3::clientScript()->registerScriptFile('/js/sfbrowser/jquery.sfbrowser.js', X3_ClientScript::POS_END);
                X3::clientScript()->registerScriptFile('/js/sfbrowser/lang/ru.js', X3_ClientScript::POS_END);
                X3::clientScript()->registerScriptFile('/js/sfbrowser/plugins/filetree/jquery.sfbrowser.filetree.min.js', X3_ClientScript::POS_END);
                X3::clientScript()->registerScriptFile('/js/sfbrowser/plugins/imageresize/jquery.sfbrowser.imageresize.min.js', X3_ClientScript::POS_END);
                X3::clientScript()->registerScriptFile('/js/sfbrowser/config.js?1', X3_ClientScript::POS_END);
                X3::clientScript()->registerScript('save1', 'jQuery.noConflict=true;jQuery.sfbrowser.defaults.swfupload = false;jQuery.sfbrowser.defaults.base = "../../uploads/User/Files' . $id . '";', X3_ClientScript::POS_END);
            }
            X3::app()->og_title = X3::app()->name . " - " . $model->title;
            X3::app()->og_url = X3::request()->getBaseUrl() . "/$model->name-project.html";
            X3::app()->og_image = X3::request()->getBaseUrl() . "/uploads/Project/220x220xw/$model->image";
            $this->template->render('show', array('model' => $model, 'interests' => $interests));
        } else {
            throw new X3_404();
        }
    }

    public function actionCommentsVK() {
        if (($id = X3::request()->getRequest('name')) !== NULL && NULL !== ($model = self::get(array('@condition' => array('project.name' => $id), '@with' => array('user_id', 'city_id')), 1))) {
            if (IS_AJAX) {
                if (isset($_GET['update'])) {// && X3::user()->token === X3::request()->getRequest('token')){
                    $model->scenario = 'comments';
                    $model->comments = (int) $_GET['update'];
                    if (!$model->save()) {
                        echo 'ERROR';
                    }
                }
                exit;
            }
            $interests = Project_Interest::get(array('@condition' => array('bought' => array('<' => '`limit`'), 'project_id' => $model->id), '@order' => 'created_at DESC'));
            X3::clientScript()->registerScriptFile('//vk.com/js/api/openapi.js?96');
            X3::clientScript()->registerScript('VkComments', 'VK.init({apiId: 3736088, onlyWidgets: true});', X3_ClientScript::POS_HEAD);
            $this->template->render('comments.vk', array('model' => $model, 'interests' => $interests));
        } else {
            throw new X3_404();
        }
    }

    public function actionEvents() {
        if (($id = X3::request()->getRequest('name')) !== NULL && NULL !== ($model = self::get(array('@condition' => array('project.name' => $id), '@with' => array('user_id', 'city_id')), 1))) {
            if (isset($_POST['eventtext'], $_POST['token'])) {
                $this->nostress();
//                require 'application/extensions/wikitexttohtml.php';
                $text = $_POST['eventtext'];
                $text = strip_tags($text, '<p><b><i><div><img><iframe><ul><ol><li><a><span>');
//                $text = stripslashes($text);
                if ($text != '' && X3::user()->etoken === $_POST['token']) {
//                    $wiki =  WikiTextToHTML::convertWikiTextToHTML(explode("\n",$text));
                    $event = new Project_Event();
                    $event->content = $text;
                    $event->project_id = $model->id;
                    $event->user_id = X3::user()->id;
                    $event->created_at = time();
                    if ($event->save()) {
                        $this->controller->refresh();
                        X3::user()->etoken = null;
                    }
                }
            }
            X3::user()->etoken = rand(10, 100) . md5(time()) . rand(10, 100);
            $limit = 10;
            $q = array('@condition' => array('project_id' => $model->id), '@limit' => $limit, '@with' => array('project_id', 'user_id'), '@order' => '`created_at` DESC');
            if (IS_AJAX) {
                if (isset($_GET['page'])) {// && X3::user()->token === X3::request()->getRequest('token')){
                    $page = (int) $_GET['page'];
                    $q['@offset'] = $page * $limit;
                    $models = Project_Event::get($q);
                    foreach ($models as $model) {
                        echo $this->template->renderPartial('_project_event', array('model' => $model));
                    }
                }
                exit;
            }
            $models = Project_Event::get($q);
            $interests = Project_Interest::get(array('@condition' => array('bought' => array('<' => '`limit`'), 'project_id' => $model->id), '@order' => 'created_at DESC'));
            $this->defineCKEditor(X3::user()->id);
            $this->template->render('events', array('models' => $models, 'model' => $model, 'interests' => $interests));
        } else {
            throw new X3_404();
        }
    }

    public function actionComments() {
        $uid = X3::user()->id;
        if (($id = X3::request()->getRequest('name')) !== NULL && NULL !== ($model = self::get(array('@condition' => array('project.name' => $id), '@with' => array('user_id', 'city_id')), 1))) {
            if (isset($_POST['commenttext'], $_POST['token']) && X3::db()->fetchScalar("SELECT COUNT(0) FROM project_invest WHERE user_id=$uid AND project_id=$id AND status=1") > 0) {
                $this->nostress();
                $text = X3_Html::encode($_POST['commenttext']);
                if ($text != '' && X3::user()->ctoken === $_POST['token']) {
                    $event = new Project_Comments();
                    $event->content = $text;
                    $event->project_id = $model->id;
                    $event->user_id = X3::user()->id;
                    $event->created_at = time();
                    if ($event->save()) {
                        $this->controller->refresh();
                        X3::user()->etoken = null;
                    } else {
                        echo X3_Html::errorSummary($event);
                        exit;
                    }
                }
            }
            X3::user()->ctoken = rand(10, 100) . md5(time()) . rand(10, 100);
            $limit = 10;
            $q = array('@condition' => array('project_id' => $model->id), '@limit' => $limit, '@with' => array('project_id', 'user_id'), '@order' => '`created_at` ASC');
            if (IS_AJAX) {
                if (isset($_GET['page'])) {// && X3::user()->token === X3::request()->getRequest('token')){
                    $page = (int) $_GET['page'];
                    $q['@offset'] = $page * $limit;
                    $models = Project_Event::get($q);
                    foreach ($models as $model) {
                        echo $this->template->renderPartial('_project_event', array('model' => $model));
                    }
                }
                exit;
            }
            $models = Project_Comments::get($q);
            $interests = Project_Interest::get(array('@condition' => array('bought' => array('<' => '`limit`'), 'project_id' => $model->id), '@order' => 'created_at DESC'));
            $this->template->render('comments', array('models' => $models, 'model' => $model, 'interests' => $interests));
        } else {
            throw new X3_404();
        }
    }

    public function actionInvestments() {
        if (($id = X3::request()->getRequest('name')) !== NULL && NULL !== ($model = self::get(array('@condition' => array('project.name' => $id)), 1))) {
            $limit = 10;
            $q = array('@condition' => array('project_id' => $model->id, 'status' => '1'), '@limit' => $limit, '@order' => '`created_at` DESC');
            if (IS_AJAX) {
                if (isset($_GET['page'])) {// && X3::user()->token === X3::request()->getRequest('token')){
                    $page = (int) $_GET['page'];
                    $q['@offset'] = $page * $limit;
                    $models = Project_Event::get($q);
                    foreach ($models as $model) {
                        echo $this->template->renderPartial('_project_event', array('model' => $model));
                    }
                }
                exit;
            }
            $models = Project_Invest::get($q);
            $interests = Project_Interest::get(array('@condition' => array('bought' => array('<' => '`limit`'), 'project_id' => $model->id), '@order' => 'created_at DESC'));
            $this->template->render('investments', array('models' => $models, 'model' => $model, 'interests' => $interests));
        } else {
            throw new X3_404();
        }
    }

    public function actionInvest() {
        if (($id = X3::request()->getRequest('name')) !== NULL && NULL !== ($model = self::get(array('@condition' => array('project.name' => $id)), 1))) {
            $interest = null;
            $invest = new Project_Invest();
            $errors = '';
            $user = User::getByPk(X3::user()->id);
            if (null != ($iid = (int) X3::request()->getRequest('id'))) {
                $interest = Project_Interest::get(array('id' => $iid, 'bought' => array('<' => '`limit`')), 1);
            }
            if (X3::user()->invest != null) {
                $data = json_decode(X3::user()->invest, 1);
                $this->template->render('pay_method', array('data' => $data, 'model' => $model, 'interest' => $interest));
            } else {
                if (isset($_POST['Project_Invest'])) {
                    $data = $_POST['Project_Invest'];
                    if ($interest === null) {
                        if ((int) $data['amount'] <= 0) {
                            $errors .= 'Сумма вклада должна быть больше нуля<br/>';
                        }
                        $invest->amount = (int) abs($data['amount']);
                        $invest->interest_id = null;
                    } else {
                        $address = '';
                        if (($name = trim(X3::request()->postRequest('name'))) == '') {
                            $errors .= 'Необходимо заполнить имя<br/>';
                        }
                        if (($surname = trim(X3::request()->postRequest('surname'))) == '') {
                            $errors .= 'Необходимо заполнить фамилию<br/>';
                        }
                        if (($address = trim(X3::request()->postRequest('address'))) == '') {
                            $errors .= 'Необходимо заполнить адрес получения<br/>';
                        }
                        if (($city = trim(X3::request()->postRequest('city'))) == '') {
                            $errors .= 'Укадите город пожалуйста<br/>';
                        }
                        $invest->interest_id = $iid;
                        $invest->amount = (int) $interest->sum;
                        $invest->address = "$name $surname\n$city,\n$address";
                    }
                    $invest->user_id = X3::user()->id;
                    $invest->project_id = $model->id;
                    $invest->created_at = time();
                    $invest->status = Project_Invest::STATUS_UNAPPOVED;
                    //clean up table
                    $time = time() - 86400; // 24h
                    X3::db()->query("DELETE FROM project_invest WHERE created_at<$time AND status=0");
                    if ($errors == '' && $invest->save()) {
                        X3::user()->invest = json_encode($invest->table->getAttributes());
                        $this->controller->refresh();
                    } else {
                        if ($invest->hasErrors())
                            $errors .= X3_Html::errorSummary($invest);
                    }
                }
                $this->template->render('invest', array('model' => $model, 'interest' => $interest, 'invest' => $invest, 'theuser' => $user, 'errors' => $errors));
            }
        }else {
            throw new X3_404();
        }
    }

    public function actionAdd() {
        if (X3::user()->isGuest())
            $this->redirect('/enter.html');
        if (X3::user()->new_project != null) {
            X3::user()->new_project = null;
        }
        $this->redirect('/project/step1.html');
    }

    public function actionEdit() {
        if (X3::user()->isGuest() || NULL === ($id = (int) X3::request()->getRequest('id')) || NULL === ($model = Project::get(array('id' => $id, 'user_id' => X3::user()->id))))
            $this->redirect('/enter.html');
        X3::user()->new_project = $model->getTable()->getAttributes();
        $this->redirect('/project/step1.html');
    }

    public function actionStep1() {
        if (X3::user()->isGuest())
            $this->redirect('/enter.html');
        if (isset($_POST['agree'])) {
            $this->redirect('/project/step2.html');
        }
        $id = X3::user()->id;
        $user = User::getByPk($id);
        $this->template->render('add_step1', array('user' => $user));
    }

    public function actionStep2() {
        if (X3::user()->isGuest())
            $this->redirect('/enter.html');
        $id = X3::user()->id;
        $model = new Project();
        $user = User::getByPk($id);
        $model->city_id = $user->city_id;
        if (X3::user()->new_project != null) {
            $model->getTable()->acquire(X3::user()->new_project);
        }
        $tags = array();
        if ($model->id > 0) {
            $tags = Project_Tags::get(array('project_id' => $model->id));
            $model->getTable()->setIsNewRecord(false);
        } else {
            $model->created_at = time();
        }
        if (isset($_POST['Project'])) {
            $data = $_POST['Project'];
            $model->getTable()->acquire($data);
            $i = new Upload($model, 'image');
            if ($i->message == null && !$i->source) {
                $i->save();
            }
            if ($model->image == null && $model->video != '' && !$i->source) {
                $vid = false;
                if (preg_match("/youtu[^\/]+\/(.+)/", $model->video, $m) > 0) {
                    $vid = str_replace("watch?v=", "", $m[1]);
                    $filename = 'Project-' . time() . rand(10, 99) . '.jpg';
                    $data = @file_get_contents("http://img.youtube.com/vi/$vid/hqdefault.jpg");
                    if ($data) {
                        @file_put_contents("uploads/Project/$filename", $data);
                        $model->image = $filename;
                    }
                }
            }
            if ($model->video == '' && $model->image == '') {
                $model->addError('image', 'Нужно прикрепить изображение или указать ссылку на видео YouTube');
            }
            if (is_array($model->links))
                $model->links = implode("\n", $model->links);
            $model->user_id = $id;
            if ($model->getTable()->getIsNewRecord())
                $model->needed_sum = 1;
            $model->created_at = time();
            if (!$model->hasErrors() && $model->validate()) {
                X3::user()->new_project = $model->getTable()->getAttributes();
                X3::user()->new_project_tags = $_POST['tags'];
                $admin_email = strip_tags(SysSettings::getValue('AdminEmail','string','Emailы Администраторов, через запятую','Общие','support@vsevmeste.kz'));
                Notify::sendMail('Project.Created.4user',array('title'=>$model->title,'name'=>X3::user()->fullname,'url'=>X3::request()->getBaseUrl() . "/{$model->name}-project.html"),$model->user_id()->email);
                Notify::sendMail('Project.Created.4admin',array('title'=>$model->title,'name'=>X3::user()->fullname,'url'=>X3::request()->getBaseUrl() . "/{$model->name}-project.html"),$admin_email);
                $this->redirect('/project/step3.html');
            }
        }
        $this->defineCkEditor($id);
        X3::app()->datapicker = true;
        X3::clientScript()->registerCssFile('/css/jquery.tagit.css', X3_ClientScript::POS_END);
        X3::clientScript()->registerCssFile('/css/tagit.ui-zendesk.css', X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/tag-it.js', X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/step1.js', X3_ClientScript::POS_END);
        $this->template->render('add_step2', array('model' => $model, 'user' => $user, 'tags' => $tags));
    }

    public function actionStep3() {
        if (X3::user()->isGuest())
            $this->redirect('/enter.html');
        if (X3::user()->new_project == null) {
            $this->redirect('/project/add/');
        }
        $id = X3::user()->id;
        $model = new Project;
        $data = X3::user()->new_project;
        $model->getTable()->acquire($data);
        if ($model->id > 0) {
            $model->table->setIsNewRecord(false);
            $model->end_at = ceil(($model->end_at - $model->created_at) / 86400);
        } else {
            $model->needed_sum = null;
            $model->end_at = '';
        }
        $user = User::getByPk($id);
        if ($model->id > 0)
            $interests = Project_Interest::get(array('project_id' => $model->id));
        else
            $interests = array();
        $hasErrors = false;
        $errors = array();
        if (isset($_POST['Project'])) {
            $data = $_POST['Project'];
            $model->getTable()->acquire($data);
            if (!$model->id > 0)
                $model->created_at = time();
            if ($data['end_at'] > 0) {
                $model->end_at = $model->created_at + $model->end_at * 86400;
            } else {
                $model->addError('end_at', 'Укажите корректное число дней');
            }
            $model->status = 0;
            if ($model->validate() && !$model->hasErrors()) {
                $model->save();
                $tags = X3::user()->new_project_tags;
                if (!empty($tags)) {
                    foreach ($tags as $tag) {
                        $T = Tags::upsert($tag);
                        if ($T->id > 0) {
                            Project_Tags::assign($model->id, $T->id);
                        }
                    }
                }
                X3::user()->new_project = $model->table->getAttributes();
                $_interests = $_POST['Project_Interest'];
                if (!empty($_interests)) {
                    //X3::db()->query("DELETE FROM project_interest WHERE project_id=$model->id");
                    $interests = array();
                    foreach ($_interests as $idata) {
                        if ($idata['sum'] == '' || $idata['title'] == '')
                            continue;
                        $interest = null;
                        if (isset($idata['id']) && $idata['id'] > 0)
                            $interest = Project_Interest::getByPk((int) $idata['id']);
                        if ($interest === null)
                            $interest = new Project_Interest;
                        $interest->getTable()->acquire($idata);
                        if ($interest)
                            $interest->project_id = $model->id;
                        $interest->sum = abs($interest->sum);
                        $interest->created_at = time();
                        if ($interest->id > 0)
                            $interest->getTable()->setIsNewRecord(false);
                        $hasErrors = $hasErrors || !$interest->save();
                        $interests[] = $interest;
                        $errors[] = $interest->table->getErrors();
                    }
                }
                if (!$hasErrors) {
                    if ($user->filled) {
                        $this->redirect("/$model->name-project.html");
                    } else {
                        $t = X3::user()->new_project;
                        $t['name'] = $model->name;
                        X3::user()->new_project = $t;
                        $this->redirect("/project/step4.html");
                    }
                }
            } else {
                $errors[] = $model->table->getErrors();
            }
        }
        X3::app()->datapicker = true;
        X3::clientScript()->registerScriptFile('/js/jqueryui.ru.js', X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/step2.js', X3_ClientScript::POS_END);
        $this->template->render('add_step3', array('model' => $model, 'interests' => $interests, 'user' => $user, 'errors' => $errors));
    }

    public function actionStep4() {
        if (X3::user()->isGuest())
            $this->redirect('/enter.html');
        if (X3::user()->new_project == null) {
            $this->redirect('/project/add/');
        }
        $id = X3::user()->id;
        $model = User::getByPk($id);
        if (isset($_POST['User'])) {
            $data = $_POST['User'];
            $model->getTable()->acquire($data);
            if (trim($model->name) == '')
                $model->addError('name', 'Необходимо ввести ваше имя');
            if (trim($model->surname) == '')
                $model->addError('surname', 'Необходимо ввести вашу фамилию');
            if (trim($model->debitcard) == '')
                $model->addError('debitcard', 'Необходимо ввести номер вашей банковской карты');
            if (NULL === City::findByPk($model->city_id))
                $model->addError('city_id', 'Выберите город из списка');
            if ($model->save()) {
                $name = X3::user()->new_project['name'];
                $this->redirect("/$name-project.html");
            }
        }
        X3::app()->datapicker = true;
        X3::clientScript()->registerScriptFile('/js/jqueryui.ru.js', X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/step3.js?1', X3_ClientScript::POS_END);
        $this->template->render('add_step4', array('model' => $model));
    }

    public function beforeValidate() {
        if ($this->video != '' && strpos($this->video, 'http') !== 0)
            $this->video = "http://$this->video";
        if ($this->scenario == 'update') {
            if ($this->city_id == 0)
                $this->city_id = null;
            if ($this->table->isNewRecord) {
                if (strpos($this->created_at, '.') !== false) {
                    $this->created_at = strtotime($this->created_at);
                } elseif ($this->created_at == 0)
                    $this->created_at = time();
            }
            if (strpos($this->end_at, '.') !== false) {
                $time = strtotime($this->end_at);
                $this->end_at = mktime(23, 59, 59, date('n', $time), date('j', $time), date('Y', $time));
            } elseif ($this->end_at == 0)
                $this->end_at = time() + 84600;
            $today = mktime(0, 0, 0, date('n'), date('j'), date('Y'));
            if (!$this->id > 0 && $this->end_at < $today)
                $this->addError('end_at', X3::translate("Нельзя создать проект который уже закончился"));
            if ($this->name == '') {
                $this->name = $this->title;
            }
            $this->name = str_replace(" ", "_", preg_replace("/[^0-9a-z\- ]+/", "", strtolower(X3_String::create($this->name)->translit())));
            $i = 1;
            while (NULL != self::get(array('name' => $this->name, 'id' => array('<>' => $this->id)), 1)) {
                $this->name .= $i;
                $i++;
            }
        }
    }

    public function getDefaultScope() {
        $q = array(
            '@order' => '(end_at - created_at) ASC,(needed_sum - current_sum) ASC'
        );
        if (isset($_GET['filter'])) {
            parse_str($_GET['filter'], $data);
            $q['@condition'] = $data;
        }
        return $q;
    }

    public function onDelete($tables, $condition) {
        if (strpos($tables, $this->tableName) !== false) {
            $model = $this->table->select('*')->where($condition)->asObject(true);
            X3::db()->query("DELETE FROM project_invest WHERE project_id=$model->id");
            X3::db()->query("DELETE FROM project_interest WHERE project_id=$model->id");
            X3::db()->query("DELETE FROM project_comments WHERE project_id=$model->id");
            X3::db()->query("DELETE FROM project_event WHERE project_id=$model->id");
            X3::db()->query("DELETE FROM project_partner WHERE project_id=$model->id");
        }
        parent::onDelete($tables, $condition);
    }

    public function partner() {
        if (!array_key_exists($this->id, $this->partners)) {
            $this->partners[$this->id] = Project_Partner::get(array('@condition' => array('project_id' => $this->id), '@order' => 'created_at', '@with' => 'user_id'), 1);
        }
        return $this->partners[$this->id];
    }

    private function defineCKEditor($id) {
        if (!file_exists("uploads/User/Files{$id}"))
            @mkdir("uploads/User/Files{$id}", 0777, true);

        X3::clientScript()->registerScriptFile('/js/ckeditor.4/ckeditor.js?2223', X3_ClientScript::POS_END);
        X3::clientScript()->registerCssFile('/js/sfbrowser/css/sfbrowser.min.css', 'screen');
        X3::clientScript()->registerCssFile('/js/sfbrowser/plugins/filetree/css/filetree.css');
        X3::clientScript()->registerCssFile('/js/sfbrowser/plugins/filetree/css/screen.min.css', 'screen');
        X3::clientScript()->registerScriptFile('/js/sfbrowser/SWFObject.min.js', X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/sfbrowser/jquery.tinysort.min.js', X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/sfbrowser/jquery.sfbrowser.js', X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/sfbrowser/lang/ru.js', X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/sfbrowser/plugins/filetree/jquery.sfbrowser.filetree.min.js', X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/sfbrowser/plugins/imageresize/jquery.sfbrowser.imageresize.min.js', X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/sfbrowser/config.js?5', X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/ckeditor.4/config.cli.js?5', X3_ClientScript::POS_END);
        X3::clientScript()->registerScript('save1', 'jQuery.noConflict=true;jQuery.sfbrowser.defaults.swfupload = false;jQuery.sfbrowser.defaults.base = "../../uploads/User/Files' . $id . '";', X3_ClientScript::POS_END);
    }

}

?>
