function loadUnreadPostsCount(url) {
    $.ajax({
        type: 'GET',
        url: url,
        success: function (response) {
            if (response === "0") {
                $('.postsCount').hide();
            } else {
                $('.postsCount').show();
                $('.postsCount').html(response);
            }
        }
    });
}
