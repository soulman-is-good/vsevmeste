<?php
/* @var $partners User[] */
$parnters = User::get(array(
    'select' => array('id','image','name','surname'),
    'condition'=>array(
        'ispartner'=>'1',
        'status'=>'1'
    ),
    '@limit'=>'20',
    '@order'=>'RAND()'
));
if($parnters->count()>0):
?>
<div class="title_cont"><img src="/images/good.png" alt="" /><?=X3::translate('Партнеры');?></div>
<div class="main_projects_cont partners_cont">
    <a href="#"><div class="left_nav"><img src="/images/left_nav.png" alt="" /></div></a>
    <a href="#"><div class="right_nav"><img src="/images/right_nav.png" alt="" /></div></a>
<?foreach($parnters as $i=>$user):?>
    <div class="partner" <?if($i==0):?>style="margin-left: 35px;"<?endif;?>><a href="/user/<?=$user->id?>/" title="<?=$user->fullName?>"><img src="<?=$user->avatar?>" alt="" /></a></div>
<?endforeach;?>
</div>
<?endif;?>