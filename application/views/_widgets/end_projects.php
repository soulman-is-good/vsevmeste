<?php
$styles = array('margin-left'=>'35px');
$models = Project::get(array(
    '@condition'=>array(),
    '@with'=>'user_id',
    '@order'=>'end_at DESC',
    '@limit'=>4
));
?>
<div class="main_projects_cont">
    <a href="#"><div class="left_nav"><img src="/images/left_nav.png" alt="" /></div></a>
    <a href="#"><div class="right_nav"><img src="/images/right_nav.png" alt="" /></div></a>
    <div class="title_cont"><img src="/images/end.png" alt="" />Завершающиеся проекты</div>
    <div style="float: right; margin-right: 35px;"><a href="/projects/ending/" class="black_link t16"><b>Посмотреть все</b></a></div>
    <div class="clear"></div>
    <?foreach($models as $model):?>
    <?$this->renderPartial('@views:project:_project_item.php',array('model'=>$model,'styles'=>$styles));?>
    <?$styles=null;endforeach;?>
    <div class="clear"></div>
</div>