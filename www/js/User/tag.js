$(document).ready(function() {
    tagPage = 1;
    sort = 'tc.desc';

    $('body').on('click', '.hashtags-table-sorting', function() {
        $.ajax({
            'url' : $(this).attr('data-url'),
            'data' : {
                'sort' : $(this).attr('data-sort') + $(this).attr('data-direction'),
                'page' : tagPage,
                'tagName' : $('input[name="tagName"]').val(),
                'all' : '1'
            },
            'dataType' : 'json',
            'context' : 'this',
            'success' : $.proxy(tagSortSuccess, this),
            'cache' : false
        });
        return false;
    });
});

function nextTagSuccess(response) {
    if (response.result == "success") {
        tagPage++;
        var tags = $(response.data.tags);
        tags.find('.items .hashtags-table-row').attr('style','display:none');
        $('#tag-list .items').append(tags.find('.items').html());
        if (response.data.isLastPage) {
            $(this).parents('.hashtags-more').hide();
        }

        $('#tag-list .items .hashtags-table-row:hidden').fadeIn("slow");
    }
}

function tagSortSuccess(response) {
    if (response.result == "success") {
        sort = $(this).attr('data-sort') + $(this).attr('data-direction');
        $('.hashtags-table-sorting').removeClass("hashtags-table-sorting_active hashtags-table-sorting_active_desc");
        if (!$(this).attr('data-direction')) {
            $(this).addClass('hashtags-table-sorting_active');
            $(this).attr('data-direction', '.desc');
        } else {
            $(this).addClass('hashtags-table-sorting_active_desc');
            $(this).attr('data-direction', '');
        }
        $('.hashtags-table-sorting').not('[class*="hashtags-table-sorting_active"]').attr('data-direction', '');
        $('#tag-list .items').html($(response.data.tags).find('.items').html());
    }
}

function tagSearchSuccess(response) {
    if (response.result == "success") {
        $('#tag-list .items').html($(response.data.tags).find('.items').html());
    }
}