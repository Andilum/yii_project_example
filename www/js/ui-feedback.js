var popup_score = 0;
var class_old = 0;

function js_feedback_foto(elem) {
    var current_color = elem.style.color;
    if (elem.classList.contains('active')) {   //если кнопка активна
        elem.classList.toggle('active');
        document.getElementsByClassName("b-feedback-gallery")[0].style.display = 'none';
    } else {    //если кнопка не активна
        elem.classList.toggle('active');
        document.getElementsByClassName("b-feedback-gallery")[0].style.display = 'block';
    }
    ;
}
function js_feedback_score(elem) {
    var current_color = elem.style.color;
    if (elem.classList.contains('active')) {   //если кнопка активна
        elem.classList.toggle('active');
        document.getElementsByClassName("b-feedback-score")[0].style.display = 'none';
    } else {    //если кнопка не активна
        elem.classList.toggle('active');
        document.getElementsByClassName("b-feedback-score")[0].style.display = 'block';
        document.getElementsByClassName("popup-score")[0].style.display = 'block';
    }
    ;
}
function js_feedback_place(elem) {
    var current_color = elem.style.color;
    if (elem.classList.contains('active')) {   //если кнопка активна
        elem.classList.toggle('active');
        document.getElementsByClassName("b-feedback-place")[0].style.display = 'none';
    } else {    //если кнопка не активна
        elem.classList.toggle('active');
        document.getElementsByClassName("b-feedback-place")[0].style.display = 'block';
    }
    ;
}
function js_feedback_foto_del(elem) {   // удаляем фото
    elem.parentNode.parentNode.removeChild(elem.parentNode);
}
function js_feedback_foto_add(elem) {  // создаем прелоадер
    var galery = document.getElementsByClassName("b-feedback-gallery")[0];
    var item = document.createElement('div');
    item.className = 'b-feedback-gallery-preloader';
    var preloader_fon = document.createElement('div');
    preloader_fon.className = 'b-feedback-gallery-preloader-fon';
    var preloader_line = document.createElement('div');
    preloader_line.className = 'b-feedback-gallery-preloader-line';
    preloader_line.style.width = '60%';    // начальное значение 
    preloader_fon.appendChild(preloader_line);
    item.appendChild(preloader_fon);
    galery.insertBefore(item, elem)
}
function js_popup_close() {
    document.getElementsByClassName("popup-score")[0].style.display = 'none';
}
function js_popup_select_open(elem) {
    var popup_select = document.getElementsByClassName("popup-score-select")[0];
    var offset_top = elem.parentNode.parentNode.offsetTop;
    var offset_scroll = document.getElementsByClassName("popup-score-content")[0].scrollTop;
    popup_select.style.top = 44 + offset_top - offset_scroll + 'px';
    var current_item = elem.parentNode.parentNode;
    /*alert('Index: ' + $('.popup-content-item').index(current_item));*/
    popup_score = $('.popup-content-item').index(current_item);

    document.getElementsByClassName("popup-score-select-top-points")[0].innerHTML = document.getElementsByClassName("popup-score-number")[popup_score].innerHTML;
    document.getElementsByClassName("popup-score-select-top-point2")[0].innerHTML = document.getElementsByClassName("popup-score-object-points2")[popup_score].innerHTML;
    var all_class = current_item.className;
    var current_class = all_class.substring(19);
    if (current_class != '') {
        current_item.classList.remove(current_class);
        document.getElementsByClassName("popup-score-select-top-points")[0].classList.add(current_class);
    }
    class_old = current_class;
    popup_select.style.display = "block";
    document.getElementsByClassName('popup-score-overlay')[0].style.display = 'block';
    $('.popup-score-content').addClass('popup-score-content_blocked');
    // /*    блокирование скролла */
    // document.getElementsByClassName('popup-score-content')[0].style.overflowY = 'hidden';
    // document.getElementsByClassName('popup-score-content-td')[0].style.overflowY = 'scroll';


}
function js_popup_select_close(elem, score) {
    var popup_select = document.getElementsByClassName("popup-score-select")[0];
    if (score == '5') {
        document.getElementsByClassName('popup-content-item')[popup_score].classList.add('color-green-cyan');
        document.getElementsByClassName("popup-score-number")[popup_score].innerHTML = '5 балов';
        document.getElementsByClassName("popup-score-object-points2")[popup_score].innerHTML = 'Восхитительно';
    }
    if (score == '5-') {
        document.getElementsByClassName('popup-content-item')[popup_score].classList.add('color-brilliant-green');
        document.getElementsByClassName("popup-score-number")[popup_score].innerHTML = '5- балов';
        document.getElementsByClassName("popup-score-object-points2")[popup_score].innerHTML = 'Прекрасно';
    }
    if (score == '4') {
        document.getElementsByClassName('popup-content-item')[popup_score].classList.add('color-yellow-green');
        document.getElementsByClassName("popup-score-number")[popup_score].innerHTML = '4 балла';
        document.getElementsByClassName("popup-score-object-points2")[popup_score].innerHTML = 'Хорошо';
    }
    if (score == '3') {
        document.getElementsByClassName('popup-content-item')[popup_score].classList.add('color-orange-yellow');
        document.getElementsByClassName("popup-score-number")[popup_score].innerHTML = '3 балла';
        document.getElementsByClassName("popup-score-object-points2")[popup_score].innerHTML = 'Так себе';
    }
    if (score == '2') {
        document.getElementsByClassName('popup-content-item')[popup_score].classList.add('color-strong-red');
        document.getElementsByClassName("popup-score-number")[popup_score].innerHTML = '2 балла';
        document.getElementsByClassName("popup-score-object-points2")[popup_score].innerHTML = 'Плохо';
    }
    if (score == '1') {
        document.getElementsByClassName('popup-content-item')[popup_score].classList.add('color-black');
        document.getElementsByClassName("popup-score-number")[popup_score].innerHTML = '1 балл';
        document.getElementsByClassName("popup-score-object-points2")[popup_score].innerHTML = 'Ужасно';
    }
    if (score == '0') {
        document.getElementsByClassName("popup-score-number")[popup_score].innerHTML = 'Незнаю';
        document.getElementsByClassName("popup-score-object-points2")[popup_score].innerHTML = 'Оцените сервис';
    }
    popup_select.style.display = "none";
    document.getElementsByClassName('popup-score-overlay')[0].style.display = 'none';
    document.getElementsByClassName("popup-score-select-top-points")[0].classList.remove(class_old);

    $('.popup-score-content').removeClass('popup-score-content_blocked');
    // /*    разблокирование скролла */
    // document.getElementsByClassName('popup-score-content')[0].style.overflowY = 'auto';
    // document.getElementsByClassName('popup-score-content-td')[0].style.overflowY = 'hidden';

}
function js_overlay_close() {
    document.getElementsByClassName('popup-score-overlay')[0].style.display = 'none';
    document.getElementsByClassName("popup-score-select")[0].style.display = 'none';
}
function js_feedback_open_input() {
    document.getElementsByClassName('b-feedback-place-close')[0].style.display = 'none';
    document.getElementsByClassName('b-feedback-place-txt')[0].style.display = 'none';
    document.getElementsByClassName('b-feedback-place-input')[0].style.display = 'inline-block';
    document.getElementsByClassName('b-feedback-place-input')[0].style.width = 200 + 'px';
    document.getElementsByClassName('b-feedback-place-input')[0].focus();
}
function js_feedback_select_close(elem) {
    var t = $.trim(elem.innerText);
    document.getElementsByClassName('b-feedback-place-input')[0].value = t;
    document.getElementsByClassName('b-feedback-select-place')[0].style.display = 'none';
    document.getElementsByClassName('overlay-all')[0].style.display = 'none';
}
function js_select_place() {
    document.getElementsByClassName('b-feedback-select-place')[0].style.display = 'block';
    document.getElementsByClassName('overlay-all')[0].style.display = 'block';

}
function js_feedback_smile_select(elem) {
    var t = $(elem).html();
    var textA = $(elem).parents('form').find('.b-feedback-txtarea');
    var ins = false;
    var sel = null;

    var nodeSelect;

    if (window.getSelection)
    {
        sel = window.getSelection();


        if (sel.anchorNode)
        {
            var html = '';
            var elTextA = textA.get(0);
            var hel;
            var insI;

            for (var i = 0; i < elTextA.childNodes.length; i++) {
                if (elTextA.childNodes[i].nodeType === 1) //элемент (смайл)
                {
                    hel = elTextA.childNodes[i].outerHTML;
                } else
                {
                    hel = elTextA.childNodes[i].nodeValue;
                }


                if (elTextA.childNodes[i] === sel.anchorNode)
                {
                    hel = htmlspecialchars(hel.substr(0, sel.anchorOffset)) + t + ' ' + htmlspecialchars(hel.substr(sel.anchorOffset));
                    ins = true;
                    insI = i;
                } else
                if (elTextA.childNodes[i].nodeType !== 1)
                {
                    hel=htmlspecialchars(hel);
                }
                
                
                
                html += hel;

            }


            if (ins)
            {
                textA.html(html);
                nodeSelect = textA.get(0).childNodes[insI + 1];
            }

        }
    }
    if (!ins)
        textA.append(' ' + t);

    if (sel && document.createRange)
    {
        if (!nodeSelect)
        {
            nodeSelect = textA.get(0).lastChild;
        }
      
        var rng = document.createRange();
        rng.setStartAfter(nodeSelect);

        sel.removeAllRanges();
        sel.addRange(rng);

    }


    textA.focus();


    $(elem).parent().hide();
    if (document.getElementsByClassName('overlay-all')[0])
        document.getElementsByClassName('overlay-all')[0].style.display = 'none';
    return false;
}
function js_smile_select_open() {
    document.getElementsByClassName('b-feedback-smile-select')[0].style.display = 'block';
    document.getElementsByClassName('overlay-all')[0].style.display = 'block';
}
function js_close_all() {
    $('.b-feedback-smile-select').hide();
    document.getElementsByClassName('b-feedback-select-place')[0].style.display = 'none';
    document.getElementsByClassName('overlay-all')[0].style.display = 'none';
}

