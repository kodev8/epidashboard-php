const loginForm = document.getElementById('login-form')
const inlineError = document.getElementById('inline-error')

function clearInlineError () { // removes inline errors (as opposed to toast )
    inlineError.classList.add('hide')
}

// get form inputs
const inputs = loginForm.querySelectorAll('input:not([type=submit])') 
// add event listener to text input to remove the error
inputs.forEach(input => input.addEventListener('input', clearInlineError))
// close on clicking x
inlineError.querySelector('.fa-xmark').addEventListener('click', clearInlineError)

// when form is submitted
loginForm.addEventListener('submit', function(event) {

    // do not refresh page
    event.preventDefault();

    // get the form data and generate key val pairs with url search params
    let formData = new FormData(loginForm)
    const data = new  URLSearchParams(formData)

    // send via post to keep safe
    fetch('/login', {
        method: 'POST',
        headers:  {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: data
    })
    .then(response => {
        if (!response.ok) {
            return handleAPITypeErrors(response, inlineError) // handle inline erros vs toast errors
        }
        return response.json()
    })
    .then(loginResponse => {
        if (loginResponse == null){
            throw new Error('Server Error: Invalid Data'); 
        }

        if (loginResponse.type == 'success'){
            // create the log in toast only on log in 
            sessionStorage.setItem('toastType', 'login-success');
            window.location.replace('/dashboard');
        }
    })
    .catch(error =>  {
        if (error.message != 'handled'){ // if not handled by pervious steps then display error toast
            createToast('error', error.message ? error.message : error);
        }
    })
});
