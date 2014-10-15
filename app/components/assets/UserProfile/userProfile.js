function userSubscribeSuccess(response) {
    if (response.result == "success") {
        $('.my-profile-bottom-feed-btn').hide();
        $('.my-profile-bottom-feed-btn.unsubscribe').show();
        $('.userinfo-feed').hide();
        $('.userinfo-feed.unsubscribe').show();

        var readerCount = response.data.readers_count;
        $('.userinfo-section.subscriptions').find('.userinfo-value:lt(1) a').text(readerCount + ' ' + declOfNum(readerCount, ['турист', 'туриста', 'туристов']));
    }
}

function userUnsubscribeSuccess(response) {
    if (response.result == "success") {
        $('.my-profile-bottom-feed-btn').show();
        $('.my-profile-bottom-feed-btn.unsubscribe').hide();
        $('.userinfo-feed').show();
        $('.userinfo-feed.unsubscribe').hide();

        var readerCount = response.data.readers_count;
        $('.userinfo-section.subscriptions').find('.userinfo-value:lt(1) a').text(readerCount + ' ' + declOfNum(readerCount, ['турист', 'туриста', 'туристов']));
    }
}
