$(document).ready(function() {
    page = 1;

    if (typeof window.pagerOptions != 'undefined') {
        window.pagerOptions.ajaxOptions.complete = function()
        {
            window.pagerOptions.xhr = null;
            $('#' + window.pagerOptions.id).find('.items-loader').hide();
        }
        window.pagerOptions.ajaxOptions.beforeSend = function(xhr)
        {
            window.pagerOptions.xhr = xhr;
            $('#' + window.pagerOptions.id).find('.items-loader').show();
        }
    }

    function getItemsYOffset()
    {
        var bl = $('#' + window.pagerOptions.id);
        return bl.outerHeight() + bl.offset().top;
    }

    window.onscroll = function() {
        if (!window.pagerOptions.isLastPage)
        {
            var scrolled = (window.pageYOffset || document.documentElement.scrollTop) + $(window).height();
            if (getItemsYOffset() <= scrolled + 300)
            {
                if (!window.pagerOptions.xhr)
                {
                    $.ajax(window.pagerOptions.ajaxOptions);
                }
            }
        }
    }


});

function nextItemsSuccess(response) {
    
    if (response.result == "success") {
    
        window.page++;
        $('.items').append($(response.data.items).find('.items').html());
        if ($("input[id*='Photo_file_']").length) {
            $("input[id*='Photo_file_']").MultiFile({
                'onFileAppend': photoFileAppend,
                'accept': 'jpg|png|gif|jpeg|bmp',
                'STRING': {
                    'denied': 'Файлы, с расширением $ext, загружать запрещено', 'duplicate': 'Вы уже выбрали этот файл:\n$file'
                }
            });
        }
        if (response.data.isLastPage) {
            //$(this).parents('.comment-item').hide();
            window.pagerOptions.isLastPage = true;
        }

        /*var newFirstItemId = $(response.data.items).find('.items .comment-item:first').attr('id');
        $('body').animate({
            scrollTop: $("#" + newFirstItemId).offset().top - 70
        }, 1000);*/
    }
}
