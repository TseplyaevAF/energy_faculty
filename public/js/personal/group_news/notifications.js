function loadUnreadPostsCount(url) {
    $.ajax({
        type: 'GET',
        url: url,
        success: function (response) {
            $('.postsCount').html(response);
        }
    });
}
