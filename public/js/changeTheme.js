let theme = $("input[name='theme']").val();

function changeTheme() {
    if (theme === 'dark') {
        darkTheme();
    } else if (theme === 'light') {
        lightTheme();
    }
}

$('.changeTheme').on('click', function () {
    theme = theme === 'light' ? 'dark' : 'light';
    $.ajax({
        type: 'PATCH',
        url: '/change-theme',
        data: {
            "_token": $("input[name='_token']").val(),
            "theme": theme
        },
        success:function(){
            changeTheme();
        },
    });
})

function darkTheme() {
    let darkThemePath = $("input[name='dark_theme_path']").val();
    $("link[id='theme-link']").attr('href', darkThemePath);
    // $('head').append(`<link rel="stylesheet" type="text/css" href="${darkThemePath}">`);
}

function lightTheme() {
    let lightThemePath = $("input[name='light_theme_path']").val();
    $("link[id='theme-link']").attr('href', lightThemePath);
    // $('head').append(`<link rel="stylesheet" type="text/css" href="${lightThemePath}">`);
}
