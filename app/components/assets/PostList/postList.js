$(document).ready(function() {
    $('body').on('click', '.comment-bottom-like.no-comments', function() {
        $(this).parents('.comment-item').find('.b-feedback').toggle();
        return false;
    });
    $('body').on('click', '.comment-bottom a[id*="post-like-"]', function() {
        $.ajax({
            'data': {
                'ownerId': $(this).attr("data-owner-id")
            },
            'dataType': 'json',
            'context': 'this',
            'success': $.proxy(likeSuccess, this),
            'url': '/like/create',
            'cache': false
        });
        return false;
    });
    $('body').on('click', '.comment-td a[id*="comment-prev-"]', function() {
        $.ajax({
            'data': {
                'postId': $(this).attr("data-post-id")
            },
            'dataType': 'json',
            'context': 'this',
            'success': $.proxy(prevCommentsSuccess, this),
            'url': '/comment/list',
            'cache': false
        });
        return false;
    });
    $('body').on('click', '.b-feedback a[id*="comment-submit-"], .post-pp a[id*="comment-submit-"]', function() {
        var commentText = $(this).parents('form').find('.b-feedback-txtarea').clone();
        commentText.find('img').each(function() {
            $(this).replaceWith($(this).attr('data-code'));
        });
        $(this).parents('form').find('textarea[name="Comment[text]"]').val(commentText.html());
        $.ajax({
            'type': 'POST',
            'data': new FormData($(this).closest("form")[0]),
            'dataType': 'json',
            'context': 'this',
            'success': $.proxy(commentSendSuccess, this),
            'cache': false,
            'contentType': false,
            'processData': false,
            'url': '/comment/create'
        });
        return false;
    });
    $('body').on('click', '.b-feedback-bot a[id*="comment-photo-"]', function() {
        $(this).toggleClass('active');
        $(this).parent().siblings('.b-feedback-body').find('.b-feedback-gallery').toggle();
        if ($(this).hasClass('active'))
        {
            $(this).parent().siblings('.b-feedback-body').find('.b-feedback-gallery>a').click();
            //$(this).parents('b-feedback-well').find()
        }
        return false;
    });
    $('body').on('click', 'form[id*="comment-form-"] .comment-smile-select', function() {
        $(this).parents('form').find('.b-feedback-smile-select').show();
        $('.overlay-all').show();
        return false;
    });
    $('body').on('focus', '.items form[id*="comment-form-"] .b-feedback-txtarea', function() {
        $(this).animate({
            'min-height': '54px'
        }, 300);
    });



    $('.items form[id*="comment-form-"] .b-feedback-txtarea').live('keydown', function(e) {
        if (e.keyCode == 13 && e.altKey)
        {
            var id = $(this).parents('.comment-item').attr('data-post-id');
            $('#comment-submit-' + id).click();
            return false;
        }
    });


    $('body').on('blur', '.items form[id*="comment-form-"] .b-feedback-txtarea', function() {
        if ($(this).text() == '') {
            $(this).animate({
                'min-height': '21px'
            }, 300);
            $(this).html('');
        }
    });
    $('body').on('click', '.items .comment-img-list li a', function() {
        var imageId = $(this).attr('data-img-id')
        $.ajax({
            'dataType': 'json',
            'context': 'this',
            'success': $.proxy(photoFullSizeInfoSuccess, this),
            'url': '/photo/' + imageId + '/fullSizeInfo',
            'cache': false
        });
        return false;
    });

    
});

function addComments(commentItem, comments) {
    if (!commentItem.find('.comment-tbl').length) {
        commentItem.find('.comment-item-in').append('<div class="comment-dialog"><table class="comment-tbl"></table></div>');
    }

    for (var key in comments) {
        var comment = comments[key];
        if (!commentItem.find('#comment-tr-' + comment.id).length) {
            var commentHtml = '<tr id="comment-tr-' + comment.id + '" style="display: none">' +
                    '<td class="comment-td"><img src="/i/comment-bottom-num.png" alt="" /></td>' +
                    '<td class="comment-td"><a href="/user/' + comment.userId + '">' + comment.nick + '</a> ' + comment.text;
            commentHtml += "<br/>";
            if (comment.photo) {
                commentHtml += '<div class="comment-img-layout"><ul class="comment-img-list">';
                var i = 1;
                for (var photo in comment.photo) {
                    var photo = comment.photo[photo];
                    commentHtml += '<li class="comment-img-list-item"><a href="#"><img src="' + photo.url + '" /></a></li>';
                    i++;
                }
                commentHtml += '</ul></div>';
            }

            commentHtml += '</td>' + '</tr>';

            var stop = false;
            commentItem.find('tr[id*="comment-tr"]').each(function() {
                if (stop) {
                    return false;
                }

                var currentId = parseInt($(this).attr('id').replace('comment-tr-', ''));
                if (comment.id < currentId) {
                    $(this).before(commentHtml);
                    stop = true;
                }
            });

            if (!stop) {
                commentItem.find('.comment-tbl').append(commentHtml);
            }
        }
    }

    commentItem.find('.comment-tbl tr:hidden').fadeIn("slow");
}

