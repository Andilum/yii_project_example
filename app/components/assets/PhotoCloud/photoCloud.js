$(document).ready(function() {
    $('body').on('click', '.rightbar .rightbar-pics a', function() {
        var currentFileName = $(this).find('img').attr('src').split('_')[0] + '.' + $(this).find('img').attr('src').split('.')[1];
        var imageId = $(this).attr('data-img-id')
        $.ajax({
            'dataType': 'json',
            'context': 'this',
            'success': $.proxy(photoCloudInfoSuccess, this),
            'url': '/photo/' + imageId + '/fullSizeInfo',
            'cache': false
        });

        $('.post-pp .post-pp-gallery').html('');
        $('.rightbar .rightbar-pics a img').each(function() {
            var imageId = $(this).parent().attr('data-img-id');
            var fileUrl = $(this).attr('src').split('_')[0] + '.' + $(this).attr('src').split('.')[1];
            $('.post-pp .post-pp-gallery').append('<li class="post-pp-gallery-i" style="display: none;" data-img-id="' + imageId + '"><img src="/i/preloader.gif" /><img src="' + fileUrl + '" style="display: none;" onload="$(this).siblings().hide();$(this).show();" /></li>');
        });
        $('.post-pp .post-pp-gallery img[src="' + currentFileName + '"]').parent().show();

        $('body').on('click', '.post-pp-gallery-nav a', function() {
            $('.post-pp .preloader').show();
            $('.post-pp .post-pp-r-head, .post-pp .pp-custom-scroll').css('opacity', '0.4');
            var imageId = $('.post-pp .post-pp-gallery li:visible').attr('data-img-id');
            $.ajax({
                'dataType' : 'json',
                'context' : 'this',
                'success' : $.proxy(photoCloudInfoSuccess, this),
                'url' : '/photo/' + imageId + '/fullSizeInfo',
                'cache' : false
            });
        });

        return false;
    });
});

function photoCloudInfoSuccess(response) {
    if (response.result == "success") {
        addSidebarInfoInPhotoPopup(response.data);
        $('.overlay').show();
        $('.post-pp').show();
    }
    $('.post-pp .preloader').hide();
    $('.post-pp .post-pp-r-head, .post-pp .pp-custom-scroll').css('opacity', '1');
}