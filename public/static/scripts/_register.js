//  same set up as log in

const registerForm = document.getElementById('register-form')
const inlineError = document.getElementById('inline-error')

function clearInlineError () {
    inlineError.classList.add('hide')
}

const inputs = registerForm.querySelectorAll('input:not([type=submit])')
inputs.forEach(input => input.addEventListener('input', clearInlineError))
inlineError.querySelector('.fa-xmark').addEventListener('click', clearInlineError)


registerForm.addEventListener('submit', function(event) {
    event.preventDefault();

    let formData = new FormData(registerForm)
    const data = new  URLSearchParams(formData)


    fetch('/register', {
        method: 'POST',
        headers:  {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: data
    })
    .then(response => {
        if (!response.ok) {
            return handleAPITypeErrors(response, inlineError)
        }
        return response.json()
    })
    .then(registerResponse => {
        

        if (registerResponse == null){
            throw new Error('Server Error: Invalid Data');
        }

        if (registerResponse.type == 'success'){
            // create the log in toast only on log in 
            createToast('success', registerResponse.message)
            registerForm.reset()
        }
    })
    .catch(error => {

        if (error.message != 'handled'){
            createToast('error', error.message ? error.message : error);

        }

    });
});