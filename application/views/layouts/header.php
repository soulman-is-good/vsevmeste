<!-- Шапка -->
	<div class="top_cont">
		<div class="top">
			<div class="logo"><a href="/" title="На главную"><h2>VSE<span>VMESTE</span></h2></a></div>
			<div class="top_link_cont"><a href="/about-us.phtml" class="black_link">О VseVmeste</a><div class="top_link_dev"></div></div>
                        <?if(X3::user()->isGuest()):?>
			<div class="top_link_cont"><a href="/enter.html" class="green_link">Регистрация</a><span>&nbsp;/&nbsp;</span><a href="/enter.html" class="black_link">Вход</a><div class="top_link_dev"></div></div>
                        <?else:?>
			<div class="top_link_cont"><a href="/user/<?=X3::user()->id?>/" class="green_link"><?=X3::user()->fullname?></a><span>&nbsp;/&nbsp;</span><a href="/user/logout.html" class="black_link">Выход</a><div class="top_link_dev"></div></div>
                        <?endif;?>
			<div class="search_cont">
				<div>
                                    <form action="/project/search.html" method="get">
                                        <input name="q" type="text" placeholder="Поиск проектов" value="<?=X3::user()->psearch!=''?X3::user()->psearch:X3::translate('Поиск проектов');?>" class="search_field" />
					<a href="#" onclick="$(this).parent().submit();return false;" title="<?=X3::translate('Искать')?>"><div class="search_button"><img src="/images/search_button.png" alt="" /></div></a>
                                    </form>
				</div>
			</div>
			<div class="clear"></div>
		</div>
<?if(!X3::app()->promo || !$main):?>
	<?=  X3_Widget::run('@views:_widgets:menu.php')?>
<?endif;?>
	</div>
<?if(X3::app()->promo && $main):?>
<!-- Слайдер -->
	<?=  X3_Widget::run('@views:_widgets:promo_slider.php')?>
<!-- Меню -->
	<?=  X3_Widget::run('@views:_widgets:menu.php')?>
<?endif;?>
