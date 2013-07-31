<?php
$promo = Project::get(array(
            '@condition' => array(
                'image' => array('@@' => "image <> '' AND image IS NOT NULL"),
                'status' => '1'
            ),
            '@order' => 'RAND()',
            '@limit' => '5'
        ));
$count = $promo->count();
?>
<div class="slider_cont" style="">
    <div class="slider_left_fade"></div>
    <div class="slider_right_fade"></div>
    <div class="slideshow" style="overflow:hidden;height: 400px;width:980px;margin:0 auto;">
        <ul>
            <? foreach ($promo as $project): ?>
                <li><img src="/uploads/Project/940x400/<?= $project->image ?>" alt="<?= X3_Html::encode($project->title) ?>" /></li>
            <? endforeach; ?>
        </ul>
    </div>
    <div class="slider-content">
        <ul>
            <? foreach ($promo as $project): ?>
                <li>
                    <div class="slider_text_cont">
                        <div class="slider_title"><?= X3_Html::encode($project->title); ?></div>
                        <? if ($project->donate): ?>
                            <div class="project_category" style="margin-bottom: 10px; font-size: 21px;">Благотворительная акция</div>
                        <? endif; ?>
                        <div style="text-shadow:1px 1px 4px #ccc;margin-bottom: 20px; font-size: 16px; font-style: italic; color: #fff;"><b><?=  number_format($project->current_sum,0,' ',' ')?></b>&nbsp;тенге вложили<br /><?=$project->getTimeLeft();?></div>
                        <a href="/<?=$project->name?>-project/"><div class="button">Посмотреть проект</div></a>
                    </div>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
    <div class="slider_pages">
        <?if($count>1):?>
        <?for($i = 0; $i<$count; $i++):?>
        <?if($i==0):?>
        <a href="#" class="change_link active"><div class="slider_page"></div></a>
        <?else:?>
        <a href="#" class="change_link"><div class="slider_page"></div></a>
        <?endif;?>
        <?endfor;?>
        <?endif;?>
    </div>
</div>
<?
/* <div class="slider_cont">
  <div class="slider" style="background: url(/images/02.jpg) no-repeat;">
  <div class="slider_text_cont">
  <div class="slider_title">Пошив модной одежды для малоимущих</div>
  <div class="project_category" style="margin-bottom: 10px; font-size: 21px;">Благотворительная акция</div>
  <div style="margin-bottom: 20px; font-size: 16px; font-style: italic; color: #fff;"><b>24 567</b>&nbsp;тенге вложили<br /><b>24</b>&nbsp;дня осталось</div>
  <a href="#"><div class="button">Посмотреть проект</div></a>
  </div>
  <div class="slider_left_fade"></div>
  <div class="slider_right_fade"></div>
  <div class="slider_pages">
  <a href="#"><div class="slider_page_active"></div></a>
  <a href="#"><div class="slider_page"></div></a>
  <a href="#"><div class="slider_page"></div></a>
  <a href="#"><div class="slider_page"></div></a>
  <a href="#"><div class="slider_page"></div></a>
  </div>
  </div>
  </div> */?>
