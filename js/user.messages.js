$(function(){
    $('.user-message.unread').each(function(){
        var self = $(this);
        var mid = self.data('mid') * 1;
        if(!isNaN(mid) && mid > 0){
            $.get('/user_Message/read/',{id:mid},function(){self.removeClass('unread');});
        }
    });
})

