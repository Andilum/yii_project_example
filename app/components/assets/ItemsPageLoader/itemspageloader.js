$(document).ready(function() {

    function loadNext($this)
    {
        if (!$this.data('itemspageloader').load)
        {
            var count = $this.find('.items>.item').length;
            $.ajax({
                dataType: 'text',
                type: "GET",
                url: window.location.href,
                data: {ajax: $this.attr('id'), offset: count},
                beforeSend: function()
                {
                    $this.data('itemspageloader').load = true;
                    $this.find('.pagin').show();
                },
                complete: function() {
                    $this.data('itemspageloader').load = false;
                    $this.find('.pagin').hide();
                },
                success: function(d) {
                    d = d.split('<!>');
                    $this.find('.items').append(d[0]);
                    if (d[1] == '0')
                    {
                        $this.data('itemspageloader').active = false;
                       // $this.find('.pagin').remove();
                    }
                }
            });
        }
    }

    function getItemsYOffset(bl)
    {
        return bl.outerHeight() + bl.offset().top;
    }

    $('.itemspageloader').each(function() {
        var $this = $(this);
 
        if ($this.find('.pagin').length) {
            $this.data('itemspageloader', {load: false, active: true});
            if ($(this).find('.pagin a').click(function() {
                loadNext($this);
                return false;
            }).length)
            {
                $this.data('itemspageloader').scroll = false;
            } else
            {
                $this.data('itemspageloader').scroll = true;
                $this.find('.pagin').hide();
            }
            
            $this.addClass('active-pager');
        }
    });


    $(window).on('scroll',function() {
        var scrolled = (window.pageYOffset || document.documentElement.scrollTop) + $(window).height();

        $('.itemspageloader.active-pager').each(function() {
            var $this = $(this);
            if ($this.data('itemspageloader').active && $this.data('itemspageloader').scroll)
            {
                    if (getItemsYOffset($this) <= scrolled + 310)
                    {
                        loadNext($this);
                    }
            }
        });
    });

});