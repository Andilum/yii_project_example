$(document).ready(function(){
    var cBl=window.contentBlock?$(window.contentBlock).get(0):document;
    
    var mode=window.modeSideMenu?window.modeSideMenu:'active';
    if (mode=='active')
    {
    
	var st = $(cBl).scrollTop();
		if (st > 220) {
			$('.side-menu').addClass('side-menu_fixed');
		} else {
			$('.side-menu').removeClass('side-menu_fixed')
		}
	$(cBl).scroll(function(){
		st = $(cBl).scrollTop();
		if (st > 220) {
			$('.side-menu').addClass('side-menu_fixed');
		} else {
			$('.side-menu').removeClass('side-menu_fixed')
		}
	});
    } else if (mode=='fixed')
    {
        $('.side-menu').addClass('side-menu_fixed');
    } else
    {
        $('.side-menu').removeClass('side-menu_fixed')
    }

	$('.leftbar-ttl-a').click(function(e){
		$(this).toggleClass('leftbar-ttl-a-spoiler');
		$(this).closest('.leftbar-ttl').next('.leftbar-ul').slideToggle();
		e.preventDefault();
	});
});