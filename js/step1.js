$(function(){
    $('#tags').tagit({
        availableTags: availableTags,
        allowSpaces: true,
        fieldName: 'tags[]',
        placeholderText:'Введите теги проекта через строчку'
    })
})