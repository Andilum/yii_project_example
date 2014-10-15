function headerFix(sw) {
	sw += "px";
	var pr = $('.header-in').css('padding-right');
	if (sw != pr) {$('.header-in').css('padding-right', sw);};
}

function getScrollbarWidth() {
	var outer = document.createElement("div");
	outer.style.visibility = "hidden";
	outer.style.width = "100px";
	outer.style.msOverflowStyle = "scrollbar";

	document.body.appendChild(outer);

	var widthNoScroll = outer.offsetWidth;

	outer.style.overflow = "scroll";


	var inner = document.createElement("div");
	inner.style.width = "100%";
	outer.appendChild(inner);        

	var widthWithScroll = inner.offsetWidth;


	outer.parentNode.removeChild(outer);

	return widthNoScroll - widthWithScroll;
}



$(document).ready(function() {
    
    var st = $('.page-wrap').scrollTop();
	var contentHeight = $(window).height() - 90;
	$('.content-full-height').css({height: contentHeight -20 + 'px'});

		
        
    $('input[placeholder], textarea[placeholder]').placeholder();
    $(".pp-custom-scroll").mCustomScrollbar();

    $('body').on('click', '.leftbar-ul .leftmenu-tail', function() {
        $(this).siblings('.leftmenu-options').toggle();
        $(this).parent().toggleClass('leftmenu-li-hotel-act');
        return false;
    });

    $('body').on('click', '.post-pp .comment-item .comment-img-layout img', function() {
        var currentFileName = $(this).attr('src').split('_')[0] + '.' + $(this).attr('src').split('.')[1];
        if ($('.post-pp .post-pp-gallery img[src="' + currentFileName + '"]').length) {
            $('.post-pp .post-pp-gallery img').parent().hide();
            $('.post-pp .post-pp-gallery img[src="' + currentFileName + '"]').parent().show();
        }
        return false;
    });

    $('body').on('click', '.overlay, .post-pp-close', function() {
        $('.overlay').hide();
        $('.post-pp').hide();
        return false;
    });

    $('body').on('click', '.post-pp-gallery-nav a', function() {
        var i = $('.post-pp-gallery-nav a').index(this);
        var t = $('.post-pp-gallery-i:visible');
        var c = $('.post-pp-gallery-i').length - 1;
        if (i == 1) {
            $('.post-pp-gallery-i').hide();
            if (t.index() == c) {
                $('.post-pp-gallery-i').eq(0).fadeIn();
            } else {
                t.next().fadeIn();
            }
        } else {
            $('.post-pp-gallery-i').hide();
            if (t.index() == 0) {
                $('.post-pp-gallery-i').eq(c).fadeIn();
            } else {
                t.prev().fadeIn();
            }
        }
        return false;
    });

    $('body').on('click', '.items .userphoto-list-item a', function() {
        var currentFileName = $(this).find('img').attr('src').split('_')[0] + '.' + $(this).find('img').attr('src').split('.')[1];
        var imageId = $(this).attr('data-img-id');
        $.ajax({
            'dataType' : 'json',
            'context' : 'this',
            'success' : $.proxy(photoGalleryInfoSuccess, this),
            'url' : '/photo/' + imageId + '/fullSizeInfo',
            'cache' : false
        });

        $('.post-pp .post-pp-gallery').html('');
        $('.items .userphoto-list-item a img').each(function() {
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
                'success' : $.proxy(photoGalleryInfoSuccess, this),
                'url' : '/photo/' + imageId + '/fullSizeInfo',
                'cache' : false
            });
        });

        return false;
    });

    $('body').on('click', '#post-form-submit', function() {
        var postText = $('#post-form .b-feedback-txtarea').clone();
        postText.find('img').each(function() {
            $(this).replaceWith($(this).attr('data-code'));
        });
        $('#Post_text').val(postText.html());
        $(this).parent().parent().parent().submit();
        return false;
    });

    $('body').on('focus', '#post-form .b-feedback-txtarea', function() {
        $(this).animate({
            'min-height': '110px'
        }, 300);
    });

    $('body').on('blur', '#post-form .b-feedback-txtarea', function() {
        if ($(this).text() == '') {
            $(this).animate({
                'min-height': '72px'
            }, 300);
            $(this).html('');
        }
    });

    $('body').on('keydown', 'div.b-feedback-txtarea', function(e) {
        if (e.keyCode === 13) {
            document.execCommand('insertHTML', false, '<br><br>');
            return false;
        }
    });
});

