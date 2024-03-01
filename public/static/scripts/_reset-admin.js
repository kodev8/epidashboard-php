let resetButton = document.getElementById('reset')

const resetModal = () => {
    
    
    resetButton.blur()
    fetch('/admin/reset-password', () => {
        method: 'GET'
    })
    .then(resp =>{ 
        if (!resp.ok) {
        return handleAPITypeErrors(resp)
         }
    return resp.text()
}
    )
    .then(res => {
        if(res == null){
            return 
        }

        let functions = [updatePassword, handleCustomInputs, handleModalButtons]
        showModal(res, functions)
    })
    .catch(error =>  {
        if (error.message != 'handled'){
            createToast('error', error?.message);
        
        }
    })
}

resetButton.addEventListener('click', resetModal)

const updatePassword = () => {
    // let resetForm = document.querySelector('.modal-form')
    // const inlineError = document.getElementById('inline-error')

    // function clearInlineError () {
    //     inlineError.classList.add('hide')
    // }

    // const inputs = resetForm.querySelectorAll('input:not([type=submit])')


    // inputs.forEach(input => input.addEventListener('input', clearInlineError))
    // inlineError.querySelector('.fa-xmark').addEventListener('click', clearInlineError)

}

