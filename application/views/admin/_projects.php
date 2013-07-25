<?php
$models = Project::get(array(
    '@condition'=>array('status'=>'0')
));
if($models->count()>0):
?>
<table class="table table-condensed table-hover">
    <caption>Проекты на рассмотрении</caption>
    <thead>
        <th>Изображение</th>
        <th>Название проекта</th>
        <th>Сумма</th>
        <th>Действия</th>
    </thead>
    <tbody>
        <?foreach($models as $model):?>
        <tr>
            <td><img alt="No Image" src="/uploads/Project/64x64/<?=$model->image?>" width="64" height="64" /></td>
            <td><a href="/<?=$model->name?>-project.html"><?=X3_Html::encode($model->title)?></a></td>
            <td><?=number_format($model->needed_sum,2,'.',' ')?></td>
            <td>
                <div class="actions">
                <a href="/admin/update/module/Project/id/<?=$model->id?>.html?field=status&value=1" class="ajax btn btn-mini btn-warning">Допустить</a>
                <a href="/admin/delete/module/Project/id/<?=$model->id?>.html" class="ajax btn btn-mini btn-danger">Удалить</a>
                </div>
            </td>
        </tr>
        <?endforeach;?>
    </tbody>
</table>
<?else:?>
<p class="label">Нет новых проектов</p>
<?endif;?>