function photoGalleryInfoSuccess(response) {
    if (response.result == "success") {
        addSidebarInfoInPhotoPopup(response.data);
        $('.overlay').show();
        $('.post-pp').show();
    }
    $('.post-pp .preloader').hide();
    $('.post-pp .post-pp-r-head, .post-pp .pp-custom-scroll').css('opacity', '1');
}

function photoFullSizeInfoSuccess(response) {
    if (response.result == "success") {
        var data = response.data;
        var post = data.post;
        var comments = data.comments;
        var currentFileName = $(this).find('img').attr('src').split('_')[0] + '.' + $(this).find('img').attr('src').split('.')[1];
        var lastCommentId = 0;

        var photos = {};
        if (post.photos) {
            for (var i in post.photos) {
                var photo = post.photos[i];
                photos[photo.id] = photo;
            }
        }
        if (comments) {
            for (var i in comments) {
                var comment = comments[i];
                if (comment.photos) {
                    for (var j in comment.photos) {
                        var photo = comment.photos[j];
                        photos[photo.id] = photo;
                    }
                }
                lastCommentId = comment.id;
            }
        }

        $('.post-pp .post-pp-gallery').html('');
        for (var key in photos) {
            var photo = photos[key];
            $('.post-pp .post-pp-gallery').append('<li class="post-pp-gallery-i" style="display: none;"><img src="/i/preloader.gif" /><img src="' + photo.url + '" style="display: none;" onload="$(this).siblings().hide();$(this).show();" /></li>');
        }
        $('.post-pp .post-pp-gallery img[src="' + currentFileName + '"]').parent().show();

        addSidebarInfoInPhotoPopup(response.data);

        $('.overlay').show();
        $('.post-pp').show();
    }
}

