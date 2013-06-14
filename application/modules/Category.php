<?php
/**
 * Category
 *
 * @author Soul_man
 */
class Category extends X3_Module_Table {

    public $encoding = 'UTF-8';
    /*
     * uncomment if want new model functional
     */
    public $tableName = 'project_category';

    public $_fields = array(
        'id'=>array('integer[10]','unsigned','primary','auto_increment'),
        'title'=>array('string[255]','language'),
        'name'=>array('string[255]','language'),
        'weight'=>array('integer','default'=>'0'),
        'status'=>array('boolean','default'=>'1'),
    );

    public function fieldNames() {
        return array(
            //'parent_id'=>'Находится в:',
            'title'=>'Название',
            'name'=>'Для URL',
            'weight'=>'Порядок',
            'status'=>'Видимость',
        );
    }
    
    public function moduleTitle() {
        return 'Категории';
    }
    
    public function getDefaultScope() {
        return array('@order'=>'weight');
    }

    public function beforeValidate() {
        if($this->name == '')
            $this->name = $this->title;
        $this->name = str_replace(" ","_",preg_replace("/[^0-9a-z\- ]+/", "", strtolower(X3_String::create($this->name)->translit())));
        //if(empty($this->parent_id) || $this->parent_id=="0") $this->parent_id = NULL;
    }

}
?>
