const logoutLink = document.querySelector('nav ul').lastChild.lastChild;
logoutLink.addEventListener('click', async e => {
    const token = decodeURIComponent(document.cookie).split('token=')[1];
    const response = await fetch(logoutLink.href, {
        method: 'GET',
        headers: {
            'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
            'type': 'xhr',
            'token': 'Bearer=' + token
        }
    });
    window.location.href = 'home';
});