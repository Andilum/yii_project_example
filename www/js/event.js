/**
 * события  (о новых сообшениях)
 * @returns {undefined}
 */
function EventClass()
{
    var $this = this;
    $this.items = [];
    $this.timeout = 40; //ожидание запроса в секундах
    $this.url = '/message/event';

    /**
     *  объект с элементами type и data и compareAttr с указанием свойст даты которые нужно проверить перед вызовом события
     *  еще можно указать url
     * @param {type} objEvent
     * @returns {undefined}
     */
    $this.add = function(objEvent)
    {
        return $this.items.push(objEvent) - 1;
    }

    /**
     * запуск новых подписок ,  можно вызвать этот метод в add
     * @returns {undefined}
     */
    $this.start = function()
    {
        for (var i = 0; i < $this.items.length; i++)
        {
            if (!$this.items[i].xhr)
            {
                $this.request($this.items[i]);
            }
        }
    }

    $this.request = function(item) {
        var url=item.url?item.url:$this.url;
        
        item.xhr = $.ajax({
            type: "POST",
            data: {r: $.toJSON({type: item.type, data: item.data})},
            url: url,
            timeout: $this.timeout * 1000,
            dataType: "json",
            success: function(d) {
                if (d)
                {
                    $this.onEvent(item.type, d);
                }

            },
            complete: function() {
                item.xhr = null;
                $this.request(item);
            }
        });
    }

    /**
     *  событие произошло
     * @param {type} data
     * @returns {undefined}
     */
    $this.onEvent = function(type, data)
    {

        for (var i = 0; i < $this.items.length; i++)
        {
            if ($this.items[i].type == type && $this.compareData($this.items[i], data))
            {

                $(window.document).trigger('siteevent', [data, i]);
            }
        }

    }

    $this.compareData = function(eventListen, eventData)
    {
        var ok = true;
        if (eventListen.data && eventListen.compareAttr)
        {
            for (var t = 0; t < eventListen.compareAttr.length; t++)
            {
                ok = ok && eventListen.data[eventListen.compareAttr[t]] == eventData[eventListen.compareAttr[t]];
            }
        }

        return ok;
    }

}

window.siteEvent = new EventClass();