function userReaderSubscribeSuccess(response) {
    if (response.result == "success") {
        $(this).parents('.subscribe-item-tools').find('.subscribe-status:hidden').show();
        $(this).parent().hide();
        alert('Подписка оформлена');
    }
}

function userReaderUnsubscribeSuccess(response) {
    if (response.result == "success") {
        $(this).parents('.subscribe-item-tools').find('.subscribe-status:hidden').show();
        $(this).parent().hide();
        alert('Подписка удалена');
    }
}

function userAllocationSubscribeSuccess(response) {
    if (response.result == "success") {
        $(this).parents('.subscribe-item-tools').find('.subscribe-status:hidden').show();
        $(this).parent().hide();
        alert('Подписка оформлена');
    }
}

function userAllocationUnsubscribeSuccess(response) {
    if (response.result == "success") {
        $(this).parents('.subscribe-item-tools').find('.subscribe-status:hidden').show();
        $(this).parent().hide();
        alert('Подписка удалена');
    }
}