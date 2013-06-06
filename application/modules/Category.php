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
        'weight'=>array('integer','default'=>'0'),
        'status'=>array('boolean','default'=>'1'),
    );

    public function fieldNames() {
        return array(
            //'parent_id'=>'Находится в:',
            'title'=>'Название',
            'weight'=>'Порядок',
            'status'=>'Видимость',
        );
    }
    
    public function moduleTitle() {
        return 'Категории';
    }

    public function actionRegion() {
        if(IS_AJAX && isset($_GET['id']) && ($id = (int)$_GET['id'])>0){
            $query = array(
                    '@condition'=>array('city_region.city_id'=>$id),
                    '@order'=>'city_region.weight'
                );
            if(X3::user()->isKsk() && !X3::user()->superAdmin){
                $query['@join'] = "INNER JOIN user_address a ON a.region_id=city_region.id";
                $query['@condition']['a.user_id'] = X3::user()->id;
                $query['@group'] = "city_region.id";
            }
            $regions = City_Region::get($query,0,'City_Region',1);
            $result = array();
            foreach($regions as $reg){
                if(X3::user()->isKsk() && !X3::user()->superAdmin){
                    $f = "SELECT DISTINCT house FROM user_address WHERE region_id={$reg['id']} AND user_id=".X3::user()->id." AND status=1";
                }else
                    $f = "SELECT DISTINCT house FROM user_address WHERE region_id={$reg['id']}";
                $houses = array();
                $q = X3::db()->query($f);
                if(is_resource($q))
                while($a = mysql_fetch_assoc($q)){
                    $houses[] = $a['house'];
                }
                $result[] = array('id'=>$reg['id'],'title'=>$reg['title'],'houses'=>$houses);
            }
            echo json_encode($result);
            exit;
        }
        throw new X3_404();
    }
    
    public function getDefaultScope() {
        return array('@order'=>'weight');
    }

    public function beforeValidate() {
        //if($this->name == '')
            //$this->name = $this->title;
        //$name = new X3_String($this->name);
        //$this->name = strtolower($name->translit(0,"'"));
        //if(empty($this->parent_id) || $this->parent_id=="0") $this->parent_id = NULL;
    }

}
?>