function likeSuccess(response) {
    if (response.result == "success") {
        var ownerId = $(this).attr("data-owner-id");
        $('.comment-item .comment-bottom-like[data-owner-id="' + ownerId + '"]').each(function() {
            var commentItem = $(this).parents('.comment-item');

            if (response.data.count) {
                if ($(this).siblings('.comment-bottom-heart').length) {
                    $(this).siblings('.comment-bottom-heart').text(response.data.count);
                } else {
                    var a = this.outerHTML;
                    var parent = $(this).closest('.comment-bottom');
                    $(this).remove();
                    parent.prepend('<div class="comment-bottom-like-num">' + a + '<a class="comment-bottom-heart" href="#">' + response.data.count + '</a></div>');
                }

                if (!commentItem.find('.comment-tbl').length) {
                    commentItem.find('.comment-item-in').append('<div class="comment-dialog"><table class="comment-tbl"></table></div>');
                }

                if (commentItem.find('.comment-dialog tr.post-likes').length) {
                    commentItem.find('.comment-dialog tr.post-likes td').eq(1).html('');
                } else {
                    commentItem.find('.comment-dialog .comment-tbl').prepend('<tr class="post-likes"><td class="comment-td"><img src="/i/comment-bottom-heart.png" alt="" /></td><td class="comment-td"></td></tr>');
                }

                var likeUserText = '';
                for (var i in response.data.users) {
                    var user = response.data.users[i];
                    likeUserText += '<a href="' + user.url + '">' + user.nick + '</a>';
                    if (i < 2 && i != (response.data.users.length - 1)) {
                        likeUserText += ', ';
                    }
                }
                if (response.data.count > 3) {
                    likeUserText += ' <span class="comment-td-gray">и еще</span> ';
                    likeUserText += '<a class="additional-likes" href="#">' + (response.data.count - 3) + ' ' + declOfNum(response.data.count - 3, ['лайк', 'лайка', 'лайков']) + '</a>';
                }
                commentItem.find('.comment-dialog tr.post-likes td').eq(1).html(likeUserText);
            } else {
                var commentBottom = $(this).closest('.comment-bottom');
                $(this).parent().remove();
                commentBottom.prepend('<a class="comment-bottom-like" id="post-like-' + ownerId + '" data-owner-id="' + ownerId + '" href="#">лайк</a>');
                commentItem.find('.comment-dialog tr.post-likes').remove();
            }
        });
    }
}

function prevCommentsSuccess(response) {
    if (response.result == "success") {
        var comments = response.data.comments;
        var postId = $(this).attr('data-post-id');
        $('.comment-item .comment-td a[id*="comment-prev-"][data-post-id="' + postId + '"]').each(function() {
            var commentItem = $(this).parents('.comment-item');
            commentItem.find('.comment-bottom-num').text(comments.length);
            $(this).closest('tr').remove();
            addComments(commentItem, comments);
        });
        if ($('.post-pp').is(':visible')) {
            var currentFileName = $('.post-pp .post-pp-gallery .post-pp-gallery-i:visible img').attr('src');
            addImagesInPhotoPopup($('.items .comment-item[data-post-id="' + postId + '"]'), currentFileName);
            $(".pp-custom-scroll").mCustomScrollbar();
        }
    }
}

function commentSendSuccess(response) {
    if (response.result == "success") {
        var comments = response.data.comments;
        var postId = $(this).parents('form').find('input[name="Comment[post_id]"]').val();
        var lastCommentId = 0;
        $('div.comment-item[data-post-id="' + postId + '"]').each(function() {
            var commentItem = $(this);
            commentItem.find('.b-feedback-well .b-feedback-gallery .b-feedback-gallery-item-close').click();
            commentItem.find('.b-feedback-well .b-feedback-gallery').hide();
            commentItem.find('.b-feedback-well .b-feedback-bot a[id*="comment-photo-"]').removeClass('active');
            addComments(commentItem, comments);
            if (commentItem.find('tr[id*="comment-tr"]:last').length) {
                lastCommentId = commentItem.find('tr[id*="comment-tr"]:last').attr('id').replace('comment-tr-', '');
            }
        });
        $(this).parents('form').find('input[name="lastCommentId"]').val(lastCommentId);
        $(this).parents('form').find('.comment-form-area textarea.comment-form-textarea').val('');
        $(this).parents('form').find('.comment-form-area div.comment-form-textarea').html('');
    }
}