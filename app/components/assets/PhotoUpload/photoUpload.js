$(document).ready(function() {
    $('body').on('click', '.b-feedback-gallery .b-feedback-gallery-item-add', function() {
        $(this).parent().parent().find('.MultiFile-wrap input[name="Photo[file][]"]:not([style="display: none; position: absolute; top: -3000px;"],[style="position: absolute; top: -3000px;"])').click();
        return false;
    });
});

function photoFileAppend(e, v, MultiFile) {
    var imageId = $(e).attr('id') + '_img';
    var item = $('<div class="b-feedback-gallery-item" style="width: auto"></div>');
    var image = $('<img id="' + imageId + '" src="#" alt="Не удалось загрузить изображение" style="max-height: 73px" />');
    var remove = $('<a href="#" class="b-feedback-gallery-item-close" title="Удалить"></a>');
    MultiFile.list.parent().parent().find('.b-feedback-gallery .b-feedback-gallery-item-add').before(
        item.append(image, ' ', remove)
    );
    readURL(e, $('#' + imageId));

    remove.click(function() {
        MultiFile.n--;
        MultiFile.current.disabled = false;

        for (key in MultiFile.slaves) {
            if ($(MultiFile.slaves[key]).attr('id') == $(e).attr('id')) {
                MultiFile.slaves[key] = null;
                break;
            }
        }
        $(e).remove();
        $(this).parent().remove();

        $(MultiFile.current).css({ position:'', top: '' });
        $(MultiFile.current).reset().val('').attr('value', '')[0].value = '';
        return false;
    });

    return false;
}