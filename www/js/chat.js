
function UsersStorageClass()
{
    var $this = this;
    $this.items = [];
    $this.add = function(data)
    {
        if (!$this.items[data['id']])
        {
            $this.items[data['id']] = data;
        }
    }

    $this.getById = function(id)
    {
        if (!$this.items[id])
        {
            $.ajax({
                url: "/user/getData",
                type: 'GET',
                data: {id: id},
                async: false,
                dataType: 'json',
                success: function(d)
                {
                    $this.items[id] = d;
                }
            });

        }
        return $this.items[id];
    }
}
window.usersStorage = new UsersStorageClass();

function SmileClass(items)
{
    var $this = this;
    $this.smiles = items;
    $this.replaceSmilesFromText = function(text)
    {
        var old;
        if (text)
        {
            for (var i = 0; i < $this.smiles.length; i++)
            {
                do {
                    old = text;
                    text = text.replace($this.smiles[i].code, '<img src="/i/blank.gif" alt="" class="b-feedback-emoji-smiles" style="background-position: 0 ' + $this.smiles[i].position + 'px;" />');
                } while (text !== old);
            }

        }
        return text;
    }
}



(function($) {

    function setSizeBlock($this)
    {
        var h = parseInt($this.find('.chat-bottom').outerHeight(true));
        var b = $this.find('.chat-body');
        var p = parseInt(b.css('paddingBottom'));
        b.css('paddingBottom', h + 'px');

        $('.page-wrap').scrollTop($('.page-wrap').scrollTop() + (h - p));

    }

    //количество секунд
    function getNowTime()
    {
        return Math.round((new Date().getTime()) / 1000);
    }

    /**
     * скрытие лишних заголовков у сообшений
     * @returns {undefined}
     */
    function hideHeaderMsgItem($this)
    {
        var user = null;
        var time = null;
        $this.find('.dialog-item').each(function() {
            if (user == $(this).attr('user'))
            {
                if ((parseInt($(this).attr('time')) - time) < 300) //если разница меньше  5 сек
                {
                    $(this).find('.dialog-head').hide();

                } else
                {
                    time = parseInt($(this).attr('time'));
                }

            } else
            {
                user = $(this).attr('user');
                time = parseInt($(this).attr('time'));
            }
        });
    }

    function getScrollTop(contein)
    {
        return $('.page-wrap').scrollTop();
    }

    function setScrollTop(contein, val)
    {
        $('.page-wrap').scrollTop(val);
    }

    function toScrollBottom(contein)
    {
        $('.page-wrap').scrollTop(99999);
    }

    function toScrollTop(contein)
    {
        $('.page-wrap').scrollTop(0);
    }




    //генерация хтмл прикриплений
    function  getHtmlAttachments(attachment)
    {

        var html = '<div class="dialog-body-msg attachments">' +
                '<div class="dialog-body-msg-inner">' +
                '<div class="dialog-body-txt">';

        for (var i = 0; i < attachment.length; i++)
        {
            if (attachment[i].type == 'file')
            {
                html += '<a class="attach-item type-photo" target="_blank" href="' + attachment[i].url + '"><img src="' + attachment[i].url_thumb + '"></a>';
            } else if (attachment[i].type == 'map')
            {
                html += '<a class="attach-item type-map" targer="_blank" href="#">map...</a>';
            }
        }
        html += '</div></div></div>';
        return html;
    }

    function nl2br(txt)
    {
        var old;
        do {
            old = txt;
            txt = txt.replace("\n", '<br>');
        } while (txt !== old);
        return txt;
    }

    function nlClear(txt)
    {
        var old;
        do {
            old = txt;
            txt = txt.replace("\n", '');
        } while (txt !== old);
        return txt;
    }

    //генерация html сообщения
    function getItemHtml(message, htmlSpec)
    {
        htmlSpec = htmlSpec || false;

        var text = window.smiles.replaceSmilesFromText(htmlSpec ? htmlspecialchars(message.message) : message.message);

        if (htmlSpec)
        {
            text = nl2br(text);
        }

        var user = window.usersStorage.getById(message['user_from_id']);

        if (!message.date_create)
        {
            message.date_create = 'только что';
        }

        var classs = 'b-chat-item'; //

        if (!message.read)
        {
            classs += ' no-read';
        }
        var attachment = '';
        if (message.attachment && message.attachment.length)
        {
            attachment = getHtmlAttachments(message.attachment);
        }


        var html = '<div class="dialog-item ' + classs + '" time="' + message.time + '" user="' + message['user_from_id'] + '">' +
                '<div class="dialog-head">' +
                '<a class="dialog-head-username" href="' + user.url + '"><img alt="" src="' + user.ava + '"><span>' + user.nik + '</span></a>' +
                '<div class="dialog-head-date">' + message.date_create + '</div>' +
                '</div>' +
                '<div class="dialog-body">' +
                '<div class="dialog-body-msg">' +
                '<div class="dialog-body-msg-inner">' +
                '<div class="dialog-body-like"><a href="#"></a></div>' +
                '<div class="dialog-body-txt">' + text + '</div>' +
                '</div>' +
                '</div>' + attachment +
                '</div>' +
                '</div>';


        return html;
    }


    /**
     * @param array ids ид  сообщений которые прочитаны
     * @param {string} type chat или user
     * @returns {undefined}
     */
    function sendRead(ids, type)
    {
        //только для чата с пользователем
        if (type == 'user')
        {
            if (typeof ids == 'object')
            {
                var ids2 = ids.join(',');
            }
            if (ids2)
            {
                $.ajax({
                    url: "/message/read",
                    type: 'POST',
                    data: {ids: ids2, type: type},
                    success: function(d)
                    {
                        onRead(ids, type);
                    }
                });
            }
        }
    }

    function onRead(ids, type)
    {
        for (var i = 0; i < ids.length; i++)
        {
            $('#' + type + '-item_' + ids[i]).removeClass('no-read');
        }
    }

    var methods = {
        init: function(options) {

            var options = $.extend({
                url: window.location.href, //URL,
                user_to_id: null,
                user_id: 0,
                chat_id: null,
                messages: [],
                history: true //есть ли что то сверху
            }, options);

            if (options.chat_id)
            {
                options.type = 'chat';
            } else
            {
                options.type = 'user';
            }



            return this.each(function() {

                var $this = $(this);
                var data = $this.data('chat');
                // Если плагин ещё не проинициализирован
                if (!data) {
                    var textarea = $this.find('.chat-text-input').eq(0);

                    if (textarea.get(0).tagName == 'DIV')
                    {
                        textarea.val = function(val) {

                            if (val === undefined)
                            {
                                var childNodes = this.get(0).childNodes;
                                var html = '';
                                for (var i = 0; i < childNodes.length; i++) {
                                    if (childNodes[i].nodeType === 1) //элемент (смайл)
                                    {
                                        if (childNodes[i].tagName === "BR")
                                            html += "\n";
                                        else
                                        {
                                            if ($(childNodes[i]).attr('data-code'))
                                                html += $(childNodes[i]).attr('data-code');
                                        }
                                    } else
                                    {
                                        if ($.trim(childNodes[i].nodeValue))
                                            html += nlClear(childNodes[i].nodeValue);
                                    }

                                }

                                return html;

                            } else
                            {
                                $(this).html(val);
                            }



                        }
                    }

                    data = {options: options, items: [], textarea: textarea};

                    $this.data('chat', data);
                    var ids = [];

                    for (var i = 0; i < options.messages.length; i++)
                    {
                        methods.addMessage.apply($this, [options.messages[i]]);
                        if (options.messages[i].id && !options.messages[i].read && options.messages[i].user_to_id == options.user_id)
                            ids.push(options.messages[i].id);
                    }

                    sendRead(ids, options.type);



                    textarea.on('keydown', function(e) {

                        if (e.keyCode == 13 && !e.shiftKey && !e.ctrlKey && !e.altKey)
                        {
                            $this.find('.send-link').click();
                            return false;
                        }


                    });



                    $this.find('.send-link').click(function() {
                        //происходит загрузка прикриплений - надо дождаться
                        if (data.attachLoad)
                        {
                            data.postmessage = true;
                            return false;
                        }

                        var val = $.trim(textarea.val());


                        var inputs = $('.attach-bl .inputs input');
                        var dataAttavh = null;
                        if (inputs.length)
                        {
                            dataAttavh = {};
                            inputs.each(function() {
                                var type = $(this).hasClass('a_file') ? 'file' : 'map';
                                if (dataAttavh[type])
                                {
                                    dataAttavh[type].push($(this).val());

                                } else
                                {
                                    dataAttavh[type] = [$(this).val()];
                                }

                            });

                        }



                        if (val || dataAttavh)
                        {

                            $this.chat('sendMessage', {
                                user_to_id: options.user_to_id,
                                user_from_id: options.user_id,
                                message: val,
                                read: false,
                                attachSend: dataAttavh,
                                time: getNowTime()
                            });
                            textarea.val('');
                        }
                        return false;
                    });
                    var objEvent;

                    if (options.type == 'user')
                    {
                        objEvent = {type: 'message_user', data: {user_from_id: options.user_to_id, lastId: data.lastId}, compareAttr: ['user_from_id']};
                    } else
                    {
                        objEvent = {type: 'message_chat', data: {chat_id: options.chat_id, lastId: data.lastId}, compareAttr: ['chat_id']};
                    }


                    // подписка на новые сообщения и прочтения сообщений
                         data.iEvent = window.siteEvent.add(objEvent);

                    if (options.type == 'user')
                    {
                              data.iEventRead = window.siteEvent.add({type: 'message_read', data: {user_to_id: options.user_to_id}});
                    }




                    $(window.document).bind('siteevent', function(e, data2, i) {

                        if (i == data.iEvent)
                        {
                            var ids = [];
                            if (data2.items)
                            {
                                for (var t = 0; t < data2.items.length; t++)
                                {
                                    $this.chat('addMessage', data2.items[t]);
                                    ids.push(data2.items[t].id);
                                }
                            } else
                            {
                                $this.chat('addMessage', data2);
                                ids.push(data2.id);
                            }
                            sendRead(ids, options.type);
                        } else if (i == data.iEventRead)
                        {
                            onRead(data2.ids.split(','), 'user');
                        }

                    });

                    var contein = $this.find('.items-container').eq(0);

                    toScrollBottom(contein);
                    
                    //.page-wrap со скролом
                    $('.page-wrap').on('scroll', function(e) {
                        if (getScrollTop(contein) < 50)
                        {
                            if (data.options.history && !data.loadHistory)
                            {
                                var id = contein.find('.b-chat-item:first-child').attr('id').split('_')[1];

                                $.ajax({
                                    beforeSend: function()
                                    {
                                        data.loadHistory = true;
                                    },
                                    complete: function() {
                                        data.loadHistory = false;
                                    },
                                    url: data.options.url,
                                    type: 'get',
                                    data: {act: 'loadHistory', firstId: id},
                                    dataType: 'json',
                                    success: function(d)
                                    {
                                        if (d.items)
                                        {
                                            var ids = [];
                                            for (var t = 0; t < d.items.length; t++)
                                            {
                                                $this.chat('addMessage', d.items[t], 'tostart');
                                                if (d.items[t].id && !d.items[t].read && d.items[t].user_to_id == options.user_id)
                                                    ids.push(d.items[t].id);
                                            }

                                            sendRead(ids, 'user');
                                        }
                                        data.options.history = d.history;

                                    }
                                });
                            }
                        }
                    });


                    $(window.document).bind('attachWidget.endLoad', function() {

                        data.attachLoad = false;
                        if (data.postmessage)
                        {
                            data.postmessage = false;
                            $this.find('.send-link').click();
                        }

                    });

                    $(window.document).bind('attachWidget.startLoad', function() {

                        data.attachLoad = true;

                    });

                    window.siteEvent.start();
                    textarea.focus();


                    setInterval(function() {
                        setSizeBlock($this);
                    }, 500);

                }
            });
        },
        destroy: function( ) {
            return this.each(function() {
                var $this = $(this);
                $(window).unbind('.chat');
                $this.removeData('chat');
            });
        },
        /**
         * Добавление сообщения,
         * @param {object} message
         * @returns {_L36.methods@call;each}
         */
        addMessage: function(message, position) {
            position = position || 'toend';

            var itemHtml = $(getItemHtml(message));

            return this.each(function() {
                var item = $(itemHtml);
                var $this = $(this);
                var data = $this.data('chat');
                if (!data.items[message.id])
                {
                    if (data.options.user_id == message['user_from_id'])
                    {
                        item.addClass('outgoing');
                    } else
                    {
                        item.addClass('incoming');
                    }

                    item.attr('id', data.options.type + '-item_' + message.id);
                    data.items[message.id] = message;

                    if (position == 'toend')
                    {
                        $this.find('.items-container').append(item);
                        toScrollBottom($this.find('.items-container'));
                        hideHeaderMsgItem($this);
                        data.lastId = message.id;
                        if (data.iEvent !== undefined)
                        {
                            window.siteEvent.items[data.iEvent].data.lastId = message.id;
                        }
                    } else
                    {
                        $this.find('.items-container').prepend(item);
                        setScrollTop($this.find('.items-container'), getScrollTop($this.find('.items-container')) + item.outerHeight(true));
                    }


                }
            });
        },
        sendMessage: function(message)
        {

            var item = $(getItemHtml(message, true));
            item.addClass('no-send');
            item.addClass('incoming');


            if (message.attachSend)
            {
                message.attachSend = $.toJSON(message.attachSend);
            }

            return this.each(function() {
                var $this = $(this);
                var data = $this.data('chat');



                $this.find('.items-container').append(item);
                hideHeaderMsgItem($this);
                toScrollBottom($this.find('.items-container'));

                var dataPost = {message: message.message};
                if (data.options.user_to_id)
                    dataPost.user_to_id = data.options.user_to_id;
                if (data.options.chat_id)
                    dataPost.chat_id = data.options.chat_id;

                if (message.attachSend)
                {
                    dataPost.attachSend = message.attachSend;
                }


                $.ajax({
                    type: "POST",
                    data: dataPost,
                    dataType: 'json',
                    url: data.options.url,
                    success: function(d) {
                        var id = d.id;
                        item.removeClass('no-send');

                        id = parseInt(id);

                        item.attr('id', data.options.type + '-item_' + id);
                        if (d.attachment && d.attachment.length)
                        {
                            item.find('.dialog-body').append(getHtmlAttachments(d.attachment));
                            $this.find('.items-container');

                            toScrollBottom($this.find('.items-container'));
                        }

                        data.items[id] = message;
                        data.items[id].id = id;

                        if (data.lastId && data.lastId < id)
                        {
                            data.lastId = id;
                            if (data.iEvent !== undefined)
                            {
                                window.siteEvent.items[data.iEvent].data.lastId = data.lastId;
                            }
                        }
                    },
                    error: function()
                    {
                        item.remove();
                    }
                });


                $(document).trigger('chat.sendMessage');



            });
        }
    };


    $.fn.chat = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Метод с именем ' + method + ' не существует для jQuery.chat');
        }
    };

})(jQuery);



$(document).ready(function(event) {
    
     

function setClickClose(bl)  //bl-селектор ul элемент
    {
        $('body').unbind('mousedown.smile');
        $('body').one('mousedown.smile', function(e) {
            if (e.target!=$(bl).get(0) && $(e.target).parents(bl).length == 0)
            {
               $(bl).hide();
            } else
            {
                setClickClose(bl);
            }
        });
    }
    

    $('body').on('mousedown', '.b-feedback .comment-smile-select', function() {
        var bl='.b-feedback .b-feedback-smile-select';
        $(bl).show();
        $('.overlay-all').show();
        setClickClose(bl);
        return false;
    });
    
    
    $('.b-feedback .comment-smile-select').on('click', function() {
        return false;
    });

    //изменение размера при клике
    /* $('body').on('focus', '.b-feedback .b-feedback-txtarea', function() {
     $(this).animate({
     'min-height': '54px'
     }, 300);
     });
     $('body').on('blur', '.b-feedback .b-feedback-txtarea', function() {
     if ($.trim($(this).val()) === '') {
     $(this).animate({
     'min-height': '21px'
     }, 300);
     $(this).val('');
     }
     });*/

});