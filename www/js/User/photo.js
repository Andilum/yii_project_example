$(document).ready(function() {
    page = 1;
});

function nextPhotoSuccess(response) {
    if (response.result == "success") {
        page++;
        var photos = $(response.data.photos);
        photos.find('.items .userphoto-list-item').attr('style','display:none');
        $('#photo-list .items').append(photos.find('.items').html());
        if (response.data.isLastPage) {
            $(this).parents('.userphoto-more').hide();
        }

        $('#photo-list .items .userphoto-list-item:hidden').fadeIn("slow");

        var newFirstItemId = photos.find('.items .userphoto-list-item:first').attr('id');
        $('body').animate({
            scrollTop: $("#" + newFirstItemId).offset().top - 70
        }, 1000);
    }
}

function photoSearchSuccess(response) {
    if (response.result == "success") {
        $('#photo-list .items').html($(response.data.photos).find('.items').html());
    }
}