<?php
$models = User::get(array(
    '@condition' => array('status'=>'0'),
    '@limit' => 10
));
if($models->count()>0):
?>
<table class="table table-condensed table-hover">
    <caption>Новые пользователи</caption>
    <thead>
        <th>Аватар</th>
        <th>Имя</th>
        <th>Дата регистрации</th>
        <th>Действия</th>
    </thead>
    <tbody>
        <?foreach($models as $model):?>
        <tr>
            <td><img alt="No Image" src="<?=$model->getAvatar('64x64')?>" width="64" height="64" /></td>
            <td><a href="/user/<?=$model->id?>/"><?=$model->fullName?></a></td>
            <td><?=date("d.m.Y H:i:s",$model->created_at)?></td>
            <td>
                <div class="actions">
                <a href="/user/<?=$model->id?>/" class="ajax btn btn-mini btn-info">Просмотреть</a>
                </div>
            </td>
        </tr>
        <?endforeach;?>
    </tbody>
</table>
<?else:?>
<p class="label">Нет новых проектов</p>
<?endif;?>