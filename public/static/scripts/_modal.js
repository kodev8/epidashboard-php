// closes modals
closeModal = (event=null) => {
    const modalbg = document.getElementById('modal-bg');
    // prevent closing modal with input clicks
    if(event == null || event.pointerType == 'mouse' ||  event.pointerType == 'touch' ){

    if (modalbg){
        document.querySelector('body').style.overflowY = 'auto'
        modalbg.classList.remove('show');
        modalbg.innerHTML = "";
    }}
    
}

// opens modals
window.showModal = (content, functions=null) => {
    // expects content with a class .modal and a buttion of id close modal to close it
    
    const modalbg = document.getElementById('modal-bg');

   

    if (modalbg){

        //show bg and add contet
        modalbg.classList.add('show');

        modalbg.innerHTML = content;

        //prevent background scroll
        document.querySelector('body').style.overflowY = 'hidden'

        // prevent closing modal when clicking on the modal itself i.e. prevent event bubbling
        document.querySelectorAll('.modal').forEach( modal => {
            
            modal.addEventListener('click', (event) => {
            event.stopPropagation();
            })

          
          
            })

        // close modal with x in top left
        let xmark = document.querySelector('#close-modal')
        xmark?.addEventListener('click',  closeModal)

        
        // execute any necessar functions that can only be done when modal is set up
        if (functions && functions.length > 0) {
            functions.forEach(f=> {
                try { 
                    f()

                }catch(err) {
                    createToast('error', 'Modal Error')
                }}
            );
        }
    }
    
}



window.showActionModal = (className) => {
    document.querySelectorAll(`.${className}`).forEach(item => {
        item.addEventListener('click', ()=> {

        try {

            var json_body = {
                modal : 1,
                modalType: className
            }
            
            var  handler

            if (className == 'delete' ) {
            
            
                //get parent table
                handler  = item.closest('table').dataset.handler

                //get the row in which the icon was clicked (edit or delete)
                const activeRow = item.closest('tr');
                
                var tokenInputField = activeRow.querySelector('input[type=hidden][data-id="token"]')
                console.log(tokenInputField)
                if (tokenInput) { 
                    json_body['tokenData'] = tokenInputField.value;
                }else {
                    throw Error('Token not found main')
                }


            }else if (className == 'add'){
                // get handler in slightly different way since add is not located in the table
                actionTable = item.closest('.table-header-container').nextElementSibling.querySelector('table')
                handler = actionTable.dataset.handler

                json_body['tokenData'] = item.parentElement.querySelector('input[type=hidden]').value;
                
                
            }else{

                throw new Error('No handler for this type')
            }

            
            if (!handler){

                throw new Error('No handler found')

            }
            }
            catch(err) {
                createToast('error', 'Cannot fetch modal: '. err)
    
            }
                
            // send request to ensure modal can be opened
            // if token has been tampered with it wont open

            fetch(`/api/${handler}/modal`, {
                method: 'POST', 
                headers: {
                        'Content-Type': 'application/json'
                        },
                body: JSON.stringify(
                   json_body
                )
            })
            .then(response => {
                if (!response.ok) {
                    return handleAPITypeErrors(response)
                }

                return response.text()
            })
            .then(html =>{

                let functions = [handleModalButtons, handleCustomInputs]
                if (className == 'add' && handler == 'course') { 
                    functions.push(handleCourseAddModal)
                }
                showModal(html, functions)
                
                
            })
            .catch(error =>  {
                if (error.message != 'handled'){
                    createToast('error', error.message ? error.message : error);
            
                }
            })
            

        });
    });
}

// handle modal confirm cancel to submit or close modal
const handleModalButtons = (inlineError=null) => {
    
    let modalForm = document.querySelector('.modal-form')
    let requestHandler =  modalForm.querySelector('#_request_handler').value
    let requestMethod =  modalForm.querySelector('#_request_method').value.toUpperCase().trim()
    let cancelRequest = modalForm.querySelector('#negative')
    let confirmRequest = modalForm.querySelector('#positive')
    const rootURL = window.location.origin
    const url = new URL(requestHandler, rootURL)


    cancelRequest.addEventListener('click', (event) => {
        event.preventDefault()
        closeModal(event)
        }
    )

    confirmRequest.addEventListener('click', (event)=>{
        event.preventDefault()

        let formData = new FormData(modalForm)
        var data = new  URLSearchParams(formData)

        

        // format to send as delete request ( only sends token )
        if (requestMethod == 'DELETE') {
            url.search = data.toString()
        }else if (requestMethod == 'PATCH' ){
            data =JSON.stringify(Object.fromEntries(formData));

        }
     

        fetch(url, {
            method: requestMethod,
            headers:  {
                'Content-Type': requestMethod == 'PATCH' ? 'application/json' : 'application/x-www-form-urlencoded'
            },
            body: requestMethod == 'DELETE' ? null :data
        })
        .then(response => {
    
            if (!response.ok) {
                return handleAPITypeErrors(response)
            }
            return response.json()
        })
        .then(modalResponse => { 
            if (modalResponse == null){
                throw new Error('Server Error: Invalid Data');
            }
    
            if (modalResponse.type == 'success-reload'){
                // set session toast to open a toast on reload
                sessionStorage.setItem('modalToast',JSON.stringify({
                    'type':'modal-success',
                    'message': modalResponse.message
                })
                );
                
                window.location.reload();
            }

            else{
                ('no reload')
                closeModal()
                createToast(modalResponse.type, modalResponse.message)
            }
        })
        .catch(error => {
    
            if (error.message != 'handled'){
                createToast('error', error.message ? error.message : error);

    
            }
    
        });
    });
     

}
const handleCourseAddModal = () =>  {
    let modalForm = document.querySelector('.modal-form')
    let choose_course = modalForm.querySelectorAll('input[type=radio]')
    let with_new = modalForm.querySelectorAll('.with_new')
    // let course_name = modalForm.querySelector('#course_name').parentElement
    let course_complement = modalForm.querySelector('#complement_course')
     
    const handleDesc = () => {
        let course_desc = modalForm.querySelector(".textarea")
        let counter = course_desc.parentElement.querySelector(".text-count")
        course_desc.addEventListener('input', (e) => {
            counter.innerText = e.target.value.length
        })
    }

    handleDesc();
    
    choose_course.forEach(radio => radio.addEventListener('click', () => { 
        if(radio.value == 'new'){
            with_new.forEach(w => w.classList.remove('hide'))
            course_complement.parentElement.classList.add('hide')
        


        }else {
            with_new.forEach(w => w.classList.add('hide'))
            // course_name.classList.add('span2')
            course_complement.parentElement.classList.remove('hide')
        }
    }))
}

