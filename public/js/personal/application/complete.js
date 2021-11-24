$(document).ready(function () {
    $('.btn-accept').click(function () {
        var res = confirm('Принять заявку?');
        if(!res){
            return false;
        }
    });
    $('.btn-reject').click(function () {
        var res = confirm('Отклонить заявку?');
        if(!res){
            return false;
        }
    });
})