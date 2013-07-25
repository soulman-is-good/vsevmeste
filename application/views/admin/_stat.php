<?php
$projects = Project::num_rows();
$users = User::num_rows(array('ispartner'=>'0'));
$partners = User::num_rows(array('ispartner'=>'1'));
?>
<table class="table table-condensed table-hover">
    <caption>Статистика на сайте</caption>
    <thead>
        <th>Пользователей</th>
        <th>Проектов</th>
        <th>Партнеров</th>
    </thead>
    <tbody>
        <tr>
            <td><?=$users?></td>
            <td><?=$projects?></td>
            <td><?=$partners?></td>
        </tr>
    </tbody>
</table>