$(document).ready(function() {
    page = 1;
    sort = 'tc.desc';

    $('body').on('click', '.searchresults-filter-a', function() {
        $.ajax({
            'url' : $(this).attr('data-url'),
            'data' : {
                'sort' : $(this).attr('data-sort') + $(this).attr('data-direction'),
                'page' : page,
                'tagName' : $('input[name="tagName"]').val(),
                'all' : '1'
            },
            'dataType' : 'json',
            'context' : 'this',
            'success' : $.proxy(itemSortSuccess, this),
            'cache' : false
        });
        return false;
    });
});

function nextItemSuccess(response) {
    if (response.result == "success") {
        page++;
        var items = $(response.data.items);
        items.find('.items .searchresults-rating-i').attr('style','display:none');
        $('.items').append(items.find('.items').html());
        if (response.data.isLastPage) {
            $(this).parents('.searchresults-rating-more').hide();
        }

        $('.items .searchresults-rating-i:hidden').fadeIn("slow");
    }
}

function itemSortSuccess(response) {
    if (response.result == "success") {
        sort = $(this).attr('data-sort') + $(this).attr('data-direction');
        $('.searchresults-filter-a').removeClass("searchresults-filter-a_up searchresults-filter-a_down");
        if (!$(this).attr('data-direction')) {
            $(this).addClass('searchresults-filter-a_up');
            $(this).attr('data-direction', '.desc');
        } else {
            $(this).addClass('searchresults-filter-a_down');
            $(this).attr('data-direction', '');
        }
        $('.searchresults-filter-a').not('[class*="searchresults-filter-a_down"]').attr('data-direction', '.desc');
        $('.items').html($(response.data.items).find('.items').html());
    }
}

function itemSearchSuccess(response) {
    if (response.result == "success") {
        $('.items').html($(response.data.items).find('.items').html());
        if (response.data.isLastPage) {
            $('.searchresults-rating-more').hide();
        } else {
            $('.searchresults-rating-more').show();
        }
    }
}