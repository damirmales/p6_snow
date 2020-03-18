$(function () {
    $("div.grid-item").hide();
    $("div.grid-item").slice(0, 4).show();
    $("#loadMoreFigBtn").on('click', function (e) {
        e.preventDefault();
        $("div.grid-item:hidden").slice(0, 4).slideDown();

        $('html,body').animate({
            scrollTop: $(this).offset().top
        }, 1500);
    });
});