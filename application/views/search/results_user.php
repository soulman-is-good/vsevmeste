<?php
$limit = min(array($paginator->limit,$models?mysql_num_rows($models):0));
$limit = ceil($limit/2);
?>
<div class="eksk-wnd">
    <div class="head">
        <div class="buttons">
            <?/*<div class="wrapper inline-block"><a class="button inline-block" id="add_admin" href="#admin/add.html"><?=X3::translate('Написать сообщение')?></a></div>*/?>
        </div>
        <h1><?=X3::translate('Результаты поиска');?></h1>
    </div>
    <div class="content">
        <div class="stats"><em>
            <?if($cnt>0):?>
            <?=X3::translate('Найдено');?>: <?=$cnt?>
            <?else:?>
            <?=X3::translate('Ничего не найдено');?>
            <?endif;?></em>
        </div>
        <?if($cnt>0):?>
        <table width="100%" class="search-results">
            <tr><td width="50%" class="fc">
        <table class="admin-list">
            <?$i=1;while($u = mysql_fetch_assoc($models)):
                $model = new User();
                $model->acquire($u);
                ?>
            <tr>
                <td class="ava"><a href="/user/<?=$model->id?>.html"><img src="<?=$model->avatar?>" width="100" alt="" /></a></td>
                <td class="name"><a href="/user/<?=$model->id?>.html"><?=$model->fullname?></a>
                    <?if($model['text']!=''):?>
                    <p><?=nl2br(X3_String::create($model['text'])->carefullCut(512));?></p>
                    <?endif;?>
                </td>
            </tr>
            <?if($i++==$limit) break;endwhile;?>
        </table>
            </td><td width="50%">
        <table class="admin-list">
            <?while($u = mysql_fetch_assoc($models)):
                $model = new User();
                $model->acquire($u);
                ?>
            <tr>
                <td class="ava"><a href="/user/<?=$model->id?>.html"><img src="<?=$model->avatar?>" width="100" alt="" /></a></td>
                <td class="name"><a href="/user/<?=$model->id?>.html"><?=Search::highlight($model->fullname)?></a></td>
            </tr>
            <?endwhile;?>
        </table>                    
            </td></tr>
        </table>
        <?endif;?>
    </div>
    <div class="shadow"><i></i><b></b><em></em></div>
</div>