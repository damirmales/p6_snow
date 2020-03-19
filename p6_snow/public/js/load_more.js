function initGridItem(limitSlidePerRow) {
    $("div.grid-item").hide();
    $("div.grid-item").slice(0, limitSlidePerRow).show();
}

function loadMore(maxSlice) {
    console.log('loadmore');
    $("div.grid-item:hidden").slice(0, maxSlice).slideDown();

    /*  $("#loadMoreFigBtn").on('click', function (e) {
          e.preventDefault();
          $("div.grid-item:hidden").slice(0, 5).slideDown();

      });*/
}