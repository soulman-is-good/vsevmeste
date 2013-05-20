<!-- Футер -->
	<div class="footer_cont">
		<div class="footer_link_cont">
			<div style="float: left; margin-right: 40px;">
				<div style="margin-bottom: 10px; color: white;"><b>Discover</b></div>
				<ul class="column">
					<a href="#"><li>Art</li></a>
					<a href="#"><li>Comics</li></a>
					<a href="#"><li>Dance</li></a>
					<a href="#"><li>Design</li></a>
					<a href="#"><li>Fashion</li></a>
					<a href="#"><li>Film & Video</li></a>
				</ul>
				<ul class="column">
					<a href="#"><li>Games</li></a>
					<a href="#"><li>Music</li></a>
					<a href="#"><li>Photography</li></a>
					<a href="#"><li>Publishing</li></a>
					<a href="#"><li>Technology</li></a>
					<a href="#"><li>Theater</li></a>
				</ul>
				<ul class="column">
					<a href="#"><li>Games</li></a>
					<a href="#"><li>Music</li></a>
					<a href="#"><li>Photography</li></a>
					<a href="#"><li>Publishing</li></a>
					<a href="#"><li>Technology</li></a>
					<a href="#"><li>Theater</li></a>
				</ul>
			</div>
			<div style="float: left; margin-right: 40px;">
				<div style="margin-bottom: 10px; color: white;"><b>Create</b></div>
				<ul class="column">
					<a href="#"><li>Art</li></a>
					<a href="#"><li>Comics</li></a>
					<a href="#"><li>Dance</li></a>
					<a href="#"><li>Design</li></a>
					<a href="#"><li>Fashion</li></a>
					<a href="#"><li>Film & Video</li></a>
				</ul>
			</div>
			<div style="float: left; margin-right: 40px;">
				<div style="margin-bottom: 10px; color: white;"><b>About</b></div>
				<ul class="column">
					<a href="#"><li>Art</li></a>
					<a href="#"><li>Comics</li></a>
					<a href="#"><li>Dance</li></a>
					<a href="#"><li>Design</li></a>
					<a href="#"><li>Fashion</li></a>
					<a href="#"><li>Film & Video</li></a>
				</ul>
			</div>
			<div style="float: left;"><img src="images/follow.png" alt="" /></div>
		</div>
		<div class="footer">
			<div <?if(X3::user()->isAdmin()):?>x3editable="SysSettings({name:\"Copyright\"}).value"<?endif;?> style="display: inline-block; margin-right: 40px; color: white;"><?=SysSettings::getValue('Copyright','string','Копирайт','Общие','&copy;&nbsp;Vsevmeste.kz,&nbsp;2013')?></div>
			<div style="display: inline-block; margin-right: 40px;"><a href="#">Условия пользования</a></div>
			<div style="display: inline-block; margin-right: 40px;"><a href="#">Условия партнерства</a></div>
			<div style="display: inline-block; margin-right: 40px;"><a href="#">Написать нам</a></div>
			<div class="madeby_cont">
				<a href="#" class="madeby_link"><div class="madeby"><div class="madeby_text">Шаблон<br />интернет-магазина</div><div class="madeby_pic"></div></div></a>
			</div>	
		</div>
	</div>