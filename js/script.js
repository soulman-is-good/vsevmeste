$(document).ready(function(){
	$('.tabs_cont .tab').click(function(){
		var index = $('.tabs_cont .tab').index(this);
		$('.tabs_cont .tab').removeClass('active_tab');
		$(this).addClass('active_tab');
		$(this).parents('.main_product').children('.main_text_cont').hide();
		$(this).parents('.main_product').children('.main_text_cont:eq('+index+')').show();
	})
})