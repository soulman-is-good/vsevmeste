<?php
return
array(
    //Common
    '/^\/epay\/([0-9]+)(.*)$/' => array('/site/kkb/id/$1$2', true),
    '/^\/wallet\/([0-9]+)(.*)$/' => array('/site/wallet/id/$1$2', true),
    '/^\/payqiwi\/([0-9]+)(.*)$/' => array('/site/qiwi/id/$1$2', true),
    '/^\/restore\/(.*)$/' => array('/site/restore/key/$1', true),
    '/^\/restore\/$/' => array('/site/restore', true),
    '/^\/enter\.html$/' => array('/user/login', true), // first element - module/action, second - if either way then redirect
    '/^\/sitemap\.xml$/' => array('/site/map/type/xml', true), // first element - module/action, second - if either way then redirect
    '/^\/sitemap.html$/' => array('/site/map/type/html'), 
    '/^\/sitemap\/(.*)$/' => array('/site/map/type/html$1'), 
    '/^\/login\/(.*)$/' => array('/admin/index/name/$1', true),
    '/^\/logout(.*)$/' => array('/user/logout$1', true),
    '/^\/feedback\.html$/' => array('/site/feedback.html', true),
    '/^\/contacts\.html$/' => array('/site/contacts.html', true),
    '/^\/unsubscribe\/(.*)$/'=>array('/subscribe/approve/unkey/$1',true),
    //Video/Photos
    '/^\/video\/([0-9]+)(.*)$/' => array('/video/show/id/$1$2', true),
    //Page
    '/^\/(.+)\.phtml$/' => array('/page/show/name/$1', true),
    //Articles/News
    '/^\/news\/([0-9]+)(.*)$/' => array('/news/show/id/$1$2', true),
    //Projects
//    '/^\/project\/add\/step(.+)$/' => array('/project/step$1', true),
    '/^\/partner\/confirm\/(.*)$/' => array('/project/partner/code/$1', true),
    '/^\/(.+)\-project\/i([0-9]+).html$/' => array('/project/invest/name/$1/id/$2', true),
    '/^\/(.+)\-project\/invest.html(.*)$/' => array('/project/invest/name/$1$2', true),
    '/^\/(.+)\-project\/investments.html(.*)$/' => array('/project/investments/name/$1$2', true),
    '/^\/(.+)\-project\/comments.html(.*)$/' => array('/project/comments/name/$1$2', true),
    '/^\/(.+)\-project\/events.html(.*)$/' => array('/project/events/name/$1$2', true),
    '/^\/(.+)\-project.*$/' => array('/project/show/name/$1', true),
    '/^\/project\/city\/([0-9]+)(.*)$/' => array('/project/city/id/$1$2', true),
    '/^\/project\/partner\/([0-9]+)(.*)$/' => array('/project/partner/id/$1$2', true),
    '/^\/projects\-([^\/]+)\/$/' => array('/project/index/category/$1', true),
    '/^\/projects\-(.+)\/(.+)$/' => array('/project/index/category/$1/sort/$2', true),
    '/^\/projects\/(.*)$/' => array('/project/index/sort/$1', true),
    
    
    '/^\/user\/login.html$/' => array('/user/login.html', true),
    //'/^\/user\/logout.html$/' => array('/user/login.html', true),
    '/^\/user\/([0-9]+)?\/funds(.*)$/' => array('/user/funds/id/$1$2', true),
    '/^\/user\/([0-9]+)?\/invested(.+)$/' => array('/user/invested/id/$1$2', true),
    '/^\/user\/([0-9]+)?\/investments(.+)$/' => array('/user/investments/id/$1$2', true),
    '/^\/user\/([0-9]+)?\/projects(.+)$/' => array('/user/projects/id/$1$2', true),
    '/^\/user\/([0-9]+)?\/messages(.+)$/' => array('/user/messages/id/$1$2', true),
    '/^\/user\/([0-9]+)\/$/' => array('/user/index/id/$1', true),
    '/^\/admins(.*)$/' => array('/user/admins/$1', true),
)
?>
