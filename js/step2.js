$(function(){
    $('#Project_end_at').css({'position': 'relative', 'z-index': '100000','width':'520px','margin-right':'10px'})
            .datepicker({showOn:'button', buttonImage: "/images/calendar.png", buttonImageOnly:true});
    function add_interest(){
        var interest = $('#interest-tmpl').html();
        interest = interest.replace(/\{index\}/g,interest_index);
        interest = $(interest);
        if(interest_index === 0)
            interest.find('.remove-interest').remove();
        interest.find('#Project_Interest_'+interest_index+'_deliver_at').css({'position': 'relative', 'z-index': '100000'})
                .datepicker({showOn:'button', buttonImage: "/images/calendar.png", buttonImageOnly:true});
        interest.find('').datepicker();
        $('#interests').append(interest);
        interest_index++;
        interest_count++;
    }
    $('.add-interest').live('click',function(){
        add_interest();
    });
    $('.remove-interest').live('click',function(){
        if(confirm('Вы уверены?')){
            $(this).parent().parent().remove();
            interest_count--;
        }
    });
    add_interest();
})