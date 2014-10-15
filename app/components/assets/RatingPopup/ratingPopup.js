$(document).ready(function() {
    $('body').on('click', '#rating-popup a.popup-score-close', function() {
        if (!$('#post-form input[name*="UserRating"]').length) {
            $('#post-form-add-rating').removeClass('active');
        }
        $('#rating-popup').hide();
        return false;
    });

    $('body').on('click', '#rating-popup ul.popup-score-menu li a', function() {
        var categoryId = $(this).attr('data-category');
        if (categoryId == '0') {
            $('#rating-popup ul li.popup-content-item').show();
        } else {
            $('#rating-popup ul li.popup-content-item').hide();
            $('#rating-popup ul li.popup-content-item[data-category="' + categoryId + '"]').show();
        }
        return false;
    });

    $('body').on('click', '#post-form-add-rating', function() {
        if ($(this).hasClass('active')) {
            if (!$('#post-form input[name*="UserRating"]').length) {
                $(this).removeClass('active');
            } else {
                $('#rating-popup').show();
            }
        } else {
            $(this).addClass('active');
            $('#rating-popup').show();
        }
        return false;
    });

    $('body').on('click', '#rating-popup .popup-score-select ul li', function() {
        var serviceId = $('#rating-popup ul li.popup-content-item').eq(popup_score).attr('data-service');
        var rating = $(this).attr('data-rating-name');
        var ratingId = $(this).attr('data-rating');
        $('#rating-popup ul li.popup-content-item').eq(popup_score).addClass($(this).find('span').attr('class'));
        if (parseInt(rating)) {
            $('#rating-popup ul li.popup-content-item .popup-score-number').eq(popup_score).html(rating + ' ' + declOfNum(parseInt(rating), ['балл', 'балла', 'баллов']));
            $('#rating-popup ul li.popup-content-item .popup-score-object-points2').eq(popup_score).html($(this).clone().children().remove().end().text());

            if (!$('#rating-popup input[name="UserRating[' + serviceId + '][service_id]"]').length) {
                $('#rating-popup').append('<input type="hidden" name="UserRating[' + serviceId + '][service_id]" />');
            }
            $('#rating-popup input[name="UserRating[' + serviceId + '][service_id]"]').val(serviceId);

            if (!$('#rating-popup input[name="UserRating[' + serviceId + '][rating_id]"]').length) {
                $('#rating-popup').append('<input type="hidden" name="UserRating[' + serviceId + '][rating_id]" />');
            }
            $('#rating-popup input[name="UserRating[' + serviceId + '][rating_id]"]').val(ratingId);
        } else {
            $('#rating-popup input[name="UserRating[' + serviceId + '][service_id]"]').remove();
            $('#rating-popup input[name="UserRating[' + serviceId + '][rating_id]"]').remove();

            $('#rating-popup ul li.popup-content-item .popup-score-number').eq(popup_score).html($(this).clone().children().remove().end().text());
            $('#rating-popup ul li.popup-content-item .popup-score-object-points2').eq(popup_score).html('Оцените сервис');
        }

        $('#rating-popup .popup-score-overlay').hide();
        $('#rating-popup .popup-score-select-top-points').removeClass(class_old);

        $(this).parents('.popup-score-select').hide();
        $('#rating-popup .popup-score-content').removeClass('popup-score-content_blocked');
    });

    $('body').on('click', '#rating-popup .popup-score-reset', function() {
        resetRating();
        return false;
    });

    $('body').on('click', '#post-form .b-feedback-score-close', function() {
        $('#post-form-add-rating').removeClass('active');
        resetRating();
        return false;
    });

    $('body').on('click', '#rating-popup .popup-score-send-btn', function() {
        $('#post-form input[name*="UserRating"]').remove();
        $('#post-form').append($('#rating-popup input[name*="UserRating"]').clone());

        if ($('#post-form input[name*="UserRating"]').length) {
            var text = '';
            var i = 0;
            $('#post-form input[name*="UserRating"][name*="service_id"]').each(function() {
                if (i >= 2) {
                    return false;
                }
                var serviceId = $(this).val();
                var ratingId = $('#post-form input[name="UserRating[' + serviceId + '][rating_id]"]').val();
                var serviceName = $('#rating-popup ul li.popup-content-item[data-service="' + serviceId + '"]').find('.popup-score-object-name').text();
                var ratingName = $('#rating-popup .popup-score-select ul li[data-rating="' + ratingId + '"]').attr('data-rating-name');
                text += serviceName + ' - ' + ratingName + '; ';
                i++;
            });
            $('#post-form .b-feedback-score span').html(text);

            var ratingCount = $('#post-form input[name*="UserRating"][name*="service_id"]').length - 2;
            var more = '';
            if (ratingCount > 0) {
                more = 'еще ' + ratingCount + ' ' + declOfNum(ratingCount, ['оценка', 'оценки', 'оценок']);
            }
            $('#post-form .b-feedback-score .b-feedback-score-more').html(more);
            $('#post-form .b-feedback-score').show();
        } else {
            $('#post-form-add-rating').removeClass('active');
        }

        $('#rating-popup').hide();
    });
});

function resetRating() {
    $('#post-form .b-feedback-score').hide();
    $('#post-form .b-feedback-score').find('span, a.b-feedback-score-more').html('');

    $('#rating-popup ul li.popup-content-item').attr('class', 'popup-content-item');
    $('#rating-popup .popup-score-select .popup-score-select-top-points').attr('class', 'popup-score-select-top-points');

    $('#rating-popup ul li.popup-content-item .popup-score-number').html($('#rating-popup .popup-score-select ul li:last').text());
    $('#rating-popup ul li.popup-content-item .popup-score-object-points2').html('Оцените сервис');

    $('#post-form input[name*="UserRating"]').remove();
    $('#rating-popup input[name*="UserRating"]').remove();
}
