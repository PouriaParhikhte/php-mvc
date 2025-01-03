const registerTicketForm = document.forms[0],
    ticketErrorMessage = document.getElementById('ticketErrorMessage');

registerTicketForm.addEventListener('submit', async e => {
    e.preventDefault();
    const tokenValue = document.cookie.split('token=')[1],
        response = await fetch(registerTicketForm.getAttribute('action'), {
            method: registerTicketForm.getAttribute('method'),
            headers: {
                'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'type': 'xhr'
            },
            body: JSON.stringify({
                ticketTitle: registerTicketForm[0].value,
                ticketText: registerTicketForm[1].value,
                token: tokenValue
            })
        });

    const message = await response.text(),
        status = await response.status,
        headers = response.headers.get('Bearer');

    if (!headers || headers !== tokenValue) {
        ticketErrorMessage.textContent = 'توکن نامعتبر!';
        return;
    }

    if (status === 200) {
        ticketErrorMessage.textContent = message;
        registerTicketForm.reset();
    }
    ticketErrorMessage.textContent = message;
});