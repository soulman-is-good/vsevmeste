<?=$this->renderPartial('_item_head',array('model'=>$model));?>
<div class="body">
    <div class="tabs">
        <ul>
            <li class="active">Проект</li>
            <li><a href="/<?=$model->name?>-project/events.html">События <i>13</i></a></li>
            <li><a href="/<?=$model->name?>-project/comments.html">Комментарии <i>13</i></a></li>
            <li><a href="/<?=$model->name?>-project/gallery.html">Вложения <i>13</i></a></li>
        </ul>
    </div>
    <div class="clearfix" style="height:30px;">&nbsp;</div>
    <div class="right-bar item-show-bar">
        <div class="price"><span><?=  number_format($model->current_sum,0,'.',' ')?></span> тенге</div>
    </div>
    <div class="item-body">
        <img width="100%" class="logo" src="/images/03.jpg" alt="" />
    </div>
    <div class="clear">&nbps;</div>
</div>