function initGridItem(limitSlidePerRow) {
    $("div.grid-item").hide();
    $("div.grid-item").slice(0, limitSlidePerRow).show();

    $("div.grid-item-photo").hide();
    $("div.grid-item-photo").slice(0, limitSlidePerRow).show();

    $("div.grid-item-video").hide();
    $("div.grid-item-video").slice(0, limitSlidePerRow).show();
}

function loadMore(maxSlice, item) {

    $("div." + item + ":hidden").slice(0, maxSlice).slideDown();

}