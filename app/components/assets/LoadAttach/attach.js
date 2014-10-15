(function($) {

    function getXmlHttp() {
        var xmlhttp;
        try {
            xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (E) {
                xmlhttp = false;
            }
        }
        if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
            xmlhttp = new XMLHttpRequest();
        }
        return xmlhttp;
    }


    function uploadFile(reader, file, url, li, $this) {


        var xhr = getXmlHttp();

        li.data('xhr', xhr);
        li.addClass('loading');
        li.removeClass('expected');

        xhr.upload.addEventListener("progress", function(e) {
            if (e.lengthComputable) {
                var progress = (e.loaded * 100) / e.total;
                $('.progress .bar', li).css('width', Number(progress) + '%');

            }
        }, false);

        xhr.onreadystatechange = function() {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    /* ... все ок! смотрим в this.responseText ... */

                    li.removeData('xhr');
                    loadComplete(li, this.responseText);

                } else {
                    /* ... ошибка! ... */
                    li.removeData('xhr');
                    li.remove();
                }
                startLoad($this);
            }
        };

        xhr.open("POST", url, true);


        var boundary = "x1xw0135x2x1504qwe";
        // Устанавливаем заголовки
        xhr.setRequestHeader("Content-Type", "multipart/form-data, boundary=" + boundary);
        xhr.setRequestHeader("Cache-Control", "no-cache");
        // Формируем тело запроса
        var body = "--" + boundary + "\r\n";
        body += "Content-Disposition: form-data; name='file'; filename='" + file.name + "'\r\n";
        body += "Content-Type: " + file.type + "\r\n\r\n";
        body += reader.result + "\r\n";
        body += "--" + boundary + "--";
        // Отправляем файлы.
        if (xhr.sendAsBinary) {
            // Только для Firefox
            xhr.sendAsBinary(body);
        } else {
            // Для остальных (как нужно по спецификации W3C)
            xhr.send(body);
        }
    }

    function loadComplete(li, id)
    {

        li.attr('id', id).removeClass('loading');

        $('.progress', li).remove();

        var bl = li.parents('.attach-bl').eq(0);


        var inp = bl.find('.inputs input[value=' + id + ']');
        if (!inp.length)
        {
            bl.find('.inputs').append('<input class="a_file" name="' + bl.data('attachWidget').options.name + '[file][]" type="hidden" value="' + id + '" />');
        }

    }

    function startLoad($this)
    {
        var data = $this.data('attachWidget');
        var options = data.options;

        var li = $this.find('.attach-items li.expected');
        if (li.length)
        {
            var countLoad = $this.find('.attach-items li.loading').length;

            if (countLoad < options.uploadCount)
            {
                if (!data.isload)
                {
                    $(document).trigger('attachWidget.startLoad');
                    data.isload = true;
                }
                var i = 0;
                while (i < li.length && (i + countLoad < options.uploadCount))
                {

                    var reader2 = new FileReader();
                    reader2.tag = i;
                    reader2.onload = function() {

                        uploadFile(reader2, li.get(reader2.tag).file, options.url, li.eq(reader2.tag), $this);
                    };
                    reader2.readAsBinaryString(li.get(i).file);

                    i++;

                }


            }

        } else
        {
            if (data.isload)
            {
                $(document).trigger('attachWidget.endLoad');
                data.isload = false;
            }
        }
    }






    var methods = {
        init: function(options) {
            return this.each(function() {

                options = $.extend({
                    name: "file",
                    max: 50,
                    url: '/message/attachment',
                    maxSizeFile: 10485760, //10 мб
                    uploadCount: 1, //одновременных загрузок
                    typesMime: [] //допустимые типы файлов
                }, options);


                var $this = $(this);



                var data = $this.data('attachWidget');
                if (!data)
                {

                    if (!XMLHttpRequest.prototype.sendAsBinary) {
                        XMLHttpRequest.prototype.sendAsBinary = function(datastr) {
                            function byteValue(x) {
                                return x.charCodeAt(0) & 0xff;
                            }
                            var ords = Array.prototype.map.call(datastr, byteValue);
                            var ui8a = new Uint8Array(ords);
                            this.send(ui8a.buffer);
                        }
                    }

                    var id = $this.attr('id');
                    if (!id)
                    {
                        $.error('должен быть указан ид');
                    }

                    var controlBlock = $('#' + id + '-control');
                    if (!controlBlock.length)
                    {
                        $.error('#' + id + '-control not fond');
                    }


                    controlBlock.append('<input type="file" name="f"  accept="' + options.typesMime.join(',') + '"  multiple style="display:none" />');

                    $this.append('<ul class="attach-items"></ul><div class="inputs"></div>');
                    $this.addClass('attach-bl');



                    $this.data('attachWidget', {
                        target: $this,
                        options: options,
                        id: id,
                        controlBlock: controlBlock,
                        i: 1
                    });



                    controlBlock.find('input[type=file]', $this).change(function() {
                        var options = $this.data('attachWidget').options;

                        if (options.max && $this.attachWidget('getCount') + this.files.length > parseInt(options.max))
                        {
                            this.value = null;
                            alert('Максимум можно прикриплений: ' + options.max);
                            return false;
                        }

                        var bl = $('ul', $this);

                        $.each(this.files, function(i, file) {

                            if ($.inArray(file.type, options.typesMime) === -1) {
                                // Отсеиваем исполняемые файлы
                                alert('Недопустимый тип: ' + file.type + ' файла' + file.name);
                                return false;
                            }

                            if (options.maxSizeFile && file.size > options.maxSizeFile)
                            {
                                alert('Превышен максимальный размер файла ' + file.name + ' ' + options.maxSizeFile + ' байт')
                                return false;
                            }


                            var html = '<li class="type_file expected"  id="' + options.name + 'li_' + $this.data('attachWidget').i + '" >' +
                                    '<img src="" >' +
                                    '<div class="progress"><div class="bar" style="width:0%;"></div></div>' +
                                    '<a href="#" class="delete-link"></a>' +
                                    ' </li>';

                            html = $(html);
                            html.get(0).file = file;
                            bl.append(html);

                            var reader = new FileReader();
                            reader.onload = function(e) {
                                $('img', html).attr('src', e.target.result);

                            };
                            reader.readAsDataURL(file);



                            $this.data('attachWidget').i++;



                        });
                          this.value = null;

                        startLoad($this);
                    });

                    controlBlock.find('a.type_file', $this).click(function() {
                        controlBlock.find('input[type=file]').click();
                        return false;
                    });


                    $('.attach-items .delete-link', $this).live('click', function() {

                        var li = $(this).parents('li');
                        if (li.data('xhr'))
                        {
                            li.data('xhr').abort();
                        } else
                        {
                            if (li.hasClass('type_map')) //прикрапления карта
                            {
                                $('#' + li.attr('id') + '_input').remove();
                            } else
                            {
                                $this.find('.inputs input[value=' + li.attr('id') + ']').remove();
                            }
                        }
                        li.remove();
                        return false;
                    });

                    $(window.document).bind('chat.sendMessage', function() {
                        $this.find('.inputs').html('');
                        $this.find('.attach-items').html('');
                    });


                }


            });
        },
        destroy: function() {

            return this.each(function() {
                $(this).removeData('attachWidget');
            });
        },
        getCount: function() {
            return $('li', this).length;
        }


    };

    $.fn.attachWidget = function(method) {

        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Метод с именем ' + method + ' не существует для jQuery.attachWidget');
        }

    };

})(jQuery);