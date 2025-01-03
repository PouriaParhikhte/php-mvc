const answerTicketForm = document.forms[0],
    answerTicketErrorMessage = document.getElementById('answerTicketErrorMessage');

answerTicketForm.addEventListener('submit', async e => {
    e.preventDefault();
    const tokenValue = document.cookie.split('token=')[1],
        response = await fetch(answerTicketForm.getAttribute('action'), {
            method: answerTicketForm.getAttribute('method'),
            headers: {
                'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'type': 'xhr'
            },
            body: JSON.stringify({
                answer: answerTicketForm[0].value,
                ticketId: answerTicketForm[1].value,
                token: tokenValue
            })
        });

    const message = await response.text(),
        status = await response.status,
        headers = response.headers.get('Bearer');

    if (!headers || headers !== tokenValue) {
        answerTicketErrorMessage.textContent = 'توکن نامعتبر!';
        return;
    }

    if (status === 200) {
        answerTicketErrorMessage.textContent = message;
        answerTicketForm.reset();
    } else
        answerTicketErrorMessage.textContent = message;
});