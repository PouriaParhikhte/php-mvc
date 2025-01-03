const loginForm = document.forms[1],
    loginErrorMessage = document.getElementById('loginErrorMessage');

if (loginForm !== undefined)
    loginForm.addEventListener('submit', async e => {
        e.preventDefault();

        let token = decodeURIComponent(document.cookie).split('token=')[1],
            inputs = loginForm.querySelectorAll('input:not([type=submit])'),
            fields = [];
        inputs.forEach((value) => {
            fields[value.name] = value.value;
        });
        const response = await fetch(loginForm.getAttribute('action'), {
            method: loginForm.getAttribute('method'),
            headers: {
                ContentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                type: 'xhr',
                token: 'Bearer=' + token
            },
            body: JSON.stringify(Object.assign({}, fields))
        });

        const message = await response.text(),
            headers = response.headers.get('Authorization');

        // if (!headers || headers.split('Bearer=')[1] !== token)
        //     loginErrorMessage.textContent = message;
        // else
        if (message)
            loginErrorMessage.textContent = message;
        else
            window.location.href = 'api/user/dashboard';
    });