function addSidebarInfoInPhotoPopup(data) {
    var post = data.post;
    var comments = data.comments;
    var lastCommentId = 0;

    $('.post-pp').find('.post-pp-userpic-wrap').attr('href', post.user_url);
    $('.post-pp').find('.post-pp-userpic-wrap').html('<img src="' + post.avatar_url + '" width="30" alt=""/>');

    $('.post-pp').find('.post-pp-user-details > a').attr('href', post.user_url);
    $('.post-pp').find('.post-pp-user-details > a').html(post.user_nick);

    $('.post-pp').find('.post-pp-user-details > span').html(post.date);

    $('.post-pp').find('.post-pp-user-details .post-pp-loc').html('в <a href="' + post.allocation_url + '">' + post.allocation_name + ' ' + post.alloccat_name + '</a>');

    $('.post-pp').find('.comment-item .comment-txt').html(post.text);

    var buttons = '';
    var buttonDisable = ' style="cursor:default;text-decoration:none"';
    if (post.like_count) {
        buttons += '<div class="comment-bottom-like-num like-button">' +
            '<a class="comment-bottom-like" id="post-like-popup>" data-owner-id="' + post.id + '" href="#"' + (data.isGuest ? buttonDisable : '') + '>лайк</a>' +
            '<a class="comment-bottom-heart" id="post-like-heart-popup" data-owner-id="' + post.id + '" href="#"' + (data.isGuest ? buttonDisable : '') + '>' + post.like_count + '</a>' +
            '</div>';
    } else {
        buttons += '<a class="comment-bottom-like" id="post-like-popup" data-owner-id="' + post.id + '" href="#"' + (data.isGuest ? buttonDisable : '') + '>лайк</a>';
    }
    if (post.comment_count) {
        buttons += '<div class="comment-bottom-like-num comment-button">' +
            '<a class="comment-bottom-like">комментарии</a>' +
            '<a class="comment-bottom-num">' + post.comment_count + '</a>' +
            '</div>';
    } else {
        buttons += '<a class="comment-bottom-like no-comments"' + (data.isGuest ? buttonDisable : '') + '>комментировать</a>';
    }
    buttons += '<a href="#" class="comment-bottom-more"></a>';
    $('.post-pp').find('.comment-item .comment-bottom').html(buttons);

    $('.post-pp').find('.comment-item .comment-dialog').html('');
    if (!$('.post-pp').find('.comment-item .comment-dialog .comment-tbl').length) {
        $('.post-pp').find('.comment-item .comment-dialog').append('<table class="comment-tbl"></table>');
    }

    if (post.like_count) {
        var likeUserText = '';
        if (post.like.users) {
            for (var i in post.like.users) {
                var user = post.like.users[i];
                likeUserText += '<a href="' + user.url + '">' + user.nick + '</a>';
                if (i < 2 && i != (post.like.users.length - 1)) {
                    likeUserText += ', ';
                }
            }
        }
        if (post.like_count > 3) {
            likeUserText += ' <span class="comment-td-gray">и еще</span> ';
            likeUserText += '<a class="additional-likes" href="#">' + (post.like_count - 3) + ' ' + declOfNum(post.like_count - 3, ['лайк', 'лайка', 'лайков']) + '</a>';
        }
        $('.post-pp').find('.comment-item .comment-dialog .comment-tbl').prepend('<tr class="post-likes"><td class="comment-td"><img src="/i/comment-bottom-heart.png" alt="" /></td><td class="comment-td"></td></tr>');
        $('.post-pp').find('.comment-item .comment-dialog tr.post-likes td').eq(1).html(likeUserText);
    }

    if (comments) {
        for (var i in comments) {
            var comment = comments[i];
            var commentHtml = '';
            commentHtml += '<tr id="comment-tr-' + comment.id + '"><td class="comment-td"><img src="/i/comment-bottom-num.png" alt="" /></td><td class="comment-td">';
            commentHtml += '<a href="' + comment.user_url + '">' + comment.user_nick + '</a> ';
            commentHtml += comment.text;
            if (comment.photos) {
                commentHtml += '<div class="comment-img-layout"><ul class="comment-img-list">';
                for (var j in comment.photos) {
                    var photo = comment.photos[j];
                    commentHtml += '<li class="comment-img-list-item"><a href="#"><img src="' + photo.url_thumbnail + '" alt=""></a></li>';
                }
                commentHtml += '</ul></div>';
            }
            commentHtml += '</td></tr>';
            $('.post-pp').find('.comment-item .comment-dialog .comment-tbl').append(commentHtml);
        }
    }

    $('.post-pp').find('form input[name="Comment[post_id]"]').val(post.id);
    $('.post-pp').find('.comment-item').attr('data-post-id', post.id);

    $('.post-pp').find('form input[name="lastCommentId"]').val(lastCommentId);

    $(".pp-custom-scroll").mCustomScrollbar("update");
}

function addImagesInPhotoPopup(commentItem, currentFileName) {
    $('.post-pp .post-pp-gallery').html('');
    var images = commentItem.find('.comment-img-layout img').clone();
    images.each(function() {
        var src = $(this).attr('src').split('/');
        var fileName = src[src.length - 1];
        src[src.length - 1] = fileName.split('_')[0] + '.' + fileName.split('.')[1];
        $(this).attr('src', src.join('/'));
        $('.post-pp .post-pp-gallery').append($('<li class="post-pp-gallery-i" style="display: none;"></li>').append($(this)));
    });
    $('.post-pp .post-pp-gallery img[src="' + currentFileName + '"]').parent().show();
}

