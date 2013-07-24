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

    public static function newInstance($class=null) {
        return parent::newInstance(__CLASS__);
    }
    public static function getInstance($class=null) {
        return parent::getInstance(__CLASS__);
    }
    
    public function filter() {
        return array(
            'allow'=>array(
                '*'=>array('index','error'),
                'user'=>array('index'),
                'ksk'=>array('index'),
                'admin'=>array('index')
            ),
            'deny'=>array(
                '*'=>array('*'),
            ),
            'handle'=>'redirect:/user/login.html'
        );
    }
    
    public function err401(){
            header("HTTP/1.0 401 Authorization Required");
            echo '<h1>Доступ запрещен.</h1>';
            exit(0);
    }
    public function cache(){
        return array(
            //'cache'=>array('actions'=>'map','role'=>'*','expire'=>'+1 day','filename'=>'sitemap.xml','directory'=>X3::app()->basePath),
            //'nocache'=>array('actions'=>'*','role'=>'admin')
        );
    }

    public function route() { //Using X3_Request $url propery to parse
        return array(
            '/^sitemap\.xml$/'=>'actionMap',
            '/^download\/(.+?).html/'=>array(
                'class'=>'Download',
                'argument'=>'$1'
            )
        );
    }
    public function actionIndex() {      
        $this->template->render('index');
    }
    
    public function actionLimit() {
        $limit = (int)$_POST['val'];
        if($limit<=0 || !IS_AJAX) exit;
        $model = ucfirst($_POST['module']);
        $path = X3::app()->getPathFromAlias("@app:modules:$model.php");
        if(!is_file($path)) exit;
        $model = $model.'Limit';
        X3::app()->user->$model = $limit;
        echo 'OK';
        exit;
    }
    
    public function actionWeights() {
        $model = ucfirst($_POST['module']);
        $ids = explode(',',$_POST['ids']);
        if(empty($ids)) exit;
        $tablename = X3_Module_Table::getInstance($model)->tableName;
        X3::db()->startTransaction();
        foreach ($ids as $i=>$id){
            if($id>0)
                X3::db()->addTransaction("UPDATE `$tablename` SET `weight`='$i' WHERE id='$id'");
        }
        X3::db()->commit();
        exit;
    }
    

    public function actionError() {
        $page = Page::get(array('name'=>'error404'),1);
        if($page == null){
            $page = new Page;
            $page->name = 'error404';
            $page->title = 'Страница не найдена';
            $page->text = 'Страница не найдена';
            $title = "title";
            $text = "text";
            foreach(X3::app()->languages as $lang){
                $page->{"{$title}_{$lang}"} = 'Страница не найдена';
                $page->{"{$text}_{$lang}"} = 'Страница не найдена';
            }
            $page->save();
        }
        $this->template->render('error',array('model'=>$page));
    }
    
    public function actionGo() {
        if(!isset($_GET['url']))
            throw new X3_404();
        $url = base64_decode($_GET['url']);
        header('Location: '.$url);
        //TODO: render warning page
        exit;
    }
    
    public function actionUpdate(){
        if(!isset($_POST['attr']) || !X3::user()->isAdmin() || !IS_AJAX)
            throw new X3_404();
        $attr = $_POST['attr'];
        $val = isset($_POST['value'])?$_POST['value']:null;
        $module = false;
        $q = '';
        if(preg_match("/\((.+)?\)/", $attr,$m)>0){
            $q = $m[1];
            $module = ucfirst(strtok(str_replace($m[0], "", $attr),'.'));
            $attr = substr($attr, strpos($attr,'.')+1);
        }else
            throw new X3_404();
        if(strpos($q, '{')!==false)
            $q = json_decode ($q);
        if(false === $module || !class_exists($module) || (!is_array($q) && ($model = X3_Module_Table::getByPk($q,$module))==null) || (is_array($q) && ($model = X3_Module_Table::get($q,1,$module))===null))
            throw new X3_404();
        if(!is_array($q)){
            $pk = X3_Module_Table::getInstance($module)->getTable()->getPK();
            $q = array($pk=>$q);
        }
        if(($attr == 'title' || $attr == 'text') && $val == ''){
            $attr = 'status';
            $val = '0';
        }
        X3_Module_Table::update(array($attr=>$val),$q,$module);
        $model->afterSave();
        exit;
    }
}
?>
