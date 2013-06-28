<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Admin
 *
 * @author Soul_man
 */
class Starter extends X3_Component {
    
    public $allowed = array(
        'user/login',
        'admin/add',
        'admin/deny',
        'user/deny',
        'user/add',
        'uploads/captcha',
        'page/*',
        'city/*',
    );
    
    private $admin = array(
        'menu/list',
        'menu/add',
        'menu/edit',
        'menu/delete',
        'sysSettings/list',
        'sysSettings/add',
        'sysSettings/edit',
        'sysSettings/delete',
    );
    
    public function init() {
        $this->addTrigger('onStartApp');
//        $this->addTrigger('onRender');
        $this->addTrigger('onEndApp');
    }
    
    public function onStartApp($controller,$action) {
        X3::app()->promo = array();
        if(!IS_AJAX)
            X3::user()->token = time() . rand(10,100);
        return array($controller,$action);
    }
    
    public function onRender($output) {
        
        return $output;
    }
    
    public function onEndApp() {
        return true;
    }
    
}

?>
