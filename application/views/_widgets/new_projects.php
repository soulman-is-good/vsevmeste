<?php
$styles = array('margin-left'=>'30px');
$left = time()-604800;//left 7 days
$models = Project::get(array(
    '@condition'=>array('project.created_at'=>array('>'=>"$left"),'project.status'=>'1'),
    '@with'=>array('user_id','city_id'),
    '@order'=>'created_at DESC',
    '@limit'=>10
));
if(($c=$models->count())>0):
?>
<div class="main_projects_cont" <?=($c>4)?'style="overflow:hidden"':''?>>
    <?if($c>4):?>
    <a href="#"><div class="left_nav"><img src="/images/left_nav.png" alt="" /></div></a>
    <a href="#"><div class="right_nav"><img src="/images/right_nav.png" alt="" /></div></a>
    <?endif;?>
    <div class="title_cont"><img src="/images/black_rocket.png" alt="" />Новые на этой неделе</div>
    <div style="float: right; margin-right: 35px;"><a href="/projects/ending/" class="black_link t16"><b>Посмотреть все</b></a></div>
    <div class="clear"></div>
    <?foreach($models as $model):?>
    <?=$this->renderPartial('@views:project:_project_item.php',array('model'=>$model,'styles'=>$styles));?>
    <?$styles=null;endforeach;?>
    <div class="clear"></div>
</div>
<?endif;?>
