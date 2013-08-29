<?php
return array(
    'basePath'=>dirname(dirname(dirname(__FILE__))),
    'baseUrl'=>'http://vsevmeste.soulman.kz',
    'name'=>'VseVmeste',
//    'errorController'=>'site/error',
    'uri'=>array(
        'suffix'=>'html'
    ),
    'locale'=>'ru',
//    'languages'=>array('en','kz'),
    'components'=>array(
        'starter'=>array(
            'class'=>'Starter'
        ),
        'profile'=>array(
            'class'=>'Profile',
            'time'=>microtime(true),
            'enable'=>false
        ),
        'db'=>array(
            'host'=>'localhost',
            'user'=>'root',//'c_448_vsevmeste',
            'password'=>'NjkmrjDgthtl',//'RVjKxnNXtlHg',
            'database'=>'vsevmeste'//'c_448_vsevmeste'
        ),
        'mongo'=>array(
            'class'=>'X3_MongoConnection',
            'host'=>'localhost',
            'user'=>null,
            'password'=>null,
            'database'=>'vsevmeste',
            'lazyConnect'=>true,            
        ),
        'seo'=>array(
            'class'=>'SeoHelper'
        ),
        'i18n'=>array(
            'class'=>'I18n'
        ),
//        'optitmizer'=>array(
//            'class'=>'Optimizer',
//            'predefine'=>include('meta.php')
//        ),
        'router'=>array(
            'routes'=>  'application/config/routes.php'
        ),
        'log'=>array(
            'dblog'=>array(
                'class'=>'X3_Log_File',
                'directory'=>'@app:log',
                'filename'=>'mysql-{d-m-Y}.log',
                'category'=>'db'
            ),
            'applog'=>array(
                'class'=>'X3_Log_File',
                'directory'=>'@app:log',
                'filename'=>'application-{d-m-Y}.log',
                'category'=>'application'
            ),
            'mailer'=>array(
                'class'=>'X3_Log_File',
                'directory'=>'@app:log',
                'filename'=>'mailer-{d-m-Y}.log',
                'category'=>'mailer'
            ),            
        )
    )
);
?>

