function allocationSubscribeSuccess(response) {
    if (response.result == "success") {
        $('.my-profile .my-profile-bottom-feed-btn').show();
        $(this).hide();
    }
}
