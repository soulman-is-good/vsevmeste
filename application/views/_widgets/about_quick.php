<?php
$q = SysSettings::getModel('ProjectAboutQuick', 'text', 'Что такое Vsevmeste?');
?>
<div class="quick_look">
    <a class="quick_look-link" href="#"><?=$q->title?></a>
    <div class="quick_look-content"><?=$q->value?></div>
</div>