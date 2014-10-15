jQuery(function($) {
    var popup;

    $.fn.eauth = function(options) {
        options = $.extend({
            id: '',
            popup: {
                width: 600,
                height: 450
            }
        }, options);

        return this.each(function() {
            var el = $(this);
            el.click(function() {
                if (popup !== undefined)
                    popup.close();

                var url = this.href;
                url += url.indexOf('?') >= 0 ? '&' : '?';
                url += 'popup=1';

                var centerWidth = ($(window).width() - options.popup.width) / 2,
                        centerHeight = ($(window).height() - options.popup.height) / 2;

                popup = window.open(url, "yii_eauth_popup", "width=" + options.popup.width + ",height=" + options.popup.height + ",left=" + centerWidth + ",top=" + centerHeight + ",resizable=yes,scrollbars=no,toolbar=no,menubar=no,location=no,directories=no,status=yes");
                popup.focus();

                return false;
            });
        });
    };
});