$(document).ready(function() {
    sort = 'date.desc';

    $('body').on('click', '.post-sorting', function() {
        $.ajax({
            'url' : $(this).attr('data-url'),
            'data' : {
                'sort' : $(this).attr('data-sort') + $(this).attr('data-direction'),
                'page' : page,
                'all' : '1'
            },
            'dataType' : 'json',
            'context' : 'this',
            'success' : $.proxy(postSortSuccess, this),
            'cache' : false
        });
        return false;
    });
});

function postSortSuccess(response) {
    if (response.result == "success") {
        sort = $(this).attr('data-sort') + $(this).attr('data-direction');

        $('#left-hotel-menu .leftmenu-options .leftmenu-option-li a').removeClass('leftmenu-option-active');
        $(this).addClass('leftmenu-option-active');
        $(this).parent().parent().parent().hide();

        $('#post-list .items').html($(response.data.items).find('.items').html());
    }
}