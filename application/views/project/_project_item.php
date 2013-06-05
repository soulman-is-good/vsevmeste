<?php
if (!isset($styles))
    $styles = "";
if(is_array($styles)){
    array_walk($styles,function(&$item, $key){return $item = $key . ":" . $item;});
    $styles = implode(';', $styles);
}
    
?>
<div class="project_cont" style="<?=$styles?>">
    <div class="green_bg">
        <div class="white_bg">
            <div class="project_pic"><a href="#"><img src="/images/03.jpg" alt="" /></a></div>
            <div class="project_text_cont">
                <div style="margin-bottom: 10px;"><a href="#" class="green_link t16"><b><?=$model->title?></b></a></div>
                <div class="name"><a href="#" class="grey_link">Константин Константинов</a></div>
                <div class="city"><img src="/images/location.png" alt="" /><a href="#" class="grey_link">Алматы</a></div>
                <div class="project_text"><p>We just finished a 22-track record over 3 years without help from a label. Help us make Endless Fantasy more than just an album!</p></div>
                <div style="float: left;"><b>25 446</b> тенге</div>
                <div style="float: right;"><b>26</b> %</div>
                <div class="clear"></div>
                <div class="finish_cont">
                    <div class="finish" style="width: 26%;"></div>
                </div>
                <i><b>25</b> дней осталось</i><br />
                <i><b>356</b> вложений</i>
            </div>
        </div>
    </div>
    <div class="project_shadow"></div>
</div>