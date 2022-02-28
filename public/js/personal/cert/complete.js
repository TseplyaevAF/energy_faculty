$(document).ready(function () {
    $('.btn-complete').click(function () {
        var res = confirm('Пожалуйста, проверьте, что все данные введены корректно' + '\n' + 'Отправить заявку?');
        if(!res){
            return false;
        }
    });
})
