let theme;
getTheme();

function changeTheme() {
    if (theme === 'light') {
        darkTheme();
    } else if (theme === 'dark') {
        lightTheme();
    }
}

function getTheme() {
    $.ajax({
        type: 'GET',
        url: '/get-theme',
        success:function(response){
            theme = response;
            changeTheme();
        },
    })
}

$('.changeTheme').on('click', function () {
    let choiceTheme = theme === 'light' ? 'dark' : 'light';
    $.ajax({
        type: 'PATCH',
        url: '/change-theme',
        data: {
            "_token": $("input[name='_token']").val(),
            "theme": choiceTheme
        },
        success:function(){
            theme = choiceTheme;
            changeTheme();
        },
    });
})

function darkTheme() {
    $('body').css({'background' : 'var(--dark)', 'color': 'var(--light)'});
    $('.container, .modal-content').css({'background' : 'var(--dark)'})
    $('.content-wrapper, .card').css({'background-color' : 'var(--dark)'});
    $('.main-header, .main-sidebar, .main-footer, .table-responsive, table, .tabs__content')
        .css({'background' : 'var(--dark_sidebar)'});
}

function lightTheme() {
    $('body').css({'background-color' : 'var(--light)', 'color': 'var(--dark)'});
    $('.container, .modal-content').css({'background' : 'white'})
    $('.content-wrapper').css({'background-color' : 'var(--content_wrapper_light)'});
    $('.main-header, .main-footer, .table-responsive, table, .tabs__content, .card').css({'background-color': 'white'})
    $('.main-sidebar').css({'background-color': 'var(--light_sidebar)'});
}
