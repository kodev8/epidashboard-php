//  same set up as log in

const resetForm = document.getElementById('reset-form')
const inlineError = document.getElementById('inline-error')

function clearInlineError () {
    inlineError.classList.add('hide')
}

const inputs = resetForm.querySelectorAll('input:not([type=submit])')
inputs.forEach(input => input.addEventListener('input', clearInlineError))
inlineError.querySelector('.fa-xmark').addEventListener('click', clearInlineError)


resetForm.addEventListener('submit', function(event) {
    event.preventDefault();



    let formData = new FormData(resetForm)
    const data = new  URLSearchParams(formData)



    fetch('/reset-password', {
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
    .then(resetResponse => {


        if (resetResponse == null){
            throw new Error('Server Error: Invalid Data');
        }

        if (resetResponse.type == 'success'){
            createToast('success', resetResponse.message)
            resetForm.reset()
        }
    })
    .catch(error => {

        if (error.message != 'handled'){
            createToast('error', error.message ? error.message : error);
        }

    });
});