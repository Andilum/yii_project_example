function readURL(input, img) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            img.attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
        img.parent().show();
    } else {
        img.attr('src', '#');
        img.parent().hide();
    }
}


function oldreadURLFlash(input, flashElement, containter) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            flashElement.attr('src', e.target.result);
            containter.parent().show();
        };

        reader.readAsDataURL(input.files[0]);

    } else {
        flashElement.attr('src', '');
        containter.parent().hide();
    }
}

function readURLFlash(input, container) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            var flash = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" type="application/x-shockwave-flash">'+
                '<param name="movie" value="cid:'+e.target.result+'"/>'+
                '<param name="quality" value="high"/>'+
                '<param name="bgcolor" value="#ffffff"/>'+
                '<embed id="src_flash_preview" src="'+e.target.result+'" quality="high" bgcolor="#ffffff"'+
                    'name="" allowscriptaccess="sameDomain" type="application/x-shockwave-flash"'+
                    'pluginspage="http://www.macromedia.com/go/getflashplayer">'+
                '</embed>'+
                '</object>';
            container.html(flash);
            container.parent().show();
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        container.parent().hide();
    }
}


