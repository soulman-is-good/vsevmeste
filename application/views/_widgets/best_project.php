<?php
$styles = array('margin-left'=>'35px');
$left = time()-604800;//left 7 days
$models = Project::get(array(
    '@condition'=>array('@@'=>array('@@'=>'`current_sum`>`needed_sum`/2')),
    '@with'=>array('user_id','city_id'),
    '@order'=>'created_at DESC',
    '@limit'=>1
));
if($models->count() > 0):
    foreach($models as $model):
?>
<div class="title_cont"><img src="/images/good.png" alt="" />Успешный проект</div>
<div class="good_project">
        <div class="good_project_pic"><img src="/images/good_project_pic.jpg" alt="" /></div>
        <div class="good_project_text">
                <h2><?=$model->title?></h2>
                <div class="project_category" style="margin: 10px 0px; font-size: 21px; color: #ffd400;">Благотворительная акция</div>
                <div class="name"><a href="#" class="grey_link"><?=$model->user_id()->fullName?></a></div>
                <div class="city"><img src="/images/location.png" alt="" /><a href="<?=$model->city_id()->id?>" class="grey_link"><?=$model->city_id()->title?></a></div>
                <div class="t16"><i><b><?=number_format($model->current_sum,0,' ',' ')?></b> тенге вложили</i></div>
        </div>
</div>
<div class="good_project_shadow"></div>
<?  endforeach;endif;?>