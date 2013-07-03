$(document).ready(function(){
	$('.tabs_cont .tab').click(function(){
		var index = $('.tabs_cont .tab').index(this);
		$('.tabs_cont .tab').removeClass('active_tab');
		$(this).addClass('active_tab');
		$(this).parents('.main_product').children('.main_text_cont').hide();
		$(this).parents('.main_product').children('.main_text_cont:eq('+index+')').show();
	});
        $('.main_projects_cont').each(function(){
            var cont = $(this);
            var max_height = 0;
            var gen_height = 0;
            $(this).find('.project_title').each(function(){
                if($(this).height() > max_height) {
                    max_height = $(this).height();
                }
            }).height(max_height);
            gen_height += max_height;
            max_height = 0;
            $(this).find('.project_text').each(function(){
                if($(this).height() > max_height) {
                    max_height = $(this).height();
                }
            }).height(max_height);
            if($(this).find('.project_cont').length > 4) {
                $(this).height($(this).children('.project_cont').height() + 50);
                $('.right_nav').click(function(e){
                    cont.find('.project_cont').eq(0).animate({'margin-left':'-230px',opacity:0},function(){$(this).insertAfter(cont.find('.project_cont').last()).css({'margin-left':0,'opacity':1});});
                    cont.find('.project_cont').eq(1).animate({'margin-left':'30px'});
                    e.stopPropagation();
                    e.preventDefault();
                    return false;
                });
                $('.left_nav').click(function(e){
                    var ea = cont.find('.project_cont').last();
                    ea.css({'margin-left':'-230px','opacity':0});
                    ea.insertBefore(cont.find('.project_cont').eq(0).animate({'margin-left':0}));
                    ea.animate({'margin-left':'30px',opacity:1},function(){});
                    e.stopPropagation();
                    e.preventDefault();
                    return false;
                });
            }
            $(this).animate({'opacity':1});
        });
        $('.admin-links a').each(function(){
            $(this).on('click',function(){
                var href = $(this).attr('href');
                $.get(href);
                $(this).parent().parent().animate({zoom:0.1,opacity:0.1},function(){$(this).remove();}).next('.hr').fadeOut();
                return false;
            });
        });
});