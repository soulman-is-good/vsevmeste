/* 
 * Super admin menu renderer
 */
$(function(){
    //////////////////////
    //x3editable logic
    //////////////////////
    $('[x3editable]').each(function(){
        var attr = $(this).attr('x3editable');
        var self = this;
        if($(this)[0].hasAttribute('contenteditable')){
            var name = $(this).attr('id');
            CKEDITOR.inline(name,{
                on:{
                    blur:function(e){
                        if ( CKEDITOR.instances[name].checkDirty() ){
                            $.loader();
                            $.post('/site/update',{'attr':attr,'value':$(self).html()},function(){$.loader();CKEDITOR.instances[name].resetDirty();}).error(function(){$.loader()});
                        }
                    }
                }
            });
//            $(self).addClass('x3edit').on('blur',function(){
//                if ( CKEDITOR.instances[name].checkDirty() ){
//                    $.loader();
//                    $.post('/site/update',{'attr':attr,'value':$(this).html()},function(){$.loader()}).error(function(){$.loader()});
//                }
//            });
        }else{
            $(this).click(function(){ 
                var self = this;
                $(self).attr('contentEditable',true).addClass('x3edit').one('blur',function(){
                    $.post('/site/update',{'attr':attr,'value':$(this).text()});
                    $(self).attr('contentEditable',false).removeClass('x3edit');
                }).focus();
                return false;
            });
            var a = $('<a />').html('E');
            $(self).css({'position':'relative'}).append(a);
        }
    });
    if(typeof document.getElementById('cr_f') !== 'undefined') {
        $('#cr_f').datepicker({changeMonth:true,changeYear:true,onClose: function( selectedDate ) {console.log(new Date(selectedDate))}});
        $('#cr_t').datepicker({changeMonth:true,changeYear:true});
    }
    
    $('.money-popup').on('click',function(){
        var val = prompt("Введите сумму пополнения (можно отрицательную для снятия)");
        val = parseFloat(val.replace(',', '.'));
        var uid = $(this).data('userid');
        if(val && !isNaN(val)) {
            $.post('/admin/refund',{value:val,uid:uid},function(m){
                if(m === 'OK') {
                    location.reload();
                } else {
                    alert(m);
                }
            });
        }
        return false;
    });
});
