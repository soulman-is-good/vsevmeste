<?php
$cats = Category::get(array('@order'=>'weight ASC','@limit'=>'24'));
?>
<!-- Футер -->
	<div class="footer_cont">
		<div class="footer_link_cont">
			<div style="float: left; margin-right: 40px;">
				<div style="margin-bottom: 10px; color: white;"><b>Открывай</b></div>
				<ul class="column">
                                <?foreach($cats as $i=>$cat):?>
					<a href="/projects-<?=$cat->name?>/"><li><?=$cat->title?></li></a>
                                <?if($i>0 && $i%6==0) echo '</ul><ul class="column">'; ?>
                                <?endforeach;?>
				</ul>
			</div>
			<div style="float: left; margin-right: 40px;">
				<div style="margin-bottom: 10px; color: white;"><b>Создавай</b></div>
				<ul class="column">
					<a href="/project/add/"><li>Создать проект</li></a>
				</ul>
			</div>
			<div style="float: left; margin-right: 40px;">
				<div style="margin-bottom: 10px; color: white;"><b>Узнай</b></div>
				<ul class="column">
					<a href="/about-us.phtml"><li>О проекте</li></a>
					<a href="/how-does-it-works.phtml"><li>Как это работает</li></a>
				</ul>
			</div>
			<div style="float: left;height:100px">
                            <a href="https://twitter.com/share" class="twitter-share-button" data-lang="en">Tweet</a><br/><br/>
                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                            <iframe src="https://www.facebook.com/plugins/like.php?href=<?=  urlencode(X3::request()->getBaseUrl().'/'.X3::request()->url)?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=true&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe>
                        </div>
		</div>
		<div class="footer">
			<div <?if(X3::user()->isAdmin()):?>x3editable="SysSettings({name:\"Copyright\"}).value"<?endif;?> style="display: inline-block; margin-right: 40px; color: white;"><?=SysSettings::getValue('Copyright','string','Копирайт','Общие','&copy;&nbsp;Vsevmeste.kz,&nbsp;2013')?></div>
			<div style="display: inline-block; margin-right: 40px;"><a href="/user-agreement.phtml">Условия пользования</a></div>
			<div style="display: inline-block; margin-right: 40px;"><a href="/partner-agreement.phtml">Условия партнерства</a></div>
			<div style="display: inline-block; margin-right: 40px;"><a href="/feedback/">Написать нам</a></div>
			<div class="madeby_cont">
				<a href="http://zuber.kz/" class="madeby_link"><div class="madeby"><div class="madeby_text">Дизайн и<br/> программирование</div><div class="madeby_pic"></div></div></a>
			</div>	
		</div>
	</div>