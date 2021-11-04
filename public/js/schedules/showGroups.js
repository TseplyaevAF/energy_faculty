$(document).ready(function () {
    $('.js-schedule-chair-link').on('click', '.extremum-click', function() {
		$(this).toggleClass('red').siblings('.extremum-slide').slideToggle(0);
	});
});


$chairLink.on('click', function (e) {
    e.preventDefault();
    var $this = $(this);
    var id = $this.data('id');
});