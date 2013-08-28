<?php

class Admin extends X3_Module {
    
    public $menu = array(
                '/admin/list/module/User'=>array('User','Пользователи','icon-user'),
                '/admin/list/module/Page'=>array('Page','Текстовые страницы','icon-font'),
                '/admin/list/module/Notify'=>array('Notify','Шаблоны','icon-envelope'),
                '/admin/list/module/City'=>array('City','Города','icon-road'),
                '/admin/list/module/Project'=>array('Project','Проекты','icon-leaf'),
                '/admin/list/module/Category'=>array('Category','Категории','icon-tags'),
                '/admin/list/module/SysSettings'=>array('SysSettings','Настройки','icon-cog'),
            );
    
    public function beforeAction(){
        $this->template->layout = 'admin';
        $html = '';
        if($this->controller->action == 'index')
            $html = '<li class="active"><a href="#" onclick="return false;"><i class="icon-home icon-white"></i> Основная информация</a></li>';
        else
            $html = '<li><a href="/admin/"><i class="icon-home icon-black"></i> Основная информация</a></li>';
        foreach($this->menu as $link=>$menu){
            if(isset($_GET['module']) && $menu[0] === $_GET['module'])
                $html .= '<li class="active"><a href="#" onclick="return false;"><i class="'.$menu[2].' icon-white"></i> '.$menu[1].'</a></li>';
            else
                $html .= '<li><a href="'.$link.'"><i class="'.$menu[2].' icon-black"></i> '.$menu[1].'</a></li>';
        }
        X3::app()->menus = $html;
        X3::clientScript()->registerScriptFile('/js/jqueryui.ru.js',  X3_ClientScript::POS_END);
    }
    
    public function filter() {
        return array(
            'allow'=>array(
                'admin'=>array('index','send','links','list','edit','update','delete','view','create')
            ),
            'deny'=>array(
                '*'=>array('*')
            ),
            'handle'=>'redirect:/user/login.html'
        );
    }
    
    public function actionIndex() {
        $this->template->render('index');
    }
    
    public function actionList() {
        if(!X3_DEBUG && !X3::user()->superAdmin)
            throw new X3_404();
        $action = strtolower($_GET['module']);
        $class = ucfirst($_GET['module']);
        $module = X3_Module::getInstance($class);
        $scope = $module->getDefaultScope();
        $count = X3_Module_Table::num_rows($scope,$class);
        $paginator = new Paginator($class."-Admin", $count,null,false);
        $scope['@limit'] = $paginator->limit;
        $scope['@offset'] = $paginator->offset;
        $models = X3_Module_Table::get($scope,0,$class,1);
        $path = X3::app()->getPathFromAlias("@views:admin:sudo:$action.php");
        if(is_file($path)){
            $this->template->render("sudo/$action",array('models'=>$models,'module'=>$module,'paginator'=>$paginator,'count'=>$count,'class'=>$class));
        }else
            $this->template->render('sudo/list',array('models'=>$models,'module'=>$module,'paginator'=>$paginator,'count'=>$count,'class'=>$class));
    }
    
    public function actionView() {
        $action = strtolower($_GET['module']);
        $path = X3::app()->getPathFromAlias("@views:admin:sudo:view:$action.php");
        $class = ucfirst($_GET['module']);
        $id = X3::db()->validateSQL($_GET['id']);
        $pk = X3_Module::getInstance($class)->getTable()->getPK();
        $scope = array("$pk" => $id);
        $model = X3_Module_Table::get($scope,1,$class);
        if(is_file($path)){
            $this->template->render("sudo/view/$action",array('model'=>$model,'class'=>$class));
        }else
            $this->template->render('sudo/view',array('model'=>$model,'class'=>$class));
    }
    
    public function actionEdit() {
        $action = strtolower($_GET['module']);
        $path = X3::app()->getPathFromAlias("@views:admin:sudo:form:$action.php");
        $class = ucfirst($_GET['module']);
        $id = X3::db()->validateSQL($_GET['id']);
        $pk = X3_Module::getInstance($class)->getTable()->getPK();
        $scope = array("$pk" => $id);
        $model = X3_Module_Table::get($scope,1,$class);
        if(isset($_POST[$class])){
            $data = $_POST[$class];
            $model->getTable()->acquire($data);
            foreach ($model->_fields as $n=>$f){
                if(strpos($f[0],"boolean")!==false && !isset($data[$n])) {
                    $model->$n = 0;
                }
            }
            if($model->save())
                $this->redirect("/admin/list/module/$class.html");
            else {
                exit;
            }
        }
        if(is_file($path)){
            $this->template->render("sudo/form/$action",array('model'=>$model,'class'=>$class));
        }else
            $this->template->render('sudo/form',array('model'=>$model,'class'=>$class));
    }
    
    public function actionCreate() {
        $action = strtolower($_GET['module']);
        $path = X3::app()->getPathFromAlias("@views:admin:sudo:form:$action.php");
        $class = ucfirst($_GET['module']);
        $model = new $class();
        if(isset($_POST[$class])){
            $data = $_POST[$class];
            $model->getTable()->acquire($data);
            if($model->save())
                $this->redirect("/admin/list/module/$class.html");
        }
        if(is_file($path)){
            $this->template->render("sudo/form/$action",array('model'=>$model,'class'=>$class));
        }
        $this->template->render('sudo/form',array('model'=>$model,'class'=>$class));
    }
    
    public function actionDelete() {
        $class = ucfirst($_GET['module']);
        $id = X3::db()->validateSQL($_GET['id']);
        $pk = X3_Module::getInstance($class)->getTable()->getPK();
        $scope = array("$pk" => $id);
        X3_Module_Table::delete($scope,$class);
        $this->redirect("/admin/list/module/$class.html");
    }
    
    public function actionUpdate() {
        $class = ucfirst($_GET['module']);
        $attr = $_GET['field'];
        $value = $_GET['value'];
        $id = X3::db()->validateSQL($_GET['id']);
        $attr = X3::db()->validateSQL($attr);
        $value = X3::db()->validateSQL($value);
        $pk = X3_Module::getInstance($class)->getTable()->getPK();
        $scope = array("$pk" => $id);
        $update = array("$attr"=>$value);
        X3_Module_Table::update($update,$scope,$class);
        $this->redirect("/admin/list/module/$class.html");
    }
}