function declOfNum(number, titles) {
    var cases = [2, 0, 1, 1, 1, 2];
    return titles[(number % 100 > 4 && number % 100 < 20) ? 2 : cases[(number % 10 < 5) ? number % 10 : 5]];
}


// возвращает cookie с именем name, если есть, если нет, то undefined
function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
            ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

// устанавливает cookie c именем name и значением value
// options - объект с свойствами cookie (expires, path, domain, secure)
function setCookie(name, value, options) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }
    document.cookie = updatedCookie;
}

// удаляет cookie с именем name
function deleteCookie(name) {
    setCookie(name, '', {expires: -10});
}

function htmlspecialchars(html) {
    // Сначала необходимо заменить &
    html = html.replace(/&/g, "&amp;");
    // А затем всё остальное в любой последовательности
    html = html.replace(/</g, "&lt;");
    html = html.replace(/>/g, "&gt;");
    html = html.replace(/"/g, "&quot;");
    // Возвращаем полученное значение
    return html;
}



function PopUp()
{
    this.pop_cache = [];
    $this = this;
    $this.xhr = null;

    $this.popupinitSize = function()
    {
        $('.popup').each(function() {
            var top = Math.round($(window).height() / 2 + $(window).scrollTop());
            var left = Math.round($(window).width() / 2 + $(window).scrollLeft());

            var mLeft = Math.round($(this).outerWidth() / 2);
            var mTop = Math.round($(this).outerHeight() / 2);

            if (mLeft > left)
                mLeft = left;
            if (mTop > top)
                mTop = top - 20;



            $(this).css('margin-left', 0 + 'px');
            $(this).css('margin-top', 0 + 'px');

            $(this).offset({top: top, left: left});

            $(this).css('margin-left', '-' + mLeft + 'px');
            $(this).css('margin-top', '-' + mTop + 'px');

        });

    }
    $this.popupinit = function()
    {
        $this.popupinitSize();
        $('.popup .close,.popup .ok').unbind('click');
        $('.popup .close,.popup .ok,#popfon').click(function() {
            $('.popup,#popfon').remove();
            return false;
        });
        $('.popup').css('display', 'block');

    };
    $this.openByHtml = function(data)
    {
        $('.popup').remove();
        $('body').append(data.indexOf('class="popup') == -1 ? $this.gethtmlmsg(data, false) : data);
        $('body').prepend('<div id="popfon"  class="overlay" style="display:block" ></div>');
      
        $(document).ready(function() {
            $this.popupinit();
        });
    }

    /**
     * загрузка содержимого в попап
     * @param {String} url
     * @param {bool} notcache не кешировать
     * @returns {undefined}
     */
    $this.getpop = function(url, notcache)
    {

        if (notcache === true || $this.pop_cache[url] == undefined)
        {
            if ($this.xhr)
            {
                return false;
                // $this.xhr.abort();
            }
            $this.xhr = $.ajax({
                url: url,
                cache: true,
                type: 'GET',
                beforeSend: function() {
                    $('body').css('cursor', 'progress');
                },
                complete: function()
                {
                    $('body').css('cursor', 'default');
                    $this.xhr = null;
                },
                success: function(data) {
                    $this.pop_cache[url] = data;
                    $this.openByHtml(data);
                },
                error: function(d) {
                    console.log(d);
                    alert(d.responseText);
                }

            });
        }
        else
            $this.openByHtml($this.pop_cache[url]);
    };

    $this.gethtmlmsg = function(txt, button)
    {
        if (button === undefined)
            button = true;

        button = ((button === false) ? '' : ((button === true) ? '<div class="buttons"><a href="#"  class="ok btn">Закрыть</a></div>' : button));

        return '<div class="popup" ><a href="#" class="close post-pp-close"></a><div class="msg">' + txt + '</div>' + button + '</div>';
    };

    $this.showmessage = function(txt, button)
    {
        $('body').append($this.gethtmlmsg(txt, button));
        $('body').prepend('<div id="popfon" class="overlay" style="display:block" ></div>');
        $this.popupinit();
    };

}

var popup = new PopUp();