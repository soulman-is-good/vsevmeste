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
        $('#upl').on('click',function(){
            $('#p-file').click();
        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var img = $('<img />').attr('src', e.target.result);
                    $('#blah').html(img.width(300)).height(200).css({'overflow':'hidden','margin-top':'20px'});
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
        $('#p-file').on('change',function(){
            $('#str').html($(this).val().split('\\').pop().split('/').pop());
            readURL($('#p-file')[0]);
        });
        $('.image-link').each(function(){
            var src = $(this).attr('href');
            var self = this;
            var img = $('<img />').on('load',function(){
                $('#blah').html($(this).width(300)).height(200).css({'overflow':'hidden','margin-top':'20px'});
                $(self).remove();
            }).attr('src', src);
        });
        $('#Project_video').on('change',function(){
            if(typeof $('#blah img')[0] === 'undefined'){
                var id = /youtu[^\/]+\/(.+)/.exec($(this).val()).pop().replace('watch?v=','');
                var img = $('<img />').attr('src', "http://img.youtube.com/vi/"+id+"/hqdefault.jpg");
                $('#blah').html(img.width(300)).height(200).css({'overflow':'hidden','margin-top':'20px'});
            }
        })
        if(typeof document.getElementById('Project_full_content') !== 'undefined' && document.getElementById('Project_full_content') !== null) {
            CKEDITOR.replace( 'Project_full_content',{toolbar : 'Custom',width:570});
        }
        if(typeof $('.slideshow')[0] !== 'undefined'){
            $('.change_link').each(function(i){
                $(this).on('click',function(e){
                    if(!$(this).hasClass('active')){
                        $('.change_link.active').removeClass('active');
                        $(this).addClass('active');
                        var idx = $(this).index();
                        $('.slideshow li:visible').fadeOut();
                        $('.slideshow li').eq(idx).fadeIn();
                        var div = $('.slider-content li:eq('+idx+') .slider_text_cont').clone();
                        $('.slideshow .slider_text_cont').fadeOut(function(){$(this).remove();});
                        $('.slideshow').prepend(div.fadeIn());

                    }
                    return false;
                });
            });
//            $('.slideshow').easySlider();
            $('.slideshow ul').css({'position':'relative','margin':'0'});
            $('.slideshow li').each(function(i){$(this).css({'display':'none','position':'absolute','left':'0','top':'0','z-index':i+1});});
            $('.slideshow li').eq(0).fadeIn();
            $('.slideshow').prepend($('.slider-content').find('li').eq(0).children('div').clone());
        }
        if($('.partners_cont .partner').length > 0){
            if($('.partners_cont .partner').length > 6){
                $('.partners_cont .left_nav').on('click',function(e){
                    e.preventDefault();
                    $('.partners_cont .partner').eq(0).animate({'opacity':0,'zoom':0.1},function(){
                        $('.partners_cont .ppp').append($('.partners_cont .partner').eq(0).css({'opacity':1,'zoom':1}));
                    });
                    return false;
                });
                $('.partners_cont .right_nav').on('click',function(e){
                    e.preventDefault();
                        $('.partners_cont .ppp').prepend($('.partners_cont .partner').last().css({'opacity':0,'zoom':0.1}));
                    $('.partners_cont .partner').eq(0).animate({'opacity':1,'zoom':1},function(){});
                    return false;
                });
            }else {
                $('.partners_cont .left_nav, .partners_cont .right_nav').remove();
            }
        }
});
