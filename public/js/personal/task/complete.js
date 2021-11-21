$(document).ready(function () {
    $('.btn-complete').click(function () {
        var res = confirm('Студенты больше не смогут загружать свои решения к этому заданию' + '\n' + 'Закрыть приём работ?');
        if(!res){
            return false;
        }
    });
})