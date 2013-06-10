<?php
/**
 * Project class
 *
 * @author Soul_man
 */
class Project extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'project';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'user_id'=>array('integer[10]','unsigned','index','ref'=>array('User','id','default'=>"name")),
        'city_id' => array('integer[10]', 'unsigned', 'index', 'ref'=>array('City','id','default'=>'title')),
        'category_id' => array('integer[10]', 'unsigned', 'index', 'ref'=>array('Category','id','default'=>'title')),
//        'gallery_id' => array('integer[10]', 'unsigned', 'index', 'ref'=>array('Project_Gallery','id','default'=>'title')),
        'title'=>array('string[32]'),
        'name'=>array('string[32]','unique'),
        'current_sum'=>array('integer[11]','default'=>'0'),
        'needed_sum'=>array('integer[11]'),
        'short_content'=>array('text'),
        'full_content'=>array('text'),
        'status'=>array('boolean','default'=>'0'),
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
        $count = self::num_rows($q);
        $paginator = new Paginator(__CLASS__, $count);
        $q['@limit'] = $paginator->limit;
        $q['@offset'] = $paginator->offset;
        $models = self::get($q);
        $cats = Category::get();
        $this->template->render('index', array('models' => $models, 'count' => $count, 'paginator' => $paginator,'cats'=>$cats));
    }
    
    public function actionShow() {
        if (isset($_GET['id']) && ($id = (int)$_GET['id']) > 0 && self::isMyVote($id)) {
            $model = self::getByPk($id);
            $this->template->render('show', array('model' => $model));
        }else
            throw new X3_404();
    }
    
    public function actionCreate() {
        $id = X3::user()->id;
        if(isset($_GET['id']) && (int)$_GET['id']>0){
            $model = Vote::getByPk((int)$_GET['id']);
        }else{
            $model = new Vote();
        }
        if(isset($_POST['Vote'])){
            $data = $_POST['Vote'];
            $model->getTable()->acquire($data);
            if($model->getTable()->getIsNewRecord())
                $model->user_id = $id;
            if(!array_reduce($ans = explode('||',$model->answer), create_function('$v,$w', 'return $w && trim($v)!="";'),true)){
                $model->addError('answer',X3::translate('Заполните все ответы'));
            }elseif(count(array_unique($ans))<count($ans)){
                $model->addError('answer',X3::translate('Ответы не должны повторяться'));
            }
            if(X3::user()->isKsk())
                $model->type = 'user';
            if(isset($_POST['public']))
                $model->status = '1';
            if($model->save()){
                if(isset($_POST['public'])){
                    $this->notify($model);
                }
                $this->redirect('/vote/');
            }
        }
        X3::app()->datapicker = true;
        $this->template->render('form', array('model' => $model));
    }

    public function actionCount() {
        if(!IS_AJAX) throw new X3_404();
        echo Message::num_rows(array('status'=>'0','user_to'=>X3::user()->id));
        exit;
    }
    
    public function actionDelete(){
        if(!X3::user()->isAdmin()){
            $id = X3::user()->id;
            if(isset($_GET['id']) && (int)$_GET['id']>0){
                Vote::delete(array('user_id'=>$id,'id'=>$_GET['id']));
            }
        }else {
            if(isset($_GET['id']) && (int)$_GET['id']>0)
                Vote::delete(array('id'=>$_GET['id']));
        }
        $this->redirect('/vote/');
    }


    public function actionRead() {
        $id = (int)$_GET['id'];
        $uid = X3::user()->id;
        $answer = (int)$_GET['val'];
        $vote = Vote::getByPk($id);
        if($vote == NULL){
            if(IS_AJAX)
                exit;
            $this->redirect($_SERVER['HTTP_REFERER']);
        }
        $author = User::getByPk($vote->user_id);
        $c = "1";
        $ax = "a1.city_id IN (SELECT city_id FROM user_address WHERE user_id=$vote->user_id AND status=1)";
        if($author->role == 'ksk'){
            $b = X3::db()->query("SELECT city_id, region_id, house FROM user_address WHERE user_id='$vote->user_id' AND status=1");
            $o = array();
            while($bb = mysql_fetch_assoc($b)){
                $o['city_id'][] = $bb['city_id'];
                $o['region_id'][] = $bb['region_id'];
                $o['house'][] = $bb['house'];
            }
            $o['city_id'] = implode(', ', $o['city_id']);
            $o['region_id'] = implode(', ', $o['region_id']);
            $o['house'] = implode(', ', $o['house']);
            if($vote->city_id > 0 && $vote->region_id > 0 && $vote->house != null && $vote->flat != null){
                $c = "a1.user_id='$uid' AND a1.city_id=a2.city_id AND a1.region_id=a2.region_id AND a1.house=a2.house AND a1.flat=a2.flat AND a2.city_id='$vote->city_id' AND a2.region_id='$vote->region_id' AND a2.house='$vote->house' AND a2.flat='$vote->flat'";
            }
            elseif($vote->city_id > 0 && $vote->region_id > 0 && $vote->house != null && $vote->flat == null){
                $c = "a1.user_id='$uid' AND a1.city_id=a2.city_id AND a1.region_id=a2.region_id AND a1.house=a2.house AND a1.flat=a2.flat AND a2.city_id='$vote->city_id' AND a2.region_id='$vote->region_id' AND a2.house='$vote->house'";
            }
            elseif($vote->city_id > 0 && $vote->region_id > 0 && $vote->house == null && $vote->flat == null){
                $c = "a1.user_id='$uid' AND a1.house IN ({$o['house']}) AND a1.city_id=a2.city_id AND a1.region_id=a2.region_id AND a1.house=a2.house AND a1.flat=a2.flat AND a2.city_id='$vote->city_id' AND a2.region_id='$vote->region_id'";
            }
            elseif($vote->city_id > 0 && $vote->region_id == 0 && $vote->house == null && $vote->flat == null){
                $c = "a1.user_id='$uid' AND a1.region_id IN ({$o['region_id']}) AND a1.house IN ({$o['house']}) AND a1.city_id=a2.city_id AND a1.region_id=a2.region_id AND a1.house=a2.house AND a1.flat=a2.flat AND a2.city_id='$vote->city_id'";
            }else
                $c = "a1.user_id='$uid' AND a1.city_id IN ({$o['city_id']}) AND a1.region_id IN ({$o['region_id']}) AND a1.house IN ({$o['house']}) AND a1.city_id=a2.city_id AND a1.region_id=a2.region_id AND a1.house=a2.house AND a1.flat=a2.flat";
        }else{
            if($vote->city_id > 0 && $vote->region_id > 0 && $vote->house != null && $vote->flat != null){
                $c = "a1.user_id='$uid' AND a1.city_id=a2.city_id AND a1.region_id=a2.region_id AND a1.house=a2.house AND a1.flat=a2.flat AND a2.city_id='$vote->city_id' AND a2.region_id='$vote->region_id' AND a2.house='$vote->house' AND a2.flat='$vote->flat'";
            }
            elseif($vote->city_id > 0 && $vote->region_id > 0 && $vote->house != null && $vote->flat == null){
                $c = "a1.user_id='$uid' AND a1.city_id=a2.city_id AND a1.region_id=a2.region_id AND a1.house=a2.house AND a1.flat=a2.flat AND a2.city_id='$vote->city_id' AND a2.region_id='$vote->region_id' AND a2.house='$vote->house'";
            }
            elseif($vote->city_id > 0 && $vote->region_id > 0 && $vote->house == null && $vote->flat == null){
                $c = "a1.user_id='$uid' AND a1.city_id=a2.city_id AND a1.region_id=a2.region_id AND a1.house=a2.house AND a1.flat=a2.flat AND a2.city_id='$vote->city_id' AND a2.region_id='$vote->region_id'";
            }
            elseif($vote->city_id > 0 && $vote->region_id == 0 && $vote->house == null && $vote->flat == null){
                $c = "a1.user_id='$uid' AND a1.city_id=a2.city_id AND a1.region_id=a2.region_id AND a1.house=a2.house AND a1.flat=a2.flat AND a2.city_id='$vote->city_id'";
            }else
                $c = "a1.user_id='$uid' AND a1.city_id=a2.city_id AND a1.region_id=a2.region_id AND a1.house=a2.house AND a1.flat=a2.flat";
        }
        $qa = X3::db()->query("SELECT a2.id FROM user_address a1, user_address a2 WHERE $c GROUP BY a2.id");
        while($address = mysql_fetch_assoc($qa)){
            $ws = new Vote_Stat;
            $ws->address_id = $address['id'];
            $ws->vote_id = $id;
            $ws->answer = $answer;
            $ws->save();
        }
        if(IS_AJAX)
            exit;
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function file() {
        if (isset($_FILES['file'])) {
            $h = new Upload('file');
            if($h->message!='')
                die(json_encode(array('status' => 'error', 'message' =>$h->message)));
            $orig = $h->filename;
            $ext = strtolower(pathinfo($orig,PATHINFO_EXTENSION));
            $allowed = SysSettings::getValue('Message_Uploads.Extensions', 'string', 'Разрешенные к загрузке расширения файлов', '[INVISIBLE]', 'jpg,png,gif,tif,rar,zip,doc,docx,xls,xlsx,txt,ppt,pptx');
            $allowed = array_map(function($item){return trim($item);},explode(',',$allowed));
            if(!in_array($ext,$allowed))
                die(json_encode(array('status' => 'error', 'message' => $ext.". ".strtr(X3::translate('Возможно загрузить только файлы с расширениями: {files}'),array('{files}'=>implode(', ',$allowed))))));
            $filename = md5_file($h->tmp_name);
            if ($h->saveAs($filename)) {
                if (NULL === Uploads::getByPk($filename)) {
                    $model = new Uploads();
                    $model->id = $filename;
                    $model->name = $orig;
                    $model->created_at = time();
                    $model->save();
                }
                echo json_encode(array('status' => 'ok', 'message' => array('id'=>$filename,'filename'=>$orig)));
            } else {
                echo json_encode(array('status' => 'error', 'message' => $h->message));
            }
        }else
            echo json_encode(array('status' => 'error', 'message' => 'Не выбрано файлов'));
        exit;
    }

    public function actionSend() {
        if(!X3::user()->isAdmin()){
            if (isset($_GET['id']) && ($id = (int)$_GET['id'])>0) {
                $a = Vote::update(array('status'=>'1'),array('user_id'=>X3::user()->id,'id'=>$id));
                $this->notify(self::getByPk($id));
                if(IS_AJAX)
                    exit;
                $this->redirect($_SERVER['HTTP_REFERER']);
            }
        }else
            if (isset($_GET['id']) && ($id = (int)$_GET['id'])>0) {
                $a = Vote::update(array('status'=>'1'),array('id'=>$id));
                $this->notify(self::getByPk($id));
                if(IS_AJAX)
                    exit;
                $this->redirect($_SERVER['HTTP_REFERER']);
            }
        throw new X3_404();
    }
    
    public function actionFlats() {
        if(!IS_AJAX)
            throw new X3_404();
        $cid = (int)$_GET['cid'];
        $rid = (int)$_GET['rid'];
        $house = $_GET['house'];
        $uid = X3::user()->id;
        if(X3::user()->isKsk())
            $q = "SELECT flat FROM user_address INNER JOIN data_user u ON u.id=user_id WHERE u.role='user' AND user_id<>$uid AND flat>0 AND city_id='$cid' AND region_id='$rid' AND house='$house' GROUP BY flat ORDER BY flat";
        else
            $q = "SELECT flat FROM user_address WHERE flat>0 AND city_id='$cid' AND region_id='$rid' AND house='$house' GROUP BY flat ORDER BY flat";
        $fq = X3::db()->query($q);
        echo X3::db()->getErrors();
        $flats = array();
        while($f = mysql_fetch_assoc($fq)){
            $flats[] = $f['flat'];
        }
        echo json_encode($flats);
        exit;
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
        if($this->city_id == 0) $this->city_id = null;
        if(strpos($this->created_at,'.')!==false){
            $this->created_at = strtotime($this->created_at);
        }elseif($this->created_at == 0)
            $this->created_at = time();
        if(strpos($this->end_at,'.')!==false){
            $time = strtotime($this->end_at);
            $this->end_at = mktime(23,59,59,date('n',$time), date('j',$time), date('Y',$time));
        }elseif($this->end_at == 0)
            $this->end_at = time() + 84600;
        $today = mktime(0,0,0,date('n'),date('j'),date('Y'));
        if($this->end_at < $today)
            $this->addError('end_at',X3::translate("Нельзя создать опрос с прошедшей датой"));
        if($this->name==''){
            $this->name = $this->title;
        }
        $this->name = str_replace(" ","_",preg_replace("/[^0-9a-z\- ]+/", "", strtolower(X3_String::create($this->name)->translit())));
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
