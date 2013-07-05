<?php
/**
 * Project class
 *
 * @author Soul_man
 */
class Project extends X3_Module_Table {
            
    public $encoding = 'UTF-8';
    public $scenario = 'update';
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
        'company_name'=>array('string[32]','default'=>'NULL'),
        'company_bin'=>array('string[32]','default'=>'NULL'),
        'title'=>array('string[32]'),
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
        
    public function actionIndex() {
        $id = X3::user()->id;
        $q = array(
            '@condition'=>array(),
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
            $this->template->render('show', array('model' => $model,'interests'=>$interests));
        }else{
            throw new X3_404();
        }
    }
    
    public function actionComments() {
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
            $this->template->render('comments', array('model' => $model,'interests'=>$interests));
        }else{
            throw new X3_404();
        }
    }
        
    public function actionEvents() {
        if (($id = X3::request()->getRequest('name')) !== NULL && NULL !== ($model = self::get(array('@condition'=>array('project.name'=>$id),'@with'=>array('user_id','city_id')),1))) {
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
    
    public function actionAdd() {
        $id = X3::user()->id;
        $model = new Project();
        if(isset($_POST['Project'])){
            throw new X3_404;
            $data = $_POST['Project'];
            $model->getTable()->acquire($data);
            $i = new Upload($model,'image');
            if($i->message == null && !$i->source){
                $i->save();
            }
            $model->links = implode("\n",$model->links);
            $model->user_id = $id;
            if($model->save()){
                Notify::sendMail('Project.Created',array('title'=>$model->title,'name'=>X3::user()->fullname,'url'=>"/{$model->name}-project.html"));
                $this->redirect('/project/add/step2.html');
            }
        }
        X3::app()->datapicker = true;
        $this->template->render('add_step1', array('model' => $model));
    }
    
    public function actionDelete(){
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
        }
    }
    
    public function onDelete($tables, $condition) {
        if (strpos($tables, $this->tableName) !== false) {
            $model = $this->table->select('*')->where($condition)->asObject(true);
            Warning_Stat::delete(array('warning_id'=>$model->id));
        }
        parent::onDelete($tables, $condition);
    }
    
    public static function search($word) {
        $id = X3::user()->id;
        $query = array();
        $date = X3::db()->fetch("SELECT created_at FROM data_user WHERE id=$id");
        $type = X3::user()->isUser()?"(f.type='user' OR f.type='*')":(X3::user()->isKsk()?"(f.type='ksk' OR f.type='*')":"(f.type='admin' OR f.type='*')");
        if(X3::user()->isUser() || 1){
            $cond = array();
            $query['@select']='f.*';
            $query['@group']='f.id';
            $query['@order']='f.created_at DESC';
            $query['@from']=array('data_vote'=>'f');
            $query['@join']="INNER JOIN data_user u ON u.id=f.user_id LEFT JOIN user_address a ON a.user_id=$id";
            $cond['id']=array(
                '@@'=>"f.title LIKE '%$word%' AND f.end_at>={$date['created_at']} AND (
            (f.user_id=$id)
                OR
            (f.status AND 
                $type AND u.role='admin' AND 
                (
                   (f.city_id=a.city_id AND f.region_id=a.region_id AND f.house=a.house AND f.flat=a.flat) OR 
                   (f.city_id=a.city_id AND f.region_id=a.region_id AND f.house=a.house AND f.flat IS NULL) OR 
                   (f.city_id=a.city_id AND f.region_id=a.region_id AND f.house IS NULL AND f.flat IS NULL) OR 
                   (f.city_id=a.city_id AND f.region_id IS NULL) OR
                   (f.city_id IS NULL)
                )
            )
                OR
            (f.status AND $type AND u.role='ksk' AND
             (
                (f.city_id=a.city_id AND f.region_id=a.region_id AND f.house=a.house AND f.flat=a.flat) OR 
                (f.city_id=a.city_id AND f.region_id=a.region_id AND f.house=a.house AND f.flat IS NULL) OR 
                (f.city_id=a.city_id AND f.region_id=a.region_id AND f.house IS NULL AND f.flat IS NULL AND a.house IN (SELECT house FROM user_address WHERE user_id=u.id AND status=1)) OR 
                (f.city_id=a.city_id AND f.region_id IS NULL AND a.region_id IN (SELECT region_id FROM user_address WHERE user_id=u.id AND status=1) AND a.house IN (SELECT house FROM user_address WHERE user_id=u.id AND status=1)) OR
                (f.city_id IS NULL AND a.city_id IN (SELECT city_id FROM user_address aa WHERE aa.user_id=u.id AND aa.status=1) AND a.region_id IN (SELECT region_id FROM user_address aa WHERE aa.user_id=u.id AND aa.status=1) AND a.house IN (SELECT house FROM user_address aa WHERE aa.user_id=u.id AND aa.status=1))
             )
             )
            )"
            );
            $query['@condition'] = $cond;
        }elseif(X3::user()->isKsk()){
            
        }
        return $query;
    }
}
?>
