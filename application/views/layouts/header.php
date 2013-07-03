<!-- Шапка -->
	<div class="top_cont">
		<div class="top">
			<div class="logo"><a href="/" title="На главную"><h2>VSE<span>VMESTE</span></h2></a></div>
			<div class="top_link_cont"><a href="/about-us.html" class="black_link">О VseVmeste</a><div class="top_link_dev"></div></div>
			<div class="top_link_cont"><a href="/enter.html" class="green_link">Регистрация</a><span>&nbsp;/&nbsp;</span><a href="/enter.html" class="black_link">Вход</a><div class="top_link_dev"></div></div>
			<div class="search_cont">
				<div>
					<input type="text" placeholder="Поиск проектов" title="Поиск проектов" class="search_field" />
					<a href="#" title="Искать"><div class="search_button"><img src="/images/search_button.png" alt="" /></div></a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
<?if(count(X3::app()->promo) == 0):?>
	<?=  X3_Widget::run('@views:_widgets:menu.php')?>
<?endif;?>
	</div>
<?if(count(X3::app()->promo) > 0):?>
<!-- Слайдер -->
	<?=  X3_Widget::run('@views:_widgets:promo_slider.php')?>
<!-- Меню -->
	<?=  X3_Widget::run('@views:_widgets:menu.php')?>
<?endif;?>