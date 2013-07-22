<!-- Футер -->
	<div class="footer_cont">
		<div class="footer">
			<div <?if(X3::user()->isAdmin()):?>x3editable="SysSettings({name:\"Copyright\"}).value"<?endif;?> style="display: inline-block; margin-right: 40px; color: white;"><?=SysSettings::getValue('Copyright','string','Копирайт','Общие','&copy;&nbsp;Vsevmeste.kz,&nbsp;2013')?></div>
			<div class="madeby_cont">
				<a href="#" class="madeby_link"><div class="madeby"><div class="madeby_text">Шаблон<br />интернет-магазина</div><div class="madeby_pic"></div></div></a>
			</div>	
		</div>
	</div>