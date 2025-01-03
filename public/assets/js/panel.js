const panelLoginForm = document.forms[0],
    panelErrorMessage = document.getElementById('panelErrorMessage');

panelLoginForm.addEventListener('submit', async e => {
    e.preventDefault();
    const response = await fetch(panelLoginForm.getAttribute('action'), {
        method: panelLoginForm.getAttribute('method'),
        headers: {
            'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
            'type': 'xhr'
        },
        body: JSON.stringify({
            username: panelLoginForm[0].value,
            password: panelLoginForm[1].value,
        })
    });
    const message = await response.text(),
        status = await response.status;
    if (status !== 200)
        panelErrorMessage.textContent = message;
    else
        window.location.href = 'api/panel/dashboard';
});