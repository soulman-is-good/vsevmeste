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
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'project';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'user_id'=>array('integer[10]','unsigned','index','ref'=>array('User','id','default'=>"name")),
        'city_id' => array('integer[10]', 'unsigned', 'index', 'ref'=>array('City','id','default'=>'title')),
        'category_id' => array('integer[10]', 'unsigned', 'index', 'ref'=>array('Category','id','default'=>'title')),
        'image' => array('file', 'allowed' => array('jpg', 'gif', 'png', 'jpeg'), 'max_size' => 10240),
        'video'=>array('string[128]','default'=>'NULL'),
        'links'=>array('content','default'=>'NULL'),
        'title'=>array('string[64]'),
        'name'=>array('string[32]','unique'),
        'current_sum'=>array('integer[11]','default'=>'0'), 
        'needed_sum'=>array('integer[11]'),
        'short_content'=>array('text'),
        'full_content'=>array('text'),
        'status'=>array('boolean','default'=>'0'),
        'donate'=>array('boolean','default'=>'0'),
        'clicks'=>array('integer','default'=>'0'),
        'comments'=>array('integer','default'=>'0'),
        'created_at'=>array('datetime'),
        'end_at'=>array('datetime')
    );

    public function fieldNames() {
        return array(
            'user_id'=>X3::translate('Создатель'),
            'city_id'=>X3::translate('Город'),
            'category_id'=>X3::translate('Категория проекта'),
            'gallery_id'=>X3::translate('Превью'),
            'title'=>X3::translate('Название'),
            'full_content'=>X3::translate('Описание проекта'),
            'short_content'=>X3::translate('Краткое описание проекта'),
            'current_sum'=>X3::translate('Вложенная сумма'),
            'needed_sum'=>X3::translate('Нужная сумма'),
            'created_at'=>X3::translate('Дата создания'),
            'end_at'=>X3::translate('Дата окончания'),
            'status'=>X3::translate('Опубликован'),
            'image'=>X3::translate('Изображение'),
            'video'=>X3::translate('Видео'),
            'links'=>X3::translate('Ссылки на проект'),
            'company_name'=>X3::translate('Название компании'),
            'company_bin'=>X3::translate('ИИН/БИН компании'),
        );
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
    
    public function getPercentDone(){
        return round($this->current_sum/$this->needed_sum*100);
    }
    
    public function getTimeLeft(){
        //<b>25</b> дней осталось
        $parts = '';
        $left = $this->end_at - time();
        if($left<=0)
            return '<b>Закончен</b>';
        if($left >= 31536000){
            $y = floor($left/31536000);
            $left -= $y * 31536000;
            $parts = "<b>$y</b> " . X3_String::create($y)->numeral($y, array('год и ','года и ','лет и '));
        }
        $d = ceil($left/86400);
        $parts .= "<b>$d</b> " . X3_String::create($d)->numeral($d, array('день остался','дня осталось','дней осталось'));
        return $parts;
    }
    
    private function nostress() {
        $last = (float)X3::user()->last_query;
        if(microtime(true) - $last < 1) {
            throw new X3_Exception('Слишком частые запросы',500);
        }
        X3::user()->last_query = microtime(true);
    }

    public function actionIndex() {
        $id = X3::user()->id;
        $q = array(
            '@condition'=>array('project.status'=>'1'),
            '@with'=>array('user_id','city_id'),
            '@order'=>'created_at DESC'
        );
        $category = null;
        //Set category
        if(X3::request()->getRequest('category') !== null){
            $category = Category::get(array('name'=>X3::request()->getRequest('category')));
            if($category == null)
                throw new X3_404();
            $q['@condition']['category_id'] = $category->id;
        }
        $sort = null;
        //Sorting by
        if(X3::request()->getRequest('sort') !== null){
            $sort = X3::request()->getRequest('sort');
            switch ($sort){
                case 'popular':
                    $q['@order']='clicks DESC';
                    break;
                case 'weekly':
                    $time = time() - 604800;
                    $q['@condition']['created_at']=array('>'=>"$time");
                    break;
                case 'ending':
                    $time = time() + 604800;
                    $q['@order'] = 'end_at DESC';
                    $q['@condition']['end_at']=array('<'=>"$time");
                    break;
                case 'cheap':
                    $q['@order'] = 'needed_sum ASC';
                    break;
                case 'almost':
                    $q['@condition']['needed_sum'] = array('@@'=>'`needed_sum` < `current_sum` + 10001 AND `needed_sum`>10000');
                    break;
                default:
                    $this->redirect('/projects/');
            }
        }
        $count = self::num_rows($q);
        $paginator = new Paginator(__CLASS__, $count);
        $q['@limit'] = $paginator->limit;
        $q['@offset'] = $paginator->offset;
        $models = self::get($q);
        $cats = Category::get();
        $this->template->render('index', array('models' => $models, 'count' => $count, 'paginator' => $paginator,'cats'=>$cats,'category'=>$category,'sort'=>$sort));
    }
    public function actionPartner() {
        if(X3::user()->isGuest())
            throw new X3_404();
        $id = X3::user()->id;
        if(null!=($code = X3::request()->getRequest('code'))){
            if(NULL === ($model = Project_Partner::get(array('@condition'=>array('confirmation'=>$code),'@with'=>'project_id'),1)) || Project_Partner::num_rows(array('status'=>'1','project_id'=>$model->project_id)) > 0) {
                throw new X3_404();
            }
            $model->status = 1;
            $model->confirmation = NULL;
            $model->save();
            $back = "/".$model->project_id()->name . "-project/";
            $this->redirect($back);
        }
        $pid = (int)X3::request()->getRequest('id');
        $this->nostress();
        if(NULL === ($model = Project::getByPk($pid)) || $model->partner() !== NULL)
            throw new X3_404();
        $a = X3::db()->fetch("SELECT UUID() AS uuid");
        $part = new Project_Partner;
        $part->user_id = $id;
        $part->project_id = $pid;
        $part->confirmation = $a['uuid'];//md5(time().rand(100,999)).rand(100,999);
        $part->status = 0;
        if($part->save()){
            $partner = User::getByPk($id);
            $user = User::getByPk($model->user_id);
            $link = X3::request()->baseUrl . "/partner/confirm/$part->confirmation.html";
            $partner_link = X3::request()->baseUrl . "/user/$partner->id/";
            Notify::sendMail('PartnerConfirm',array('link'=>$link,'partner'=>$partner->fullName,'name'=>$user->fullName,'partner_link'=>$partner_link),$user->email);
            $this->redirect($_SERVER['HTTP_REFERER']);
        }else {
            echo X3_Html::errorSummary($part);
            exit;
        }
    }
    public function actionCity() {
        $id = X3::user()->id;
        $cid = (int)X3::request()->getRequest('id');
        $this->nostress();
        $q = array(
            '@condition'=>array('project.status'=>'1','project.city_id'=>$cid),
            '@with'=>array('user_id','city_id'),
            '@order'=>'created_at DESC'
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
            '@condition'=>array('project.status'=>'1'),
            '@with'=>array('user_id','city_id'),
            '@order'=>'created_at DESC'
        );
        if(isset($_GET['q'])) {
            $w = X3_Html::encode($_GET['q']);
            X3::user()->psearch = $w;
        }
        if(X3::user()->psearch!=''){
            $w = X3::user()->psearch;
            $w = X3::db()->validateSQL($w);
            $q['@condition'][] = array(array('project.title' => array('LIKE'=>"'%$w%'")),array('project.full_content' => array('LIKE'=>"'%$w%'")));
        }
        $count = self::num_rows($q);
        $paginator = new Paginator(__CLASS__, $count);
        $q['@limit'] = $paginator->limit;
        $q['@offset'] = $paginator->offset;
        $models = self::get($q);
        $this->template->render('search', array('models' => $models, 'count' => $count, 'paginator' => $paginator));
    }
    
    public function actionShow() {
        
        if (($id = X3::request()->getRequest('name')) !== NULL && NULL !== ($model = self::get(array('@condition'=>array('project.name'=>$id),'@with'=>array('user_id','city_id')),1))) {
            if(!isset($_COOKIE["clicked$model->id"])){
                $model->scenario = 'click';
                $model->clicks += 1;
                if($model->save()){
                    setcookie("clicked$model->id", '1',time()+864000);
                }
            }
            $interests = Project_Interest::get(array('@condition'=>array('left'=>array('>'=>'0'),'project_id'=>$model->id),'@order'=>'`left` DESC, created_at DESC'));
            X3::clientScript()->registerScriptFile('//yandex.st/share/share.js',  X3_ClientScript::POS_END);
            X3::app()->og_title = X3::app()->name . " - " . $model->title;
            X3::app()->og_url = X3::app()->baseUrl . "/$model->name-project.html";
            X3::app()->og_image = X3::app()->baseUrl . "/uploads/Project/220x220xw/$model->image";
            $this->template->render('show', array('model' => $model,'interests'=>$interests));
        }else{
            throw new X3_404();
        }
    }
    
    public function actionCommentsVK() {
        if (($id = X3::request()->getRequest('name')) !== NULL && NULL !== ($model = self::get(array('@condition'=>array('project.name'=>$id),'@with'=>array('user_id','city_id')),1))) {
            if(IS_AJAX){
                if(isset($_GET['update'])){// && X3::user()->token === X3::request()->getRequest('token')){
                    $model->scenario = 'comments';
                    $model->comments = (int)$_GET['update'];
                    if(!$model->save()){
                        echo 'ERROR';
                    }
                }
                exit;
            }
            $interests = Project_Interest::get(array('@condition'=>array('left'=>array('>'=>'0'),'project_id'=>$model->id),'@order'=>'`left` DESC, created_at DESC'));
            X3::clientScript()->registerScriptFile('//vk.com/js/api/openapi.js?96');
            X3::clientScript()->registerScript('VkComments','VK.init({apiId: 3736088, onlyWidgets: true});',  X3_ClientScript::POS_HEAD);
            $this->template->render('comments.vk', array('model' => $model,'interests'=>$interests));
        }else{
            throw new X3_404();
        }
    }
        
    public function actionEvents() {
        if (($id = X3::request()->getRequest('name')) !== NULL && NULL !== ($model = self::get(array('@condition'=>array('project.name'=>$id),'@with'=>array('user_id','city_id')),1))) {
            if(isset($_POST['eventtext'],$_POST['token'])){
                $this->nostress();
                require 'application/extensions/wikitexttohtml.php';
                $text = $_POST['eventtext'];
                $text = stripslashes($text);
                if($text!='' && X3::user()->etoken === $_POST['token']){
                    $wiki =  WikiTextToHTML::convertWikiTextToHTML(explode("\n",$text));
                    $event = new Project_Event();
                    $event->content = implode("\n",$wiki);
                    $event->project_id = $model->id;
                    $event->user_id = X3::user()->id;
                    $event->created_at = time();
                    if($event->save()){
                        $this->controller->refresh();
                        X3::user()->etoken = null;
                    }
                }
            }
            X3::user()->etoken = rand(10,100) . md5(time()) . rand(10,100);
            $limit = 10;
            $q = array('@condition'=>array('project_id'=>$model->id),'@limit'=>$limit,'@with'=>array('project_id','user_id'),'@order'=>'`created_at` DESC');
            if(IS_AJAX){
                if(isset($_GET['page'])){// && X3::user()->token === X3::request()->getRequest('token')){
                    $page = (int)$_GET['page'];
                    $q['@offset'] = $page * $limit;
                    $models = Project_Event::get($q);
                    foreach ($models as $model) {
                        echo $this->template->renderPartial('_project_event',array('model'=>$model));
                    }
                }
                exit;
            }
            $models = Project_Event::get($q);
            $interests = Project_Interest::get(array('@condition'=>array('left'=>array('>'=>'0'),'project_id'=>$model->id),'@order'=>'`left` DESC, created_at DESC'));
            $this->template->render('events', array('models' => $models,'model'=>$model,'interests'=>$interests));
        }else{
            throw new X3_404();
        }
    }
    
    public function actionComments() {
        if (($id = X3::request()->getRequest('name')) !== NULL && NULL !== ($model = self::get(array('@condition'=>array('project.name'=>$id),'@with'=>array('user_id','city_id')),1))) {
            if(isset($_POST['commenttext'],$_POST['token'])){
                $this->nostress();
                $text = X3_Html::encode($_POST['commenttext']);
                if($text!='' && X3::user()->ctoken === $_POST['token']){
                    $event = new Project_Comments();
                    $event->content = $text;
                    $event->project_id = $model->id;
                    $event->user_id = X3::user()->id;
                    $event->created_at = time();
                    if($event->save()){
                        $this->controller->refresh();
                        X3::user()->etoken = null;
                    }else {
                        echo X3_Html::errorSummary($event);
                        exit;
                    }
                }
            }
            X3::user()->ctoken = rand(10,100) . md5(time()) . rand(10,100);
            $limit = 10;
            $q = array('@condition'=>array('project_id'=>$model->id),'@limit'=>$limit,'@with'=>array('project_id','user_id'),'@order'=>'`created_at` ASC');
            if(IS_AJAX){
                if(isset($_GET['page'])){// && X3::user()->token === X3::request()->getRequest('token')){
                    $page = (int)$_GET['page'];
                    $q['@offset'] = $page * $limit;
                    $models = Project_Event::get($q);
                    foreach ($models as $model) {
                        echo $this->template->renderPartial('_project_event',array('model'=>$model));
                    }
                }
                exit;
            }
            $models = Project_Comments::get($q);
            $interests = Project_Interest::get(array('@condition'=>array('left'=>array('>'=>'0'),'project_id'=>$model->id),'@order'=>'`left` DESC, created_at DESC'));
            $this->template->render('comments', array('models' => $models,'model'=>$model,'interests'=>$interests));
        }else{
            throw new X3_404();
        }
    }
    
    public function actionAdd() {
        if(X3::user()->isGuest())
            $this->redirect('/enter.html');
        if(X3::user()->new_project != null){
            X3::user()->new_project = null;
        }
        $this->redirect('/project/step1.html');
    }
    public function actionStep1() {
        if(X3::user()->isGuest())
            $this->redirect('/enter.html');
        $id = X3::user()->id;
        $model = new Project();
        $user = User::getByPk($id);
        $model->city_id = $user->city_id;
        if(X3::user()->new_project != null){
            $model->getTable()->acquire(X3::user()->new_project);
        }
        if(isset($_POST['Project'])){
            $data = $_POST['Project'];
            $model->getTable()->acquire($data);
            $i = new Upload($model,'image');
            if($i->message == null && !$i->source){
                $i->save();
            }
            $model->links = implode("\n",$model->links);
            $model->user_id = $id;
            $model->needed_sum = 1;
            $model->created_at = time();
            if($model->validate()){
                X3::user()->new_project = $model->getTable()->getAttributes();
                //Notify::sendMail('Project.Created',array('title'=>$model->title,'name'=>X3::user()->fullname,'url'=>"/{$model->name}-project.html"));
                $this->redirect('/project/step2.html');
            }
        }
        if(!file_exists("uploads/User/Files{$id}"))
            @mkdir("uploads/User/Files{$id}",0777,true);
        
        X3::clientScript()->registerScriptFile('/js/ckeditor.4/ckeditor.js?2223',  X3_ClientScript::POS_END);
        X3::clientScript()->registerCssFile('/js/sfbrowser/css/sfbrowser.min.css',  'screen');
        X3::clientScript()->registerCssFile('/js/sfbrowser/plugins/filetree/css/filetree.css');
        X3::clientScript()->registerCssFile('/js/sfbrowser/plugins/filetree/css/screen.min.css','screen');
        X3::clientScript()->registerScriptFile('/js/sfbrowser/SWFObject.min.js',  X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/sfbrowser/jquery.tinysort.min.js',  X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/sfbrowser/jquery.sfbrowser.js',  X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/sfbrowser/lang/ru.js',  X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/sfbrowser/plugins/filetree/jquery.sfbrowser.filetree.min.js',  X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/sfbrowser/plugins/imageresize/jquery.sfbrowser.imageresize.min.js',  X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/sfbrowser/config.js?1',  X3_ClientScript::POS_END);
        X3::clientScript()->registerScript('save1','jQuery.noConflict=true;jQuery.sfbrowser.defaults.base = "../../uploads/User/Files'.$id.'";',  X3_ClientScript::POS_END);
        
        $this->template->render('add_step1', array('model' => $model, 'user'=>$user));
    }
    
    public function actionStep2(){
        if(X3::user()->isGuest())
            $this->redirect('/enter.html');
        if(X3::user()->new_project == null){
            $this->redirect('/project/add/');
        }
        $id = X3::user()->id;
        $model = new Project;
        $data = X3::user()->new_project;
        $model->getTable()->acquire($data);
        $model->needed_sum = null;
        $user = User::getByPk($id);
        if($model->id>0)
            $interests = Project_Interest::get(array('project_id'=>$model->id));
        else
            $interests = array();
        $hasErrors = false;
        if(isset($_POST['Project'])){
            $data = $_POST['Project'];
            $model->getTable()->acquire($data);
            $model->created_at = time();
            $model->status = 0;
            if($model->save()){
                $_interests = $_POST['Project_Interest'];
                if(!empty($_interests)){
                    Project_Interest::delete(array('project_id'=>$model->id));
                    $interests = array();
                    foreach($_interests as $idata) {
                        $interest = new Project_Interest;
                        $interest->getTable()->acquire($idata);
                        $interest->project_id = $model->id;
                        $interest->sum = abs($interest->sum);
                        $interest->created_at = time();
                        if($interest->id>0)
                            $interest->getTable()->setIsNewRecord(false);
                        $hasErrors = $hasErrors || $interest->save();
                        $interests[] = $interest;
                    }
                }
                if(!$hasErrors){
                    if($user->filled){
                        $this->redirect("/$model->name-project.html");
                    }else{
                        $t = X3::user()->new_project;
                        $t['name'] = $model->name;
                        X3::user()->new_project = $t;
                        $this->redirect("/project/step3.html");
                    }
                }
            }
        }
        X3::app()->datapicker = true;
        X3::clientScript()->registerScriptFile('/js/jqueryui.ru.js',  X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/step2.js',  X3_ClientScript::POS_END);
        $this->template->render('add_step2', array('model' => $model,'interests'=>$interests,'user'=>$user));
    }
    
    public function actionStep3(){
        if(X3::user()->isGuest())
            $this->redirect('/enter.html');
        if(X3::user()->new_project == null){
            $this->redirect('/project/add/');
        }
        $id = X3::user()->id;
        $model = User::getByPk($id);
        if(isset($_POST['User'])){
            $data = $_POST['User'];
            $model->getTable()->acquire($data);
            if(trim($model->name) == '') 
                $model->addError ('name', 'Необходимо ввести ваше имя');
            if(trim($model->surname) == '') 
                $model->addError ('surname', 'Необходимо ввести вашу фамилию');
            if(trim($model->debitcard) == '') 
                $model->addError ('debitcard', 'Необходимо ввести номер вашей банковской карты');
            if(NULL === City::findByPk($model->city_id)) 
                $model->addError ('city_id', 'Выберите город из списка');
            if($model->save()){
                $name = X3::user()->new_project['name'];
                $this->redirect("/$name-project.html");
            }
        }
        X3::app()->datapicker = true;
        X3::clientScript()->registerScriptFile('/js/jqueryui.ru.js',  X3_ClientScript::POS_END);
        X3::clientScript()->registerScriptFile('/js/step3.js?1',  X3_ClientScript::POS_END);
        $this->template->render('add_step3', array('model' => $model));
    }
    
    public function actionDelete(){
        if(X3::user()->isGuest())
            throw new X3_404;
        if(isset($_GET['id']) && (int)$_GET['id']>0)
            Project::delete(array('id'=>$_GET['id']));
        
        $this->redirect('/projects/');
    }

    public function notify($model) {
        $role = $model->type == '*'?'':"role='$model->type' AND ";
        $id = X3::user()->id;
        if(X3::user()->isKsk()){
            $b = X3::db()->query("SELECT city_id, region_id, house FROM user_address WHERE user_id='$model->user_id' AND status=1");
            $o = array();
            while($bb = mysql_fetch_assoc($b)){
                $o['city_id'][] = $bb['city_id'];
                $o['region_id'][] = $bb['region_id'];
                $o['house'][] = $bb['house'];
            }
            $o['city_id'] = implode(', ', $o['city_id']);
            $o['region_id'] = implode(', ', $o['region_id']);
            $o['house'] = implode(', ', $o['house']);
            if($model->city_id > 0 && $model->region_id > 0 && $model->house != null && $model->flat != null){
                $c = "a1.city_id=$model->city_id AND a1.region_id=$model->region_id AND a1.house='$model->house' AND a1.flat='$model->flat'";
            }
            elseif($model->city_id > 0 && $model->region_id > 0 && $model->house != null && $model->flat == null){
                $c = "a1.city_id='$model->city_id' AND a1.region_id='$model->region_id' AND a1.house='$model->house'";
            }
            elseif($model->city_id > 0 && $model->region_id > 0 && $model->house == null && $model->flat == null){
                $c = "a1.house IN ({$o['house']}) AND a1.city_id='$model->city_id' AND a1.region_id='$model->region_id'";
            }
            elseif($model->city_id > 0 && $model->region_id == 0 && $model->house == null && $model->flat == null){
                $c = "a1.region_id IN ({$o['region_id']}) AND a1.house IN ({$o['house']}) AND a1.city_id='$model->city_id'";
            }else
                $c = "a1.city_id IN ({$o['city_id']}) AND a1.region_id IN ({$o['region_id']}) AND a1.house IN ({$o['house']})";
        }else{
            if($model->city_id > 0 && $model->region_id > 0 && $model->house != null && $model->flat != null){
                $c = "a1.city_id='$model->city_id' AND a1.region_id='$model->region_id' AND a1.house='$model->house' AND a1.flat='$model->flat'";
            }
            elseif($model->city_id > 0 && $model->region_id > 0 && $model->house != null && $model->flat == null){
                $c = "a1.city_id='$model->city_id' AND a1.region_id='$model->region_id' AND a1.house='$model->house'";
            }
            elseif($model->city_id > 0 && $model->region_id > 0 && $model->house == null && $model->flat == null){
                $c = "a1.city_id='$model->city_id' AND a1.region_id='$model->region_id'";
            }
            elseif($model->city_id > 0 && $model->region_id == 0 && $model->house == null && $model->flat == null){
                $c = "a1.city_id='$model->city_id'";
            }else
                $c = "1";
        }
        $users = X3::db()->query("SELECT u.id, CONCAT(name,' ',surname) username, email FROM data_user u INNER JOIN user_address a1 ON a1.user_id=u.id WHERE 
            u.id<>$id AND ($role $c)
            GROUP BY u.id
            ");
        while($user = mysql_fetch_assoc($users)){
            $userset = X3::db()->fetch("SELECT * FROM user_settings us WHERE user_id='{$user['id']}'");
            if($userset['mailVote'])
                Notify::sendMail('newVote', array('text'=>$model->title,'name'=>$user['username'],'from'=>X3::user()->fullname), $user['email']);
        }
    }

    public function beforeValidate() {
        if($this->scenario == 'update') {
            if($this->city_id == 0) $this->city_id = null;
            if($this->table->isNewRecord){
                if(strpos($this->created_at,'.')!==false){
                    $this->created_at = strtotime($this->created_at);
                }elseif($this->created_at == 0)
                    $this->created_at = time();
            }
            if(strpos($this->end_at,'.')!==false){
                $time = strtotime($this->end_at);
                $this->end_at = mktime(23,59,59,date('n',$time), date('j',$time), date('Y',$time));
            }elseif($this->end_at == 0)
                $this->end_at = time() + 84600;
            $today = mktime(0,0,0,date('n'),date('j'),date('Y'));
            if($this->end_at < $today)
                $this->addError('end_at',X3::translate("Нельзя создать проект который уже закончился"));
            if($this->name==''){
                $this->name = $this->title;
            }
            $this->name = str_replace(" ","_",preg_replace("/[^0-9a-z\- ]+/", "", strtolower(X3_String::create($this->name)->translit())));
            $i = 1;
            while(NULL!=self::get(array('name'=>$this->name),1)){
                $this->name .= $i;
                $i++;
            }
        }
    }
    
    public function onDelete($tables, $condition) {
        if (strpos($tables, $this->tableName) !== false) {
            $model = $this->table->select('*')->where($condition)->asObject(true);
            Warning_Stat::delete(array('warning_id'=>$model->id));
        }
        parent::onDelete($tables, $condition);
    }
    
    public function partner() {
        if(!array_key_exists($this->id, $this->partners)){
            $this->partners[$this->id] = Project_Partner::get(array('@condition'=>array('project_id'=>$this->id),'@order'=>'created_at','@with'=>'user_id'),1);
        }
        return $this->partners[$this->id];
    }
}
?>
