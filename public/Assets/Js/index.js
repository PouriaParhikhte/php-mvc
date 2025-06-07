$(function () {
    $(document).on('submit', '#mobileNumber', event => {
        sendMobileNumber(event, $('#mobileNumber').serialize());
    });

    $(document).on('submit', '#temporaryCode', event => {
        sendTemporaryCode(event, $('#temporaryCode').serialize());
    });

    $(document).on('click', 'ul.pagination li a', event => {
        receiveAndDisplayPagePosts(event);
    });

    $('nav ul li a').on('click', event => {
        receiveAndDisplayPagePosts(event);
    });

    if (posts = sessionStorage.getItem('posts'))
        $('#posts').html(posts);
});

function sendMobileNumber(event, data) {
    sendFormData(event, data);
}

function sendTemporaryCode(event, data) {
    sendFormData(event, data);
}

function sendFormData(event, data) {
    $.ajax({
        type: event.target.method,
        url: event.target.action,
        headers: {
            type: 'xhr'
        },
        data: data,
        success: function (response) {
            response = JSON.parse(response);
            if (response.result === 'temporaryCode' || response.result === 'customer')
                window.location.reload();
            else
                alert(response.result);
        }
    });
    event.preventDefault();
}

function receiveAndDisplayPagePosts(event) {
    $.ajax({
        type: 'POST',
        url: event.target.getAttribute('href'),
        headers: {
            type: 'xhr'
        },
        data: { url: event.target.getAttribute('href') },
        success: function (response) {
            response = JSON.parse(response);
            if (response.status !== 404) {
                sessionStorage.setItem('posts', response.result);
                $('#posts').html(response.result);
            } else
                window.location = '404';
        }
    });
    event.preventDefault();
}